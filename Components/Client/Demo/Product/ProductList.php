<?php

namespace Amplify\Widget\Components\Client\Demo\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductList
 */
class ProductList extends BaseComponent
{
    protected $orderList;

    public function isMasterProduct($product): bool
    {
        return ! empty($product?->Full_Sku_Count) && $product?->Sku_Count > 1;
    }

    public function isSkuProduct($product): bool
    {
        return ! empty($product?->Full_Sku_Count) && $product?->Sku_Count == 1;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @throws \ErrorException
     */
    public function render(): View|Closure|string
    {
        $easyAskResult = store()->eaProductsData;
        $productView = active_shop_view();
        $productsData = new \StdClass;
        $productsData->items = $easyAskResult->getProducts();
        $seoPath = $easyAskResult->getCurrentSeoPath();
        $message = $easyAskResult->getMessage();
        $orderList = [];

        $productCodes = array_map(function ($product) {
            $product->hasSku = 0;

            if ($this->isSkuProduct($product)) {
                $product->productCode = $product->Sku_ProductCode ?? ($product->Product_Code ?? '');
                $product->productName = $product->Sku_Name ?? ($product->Product_Name ?? '');
                $product->productImage = $product->Sku_ProductImage ?? ($product->Product_Image ?? '');
            } else {
                $product->productCode = $product->Product_Code ?? '';
                $product->productName = $product->Product_Name ?? '';
                $product->productImage = $product->Product_Image ?? '';

                if ($this->isMasterProduct($product)) {
                    $product->hasSku = 1;
                }
            }

            return [
                'item' => $product->productCode,
            ];
        }, $productsData->items);

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {
            $dbProducts = Product::whereIn('product_code', $productCodes)->get();
            $erpProductDetails = ErpApi::getProductPriceAvailability(['items' => $productCodes, 'warehouse' => ErpApi::getCustomerDetail()?->DefaultWarehouse]);

            if (customer_check()) {
                $this->orderList = OrderList::with('orderListItems')->whereCustomerId(customer()->getKey())->get();
            }

            array_walk($productsData->items, function (&$product) use ($erpProductDetails, $dbProducts) {
                $product->entry = $dbProducts->first(fn ($p) => trim($p->product_code) == trim($product->productCode));
                $product->ERP = $erpProductDetails->first(fn ($erp) => trim($erp->ItemNumber) == trim($product->productCode));

                if (customer_check()) {
                    $this->productExistOnFavorite($product->Product_Id, $product);
                }
            });
        }

        return view('widget::client.demo.product.product-list', [
            'productsData' => $productsData,
            'seoPath' => $seoPath,
            'message' => $message,
            'productView' => $productView,
            'orderList' => $orderList,
        ]);
    }

    private function productExistOnFavorite($id, &$product): void
    {
        foreach ($this->orderList as $orderList) {
            if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                $product->exists_in_favorite = true;
                $product->favorite_list_id = $item->id;
            }
        }
    }
}

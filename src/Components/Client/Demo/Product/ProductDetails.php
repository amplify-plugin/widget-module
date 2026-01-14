<?php

namespace Amplify\Widget\Components\Client\Demo\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Traits\ProductDetailTrait;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductDetails
 */
class ProductDetails extends BaseComponent
{
    use ProductDetailTrait;

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
     */
    public function render(): View|Closure|string
    {
        $dbProduct = store()->productModel;

        $response = $this->getProductFromEasyAsk($dbProduct->id, 'All');

        abort_if(isset($response['noResultsMessage']), 404, 'Product is not available on EasyAsk Catalog.');

        $productInfoTabs = [];
        $erpAdditionalImages = [];
        $erpMediaType = Product::MEDIA_TYPE;
        $Product = array_shift($response['products']->items);

        if ($this->isSkuProduct($Product)) {
            $Product->productCode = $Product->Sku_ProductCode ?? ($Product->Product_Code ?? '');
            $Product->productName = $Product->Sku_Name ?? ($Product->Product_Name ?? '');
        } else {
            $Product->productCode = $Product->Product_Code ?? '';
            $Product->productName = $Product->Product_Name ?? '';
        }

        $Product->Sku_List_Attributes = $response['attributes']?->attribute ?? [];
        $Product->categories = $this->getCategories($Product->Product_Id);
        $Product->documents = $this->getDocuments($Product->Product_Id);
        $Product->description = $dbProduct->description;
        $Product->features = json_decode($dbProduct->features);
        $Product->short_description = $dbProduct->short_description;
        $Product->product_image = $dbProduct->productImage;
        $Product->allowBackOrder = $dbProduct->allow_back_order;
        $Product->manufacturer = $dbProduct?->manufacturerRelation ?? null;
        $Product->brand_name = $dbProduct->brand_name;
        $Product->min_order_qty = $dbProduct->min_order_qty;
        $Product->qty_interval = $dbProduct->qty_interval;

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {
            $payload = ['items' => [['item' => $Product->Sku_ProductCode ?? $Product->Product_Code]], 'warehouse' => ErpApi::getCustomerDetail()?->DefaultWarehouse];
            $Product->ERP = ErpApi::getProductPriceAvailability($payload)?->first();
            $productInfoTabs = $this->getProductInfoTabs($Product->ERP->ProductDetails ?? [], $erpAdditionalImages);
        }

        if (customer_check()) {
            $this->productExistOnFavorite($Product->Product_Id, $Product);
        }

        return view('widget::client.demo.product.product-details', [
            'Product' => $Product,
            'productInfoTabs' => $productInfoTabs,
            'erpAdditionalImages' => $erpAdditionalImages,
            'erpMediaType' => $erpMediaType,
            'auth' => customer_check() ? customer() : null,
            'qtyConfig' => config('amplify.pim.use_minimum_order_quantity'),
        ]);
    }

    private function getProductInfoTabs($productDetails, &$erpAdditionalImages)
    {
        $productInfoTabs = [];

        foreach ($productDetails ?? [] as $productDetail) {
            $link_extension = pathinfo($productDetail['LinkValue'], PATHINFO_EXTENSION);
            $display_type = $productDetail['DisplayType'] === 'L' && $link_extension === 'pdf' ?
                'M' : $productDetail['DisplayType'];

            if ($display_type === 'I') {
                $erpAdditionalImages[] = [
                    'label' => $productDetail['DisplayText'],
                    'value' => $productDetail['LinkValue'],
                ];

                continue;
            }

            $productInfoTabs[$display_type][] = [
                'label' => $productDetail['DisplayText'],
                'value' => $productDetail['LinkValue'],
                'extension' => $link_extension,
            ];
        }

        return $productInfoTabs;
    }

    private function productExistOnFavorite($id, &$product): void
    {
        $orderList = OrderList::with('orderListItems')->whereCustomerId(customer()->getKey())->get();

        foreach ($orderList as $orderList) {
            if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                $product->exists_in_favorite = true;
                $product->favorite_list_id = $item->id;
            }
        }
    }
}

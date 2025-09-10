<?php

namespace Amplify\Widget\Components\Client\Hanco\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;

/**
 * @class ProductList
 */
class ProductList extends BaseComponent
{
    private $orderList;

    public function isMasterProduct($product): bool
    {
        return ! empty($product?->Full_Sku_Count) && $product?->Full_Sku_Count == $product?->Sku_Count;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        if (customer_check()) {
            $this->orderList = OrderList::with('orderListItems')
                ->whereCustomerId(customer()->getKey())->get();
        }
        $productView = active_shop_view();
        $easyAskResult = store()->eaProductsData;
        $productsData = $easyAskResult->getProducts();
        $seoPath = $easyAskResult->getCurrentSeoPath();
        $message = $easyAskResult->getMessage();
        $cellCount = 4;
        $productCodes = array_map(function ($product) {
            if (! empty($product?->Full_Sku_Count) && $product?->Full_Sku_Count == $product?->Sku_Count) {
                $product->productCode = $product->Sku_ProductCode ?? ($product->Product_Code ?? '');
                $product->productName = $product->Sku_Name ?? ($product->Product_Name ?? '');
                $product->productImage = $product->Sku_ProductImage ?? ($product->Product_Image ?? '');
                $product->hasSku = 1;
            } else {
                $product->productCode = $product->Product_Code ?? '';
                $product->productName = $product->Product_Name ?? '';
                $product->productImage = $product->Product_Image ?? '';
                $product->hasSku = 0;
            }

            return [
                'item' => $product->productCode,
            ];
        }, $productsData);

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {
            $cellCount += 4;
            $warehouses = ErpApi::getWarehouses();
            $warehouseString = $warehouses->reduce(function ($previous, $current) {
                return $previous.','.$current->WarehouseNumber;
            }, '');
            if (! empty($productCodes)) {
                $erpProductDetails = ErpApi::getProductPriceAvailability([
                    'items' => $productCodes,
                    'warehouse' => $warehouseString,
                ]);

                array_walk($productsData, function (&$product) use ($erpProductDetails, $warehouses) {
                    $erpProduct = $erpProductDetails->first(function ($item) use ($product) {
                        return trim($item->ItemNumber) == trim($product->productCode);
                    });
                    $wareHouses = $warehouses->first(function ($ware) use ($erpProduct) {
                        if (isset($erpProduct->WarehouseID)) {
                            return trim($ware->WarehouseNumber) == trim($erpProduct->WarehouseID);
                        } else {
                            return $ware;
                        }
                    });
                    $product->wareHouse = $wareHouses;
                    $product->ERP = $erpProduct;

                    $orderList = $this->productExistOnFavorite($product->Product_Id, $product);
                    $product->exists_in_favorite = $orderList != null;
                    $product->favorite_list_id = $orderList->id ?? null;

                    $ownProduct = Product::find($product->Product_Id);
                    $product->manufacturer = $ownProduct->manufacturer;
                    $product->min_order_qty = $ownProduct->min_order_qty;
                    $product->qty_interval = $ownProduct->qty_interval;
                    $product->allow_back_order = $ownProduct->allow_back_order;
                });
            }
        }

        return view('widget::client.hanco.product.product-list', [
            'productsData' => $productsData,
            'seoPath' => $seoPath,
            'message' => $message,
            'productView' => $productView,
            'cellCount' => $cellCount,
        ]);
    }

    private function productExistOnFavorite($id, &$product): ?OrderListItem
    {
        if ($this->orderList) {
            foreach ($this->orderList as $orderList) {
                if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                    return $item;
                }
            }
        }

        return null;
    }
}

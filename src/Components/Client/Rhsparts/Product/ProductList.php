<?php

namespace Amplify\Widget\Components\Client\Rhsparts\Product;

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
    private $orderList;

    public function isMasterProduct($product): bool
    {
        return ! empty($product?->Full_Sku_Count) && $product?->Full_Sku_Count == $product?->Sku_Count;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (customer_check()) {
            $this->orderList = OrderList::with('orderListItems')
                ->whereCustomerId(customer()->getKey())->get();
        }
        $easyAskResult = store()->eaProductsData;
        $productView = active_shop_view();
        $productsData = new \StdClass;
        $productsData->items = $easyAskResult['products']?->items ?? [];
        $productsData->itemDescription = $easyAskResult['products']?->itemDescription ?? new \StdClass;

        $productsData->items = $productsData->items ?? [];
        $seoPath = $easyAskResult['seopath'] ?? '';
        $message = $easyAskResult['message'] ?? null;
        $orderList = $easyAskResult['orderList'] ?? [];
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
                'p_ids' => $product->Product_Id,
            ];
        }, $productsData->items);

        $productIds = array_column($productCodes, 'p_ids');

        $productManufacturers = [];
        if (! empty($productIds)) {
            $productManufacturers = Product::select('products.id', 'manufacturers.name as manufacturers_name', 'products.short_description')
                ->leftJoin('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                ->whereIn('products.id', $productIds)
                ->get();
        }

        $warehouses = $erpProductDetails = null;

        if (has_erp_customer()) {
            $warehouses = ErpApi::getWarehouses();
            $warehouseString = $this->getWarehouseString($warehouses);
            $cellCount += 4;
            $erpProductDetails = ErpApi::getProductPriceAvailability(['items' => $productCodes, 'warehouse' => $warehouseString]);
        }

        array_walk($productsData->items, function (&$product) use ($erpProductDetails, $warehouses, $productManufacturers) {
            if (! empty($erpProductDetails)) {
                $erpProduct = $erpProductDetails->filter(function ($item) use ($product) {
                    return trim($item->ItemNumber) == trim($product->productCode);
                })->values();
                $product->ERP = $erpProduct;
            }

            if (! empty($warehouses)) {
                $warehouses = $this->getWarehouses($warehouses, $erpProduct);
                $product->wareHouse = $warehouses;
            }

            $pro = $productManufacturers->where('id', '=', $product->Product_Id)->first();
            if (! empty($pro)) {
                $product->manufacturer = $pro->manufacturers_name ?? '';
                $product->short_description = $pro->local_short_description ?? '';
            }

            // $orderList = $this->productExistOnFavorite($product->Product_Id, $product);
            //     $product->exists_in_favorite = $orderList != null;
            //     $product->favorite_list_id = $orderList->id ?? null;
        });

        return view('widget::client.rhsparts.product.product-list', [
            'productsData' => $productsData,
            'seoPath' => $seoPath,
            'message' => $message,
            'productView' => $productView,
            'cellCount' => $cellCount,
        ]);
    }

    private function getWarehouseString($warehouses)
    {
        return $warehouses->reduce(function ($previous, $current) {
            return $previous.$current->WarehouseNumber;
        }, '');
    }

    private function getWarehouses($warehouses, $erpProduct)
    {
        return $warehouses->map(function ($wh) use ($erpProduct) {
            $ware = clone $wh;
            if ($erpProduct->count() > 0) {
                $erpData = $erpProduct->first(function ($erp) use ($ware) {
                    return trim($ware->WarehouseNumber) == trim($erp->WarehouseID);
                });

                $ware->warehouseQty = ! empty($erpData->QuantityAvailable) ? $erpData->QuantityAvailable : 0;
            }

            return $ware;
        });
    }

    // private function productExistOnFavorite($id, &$product): ?OrderListItem
    // {
    //     foreach ($this->orderList ?? [] as $orderList) {
    //         if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
    //             return $item;
    //         }
    //     }

    //     return null;
    // }
}

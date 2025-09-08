<?php

namespace Amplify\Widget\Components\Client\DKLOK\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

/**
 * @class ProductList
 */
class ProductList extends BaseComponent
{
    private $orderList;

    public function isMasterProduct($product): bool
    {
        return ! empty($product->Full_Sku_Count) && ! empty($product->Sku_Count);
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
        $productView = active_shop_view();
        $easyAskResult = store()->eaProductsData;
        $productsData = new \StdClass;
        $productsData->items = $easyAskResult->getProducts();
        $productsData->itemDescription = $easyAskResult->getItemDescriptions();
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
        }, $productsData->items);

        // Get the products with attributes by product codes from DB
        $dbProductWithAttributes = Product::whereIn('product_code', array_column($productCodes, 'item'))
            ->with('attributes')
            ->get();

        // Assign filtered attributes to each product in the productsData
        array_walk($productsData->items, function (&$product) use ($dbProductWithAttributes) {
            // find the matching Eloquent Product by its code
            $dbProduct = $dbProductWithAttributes->firstWhere('product_code', $product->productCode);
            // assign filtered_attributes (or an empty collection if not found)
            $product->filtered_attributes = $dbProduct
                ? $this->getFilteredAttributes($dbProduct)
                : collect();
        });

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {
            $cellCount += 4;
            $warehouses = ErpApi::getWarehouses();

            $warehouseString = $warehouses->reduce(function ($previous, $current) {
                return $previous.$current->WarehouseNumber;
            }, '');
            if (! empty($productCodes)) {
                $erpProductDetails = ErpApi::getProductPriceAvailability([
                    'items' => $productCodes, 'warehouse' => $warehouseString,
                ]);
                array_walk($productsData->items, function (&$product) use ($erpProductDetails, $warehouses) {
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
                    if (! empty($ownProduct)) {
                        $product->manufacturer = $ownProduct->manufacturer;
                        $product->min_order_qty = $ownProduct->min_order_qty;
                        $product->qty_interval = $ownProduct->qty_interval;
                        $product->allow_back_order = $ownProduct->allow_back_order;
                    }
                });
            }
        }

        return view('widget::client.dklok.product.product-list', [
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

    private function getFilteredAttributes($product): Collection
    {
        if (! empty($product->attributes)) {
            $filteredKeys = ['Item #', 'Item Name', 'CADPartID', 'Description'];

            return $product->attributes->filter(fn ($attribute) => ! in_array($attribute->local_name, $filteredKeys));
        }

        return collect([]);
    }
}

<?php

namespace Amplify\Widget\Components\Client\Rhsparts\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductDetail
 */
class ProductDetail extends BaseComponent
{
    use \Amplify\Widget\Traits\ProductDetailTrait;

    public $showSkuTable;

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
        $this->getOrderList();

        $dbProduct = store()->productModel;

        $response = $this->getProductFromEasyAsk($dbProduct->id, 'All');
        abort_if(isset($response['noResultsMessage']), 404);

        $Product = array_shift($response['products']->items);

        $Product->Sku_List_Attributes = $response['attributes']?->attribute ?? [];
        $Product->categories = $this->getCategories($Product->Product_Id);
        $Product->documents = $this->getDocuments($Product->Product_Id);
        $Product->description = $dbProduct->description;
        $Product->short_description = $dbProduct->short_description;
        $Product->product_image = $dbProduct->productImage;
        $Product->MPN = $dbProduct->manufacturer;
        $Product->manufacturer = $dbProduct?->manufacturerRelation ?? null;

        if (has_erp_customer()) {
            $warehouses = ErpApi::getWarehouses();
            $warehouseString = $warehouses->reduce(function ($previous, $current) {
                return $previous.$current->WarehouseNumber;
            }, '');

            $erpProductDetails = ErpApi::getProductPriceAvailability([
                'items' => [
                    ['item' => $Product->Sku_ProductCode ?? $Product->Product_Code],
                ],
                'warehouse' => $warehouseString,
            ]);

            $Product->ERP = $erpProductDetails?->first();

            $Product->wareHouse = $this->getWarehouses($warehouses, $erpProductDetails);
        }

        $ownProduct = Product::find($Product->Product_Id);

        // $orderList = $this->productExistOnFavorite($Product->Product_Id);
        // $Product->exists_in_favorite = $orderList != null;
        // $Product->favorite_list_id = $orderList->id ?? null;
        $Product->modelCodes = $ownProduct->modelCodes;
        $Product->prop65_message = $ownProduct->prop65_message;
        $Product->short_description = $ownProduct->local_short_description;

        return view('widget::client.rhsparts.product.product-detail', [
            'product' => $Product,
            'productImage' => $dbProduct->productImage,
        ]);
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

    /**
     * @param  mixed  $id
     * @param  mixed  $product
     *                          Define Product Exist on Favorite list
     */
    // private function productExistOnFavorite($id): ?OrderListItem
    // {
    //     if (! empty($this->orderList)) {
    //         foreach ($this->orderList as $orderList) {
    //             if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
    //                 return $item;
    //             }
    //         }
    //     }

    //     return null;
    // }

    /**
     * Get Customer OrderList
     *
     * @return [type]
     */
    public function getOrderList()
    {
        if (customer_check()) {
            $this->orderList = OrderList::with('orderListItems')
                ->whereCustomerId(customer()->getKey())->get();
        }
    }
}

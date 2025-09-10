<?php

namespace Amplify\Widget\Components\Client\Hanco\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Backend\Models\Warehouse;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Amplify\Widget\Traits\ProductDetailTrait;
use Illuminate\Support\Collection;

/**
 * @class ProductDetail
 */
class ProductDetail extends BaseComponent
{
    use ProductDetailTrait;

    private Collection $orderList;

    public function isMasterProduct($product): bool
    {
        return ! empty($product?->Full_Sku_Count) && $product?->Full_Sku_Count == $product?->Sku_Count;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @throws \ErrorException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function render()
    {
        $this->getOrderList();

        $dbProduct = store()->productModel;

        if (! $dbProduct) {
            abort(404, 'Product Unavailable');
        }

        $easSearchData = $this->getProductFromEasyAsk($dbProduct->id);

        $Product = $easSearchData->getFirstProduct();
        $Product->Sku_List_Attributes = $easSearchData->getAttributes()->getFullAttributes();
        $Product->categories = $this->getCategories($Product->Product_Id);
        $Product->documents = $this->getDocuments($Product->Product_Id);
        $Product->description = $dbProduct->description;
        $Product->features = json_decode($dbProduct->features);
        $Product->short_description = $dbProduct->short_description;
        $Product->product_image = $dbProduct->productImage;
        $Product->allow_back_order = $dbProduct->allow_back_order;
        $Product->manufacturer = $dbProduct?->manufacturerRelation ?? null;
        //        $Product->specifications = json_decode($dbProduct->specifications);
        $specifications = new \stdClass;
        $specifications->group_name = 'Specifications';
        $specifications->group_items = $dbProduct->attributes->map(function ($item) {
            $value = $item->pivot->attribute_value;
            $value = UtilityHelper::isJson($value) ? json_decode($value, true)[config('app.locale')] ?? null : $value;

            return (object) [
                'name' => $item->name,
                'value' => $value,
            ];
        })->toArray();

        $Product->specifications = [$specifications];

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {
            $warehouses = ErpApi::getWarehouses();
            $warehouseString = $warehouses->pluck('WarehouseNumber')->implode(',');

            $products = ErpApi::getProductPriceAvailability([
                'items' => [
                    [
                        'item' => (! empty($Product->Sku_ProductCode) ? $Product->Sku_ProductCode : $Product->Product_Code),
                    ],
                ],
                'warehouse' => $warehouseString,
            ]);

            $warehouseNumber = $products->pluck('WarehouseID');
            $Product->warehouses = Warehouse::whereIn('code', $warehouseNumber)->get(['code', 'name'])->toArray();

            $Product->warehouses = array_map(fn ($warehouse, $index) => array_merge($warehouse, [
                'price' => $products[$index]['Price'],
                'quantity_available' => (int) $products[$index]['QuantityAvailable'],
                'unit_of_measure' => $products[$index]['UnitOfMeasure'],
            ]), $Product->warehouses, array_keys($Product->warehouses));

            $customer = ErpApi::getCustomerDetail();
            $warehouse_code = $customer->DefaultWarehouse ?: (customer_check() ? customer()?->warehouse?->code : config('amplify.frontend.guest_checkout_warehouse'));

            $Product->ERP = $products->when((bool) $warehouse_code, fn ($query) => $query->where('WarehouseID', $warehouse_code))->first();

        }

        $ownProduct = Product::find($Product->Product_Id);

        $orderList = $this->productExistOnFavorite($Product->Product_Id);
        $Product->exists_in_favorite = $orderList != null;
        $Product->favorite_list_id = $orderList->id ?? null;
        $Product->modelCodes = $ownProduct->modelCodes;
        $Product->prop65_message = $ownProduct->prop65_message;
        $Product->mpn = $ownProduct->manufacturer;
        $Product->brand_name = $ownProduct->brand_name;
        $Product->min_order_qty = $ownProduct->min_order_qty;
        $Product->qty_interval = $ownProduct->qty_interval;

        return view('widget::client.hanco.product.product-detail', [
            'product' => $Product,
            'productImage' => $dbProduct->productImage,
            'qtyConfig' => config('amplify.pim.use_minimum_order_quantity'),
        ]);
    }

    private function productExistOnFavorite($id): ?OrderListItem
    {
        if (! empty($this->orderList)) {
            foreach ($this->orderList as $orderList) {
                if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                    return $item;
                }
            }
        }

        return null;
    }

    /**
     * Get Customer OrderList
     */
    public function getOrderList(): void
    {
        if (customer_check()) {
            $this->orderList = OrderList::with('orderListItems')
                ->whereCustomerId(customer()->getKey())->get();
        }
    }
}

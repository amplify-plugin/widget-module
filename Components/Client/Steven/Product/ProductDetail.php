<?php

namespace Amplify\Widget\Components\Client\Steven\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Warehouse;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Amplify\Widget\Traits\ProductDetailTrait;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
    public function render(): View|Closure|string
    {
        $this->getOrderList();

        $dbProduct = store()->productModel;

        if (! $dbProduct) {
            abort(404, 'Product Unavailable');
        }

        $easSearchData = $this->getProductFromEasyAsk($dbProduct->id);

        $Product = $easSearchData->getFirstProduct();
        if (! $Product) {
            abort(404, 'Product Unavailable in Catalog');
        }
        $Product->Sku_List_Attributes = $easSearchData->getAttributes()->getFullAttributes();
        $Product->categories = $this->getCategories($Product->Product_Id);
        $Product->documents = $this->getDocuments($Product->Product_Id);
        $Product->description = $dbProduct->description;
        $Product->features = json_decode($dbProduct->features);
        $Product->short_description = $dbProduct->short_description;
        $Product->product_image = $dbProduct->productImage;
        $Product->allow_back_order = $dbProduct->allow_back_order;
        $Product->manufacturer = $dbProduct?->manufacturerRelation ?? null;
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
            $warehouses = ErpApi::getWarehouses([['enabled', '=', true]]);
            $warehouseString = $warehouses->pluck('WarehouseNumber')->implode(',');
            $erpCustomer = ErpApi::getCustomerDetail();
            if (!Str::contains($warehouseString, $erpCustomer->DefaultWarehouse)) {
                $warehouseString = "$warehouseString,{$erpCustomer->DefaultWarehouse}";
            }
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

            $warehouse_code = $erpCustomer->DefaultWarehouse ?: (customer_check() ? customer()?->warehouse?->code : config('amplify.frontend.guest_checkout_warehouse'));

            $Product->ERP = $products->when((bool) $warehouse_code, fn ($query) => $query->where('WarehouseID', $warehouse_code))->first();

        }

        $orderList = $this->productExistOnFavorite($Product->Product_Id);
        $Product->exists_in_favorite = $orderList != null;
        $Product->favorite_list_id = $orderList->id ?? null;
        $Product->modelCodes = $dbProduct->modelCodes;
        $Product->mpn = $dbProduct->manufacturer;
        $Product->brand_name = $dbProduct->brand_name;
        $Product->min_order_qty = $dbProduct->min_order_qty;
        $Product->qty_interval = $dbProduct->qty_interval;
        $Product->default_document = $dbProduct->default_document_type ?? null;

        return view('widget::client.steven.product.product-detail', [
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

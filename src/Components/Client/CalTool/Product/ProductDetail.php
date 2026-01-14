<?php

namespace Amplify\Widget\Components\Client\CalTool\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Backend\Models\Warehouse;
use Amplify\System\Traits\ProductDetailTrait;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
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
    public function render(): View|Closure|string
    {
        $this->getOrderList();

        $dbProduct = store()->productModel;

        $response = $this->getProductFromEasyAsk($dbProduct->id, 'All');

        abort_if(isset($response['noResultsMessage']), 404);

        $productInfoTabs = [];
        $erpMediaType = Product::MEDIA_TYPE;

        $Product = array_shift($response['products']->items);

        $Product->Sku_List_Attributes = $response['attributes']?->attribute ?? [];
        $Product->categories = $this->getCategories($Product->Product_Id);
        $Product->documents = $this->getDocuments($Product->Product_Id);
        $Product->description = $dbProduct->description;
        $Product->features = json_decode($dbProduct->features);
        $Product->short_description = $dbProduct->short_description;
        $Product->product_image = $dbProduct->productImage;
        $Product->allow_back_order = $dbProduct->allow_back_order;
        $Product->manufacturer = $dbProduct?->manufacturerRelation ?? null;
        $Product->specifications = json_decode($dbProduct->specifications);

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {
            $warehouses = ErpApi::getWarehouses();
            $warehouseString = $warehouses->pluck('WarehouseNumber')->implode(',');

            $products = ErpApi::getProductPriceAvailability([
                'items' => [['item' => $Product->Sku_ProductCode ?? $Product->Product_Code]],
                'warehouse' => $warehouseString,
            ]);

            $warehouseNumber = $products->pluck('WarehouseID');
            $Product->warehouses = Warehouse::whereIn('code', $warehouseNumber)->get(['code', 'name'])->toArray();

            $Product->warehouses = array_map(fn ($warehouse, $index) => array_merge($warehouse, [
                'price' => $products[$index]['Price'],
                'quantity_available' => (int) $products[$index]['QuantityAvailable'],
            ]), $Product->warehouses, array_keys($Product->warehouses));

            $customer = ErpApi::getCustomerDetail();
            $warehouse_code = $customer->DefaultWarehouse ?: (customer_check() ? customer()?->warehouse?->code : config('amplify.frontend.guest_checkout_warehouse'));

            $Product->ERP = $products->when((bool) $warehouse_code, fn ($query) => $query->where('WarehouseID', $warehouse_code))->first();

            $productInfoTabs = $this->getProductInfoTabs($Product->ERP->ProductDetails ?? [], $erpAdditionalImages);
        }

        $ownProduct = Product::find($Product->Product_Id);

        $orderList = $this->productExistOnFavorite($Product->Product_Id);
        $Product->exists_in_favorite = $orderList != null;
        $Product->favorite_list_id = $orderList->id ?? null;
        $Product->modelCodes = $ownProduct->modelCodes;
        $Product->prop65_message = $ownProduct->prop65_message;
        $Product->mpn = $ownProduct->manufacturer;
        $Product->brand_name = optional($ownProduct->brand)->title;
        $Product->min_order_qty = $ownProduct->min_order_qty;
        $Product->qty_interval = $ownProduct->qty_interval;

        return view('widget::client.cal-tool.product.product-detail', [
            'product' => $Product,
            'productImage' => $dbProduct->productImage,
            'productInfoTabs' => $productInfoTabs,
            'erpMediaType' => $erpMediaType,
            'qtyConfig' => config('amplify.pim.use_minimum_order_quantity'),
        ]);
    }

    private function getProductInfoTabs($productDetails, &$erpAdditionalImages): array
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

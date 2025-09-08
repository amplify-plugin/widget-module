<?php

namespace Amplify\Widget\Components\Client\Nudraulix\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Backend\Models\Warehouse;
use Amplify\Widget\Abstracts\BaseComponent;
use Amplify\Widget\Traits\ProductDetailTrait;
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

        $response = $this->getProductFromEasyAsk($dbProduct->id);

        $productInfoTabs = [];
        $erpMediaType = Product::MEDIA_TYPE;
        $easyAskProducts = $response->getProducts();
        $seoPath = $response->getCurrentSeoPath();

        $Product = array_shift($easyAskProducts);

        abort_if(empty($Product) && empty($dbProduct), 404);

        if (empty($Product)) {
            $Product = $this->formatDBProductToEasyask($dbProduct);
        }

        $Product->Sku_List_Attributes = $response->getAttributes();
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

            // $customer = ErpApi::getCustomerDetail();
            // $warehouse_code = $customer->DefaultWarehouse ?: (customer_check() ? customer()?->warehouse?->code : config('amplify.frontend.guest_checkout_warehouse'));
            $warehouses = ErpApi::getWarehouses();
            $warehouse_code = $warehouses->reduce(function ($previous, $current) {
                return $previous.$current->WarehouseNumber;
            }, '');

            $Product->ERP = $products->when((bool) $warehouse_code, fn ($query) => $query->where('WarehouseID', $warehouse_code))->first();

            $productInfoTabs = $this->getProductInfoTabs($Product->ERP->ProductDetails ?? [], $erpAdditionalImages);
        }

        $ownProduct = $dbProduct;
        $orderList = $this->productExistOnFavorite($Product->Product_Id);
        $Product->exists_in_favorite = $orderList != null;
        $Product->favorite_list_id = $orderList->id ?? null;
        $Product->modelCodes = $ownProduct->modelCodes;
        $Product->prop65_message = $ownProduct->prop65_message;
        $Product->mpn = $ownProduct->manufacturer;
        $Product->brand_name = $ownProduct->brand_name;
        $Product->min_order_qty = $ownProduct->min_order_qty;
        $Product->qty_interval = $ownProduct->qty_interval;
        $Product->filtered_attributes = $this->getFilteredAttributes($ownProduct);

        $tabs = [];
        $first = true;

        if ($this->isMasterProduct($Product)) {
            $tabs[] = [
                'id' => 'skus',
                'label' => 'Skus',
                'active' => $first,
            ];
            $first = false;
        }

        if ($Product->filtered_attributes->count() > 0) {
            $tabs[] = [
                'id' => 'details',
                'label' => 'Product Details',
                'active' => $first,
            ];
            $first = false;
        }

        if ($Product->documents->count() > 0) {
            $tabs[] = [
                'id' => 'catalogs',
                'label' => 'Download Catalogs',
                'active' => $first,
            ];
            $first = false;
        }

        return view('widget::client.nudraulix.product.product-detail', [
            'product' => $Product,
            'productImage' => $dbProduct->productImage,
            'productInfoTabs' => $productInfoTabs,
            'erpMediaType' => $erpMediaType,
            'qtyConfig' => config('amplify.pim.use_minimum_order_quantity'),
            'seoPath' => $seoPath,
            'tabs' => $tabs,
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

    private function formatDBProductToEasyask(Product $dbProduct)
    {

        return (object) [
            'Product_Id' => $dbProduct->id,
            'Product_Type' => 'normal',
            'Product_Code' => $dbProduct->product_code,
            'Product_Image' => $dbProduct->productImage?->main,
            'Product_Name' => $dbProduct->local_product_name,
            'Status' => 'incomplete',
            'Brand_Name' => 'string',
            'GTIN' => $dbProduct->gtin_number,
            'HasImage' => $dbProduct->productImage->count() > 0,
            'Product_Description' => $dbProduct->local_description,
            'Product_Slug' => $dbProduct->product_slug,
            'Manufacturer' => ! empty($dbProduct->manufacturer_relation) ? $dbProduct->manufacturer_relation->name : '',
            'MPN' => $dbProduct->manufacturer,
            'Price' => null,
            'Msrp' => null,
            'Days_Published' => $dbProduct->published_at,
            'Sku_Id' => $dbProduct->sku_id,
            'Sku_ProductCode' => null,
            'Sku_ProductImage' => null,
            'Sku_Name' => null,
            'Sku_Status' => null,
            'EAScore' => null,
            'EASource' => null,
            'EAWeight' => null,
            'EARules' => null,
            'Sku_Count' => 1,
            'Sku_List' => [],
            'MinPrice' => null,
            'MaxPrice' => null,
        ];
    }
}

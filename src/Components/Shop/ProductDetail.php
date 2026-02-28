<?php

namespace Amplify\Widget\Components\Shop;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Frontend\Http\Controllers\DynamicPageLoadController;
use Amplify\System\Backend\Enums\ProductAvailabilityEnum;
use Amplify\System\Backend\Models\CategoryProduct;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Warehouse;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\System\Traits\ProductDetailTrait;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @class ProductDetails
 */
class ProductDetail extends BaseComponent
{
    use ProductDetailTrait;

    protected Collection $orderList;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public bool $showDiscountBadge = false,
        public bool $showFavourite = false,
        public bool $displayProductCode = false,
        public string $cartButtonLabel = 'Add To Cart'
    ) {
        parent::__construct();

        $this->getOrderList();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $dbProduct = store()->productModel;

        if (! $dbProduct) {
            abort(404, 'Product Unavailable');
        }

        $easSearchData = $this->getProductFromEasyAsk($dbProduct->id);

        $Product = $easSearchData->getFirstProduct();

        if (! $Product) {
            $Product = new ItemRow([], []);
            logger()->debug("Product ID: {$dbProduct->getKey()} not Found in EasyAsk.");
            $Product->Amplify_Id = $dbProduct->getKey();
            $Product->Product_Name = $dbProduct->product_name ?? '';
            $Product->Product_Code = $dbProduct->product_code ?? '';
            $Product->UoM = $dbProduct->uom ?? 'EA';
        }

        $Product->documents = $this->getDocuments($Product->Amplify_Id);
        $Product->description = $dbProduct->description;
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

        $priceAvailability = collect();

        $erpCustomer = ErpApi::getCustomerDetail();

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {
            $warehouses = ErpApi::getWarehouses([['enabled', '=', true]]);
            $warehouseString = $warehouses->pluck('WarehouseNumber')->implode(',');
            if (! Str::contains($warehouseString, $erpCustomer->DefaultWarehouse)) {
                $warehouseString = "$warehouseString,{$erpCustomer->DefaultWarehouse}";
            }
            $priceAvailability = ErpApi::getProductPriceAvailability([
                'items' => [[
                    'item' => $Product->Product_Code,
                    'uom' => $Product->UoM,
                    'qty' => $dbProduct?->min_order_qty ?? 1,
                ]],
                'warehouse' => $warehouseString,
            ]);

            $warehouseNumber = $priceAvailability->pluck('WarehouseID');

            $Product->warehouses = Warehouse::whereIn('code', $warehouseNumber)->get(['code', 'name'])->toArray();

            $Product->warehouses = array_map(fn ($warehouse, $index) => array_merge($warehouse, [
                'price' => $priceAvailability[$index]['Price'],
                'quantity_available' => (int) $priceAvailability[$index]['QuantityAvailable'],
                'unit_of_measure' => $priceAvailability[$index]['UnitOfMeasure'],
            ]), $Product->warehouses, array_keys($Product->warehouses));

            $warehouse_codes = array_unique([$erpCustomer->DefaultWarehouse, customer()?->warehouse?->code, config('amplify.frontend.guest_checkout_warehouse')]);

            $filteredPriceAvailability = $priceAvailability->whereIn('WarehouseID', $warehouse_codes);

            $Product->ERP = $filteredPriceAvailability->isNotEmpty() ? $filteredPriceAvailability->first() : $priceAvailability->first();

        }

        $orderList = $this->productExistOnFavorite($Product->Amplify_Id);
        $Product->exists_in_favorite = $orderList != null;
        $Product->favorite_list_id = $orderList->id ?? null;
        $Product->modelCodes = $dbProduct->modelCodes;
        $Product->mpn = $dbProduct->manufacturer ?? 'N/A';
        $Product->brand_name = $dbProduct->brand_name;
        $Product->min_order_qty = $Product->ERP->MinOrderQuantity ?? $dbProduct->min_order_qty;
        $Product->qty_interval = $Product->ERP->QuantityInterval ?? $dbProduct->qty_interval;
        $Product->allow_back_order = $Product->ERP->AllowBackOrder ?? $dbProduct->allow_back_order ?? false;
        $Product->default_document = $dbProduct->default_document_type ?? null;
        $Product->assembled = $dbProduct->vendornum == 3160;
        $Product->in_stock = $dbProduct->vendornum == 3160 ? true : $dbProduct->in_stock ?? false;
        $Product->total_quantity_available = $priceAvailability->where('ItemNumber', $dbProduct->product_code)->sum('QuantityAvailable');
        $Product->is_ncnr = $dbProduct->is_ncnr ?? false;
        $Product->ship_restriction = $dbProduct->ship_restriction ?? false;
        $Product->availability = $dbProduct->availability ?? ProductAvailabilityEnum::Actual;
        $Product->related_product = $dbProduct?->relatedProducts()->exists() ?? false;
        $Product->pricing = true;

        return view('widget::shop.product-detail', [
            'product' => $Product,
            'customer' => $erpCustomer,
            'productImage' => $dbProduct->productImage,
        ]);
    }

    public function allowDisplayProductCode(): bool
    {
        return (bool) $this->displayProductCode;
    }

    public function allowFavourites(): bool
    {
        return (bool) $this->showFavourite;
    }

    public function displayDiscountBadge(): bool
    {
        return (bool) $this->showDiscountBadge;
    }

    public function isMasterProduct($product): bool
    {
        return $this->component->isMasterProduct($product);
    }

    public function isShowMultipleWarehouse($product): bool
    {
        return ! $this->isMasterProduct($product) && erp()->allowMultiWarehouse() && havePermissions(['checkout.choose-warehouse']);
    }

    public function addToCartBtnLabel(): string
    {
        return $this->cartButtonLabel ?? 'Add To Cart';
    }

    public function showSkuTable()
    {
        return true;
    }

    /**
     * @return mixed
     */
    private function getCategories($Product_Id)
    {
        return CategoryProduct::select(['categories.id', 'categories.category_name'])
            ->where('product_id', $Product_Id)
            ->join('categories', 'category_product.category_id', 'categories.id')
            ->get();
    }

    private function getSingleProductMetas($isSkuProduct, $Product)
    {
        DynamicPageLoadController::$metaInfo = [
            [
                'content' => $isSkuProduct ?
                    assets_image($Product->Sku_ProductImage ?? '')
                    : assets_image(optional($Product)->Product_Image ?? ''),

                'property' => 'og:image',
                'name' => 'image',
            ], [
                'content' => $isSkuProduct ?
                    assets_image($Product->Sku_ProductImage ?? '')
                    : assets_image(optional($Product)->Product_Image ?? ''),

                'property' => 'og:image:secure_url',
                'name' => 'secure_url',
            ], [
                'content' => $Product->Product_Name ?? '',
                'property' => 'og:title',
                'name' => 'title',
            ], [
                'content' => $Product->Product_Description ?? '',
                'property' => 'og:description',
                'name' => 'description',
            ], [
                'content' => url()->current(),
                'property' => 'og:url',
                'name' => 'url',
            ],
        ];
    }

    protected function productExistOnFavorite($id): ?OrderListItem
    {
        if (! empty($this->orderList) && $this->showFavourite) {
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
    protected function getOrderList(): void
    {
        if (customer_check() && $this->showFavourite) {
            $this->orderList = OrderList::with('orderListItems')
                ->whereCustomerId(customer()->getKey())->get();
        }
    }
}

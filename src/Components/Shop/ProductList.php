<?php

namespace Amplify\Widget\Components\Shop;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\ErpApi\Wrappers\ProductPriceAvailability;
use Amplify\System\Backend\Enums\ProductAvailabilityEnum;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * @class ProductList
 */
class ProductList extends BaseComponent
{
    /**
     * @var Collection
     */
    public $orderList;

    public function __construct(
        public bool $showDiscountBadge = false,
        public bool $showFavourite = false,
        public bool $showPublicPrice = false,
        public bool $showCartButton = false,
        public bool $showProductCode = false,
        public bool $showProductBrand = false,
        public bool $skuQuickOrderOption = false,
        public string $cartButtonLabel = 'Add To Cart',
        public string $detailButtonLabel = 'View Details',
        public string $alertMessageUnauthenticated = '',
        public int $gridItemsPerLine = 5,
        public bool $showPaginationOnTop = false
    ) {
        parent::__construct();

        if (customer_check() & $this->showFavourite) {
            $this->orderList = OrderList::with('orderListItems')
                ->whereCustomerId(customer()->getKey())
                ->get();
        } else {
            $this->orderList = new Collection;
        }

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return true;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $productView = active_shop_view();

        $easyAskResult = store()->eaProductsData;

        $products = $easyAskResult->getProducts();

        $seoPath = $easyAskResult->getCurrentSeoPath();

        $message = $easyAskResult->getMessage();

        $ids = [];
        $erpProductCodes = [];

        foreach ($products as $product) {
            $ids[] = $product->Amplify_Id;
        }
        /**
         * @var Collection $dbProducts
         */
        $dbProducts = Product::whereIn('id', $ids)->get();

        foreach ($products as $index => $product) {
            $erpProductCodes[] = [
                'item' => $product->Product_Code,
                'uom' => $product->UoM,
                'qty' => $dbProducts->firstWhere('id', '=', $product->Amplify_Id)?->min_order_qty ?? 1,
            ];

            $dbProduct = $dbProducts->firstWhere('id', '=', $product->Amplify_Id);

            $orderList = $this->productExistOnFavorite($product->Amplify_Id, $product);
            $product->exists_in_favorite = $orderList != null;
            $product->favorite_list_id = $orderList->id ?? null;
            $product->mpn = $dbProduct->manufacturer ?? 'N/A';
            $product->in_stock = $dbProduct->in_stock ?? false;
            $product->is_ncnr = $dbProduct?->is_ncnr ?? false;
            $product->ship_restriction = $dbProduct->ship_restriction ?? false;
            $product->availability = $dbProduct->availability ?? ProductAvailabilityEnum::Actual;
            $product->pricing = true;
            $product->avaliable = 0;
            $product->total_quantity_available = 0;
            $product->ERP = new ProductPriceAvailability($dbProduct->toArray());
            $product->ERP->Price = $dbProduct->msrp;
            $product->ERP->ListPrice = $dbProduct->msrp;
            $product->ERP->StandardPrice = $dbProduct->msrp;
            $product->ERP->ExtendedPrice = $dbProduct->selling_price;
            $product->min_order_qty = $dbProduct->min_order_qty;
            $product->qty_interval = $dbProduct->qty_interval;
            $product->allow_back_order = $dbProduct->allow_back_order ?? false;

            $products[$index] = $product;
        }

        unset($product);

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {

            $warehouses = ErpApi::getWarehouses([['enabled', '=', true]]);

            $warehouseString = $warehouses->pluck('WarehouseNumber')->implode(',');

            $erpCustomer = ErpApi::getCustomerDetail();

            if (! Str::contains($warehouseString, $erpCustomer->DefaultWarehouse)) {
                $warehouseString = "$warehouseString,{$erpCustomer->DefaultWarehouse}";
            }

            if (! empty($erpProductCodes)) {

                $erpProductDetails = ErpApi::getProductPriceAvailability([
                    'items' => $erpProductCodes, 'warehouse' => $warehouseString,
                ]);

                $warehouse_codes = array_unique([$erpCustomer->DefaultWarehouse, customer()?->warehouse?->code, config('amplify.frontend.guest_checkout_warehouse')]);

                array_walk($products, function (ItemRow &$product) use ($erpProductDetails, $warehouse_codes, $dbProducts) {

                    $filteredPriceAvailability = $erpProductDetails
                        ->where('ItemNumber', $product->Product_Code)
                        ->whereIn('WarehouseID', $warehouse_codes);

                    $product->ERP = $filteredPriceAvailability->isNotEmpty()
                        ? $filteredPriceAvailability->first()
                        : $erpProductDetails->where('ItemNumber', $product->Product_Code)
                            ->first();

                    $product->avaliable = $erpProductDetails
                        ->where('ItemNumber', $product->Product_Code)
                        ->where('QuantityAvailable', '>=', 1)
                        ->count();

                    $product->total_quantity_available = $erpProductDetails->where('ItemNumber', $product->Product_Code)->sum('QuantityAvailable');
                    $ownProduct = $dbProducts->firstWhere('id', '=', $product->Amplify_Id);
                    $product->min_order_qty = $product->ERP->MinOrderQuantity ?? $ownProduct->min_order_qty;
                    $product->qty_interval = $product->ERP->QuantityInterval ?? $ownProduct->qty_interval;
                    $product->allow_back_order = $product->ERP->AllowBackOrder ?? $ownProduct->allow_back_order ?? false;
                });
            }
        }

        return view('widget::shop.product-list', compact('productView', 'products', 'message', 'seoPath'));
    }

    public function allowDisplayProductCode(): bool
    {
        return $this->showProductCode;
    }

    public function allowDisplayProductBrand(): bool
    {
        return $this->showProductBrand;
    }

    public function allowFavourites(): bool
    {
        return $this->showFavourite;
    }

    public function displayDiscountBadge(): bool
    {
        return $this->showDiscountBadge;
    }

    public function allowQuickView(): bool
    {
        return $this->skuQuickOrderOption;
    }

    public function humanizeProductName(string $productName = ''): ?string
    {
        return $productName;
    }

    public function addToCartBtnLabel(): string
    {
        return $this->cartButtonLabel ?? 'Add To Cart';
    }

    public function productDetailBtnLabel(): string
    {
        return $this->detailButtonLabel ?? 'View Detail';
    }

    public function isMasterProduct($product)
    {
        if ($product instanceof ItemRow) {
            return $product->HasSku;
        }

        if ($product instanceof Product) {
            return ! empty($product->has_sku) && empty($product->parent_id);
        }

        return false;
    }

    public function isShowMultipleWarehouse($product): bool
    {
        return ! $this->isMasterProduct($product) && erp()->allowMultiWarehouse() && havePermissions(['checkout.choose-warehouse']);
    }

    protected function productExistOnFavorite($id, &$product): ?OrderListItem
    {
        if ($this->orderList && $this->showFavourite) {
            foreach ($this->orderList as $orderList) {
                if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                    return $item;
                }
            }
        }

        return null;
    }
}

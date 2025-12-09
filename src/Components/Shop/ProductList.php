<?php

namespace Amplify\Widget\Components\Shop;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Enums\ProductAvailabilityEnum;
use Amplify\System\Backend\Models\DocumentType;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
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

        foreach ($products as $product) {
            $erpProductCodes[] = [
                'item' => $product->Product_Code,
                'uom' => $product->UoM,
                'qty' => $dbProducts->firstWhere('id', '=', $product->Amplify_Id)?->min_order_qty ?? 1,
            ];
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

//                $productDefaultDocumentTypes = DocumentType::select(['document_types.*', 'document_type_product.file_path', 'document_type_product.product_id'])
//                    ->join('document_type_product', function (JoinClause $join) use ($ids) {
//                        return $join->whereIn('product_id', $ids);
//                    })
//                    ->where('document_types.id', '=', config('amplify.pim.document_type'))
//                    ->get();

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

                    $product->Product_Name = strip_tags(explode('<B>', $product->Product_Name)[0] ?? '');
                    $product->total_quantity_available = $erpProductDetails->where('ItemNumber', $product->Product_Code)->sum('QuantityAvailable');
                    $orderList = $this->productExistOnFavorite($product->Amplify_Id, $product);
                    $product->exists_in_favorite = $orderList != null;
                    $product->favorite_list_id = $orderList->id ?? null;
                    $ownProduct = $dbProducts->firstWhere('id', '=', $product->Amplify_Id);
                    $product->mpn = $ownProduct->manufacturer ?? 'N/A';
                    $product->min_order_qty = $product->ERP->MinOrderQuantity ?? $ownProduct->min_order_qty;
                    $product->qty_interval = $product->ERP->QuantityInterval ?? $ownProduct->qty_interval;
                    $product->allow_back_order = $product->ERP->AllowBackOrder ?? $ownProduct->allow_back_order ?? false;
                    $product->in_stock = $ownProduct->in_stock ?? false;
                    $product->is_ncnr = $ownProduct?->is_ncnr ?? false;
                    $product->ship_restriction = $ownProduct->ship_restriction ?? false;
                    $product->availability = $ownProduct->availability ?? ProductAvailabilityEnum::Actual;
                    $product->pricing = true;
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

    public function isMasterProduct($product): bool
    {
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

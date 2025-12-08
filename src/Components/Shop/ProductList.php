<?php

namespace Amplify\Widget\Components\Shop;

use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

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

        $class = match (config('amplify.client_code')) {
            'RHS' => \Amplify\Widget\Components\Client\Rhsparts\Product\ProductList::class,
            'ACT' => \Amplify\Widget\Components\Client\CalTool\Product\ProductList::class,
            default => \Amplify\Widget\Components\Client\Demo\Product\ProductList::class,
        };

        $this->component = new $class;

        $this->component->attributes = $this->attributes;

        return \view('widget::shop.product-list', compact('productView', 'products', 'message', 'seoPath'));
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
        return $this->component->isMasterProduct($product);
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

<?php

namespace Amplify\Widget\Components\Shop;

use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductList
 */
class ProductList extends BaseComponent
{
    public function __construct(
        public bool   $showDiscountBadge = false,
        public bool   $showFavourite = false,
        public bool   $showPublicPrice = false,
        public bool   $showCartButton = false,
        public bool   $displayProductCode = false,
        public bool   $displayProductBrand = false,
        public bool   $skuQuickOrderOption = false,
        public string $cartButtonLabel = 'Add To Cart',
        public string $detailButtonLabel = 'View Details',
        public bool   $humanizeProductName = true,
        public string $alertMessageUnauthenticated = '',
    )
    {
        parent::__construct();
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
        $class = match (config('amplify.client_code')) {
            'MW' => \Amplify\Widget\Components\Client\MountainWest\Product\ProductList::class,
            'SPI' => \Amplify\Widget\Components\Client\SpiSafety\Product\ProductList::class,
            'RHS' => \Amplify\Widget\Components\Client\Rhsparts\Product\ProductList::class,
            'ACT' => \Amplify\Widget\Components\Client\CalTool\Product\ProductList::class,
            'NUX' => \Amplify\Widget\Components\Client\Nudraulix\Product\ProductList::class,
            'DKL' => \Amplify\Widget\Components\Client\DKLOK\Product\ProductList::class,
            'HAN' => \Amplify\Widget\Components\Client\Hanco\Product\ProductList::class,
            default => \Amplify\Widget\Components\Client\Demo\Product\ProductList::class,
        };

        $this->component = new $class;

        $this->component->attributes = $this->attributes;

        return $this->component->render();
    }

    public function allowDisplayProductCode(): bool
    {
        return $this->displayProductCode;
    }

    public function allowDisplayProductBrand(): bool
    {
        return $this->displayProductBrand;
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
        if ($this->humanizeProductName) {
            return ucwords(str_replace(['_', '-'], ' ', $productName));
        }

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
        return !$this->isMasterProduct($product) && erp()->allowMultiWarehouse() && havePermissions(['checkout.choose-warehouse']);
    }
}

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
    public $component;

    public ?bool $showDiscountBadge;

    public ?bool $showFavourite;

    public ?bool $displayProductCode;

    public ?bool $displayProductBrand;

    public ?bool $skuQuickOrderOption;

    public ?bool $showPublicPrice;

    public ?bool $showCartButton;

    public string $cartButtonLabel;

    public string $detailButtonLabel;

    public string $alertMessageUnauthenticated;

    private bool $humanizeProductName;

    public function __construct(
        string $showDiscountBadge = 'false',
        string $showFavourite = 'false',
        string $showPublicPrice = 'false',
        string $showCartButton = 'false',
        string $displayProductCode = 'false',
        string $displayProductBrand = 'false',
        string $skuQuickOrderOption = 'false',
        string $cartButtonLabel = 'Add To Cart',
        string $detailButtonLabel = 'View Details',
        string $humanizeProductName = 'true',
        string $alertMessageUnauthenticated = '',

    ) {
        parent::__construct();
        $this->showDiscountBadge = UtilityHelper::typeCast($showDiscountBadge, 'bool');
        $this->showFavourite = UtilityHelper::typeCast($showFavourite, 'bool');
        $this->showPublicPrice = UtilityHelper::typeCast($showPublicPrice, 'bool');
        $this->showCartButton = UtilityHelper::typeCast($showCartButton, 'bool');
        $this->displayProductCode = UtilityHelper::typeCast($displayProductCode, 'bool');
        $this->displayProductBrand = UtilityHelper::typeCast($displayProductBrand, 'bool');
        $this->skuQuickOrderOption = UtilityHelper::typeCast($skuQuickOrderOption, 'bool');
        $this->humanizeProductName = UtilityHelper::typeCast($humanizeProductName, 'bool');
        $this->cartButtonLabel = $cartButtonLabel;
        $this->detailButtonLabel = $detailButtonLabel;
        $this->alertMessageUnauthenticated = $alertMessageUnauthenticated;
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
        $class = match (config('amplify.basic.client_code')) {
            'MW' => \Amplify\Widget\Components\Client\MountainWest\Product\ProductList::class,
            'SPI' => \Amplify\Widget\Components\Client\SpiSafety\Product\ProductList::class,
            'RHS' => \Amplify\Widget\Components\Client\Rhsparts\Product\ProductList::class,
            'ACT' => \Amplify\Widget\Components\Client\CalTool\Product\ProductList::class,
            'NUX' => \Amplify\Widget\Components\Client\Nudraulix\Product\ProductList::class,
            'DKL' => \Amplify\Widget\Components\Client\DKLOK\Product\ProductList::class,
            'STV' => \Amplify\Widget\Components\Client\Steven\Product\ProductList::class,
            'HAN' => \Amplify\Widget\Components\Client\Hanco\Product\ProductList::class,
            default => \Amplify\Widget\Components\Client\Demo\Product\ProductList::class,
        };

        $this->component = new $class;

        $this->component->attributes = $this->attributes;

        return $this->component->render();
    }

    public function allowDisplayProductCode(): bool
    {
        return (bool) $this->displayProductCode;
    }

    public function allowDisplayProductBrand(): bool
    {
        return (bool) $this->displayProductBrand;
    }

    public function allowFavourites(): bool
    {
        return $this->showFavourite;
    }

    public function displayDiscountBadge(): bool
    {
        return (bool) $this->showDiscountBadge;
    }

    public function allowQuickView(): bool
    {
        return (bool) $this->skuQuickOrderOption;
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
        return ! $this->isMasterProduct($product) && erp()->allowMultiWarehouse() && havePermissions(['checkout.choose-warehouse']);
    }
}

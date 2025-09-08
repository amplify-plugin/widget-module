<?php

namespace Amplify\Widget\Components\Shop;

use Amplify\Frontend\Http\Controllers\DynamicPageLoadController;
use Amplify\System\Backend\Models\CategoryProduct;
use Amplify\System\Backend\Models\DocumentTypeProduct;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductDetails
 */
class ProductDetails extends BaseComponent
{
    public ?bool $showDiscountBadge;

    public ?bool $showFavourite;

    public ?bool $displayProductCode;

    /**
     * @var array
     */
    public $options;

    public $component;

    public string $cartButtonLabel;

    /**
     * Create a new component instance.
     */
    public function __construct(string $showDiscountBadge = 'false',
        string $showFavourite = 'false',
        string $displayProductCode = 'false',
        string $cartButtonLabel = 'Add To Cart'
    ) {
        parent::__construct();
        $this->showDiscountBadge = UtilityHelper::typeCast($showDiscountBadge, 'bool');
        $this->showFavourite = UtilityHelper::typeCast($showFavourite, 'bool');
        $this->displayProductCode = UtilityHelper::typeCast($displayProductCode, 'bool');
        $this->cartButtonLabel = $cartButtonLabel;

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
            'MW' => \Amplify\Widget\Components\Client\MountainWest\Product\ProductDetail::class,
            'SPI' => \Amplify\Widget\Components\Client\SpiSafety\Product\ProductDetail::class,
            'RHS' => \Amplify\Widget\Components\Client\Rhsparts\Product\ProductDetail::class,
            'ACT' => \Amplify\Widget\Components\Client\CalTool\Product\ProductDetail::class,
            'NUX' => \Amplify\Widget\Components\Client\Nudraulix\Product\ProductDetail::class,
            'DKL' => \Amplify\Widget\Components\Client\DKLOK\Product\ProductDetail::class,
            'STV' => \Amplify\Widget\Components\Client\Steven\Product\ProductDetail::class,
            'HAN' => \Amplify\Widget\Components\Client\Hanco\Product\ProductDetail::class,
            default => \Amplify\Widget\Components\Client\Demo\Product\ProductDetails::class,
        };
        $this->component = new $class;
        $this->component->attributes = $this->attributes;

        return $this->component->render();
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

    private function getProductFromEasyAsk($product_id, $paginatePerPage = 12): array
    {
        $site_search_additional_param = '';
        if (request()->seo_path) {
            $site_search_additional_param = '/'.request()->seo_path;
        } elseif (request()->has('seopath')) {
            $site_search_additional_param = '/'.request()->get('seopath');
        }

        /* Preparing site_search */
        $site_search = '-'.trim(config('amplify.search.product_search_by_id_prefix')).'='.trim($product_id)
            .$site_search_additional_param;
        /* Getting product from easyAsk server and return */
        $easyAskPageService = new Sayt;

        return $easyAskPageService->storeProducts($site_search, $paginatePerPage, false);
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

    /**
     * @return mixed
     */
    private function getDocuments($Product_Id)
    {
        return DocumentTypeProduct::with('documentType')
            ->where('product_id', $Product_Id)
            ->orderBy('order', 'ASC')
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
}

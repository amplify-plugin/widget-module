<?php

namespace Amplify\Widget\Components\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\System\Marketing\Models\MerchandisingZone;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

/**
 * @class ProductSlider
 */
class ProductSlider extends BaseComponent
{
    public $title = '';

    private $merchandising_zone = 1;

    private $products_limit = 5;

    public $show_cart_btn = true;

    public $show_title = true;

    public $show_price = true;

    public $show_top_discount_badge = false;

    public $show_customer_list = false;

    public $slider_item_gap = '30px';

    public $show_navigation = false;

    public $small_button = false;

    public $cart_button_label = false;

    public $products;

    private $orderList;

    private $seopath;

    public string $displayProductCode;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $showTitle = 'true',
        string $title = 'Product Slider',
        string $merchandisingZone = '1',
        string $productsLimit = '5',
        string $showCartBtn = 'true',
        string $cartButtonLabel = 'Add To Order',
        string $smallButton = 'true',
        string $showPrice = 'true',
        string $showTopDiscountBadge = 'false',
        string $showCustomerList = 'true',
        string $showNavigation = 'false',
        string $sliderItemGap = '15',
        string $displayProductCode = 'false'
    ) {

        parent::__construct();
        $this->title = UtilityHelper::typeCast($title, 'string');
        $this->cart_button_label = UtilityHelper::typeCast($cartButtonLabel);
        $this->merchandising_zone = UtilityHelper::typeCast($merchandisingZone, 'integer');
        $this->products_limit = UtilityHelper::typeCast($productsLimit, 'integer');
        $this->slider_item_gap = UtilityHelper::typeCast($sliderItemGap, 'integer');
        $this->show_cart_btn = UtilityHelper::typeCast($showCartBtn, 'bool');
        $this->show_title = UtilityHelper::typeCast($showTitle, 'bool');
        $this->show_price = UtilityHelper::typeCast($showPrice, 'bool');
        $this->small_button = UtilityHelper::typeCast($smallButton, 'bool');
        $this->show_top_discount_badge = UtilityHelper::typeCast($showTopDiscountBadge, 'bool');
        $this->show_customer_list = UtilityHelper::typeCast($showCustomerList, 'bool');
        $this->show_navigation = UtilityHelper::typeCast($showNavigation, 'bool');
        $this->products = collect();
        $this->orderList = collect();
        $this->displayProductCode = UtilityHelper::typeCast($displayProductCode, 'bool');
        $this->prepareData();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return true; // ! $this->products->isEmpty();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::product.product-slider');
    }

    private function prepareData()
    {
        $merchandisingZone = MerchandisingZone::find($this->merchandising_zone);

        if ($merchandisingZone) {

            if (customer_check()) {
                $this->orderList = OrderList::with('orderListItems')
                    ->whereCustomerId(customer()->getKey())->get();
            }

            $this->products = Cache::remember(
                Session::token().'-site-'.$merchandisingZone->easyask_key, HOUR,
                function () use ($merchandisingZone) {

                    $result = \Sayt::marchProducts($merchandisingZone->easyask_key, ['per_page' => $this->products_limit]);

                    $this->seopath = $result->getCurrentSeoPath();

                    $formattedProducts = collect();

                    foreach ($result->getProducts() as $product) {
                        $this->push($formattedProducts, $product);
                    }

                    $productIds = $formattedProducts->pluck('id');
                    $productInfo = Product::select('products.id', 'manufacturers.name as manufacturers_name', 'products.short_description')
                        ->leftJoin('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
                        ->whereIn('products.id', $productIds)
                        ->get();

                    $formattedProducts->map(function ($product) use ($productInfo) {
                        $pro = $productInfo->where('id', '=', $product->id)->first();
                        if (! empty($pro)) {
                            $product->manufacturer = $pro->manufacturers_name ?? '';
                            $product->short_description = $pro->local_short_description ?? '';
                        }

                        return $product;
                    });

                    return $formattedProducts;
                }
            );
        }
    }

    private function push(&$collection, $product)
    {
        $item = new \stdClass;

        $item->id = $product->Product_Id;
        $item->product_code = $product->Product_Code;
        $item->cart_link = $this->productCartLink($product);
        $item->detail_link = $this->productDetailLink($product);
        $item->name = $this->productTitle($product);
        $item->image = $this->productImage($product);
        $item->price = $this->productPrice($product);
        $item->old_price = $item->price;
        $item->exists_in_favorite = false;
        $item->favorite_list_id = null;
        $collection->push($item);
    }

    private function productCartLink($product): string
    {
        return customer_check() || config('amplify.basic.enable_guest_pricing') ? frontendSingleProductURL($product) : route('frontend.login');
    }

    private function productDetailLink($product): string
    {

        return frontendSingleProductURL($product, $this->seopath);
    }

    public function carouselOptions(): string
    {
        return json_encode([
            'lazyLoad' => true,
            'animateIn' => 'fadeIn',
            'animateOut' => 'fadeOut',
            'dots' => true,
            'nav' => true,
            'margin' => intval($this->slider_item_gap),
            'responsive' => [
                '0' => ['items' => 1],
                '576' => ['items' => 2],
                '768' => ['items' => 3],
                '991' => ['items' => 4],
                '1200' => ['items' => 4],
            ],
        ]);
    }

    private function productImage($product): string
    {
        $image = $product->Product_Image ?? '';

        if (! empty($product->Sku_List) && count($product->Sku_List) === 1) {
            if (! empty($product->Sku_ProductImage)) {
                $image = $product->Sku_ProductImage;
            }
        } elseif (! empty($product->Sku_Count) && ! empty($product->Full_Sku_Count) && $product->Sku_Count > 1 && $product->Sku_Count !== $product->Full_Sku_Count) {
            $image = ! empty($product->Sku_ProductImage) ? $product->Sku_ProductImage : $productImage ?? '';
        }

        return assets_image($image);
    }

    private function productTitle($product)
    {
        $name = $product->Product_Name ?? '';

        if (! empty($product->Sku_Name)) {
            $name = $product->Sku_Name;
        }

        return $name;
    }

    private function productPrice($product): float|int|string
    {
        $price = isset($product->Msrp) ? $product->Msrp : $product->Price ?? 0.0;

        if (ErpApi::enabled() && has_erp_customer()) {
            $erpProduct = ErpApi::getProductPriceAvailability([
                'items' => [['item' => $product->Sku_ProductCode ?? $product->Product_Code, 'qty' => 1]],
            ])->first();

            if ($erpProduct) {
                $price = $erpProduct->Price ?? $erpProduct->ListPrice ?? 0;
            }
        }

        return price_format($price);
    }
}

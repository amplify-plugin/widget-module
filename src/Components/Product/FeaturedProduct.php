<?php

namespace Amplify\Widget\Components\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\System\Marketing\Models\MerchandisingZone;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

/**
 * @class FeaturedProduct
 */
class FeaturedProduct extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $title = '';

    private $merchandising_zone = 1;

    private $products_limit = 5;

    public $show_title = true;

    public $show_price = true;

    public $show_discount_price = false;

    public $products;

    private $orderList;

    private $seopath;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $showTitle = 'true',
        $title = 'Featured Products',
        $merchandisingZone = '1',
        $productsLimit = '5',
        $showPrice = 'false',
        $showDiscountPrice = 'false',
    ) {
        parent::__construct();

        $this->products = collect();
        $this->show_title = UtilityHelper::typeCast($showTitle, 'bool');
        $this->title = UtilityHelper::typeCast($title, 'string');
        $this->merchandising_zone = UtilityHelper::typeCast($merchandisingZone, 'integer');
        $this->products_limit = UtilityHelper::typeCast($productsLimit, 'integer');
        $this->show_price = UtilityHelper::typeCast($showPrice, 'bool');
        $this->show_discount_price = UtilityHelper::typeCast($showDiscountPrice, 'bool');
        $this->prepareData();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return ! $this->products->isEmpty();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::product.featured-product');
    }

    private function prepareData(): void
    {
        $merchandisingZone = MerchandisingZone::find($this->merchandising_zone);

        if ($merchandisingZone) {

            $this->products = Cache::remember('site-feature-product-'.$merchandisingZone->easyask_key, HOUR, function () use ($merchandisingZone) {

                $result = \Sayt::marchProducts($merchandisingZone->easyask_key, ['per_page' => $this->products_limit]);

                $this->seopath = $result->getCurrentSeoPath();

                $formattedProducts = collect();

                foreach ($result->getProducts() as $product) {
                    $this->push($formattedProducts, $product);
                }

                return $formattedProducts;
            });
        }
    }

    private function push(&$collection, $product)
    {
        $item = new \stdClass;

        $item->id = $product->Product_Id;
        $item->detail_link = $this->productDetailLink($product);
        $item->name = $this->productTitle($product);
        $item->image = $this->productImage($product);
        $item->price = $this->productPrice($product);
        $item->old_price = $item->price;
        $item->discount = '10% off';
        $collection->push($item);
    }

    private function productCartLink($product): string
    {
        return customer_check() ? frontendSingleProductURL($product) : route('frontend.login');
    }

    private function productDetailLink($product): string
    {
        return frontendSingleProductURL($product, $this->seopath);
    }

    private function productImage($product): string
    {
        $image = $product->Product_Image ?? '';

        if (! empty($product->Sku_List) && count(json_decode($product->Sku_List, true)) === 1) {
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
        $price = isset($product->Msrp) ? $product->Msrp : $product->Price ?? 0.00;
        if (ErpApi::enabled() && has_erp_customer()) {
            $erpProduct = ErpApi::getProductPriceAvailability([
                'items' => [['item' => $product->Sku_ProductCode ?? $product->Product_Code, 'qty' => 1]],
            ])->first();

            if ($erpProduct) {
                $price = $erpProduct->Price ?? $erpProduct->ListPrice;
            }
        }

        return price_format($price);
    }
}

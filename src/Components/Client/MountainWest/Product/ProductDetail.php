<?php

namespace Amplify\Widget\Components\Client\MountainWest\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductDetail
 */
class ProductDetail extends BaseComponent
{
    public function isMasterProduct($product): bool
    {
        return ! empty($product?->Full_Sku_Count) && $product?->Full_Sku_Count == $product?->Sku_Count;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $dbProduct = store()->productModel;

        //        $Product->manufacturer_mage = $dbProduct?->manufacturer_image ?? null;

        return view('widget::client.mountain-west.product.product-detail');
    }
}

<?php

namespace Amplify\Widget\Components\Shop\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ManufacterImage
 */
class ManufacturerImage extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $product;

    /**
     * Create a new component instance.
     */
    public function __construct($product)
    {
        parent::__construct();

        $this->product = $product;

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return $this->product->manufacturer !== null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        return view('widget::shop.product.manufacture-image');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['product-manufacturer-image']);

        return parent::htmlAttributes();
    }
}

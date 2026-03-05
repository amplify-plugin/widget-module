<?php

namespace Amplify\Widget\Components\Shop\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ManufacturerImage
 */
class ManufacturerImage extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(public mixed $product)
    {
        parent::__construct();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return !empty($this->product->manufacturer);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::product.manufacturer-image');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['product-manufacturer-image']);

        return parent::htmlAttributes();
    }
}

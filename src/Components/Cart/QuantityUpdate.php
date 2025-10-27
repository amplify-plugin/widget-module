<?php

namespace Amplify\Widget\Components\Cart;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class QuantityUpdate
 */
class QuantityUpdate extends BaseComponent
{
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
        return view('widget::cart.quantity-update');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class('d-flex justify-content-center product-count align-items-center');

        return parent::htmlAttributes();
    }
}

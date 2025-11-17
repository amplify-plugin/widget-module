<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Price
 */
class Price extends BaseComponent
{
    public function __construct(public Product|ItemRow $product,
                                public mixed           $value = null,
                                public ?string         $uom = 'EA',
                                public string          $element = 'div',
                                public ?float           $stdPrice = null)
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

        return view('widget::product.price');
    }
}

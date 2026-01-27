<?php

namespace Amplify\Widget\Components\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ShoppingList
 */
class ShoppingList extends BaseComponent
{
    public function __construct(public string $productId = '', public string $addLabel = 'Add to Shopping List', public string $widgetTitle = 'Shopping List', public ?int $index = null)
    {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::product.shopping-list');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['btn-group']);

        return parent::htmlAttributes();
    }
}

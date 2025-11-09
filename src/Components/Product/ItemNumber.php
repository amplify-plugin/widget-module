<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\View\View;
use Amplify\Widget\Abstracts\BaseComponent;

/**
 * @class ItemNumber
 * @package Amplify\Widget\Components\Product
 *
 */
class ItemNumber extends BaseComponent
{
    public function __construct(public ItemRow|Product $product, public mixed $loop = null)
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

        return view('widget::product.item-number');
    }
}

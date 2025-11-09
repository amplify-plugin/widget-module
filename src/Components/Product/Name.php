<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\View\View;
use Amplify\Widget\Abstracts\BaseComponent;

/**
 * @class Name
 * @package Amplify\Widget\Components\Product
 *
 */
class Name extends BaseComponent
{
    public function __construct(public ItemRow|Product $product, public mixed $loop = null, public string $element = 'p', public int $maxLine = 1)
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
        $eaResult = store()->eaProductsData;

        $currentSeoPath = $eaResult?->getCurrentSeoPath() ?? '';

        $productName = ($this->product instanceof ItemRow)
            ? $this->product->Product_Name
            : $this->product->product_name;

        return view('widget::product.name', compact('productName', 'currentSeoPath'));
    }
}

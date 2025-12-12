<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ItemNumber
 */
class ItemNumber extends BaseComponent
{
    public function __construct(public ItemRow|Product $product, public string $format = '<b>Item Number: </b>{product_code}', public string $element = 'span')
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

    public function formattedItemNumber(): array|string
    {
        $code = $this->product instanceof ItemRow ? $this->product->Product_Code : $this->product->product_code;

        return str_replace('{product_code}', $code, $this->format);

    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['product-code']);

        return parent::htmlAttributes();
    }
}

<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Availability
 */
class Availability extends BaseComponent
{
    public int $restrictLimit = 25;

    public function __construct(public Product|ItemRow|\stdClass|null $product, public mixed $value = null)
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
        if ($this->product instanceof ItemRow && ! isset($this->product->availability)) {
            $this->product = Product::findOrFail($this->product->Amplify_Id);
        }

        $availability = $this->product?->availability?->value ?? 'A';

        return view('widget::product.availability', compact('availability'));
    }
}

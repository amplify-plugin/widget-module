<?php

namespace Amplify\Widget\Components\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class HiddenFields
 */
class HiddenFields extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private $product;

    private $input;

    private $name;

    /**
     * Create a new component instance.
     */
    public function __construct($product, $input = null, $name = null)
    {
        parent::__construct();
        $this->product = $product;
        $this->input = $input;
        $this->name = $name;
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

        return view('widget::product.hidden-fields', [
            'product' => $this->product,
            'name' => $this->name,
            'input' => $this->input,
        ]);
    }
}

<?php

namespace Amplify\Widget\Components\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Uom
 */
class Uom extends BaseComponent
{
    public function __construct(public string $code = 'EA', public string $default = 'Each')
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

        return view('widget::product.uom');
    }

    public function uomLabel(): string|\Illuminate\Support\Collection
    {
        return unit_of_measurement($this->code, $this->default);
    }
}

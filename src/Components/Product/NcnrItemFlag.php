<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class NcnrItemFlag
 */
class NcnrItemFlag extends BaseComponent
{
    public function __construct(public Product|ItemRow $product, public bool $showFullForm = false)
    {
        parent::__construct();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return $this->product?->is_ncnr ?? false;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::product.ncnr-item-flag');
    }
}

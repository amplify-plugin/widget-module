<?php

namespace Amplify\Widget\Components\Shop\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class DiscountBadge
 */
class DiscountBadge extends BaseComponent
{
    public string $discount;

    /**
     * Create a new component instance.
     *
     * @param  string  $displayListPrice
     */
    public function __construct(
        public $listPrice = '',
        public $price = '',
        public bool $displayListPrice = true
    ) {
        parent::__construct();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return is_numeric($this->listPrice) && is_numeric($this->price) && $this->listPrice > $this->price;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->discount = discount_badge_label($this->price, $this->listPrice);

        return view('widget::shop.product.discount-badge');
    }

    public function htmlAttributes(): string
    {
        $class = 'badge rounded-0';

        if ($this->discount == 'Special Price') {
            $class .= ' d-none';
        }

        $this->attributes = $this->attributes->class([$class]);

        return parent::htmlAttributes();
    }
}

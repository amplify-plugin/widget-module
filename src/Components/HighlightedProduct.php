<?php

namespace Amplify\Widget\Components;

use Amplify\System\CustomItem\Traits\CustomItemERPService;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class HighlightedProduct
 */
class HighlightedProduct extends BaseComponent
{
    use CustomItemERPService;

    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $productCode,
        public string $title,
        public string $description,
        public string $buttonText = 'Buy Now',
        public string $backgroundColor = 'secondary',
        public string $textColor = 'dark',
        public string $buttonColor = 'primary',
    ) {
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
        $product = $this->getProductDetailsFromERP($this->productCode);

        return view('widget::highlighted-product', compact('product'));
    }
}

<?php

namespace Amplify\Widget\Components\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductGallery
 */
class ProductGallery extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public $image,
        public $erpAdditionalImages = []
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
        return view('widget::product.product-gallery', [
            'productImage' => $this->image,
            'erpAdditionalImages' => $this->erpAdditionalImages,
        ]);
    }
}

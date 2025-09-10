<?php

namespace Amplify\Widget\Components\Shop\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;

/**
 * @class SocialMediaLink
 */
class SocialMediaLink extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public mixed $product;

    public $links;

    /**
     * Create a new component instance.
     */
    public function __construct($product)
    {
        parent::__construct();

        $this->product = $product;

        $this->links = [];

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return config('amplify.marketing.social_media_share', true);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        foreach (config('amplify.marketing.social_media_links', []) as $item => $value) {
            $this->links[$item] = str_replace('__webpage_url__', Request::url(), $value);
        }

        return view('widget::shop.product.social-media-link');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['d-flex flex-wrap justify-content-between']);

        return parent::htmlAttributes();
    }
}

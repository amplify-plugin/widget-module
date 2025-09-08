<?php

namespace Amplify\Widget\Components\Campaign;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class CampaignProductFilter
 */
class CampaignProductFilter extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $productView = 'list',
        public string $sortBy = 'product_code--ASC',
        public string $perPage = '10',
        public $products = [])
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

        return view('widget::campaign.campaign-product-filter');
    }
}

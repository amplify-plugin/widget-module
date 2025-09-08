<?php

namespace Amplify\Widget\Components\Campaign;

use Amplify\System\Marketing\Models\Campaign;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class CampaignList
 */
class CampaignList extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct()
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
        $campaigns = Campaign::with(['products', 'products.productImage'])
            ->latest()
            ->paginate(getPaginationLengths()[0]);

        return view('widget::campaign.campaign-list', [
            'campaigns' => $campaigns,
        ]);
    }
}

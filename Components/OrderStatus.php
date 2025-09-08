<?php

namespace Amplify\Widget\Components;

use Amplify\System\Backend\Models\CustomerOrder;
use Amplify\System\OrderRule\Models\CustomerOrderRuleTrack;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class OrderStatus
 */
class OrderStatus extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $successMessage = 'Your order has been submitted.',
        public string $approvalMessage = 'Your order has been submitted for approval.',
    ) {
        parent::__construct();

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return CustomerOrder::where('id', request()->order)->exists();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $hasOrderRule = CustomerOrderRuleTrack::where('customer_order_id', request()->order)->exists();

        return view('widget::order-status', compact('hasOrderRule'));
    }
}

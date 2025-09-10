<?php

namespace Amplify\Widget\Components\Customer\Order\Approvals;

use Amplify\System\OrderRule\Models\CustomerOrderRuleTrack;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class Index
 */
class Index extends BaseComponent
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
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);

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
        $order_waiting_approval = CustomerOrderRuleTrack::with('orderRule', 'customerOrder')
            ->where(function ($query) {
                $query->where('approver_id', customer(true)->id)
                    ->orWherehas('customerOrder', function ($q) {
                        return $q->where('contact_id', customer(true)->id);
                    });
            })->where('approver_id', customer(true)->id)->where('status', 'pending')
            ->orderBy('id', 'DESC')->get();

        return view('widget::customer.order.approvals.index', [
            'order_waiting_approval' => $order_waiting_approval,
        ]);
    }
}

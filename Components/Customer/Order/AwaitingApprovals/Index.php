<?php

namespace Amplify\Widget\Components\Customer\Order\AwaitingApprovals;

use Amplify\System\Backend\Models\CustomerOrder;
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
        $status = request('status', null);

        $order_awaiting_approval = CustomerOrder::where('contact_id', customer(true)->id)
            ->where(function ($q) use ($status) {
                if ($status) {
                    $q->where('order_status', ucfirst($status));
                } else {
                    $q->whereIn('order_status', [
                        'Awaiting Approval',
                        'Pending',
                    ]);
                }
            })
            ->orderBy('id', 'DESC')->get();

        return view('widget::customer.order.awaiting-approvals.index', [
            'order_awaiting_approval' => $order_awaiting_approval,
        ]);
    }
}

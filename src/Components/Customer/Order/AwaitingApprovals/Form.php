<?php

namespace Amplify\Widget\Components\Customer\Order\AwaitingApprovals;

use Amplify\System\Backend\Models\CustomerOrder;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class Form
 */
class Form extends BaseComponent
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
        // dd(request()->order_awaiting_approval);

        $order = CustomerOrder::where('contact_id', customer(true)->id)
            ->whereIn('order_status', [
                'Awaiting Approval',
                'Pending',
            ])
            ->with('orderRule')
            ->findOrFail(request()->order_awaiting_approval);

        // dd($order);

        return view('widget::customer.order.awaiting-approvals.form', [
            'order' => $order,
        ]);
    }
}

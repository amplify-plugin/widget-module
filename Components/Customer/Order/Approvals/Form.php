<?php

namespace Amplify\Widget\Components\Customer\Order\Approvals;

use Amplify\System\OrderRule\Models\CustomerOrderRuleTrack;
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
        $approval_data = CustomerOrderRuleTrack::findOrFail(request()->order_approval);
        $order = $approval_data->customerOrder;

        return view('widget::customer.order.approvals.form', [
            'order' => $order,
            'approval_data' => $approval_data,
        ]);
    }
}

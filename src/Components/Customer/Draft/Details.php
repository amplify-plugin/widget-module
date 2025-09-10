<?php

namespace Amplify\Widget\Components\Customer\Draft;

use Amplify\System\Backend\Models\CustomerOrder;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Details
 */
class Details extends BaseComponent
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
        $customer = customer(true);
        $order = CustomerOrder::where(['contact_id' => $customer->id, 'id' => request()->draft])
            ->with('approver', 'orderLines')
            ->firstOrFail();

        return view('widget::customer.draft.details', [
            'customer' => $customer,
            'order' => $order,
        ]);
    }
}

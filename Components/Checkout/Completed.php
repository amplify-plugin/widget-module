<?php

namespace Amplify\Widget\Components\Checkout;

use Amplify\System\Backend\Models\CustomerOrder;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Completed
 */
class Completed extends BaseComponent
{
    public $message;

    public $order;

    public function __construct(
        public $heading = 'Thank you for your order!',
        $message = 'We have got your order! Your world is about to look a whole lot better. You have earned __reward_point__ rewards.'
    ) {
        $order = CustomerOrder::findOrFail(request()->order);
        $this->order = $order;
        $this->message = str_replace('__reward_point__', '<b>'.$order->total_amount.'</b>', $message);
        $this->message = str_replace('__order_number__', '<b>'.$order->erp_order_id.'</b>', $message);
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

        return view('widget::checkout.completed', [
            'order' => $this->order,
        ]);
    }
}

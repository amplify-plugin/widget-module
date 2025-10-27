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
    public ?CustomerOrder $order;

    public function __construct(
        public string $heading = 'Thank you for your order!',
        public string $message = 'We have got your order! Your world is about to look a whole lot better. You have earned __reward_point__ rewards.'
    )
    {
        parent::__construct();

        $this->order = CustomerOrder::findOrFail(request('order'));
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
        $this->message = str_replace(['__reward_point__', '__order_number__'], ["<b>{$this->order->total_amount}</b>", "<b>{$this->order->erp_order_id}</b>"], $this->message);

        return view('widget::checkout.completed');
    }
}

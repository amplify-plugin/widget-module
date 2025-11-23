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
    ) {
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
        $isQuote = ($this->order->order_type == 1);

        // If it's a quote, override heading + message
        if ($isQuote) {

            // override heading text
            $this->heading = str_replace('order', 'quote', strtolower($this->heading));

            // override message text
            $this->message = str_replace('order', 'quote', strtolower($this->message));

            // Replace placeholders for QUOTES
            $this->message = str_replace(
                ['__reward_point__', '__quote_number__'],
                [
                    "<b>{$this->order->total_amount}</b>",
                    "<b>{$this->order->erp_order_id}</b>",
                ],
                $this->message
            );
        } else {
            // Replace placeholders for NORMAL ORDERS
            $this->message = str_replace(
                ['__reward_point__', '__order_number__'],
                [
                    "<b>{$this->order->total_amount}</b>",
                    "<b>{$this->order->erp_order_id}</b>",
                ],
                $this->message
            );
        }

        return view('widget::checkout.completed', [
            'isQuote' => $isQuote,
        ]);
    }
}

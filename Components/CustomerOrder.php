<?php

namespace Amplify\Widget\Components;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class CustomerOrder
 */
class CustomerOrder extends BaseComponent
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

    public $cart;

    public $customer;

    public $customer_details;

    public $customer_carrier_code;

    public $order_summary;

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->cart = getCart();
        $this->customer = customer(true);
        $this->customer_details = ErpApi::getCustomerDetail();
        $this->customer_carrier_code = customer()->carrier_code ?? null;
        $this->getPricing();

        // dd($this->order_summary);

        return view('widget::customer-order');
    }

    private function getPricing()
    {
        $products = collect();

        if (! empty($this->cart) && isset($this->cart->cartItems)) {
            $products = $this->cart->cartItems->map(function ($item) {
                return [
                    'ItemNumber' => $item->product_code,
                    'WarehouseID' => customer()->warehouse->code ?? null,
                    'OrderQty' => $item->quantity,
                ];
            });
        }

        $order_infos = [
            'customer_number' => $this->customer_details->CustomerNumber,
            'payment_type' => $this->customer_details->CreditCardOnly === 'Y' ? 'CreditCard' : 'Standard',
            'order_type' => 'T',
            'return_type' => 'D',
        ];

        if ($products->isNotEmpty()) {
            $this->order_summary = ErpApi::createQuotation([
                'order' => $order_infos, 'items' => $products->toArray(),
            ])->first();
        }
    }
}

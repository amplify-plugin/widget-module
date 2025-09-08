<?php

namespace Amplify\Widget\Components\Checkout;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Address
 */
class Summary extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $cart;

    public $customer;

    public $customer_details;

    public $order_summary;

    public $total;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->cart = getCart();
        $this->customer = customer(true);
        $this->customer_details = ErpApi::getCustomerDetail();
        $this->getPricing();
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
        return view('widget::checkout.summary');
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

        // if ($products->isNotEmpty()) {
        //     $this->order_summary = ErpApi::createOrder([
        //         'order' => $order_infos, 'items' => $products->toArray()
        //     ]);
        // }

        if (! empty($this->order_summary)) {
            $this->total = (object) [
                'amount' => number_format((float) $this->order_summary->InvoiceAmount, 2) ?? 0.00,
                'shippingAmount' => number_format((float) $this->order_summary->FreightAmount, 2) ?? 0.00,
                'totalAmount' => number_format(((float) $this->order_summary->InvoiceAmount ?? 0.00) + ((float) $this->order_summary->FreightAmount ?? 0.00), 2),
            ];
        }
    }
}

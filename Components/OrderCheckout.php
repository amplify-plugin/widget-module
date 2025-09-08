<?php

namespace Amplify\Widget\Components;

use Amplify\ErpApi\Collections\ShippingLocationCollection;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Country;
use Amplify\System\Backend\Models\CustomerOrder;
use Amplify\System\Backend\Models\State;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class OrderCheckout
 */
class OrderCheckout extends BaseComponent
{
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
        $customer = ErpApi::getCustomerDetail();
        $addresses = new ShippingLocationCollection;
        $order = CustomerOrder::where('contact_id', customer(true)->id)
            ->where('order_status', 'Pending')
            ->where('approval_status', 'passed')
            ->with('orderRule', 'orderLines', 'orderLines.product', 'orderLines.product.productImage')
            ->findOrFail(request()->order_id);
        $itemCount = $order->orderLines?->count() ?? 0;
        $cartPricingInfo = $this->getOrderPricing($order);

        $steps = [
            ['index' => 0, 'id' => 'customer', 'label' => 'Account', 'active' => false, 'component' => 'Customer'],
            ['index' => 1, 'id' => 'shipping', 'label' => 'Shipping', 'active' => false, 'component' => 'Shipping'],
            ['index' => 2, 'id' => 'review', 'label' => 'Review', 'active' => true, 'component' => 'Review'],
        ];

        if ($customer->CreditCardOnly == 'Y' && havePermissions(['checkout.allow-credit-card-payment'])) {
            switch (config('amplify.payment.default')) {
                case 'cenpos':
                    $billingTab = ['index' => 3, 'id' => 'billing', 'label' => 'Billing', 'active' => false, 'component' => 'CenposBilling'];
                    break;

                default:
                    $billingTab = ['index' => 3, 'id' => 'billing', 'label' => 'Billing', 'active' => false, 'component' => 'Billing'];
                    break;
            }

            $steps[] = $billingTab;
        }

        if (customer_check()) {
            $addresses = ErpApi::getCustomerShippingLocationList();
        }

        $steps = array_reverse($steps);
        $addresses->push($this->loadEmptyShippingLocation());
        $country_codes = array_map(fn ($country) => $country['id'], config('amplify.basic.countries'));
        $countries = Country::select('id', 'name', 'iso2')->whereIn('id', $country_codes)->get();
        $states = State::select('iso2', 'country_id', 'name')->whereIn('country_id', $country_codes)->get();
        $shipOptions = ErpApi::getShippingOption();
        $templateBrandColor = theme_option('primary_color');
        $hasChooseShipPermission = havePermissions(['checkout.choose-shipto']);

        return view('widget::order-checkout', compact(
            'templateBrandColor',
            'steps',
            'order',
            'itemCount',
            'customer',
            'addresses',
            'countries',
            'states',
            'shipOptions',
            'hasChooseShipPermission'
        ));
    }

    private function getOrderPricing($order)
    {
        try {
            $customerDetails = ErpApi::getCustomerDetail();
            $products = $order->orderLines->map(function ($item) {
                return [
                    'ItemNumber' => $item->product_code,
                    'WarehouseID' => $item->warehouse->warehouse->code ?? (customer()->warehouse->code ?? null),
                    'OrderQty' => $item->qty,
                ];
            });

            $order_infos = [
                'customer_number' => $customerDetails->CustomerNumber,
                'ship_to_number' => $customerDetails->DefaultShipTo,
                'payment_type' => $customerDetails->CreditCardOnly === 'Y' ? 'CreditCard' : 'Standard',
                'order_type' => 'T',
                'return_type' => 'D',
            ];

            $quote = ErpApi::createQuotation([
                'order' => $order_infos, 'items' => $products->toArray(),
            ])->first();

            return [
                'order_subtotal' => $quote->TotalOrderValue,
                'order_tax' => $quote->SalesTaxAmount,
                'order_ship' => $quote->FreightAmount,
                'order_total' => $quote->TotalOrderValue + $quote->SalesTaxAmount + $quote->FreightAmount,
                'threshold_limit' => $customerDetails->FreightOptionAmount ?? config('amplify.marketing.free_ship_threshold'),
                'threshold_message' => config('amplify.marketing.checkout_threshold_replace'),

            ];

        } catch (\Exception $exception) {
            return [
                'order_subtotal' => null,
                'order_tax' => null,
                'order_ship' => null,
                'order_total' => null,
                'threshold_limit' => config('amplify.marketing.free_ship_threshold'),
                'threshold_message' => customer()->free_shipment_amount ?? config('amplify.marketing.checkout_threshold_replace'),
            ];
        }
    }

    private function loadEmptyShippingLocation()
    {
        return ErpApi::adapter()->renderSingleCustomerShippingLocation([
            'ShipToNumber' => 'TEMP',
            'ShipToName' => strtoupper('Temporary address'),
        ]);
    }
}

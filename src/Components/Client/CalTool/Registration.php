<?php

namespace Amplify\Widget\Components\Client\CalTool;

use Amplify\ErpApi\Collections\ShippingLocationCollection;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Cart;
use Amplify\System\Backend\Models\Country;
use Amplify\System\Backend\Models\State;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Registration
 */
class Registration extends BaseComponent
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
        $cart = getCart();
        $customer = ErpApi::getCustomerDetail();
        $cartItemCount = ($cart instanceof Cart) ? $cart->cartItems()->count() : 0;
        $addresses = new ShippingLocationCollection;
        $stepCount = 0;
        $steps = [
            [
                'index' => $stepCount, 'id' => 'customer', 'label' => 'Account', 'active' => true,
                'component' => 'Customer',
            ],
            [
                'index' => $stepCount += 1, 'id' => 'shipping', 'label' => 'Shipping', 'active' => false,
                'component' => 'Shipping',
            ],
        ];

        if ($customer->CreditCardOnly == 'N') {
            $steps[] = match (config('amplify.payment.default')) {
                'cenpos' => ['index' => $stepCount += 1, 'id' => 'billing', 'label' => 'Billing', 'active' => false, 'component' => 'CenposBilling'],
                default => ['index' => $stepCount += 1, 'id' => 'billing', 'label' => 'Billing', 'active' => false, 'component' => 'Billing'],
            };
        }

        $reviews = ['index' => $stepCount += 1, 'id' => 'review', 'label' => 'Review', 'active' => false, 'component' => 'Review'];
        $steps[] = $reviews;

        if (customer_check()) {
            $addresses = ErpApi::getCustomerShippingLocationList();
        }

        $steps = array_reverse($steps);
        $addresses->push($this->loadEmptyShippingLocation());

        $country_codes = array_map(fn ($country) => $country['id'], config('amplify.basic.countries'));
        $countries = Country::select(['iso2', 'id', 'name'])->whereIn('id', $country_codes)->orderBy('name', 'ASC')->get();
        $states = State::select(['iso2', 'country_id', 'country_code', 'name'])->whereIn('country_id', $country_codes)->orderBy('name', 'ASC')->get();
        $shipOptions = ErpApi::getShippingOption();
        $templateBrandColor = theme_option('primary_color');

        $cenposPaymentUrl = config('amplify.payment.gateways.cenpos.payment_url');
        $clientCode = config('amplify.basic.client_code');

        return view('widget::client.cal-tool.checkout', compact('templateBrandColor', 'steps', 'cart', 'cartItemCount', 'customer', 'addresses', 'countries', 'states', 'shipOptions', 'cenposPaymentUrl', 'clientCode'));
    }

    private function loadEmptyShippingLocation()
    {
        return match (config('amplify.erp.default')) {
            'default' => ErpApi::adapter()->renderSingleCustomerShippingLocation([
                'id' => 'TEMP',
                'address_name' => strtoupper('Temporary address'),
            ]),
            default => ErpApi::adapter()->renderSingleCustomerShippingLocation([
                'ShipToNumber' => 'TEMP',
                'ShipToName' => strtoupper('Temporary address'),
            ]),
        };
    }
}

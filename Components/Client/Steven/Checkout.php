<?php

namespace Amplify\Widget\Components\Client\Steven;

use Amplify\ErpApi\Collections\ShippingLocationCollection;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Cart;
use Amplify\System\Backend\Models\Country;
use Amplify\System\Backend\Models\State;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Checkout
 */
class Checkout extends BaseComponent
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
    public function render(): View|Closure|string|array
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

        $steps[] = match (config('amplify.payment.default')) {
            'cenpos' => ['index' => $stepCount += 1, 'id' => 'billing', 'label' => 'Billing', 'active' => false, 'component' => 'CenposBilling'],
            default => ['index' => $stepCount += 1, 'id' => 'billing', 'label' => 'Billing', 'active' => false, 'component' => 'Billing'],
        };

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
        $templateBrandColor = template_option('primary_color');
        $cenposPaymentUrl = config('amplify.payment.gateways.cenpos.payment_url');
        $cenposAchPaymentUrl = config('amplify.payment.gateways.cenpos.ach_payment_url');
        $clientCode = config('amplify.basic.client_code');
        $cenposMerchantId = config('amplify.payment.gateways.cenpos.merchant_id');
        $termsType = ErpApi::getTermsType();
        $isCreditCardRequired = $termsType->isCreditCardOnly();
        $isAchOnly = $termsType->isACHOnly();

        return view('widget::client.steven.checkout', compact(
            'cart',
            'customer',
            'cartItemCount',
            'steps',
            'addresses',
            'isCreditCardRequired',
            'countries',
            'states',
            'shipOptions',
            'templateBrandColor',
            'cenposPaymentUrl',
            'clientCode',
            'isAchOnly',
            'cenposMerchantId',
            'cenposAchPaymentUrl',
        ));
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

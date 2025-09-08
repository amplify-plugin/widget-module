<?php

namespace Amplify\Widget\Components\Client\SpiSafety;

use Amplify\ErpApi\Collections\ShippingLocationCollection;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Http\Controllers\CustomerOrderController;
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
    public function render(): View|Closure|string
    {
        $cart = getCart();
        $customer = ErpApi::getCustomerDetail();
        $cartPricingInfo = (new CustomerOrderController)->getOrderPricing();
        $cartItemCount = ($cart instanceof Cart) ? $cart->cartItems()->count() : 0;
        $addresses = new ShippingLocationCollection;

        $steps = [
            ['index' => 0, 'id' => 'customer', 'label' => 'Account', 'active' => true, 'component' => 'Customer'],
            ['index' => 1, 'id' => 'shipping', 'label' => 'Shipping', 'active' => false, 'component' => 'Shipping'],
            ['index' => 2, 'id' => 'review', 'label' => 'Review', 'active' => false, 'component' => 'Review'],
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

        return view('widget::checkout', compact('templateBrandColor', 'steps', 'cart', 'cartItemCount', 'customer', 'cartPricingInfo', 'addresses', 'countries', 'states', 'shipOptions', 'hasChooseShipPermission'));
    }

    private function loadEmptyShippingLocation()
    {
        switch (config('amplify.erp.default')) {
            case 'default':
                return ErpApi::adapter()->renderSingleCustomerShippingLocation([
                    'id' => 'TEMP',
                    'address_name' => strtoupper('Temporary address'),
                ]);
                break;

            default:
                return ErpApi::adapter()->renderSingleCustomerShippingLocation([
                    'ShipToNumber' => 'TEMP',
                    'ShipToName' => strtoupper('Temporary address'),
                ]);
                break;
        }
    }
}

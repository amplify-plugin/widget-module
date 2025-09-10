<?php

namespace Amplify\Widget\Components\Checkout;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Country;
use Amplify\System\Backend\Models\State;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Address
 */
class Address extends BaseComponent
{
    public function __construct(public bool $isActive = false, public string $id = 'address', public ?int $index = null)
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
        $addresses = ErpApi::getCustomerShippingLocationList();

        $addresses->push($this->loadEmptyShippingLocation());

        $states = State::enabled()->get()->pluck('name', 'iso2')->toArray();

        $countries = Country::enabled()->get()->pluck('name', 'iso2')->toArray();

        $customer = ErpApi::getCustomerDetail();

        return view('widget::checkout.address', compact('addresses', 'states', 'customer', 'countries'));
    }

    private function loadEmptyShippingLocation()
    {
        return ErpApi::adapter()->renderSingleCustomerShippingLocation([
            'ShipToNumber' => 'TEMP',
            'ShipToName' => strtoupper('Temporary address'),
            'ShipToCountryCode' => '',
            'ShipToAddress1' => '',
            'ShipToAddress2' => '',
            'ShipToAddress3' => '',
            'ShipToCity' => '',
            'ShipToState' => '',
            'ShipToZipCode' => '',
            'ShipToPhoneNumber' => '',
            'ShipToContact' => '',
            'ShipToWarehouse' => '',
            'BackorderCode' => '',
            'CarrierCode' => '',
            'PoRequired' => '',
        ]);
    }
}

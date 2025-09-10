<?php

namespace Amplify\Widget\Components\Customer\Address;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ShipToAddress
 */
class ShipToAddress extends BaseComponent
{
    public $addresses;

    public $selectedShipToAddress;

    public function __construct()
    {
        parent::__construct();

        $this->addresses = ErpApi::getCustomerShippingLocationList();
        $this->selectedShipToAddress = session('ship_to_address') ?? null;
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
        return view('widget::customer.address.ship-to-address');
    }
}

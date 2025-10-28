<?php

namespace Amplify\Widget\Components\Customer;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

/**
 * @class CurrentSession
 */
class CurrentSession extends BaseComponent
{
    public array $sessionInfo = [];

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer_check();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->sessionInfo = Cache::remember(Session::token().'session-info', 30 * MINUTE, function () {

            $contact = customer(true);
            $customer = $contact->customer;
            $currentLogin = $contact->contactLogins()->latest()->first();
            $sessionWarehouse = $currentLogin?->warehouse ?? null;
            $sessionShipTo = $currentLogin?->customerAddress ?? null;

            $sessionInfo['Account Name'] = $contact->name ?? 'N/A';
            $sessionInfo['Customer'] = $customer->customer_name ? "{$customer->erp_id} - {$customer->customer_name}" : 'N/A';
            $sessionInfo['Warehouse'] = $sessionWarehouse ? "{$sessionWarehouse?->code} - {$sessionWarehouse?->name}" : 'None';
            $sessionInfo['Pickup Location'] = 'None';
            $sessionInfo['Shipping Address'] = $sessionShipTo?->address_name ? "{$sessionShipTo?->address_code} - {$sessionShipTo?->address_name}" : 'None';
            $sessionInfo['Phone Number'] = $contact->phone ?? 'N/A';
            $sessionInfo['Email Address'] = $contact->email ?? 'N/A';

            return $sessionInfo;
        });

        return view('widget::customer.current-session');
    }
}

<?php

namespace Amplify\Widget\Components\Checkout;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Contact;
use Amplify\System\Backend\Models\Country;
use Amplify\System\Backend\Models\State;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Customer
 */
class Customer extends BaseComponent
{
    public function __construct(public bool $isActive = false, public string $id = 'customer', public ?int $index = null)
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
        $customer = customer_check() ? ErpApi::getCustomerDetail() : ErpApi::adapter()->getCustomerDetail();

        $contact = customer_check() ? customer(true) : new Contact;

        $countries = Country::enabled()->get()->pluck('name', 'iso2')->toArray();

        $states = State::enabled()->get()->pluck('name', 'iso2')->toArray();

        return view('widget::checkout.customer', compact('contact', 'customer', 'states', 'countries'));
    }
}

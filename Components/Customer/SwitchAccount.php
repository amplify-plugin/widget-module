<?php

namespace Amplify\Widget\Components\Customer;

use Amplify\System\Backend\Models\ContactLogin;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class SwitchAccount
 */
class SwitchAccount extends BaseComponent
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

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $assignableCustomers = [];
        $account = customer(true);

        $customers = ContactLogin::select('customers.customer_name', 'contact_logins.customer_id')
            ->where('contact_logins.contact_id', $account->getKey())
            ->join('customers', 'contact_logins.customer_id', '=', 'customers.id')
            ->get();

        return view('widget::customer.switch-account', compact('account', 'customers'));
    }
}

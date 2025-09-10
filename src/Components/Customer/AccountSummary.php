<?php

namespace Amplify\Widget\Components\Customer;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class AccountSummary extends BaseComponent
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
        return customer(true)->can('account-summary.allow-account-summary');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @throws \ErrorException
     */
    public function render(): View|Closure|string
    {
        $customer = customer();
        $accountSummary = erp()->getCustomerARSummary(['customer_number' => $customer->customer_code]);

        return view('widget::customer.account-summary', [
            'accountSummary' => $accountSummary,
        ]);
    }
}

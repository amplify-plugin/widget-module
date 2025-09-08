<?php

namespace Amplify\Widget\Components\Customer;

use Amplify\Frontend\Helpers\CustomerHelper;
use Amplify\System\Backend\Models\Contact;
use Amplify\System\Backend\Models\Customer;
use Amplify\System\Helpers\SecurityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;

/**
 * @class Profile
 */
class Profile extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public Authenticatable|null|Customer|Contact $account;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->account = customer(true);

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
        $urls = CustomerHelper::redirecteableUrls();
        $minPassLength = SecurityHelper::passwordLength();

        return view('widget::customer.profile', compact('urls', 'minPassLength'));
    }

    public function customerName()
    {
        return Customer::find($this->account->customer_id)->customer_name ?? null;
    }
}

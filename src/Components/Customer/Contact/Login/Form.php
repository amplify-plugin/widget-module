<?php

namespace Amplify\Widget\Components\Customer\Contact\Login;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Contact;
use Amplify\System\Backend\Models\CustomerPermission;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class Form
 */
class Form extends BaseComponent
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
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);
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
        $contactModel = store('contactModel');
        $warehouses = ErpApi::getWarehouses();
        $raw_permissions = CustomerPermission::where('guard_name', Contact::AUTH_GUARD)->orderBy('name', 'ASC')->pluck('name', 'id');
        $permissions = [];

        foreach ($raw_permissions as $key => $option) {
            $group = explode('.', $option, 2);
            $permissions[$group[0]][$key] = [
                'label' => ucwords(str_replace(['.', '-', 'show'], [' ', ' ', 'detail'], $option)),
                'code' => $option,
                'is_checked' => false,
            ];
        }

        return view('widget::customer.contact.login.form', compact('contactModel', 'warehouses', 'permissions'));
    }
}

<?php

namespace Amplify\Widget\Components\Customer\Contact;

use Amplify\Frontend\Helpers\CustomerHelper;
use Amplify\System\Backend\Models\Contact;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Create
 */
class Form extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * @var Contact|null
     */
    public $contact;

    private bool $editable;

    /**
     * Create a new component instance.
     *
     * @param  bool  $editable
     *
     * @throws \ErrorException
     */
    public function __construct($editable)
    {
        parent::__construct();

        $this->editable = UtilityHelper::typeCast($editable, 'boolean');

        if ($this->editable) {
            $this->contact = store()->contactModel;
        }
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
        $addresses = \Amplify\System\Backend\Models\CustomerAddress::where('customer_id', customer()->id)->get();
        $roles = \Amplify\System\Backend\Models\Role::where(['team_id' => customer()->id, 'guard_name' => 'customer'])->get();

        $action_route = route('frontend.contacts.store');
        $action_method = 'POST';
        $contact_roles = [];

        if ($this->editable) {
            $action_route = route('frontend.contacts.update', ($this->contact->id ?? ''));
            $action_method = 'PUT';
            $contact_roles = $this->contact->roles->pluck('id')->toArray();
        }

        $urls = CustomerHelper::redirecteableUrls();

        return view('widget::customer.contact.form', [
            'addresses' => $addresses,
            'roles' => $roles,
            'contact' => $this->contact,
            'action_route' => $action_route,
            'action_method' => $action_method,
            'editable' => $this->editable,
            'contact_roles' => $contact_roles,
            'urls' => $urls,
        ]);
    }
}

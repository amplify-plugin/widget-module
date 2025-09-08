<?php

namespace Amplify\Widget\Components\Customer\Contact;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Details
 */
class Details extends BaseComponent
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
        $contact = request('contact');

        if ($contact->customer_id != customer()->id) {
            abort(401, 'Unauthorized');
        }

        $addresses = \Amplify\System\Backend\Models\CustomerAddress::where('customer_id', customer()->id)->get();
        $roles = \Amplify\System\Backend\Models\Role::where(['team_id' => customer()->id, 'guard_name' => 'customer'])->get();

        return view('widget::customer.contact.show', [
            'contact' => $contact,
            'addresses' => $addresses,
            'roles' => $roles,
        ]);
    }
}

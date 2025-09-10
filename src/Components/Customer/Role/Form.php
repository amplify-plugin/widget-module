<?php

namespace Amplify\Widget\Components\Customer\Role;

use Amplify\System\Backend\Models\CustomerRole;
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

    private bool $editable;

    /**
     * @var CustomerRole|null
     */
    public $role;

    /**
     * Create a new component instance.
     *
     * @throws \ErrorException
     */
    public function __construct($editable)
    {
        parent::__construct();

        $this->editable = UtilityHelper::typeCast($editable, 'boolean');

        if ($this->editable) {
            $this->role = store('contactRoleModel');
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
        $action_route = route('frontend.roles.store');
        $action_method = 'POST';
        $role_permissions = [];

        if ($this->editable) {
            $action_route = route('frontend.roles.update', ($this->role->id ?? ''));
            $action_method = 'PUT';
            $role_permissions = $this->role->permissions->pluck('id')->toArray();
        }

        return view('widget::customer.role.form', [
            'role' => $this->role,
            'editable' => $this->editable,
            'action_route' => $action_route,
            'action_method' => $action_method,
            'role_permissions' => $role_permissions,
        ]);
    }
}

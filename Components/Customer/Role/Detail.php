<?php

namespace Amplify\Widget\Components\Customer\Role;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Update
 */
class Detail extends BaseComponent
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
        $role = store()->contactRoleModel;

        $permissionArray = [];

        foreach ($role->permissions as $key => $permission) {
            $group = explode('.', $permission->name, 2);
            $permissionArray[$group[0]][$key] = $permission->name;
        }

        return view('widget::customer.role.show', [
            'role' => $role,
            'permissionArray' => $permissionArray,
        ]);
    }
}

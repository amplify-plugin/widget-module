<?php

namespace Amplify\Widget\Components\Customer\Role\Partials;

use Amplify\System\Backend\Models\Permission;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Permissions
 */
class Permissions extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(public $role = null)
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
        $key_attribute = 'id';

        $field['options'] = Permission::where('guard_name', 'customer')->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $field['value'] = $this->role?->permissions()->pluck($key_attribute)->toArray() ?? [];
        $field['label'] = 'Permission';
        $field['name'] = 'permission';
        $field['fieldInitChecklist'] = 'fieldInitChecklist'.$field['name'];

        $permissionOptions = [];

        foreach ($field['options'] as $key => $option) {
            $group = explode('.', $option, 2);
            $permissionOptions[$group[0]][$key] = $option;
        }

        return view('widget::customer.role.partials.permissions', [
            'model' => Permission::class,
            'key_attribute' => $key_attribute,
            'field' => $field,
            'permissionOptions' => $permissionOptions,
        ]);
    }
}

<?php

namespace Amplify\Widget\Components\Customer\Role;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class Index extends BaseComponent
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
        return customer(true)->can('role.view');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $perPageOptions = getPaginationLengths();
        $perPage = request('per_page', $perPageOptions[0]);
        $search = request('search');
        $roles = \Amplify\System\Backend\Models\Role::query();

        if ($search) {
            $roles->where('name', 'like', "%{$search}%");
        }

        $roles = $roles->where(['team_id' => customer()->id, 'guard_name' => 'customer'])
            ->latest()->paginate($perPage);

        return view('widget::customer.role.index', [
            'perPageOptions' => $perPageOptions,
            'perPage' => $perPage,
            'search' => $search,
            'roles' => $roles,
        ]);
    }
}

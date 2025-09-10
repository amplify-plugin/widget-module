<?php

namespace Amplify\Widget\Components\Deprecate;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Menu
 */
class Menu extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private $menu_group;

    /**
     * Create a new component instance.
     */
    public function __construct($menu_group)
    {
        parent::__construct();

        $this->menu_group = $menu_group;
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

        return view('widget::deprecate.menu', [
            'menu_group' => $this->menu_group,
        ]);
    }
}

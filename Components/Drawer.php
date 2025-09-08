<?php

namespace Amplify\Widget\Components;

use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Drawer
 */
class Drawer extends BaseComponent
{
    public bool $onlyDrawer;

    public function __construct(
        public string $title,
        public string $id,
        public string $toggleIcon = '📂',
        string $onlyDrawer = 'false'
    ) {
        $this->onlyDrawer = UtilityHelper::typeCast($onlyDrawer, 'bool');

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

        return view('widget::drawer');
    }

    public function htmlAttributes(): string
    {
        //        dd($this->attributes->getAttributes());

        $this->attributes = $this->attributes->class(['offcanvas-container']);

        return parent::htmlAttributes();
    }
}

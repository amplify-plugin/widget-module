<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Toolbar
 */
class Toolbar extends BaseComponent
{
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

        return view('widget::toolbar');
    }

    public function searchBoxPlaceholder()
    {
        return config('amplify.search.search_box_placeholder');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class('toolbar');

        return parent::htmlAttributes();
    }
}

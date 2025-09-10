<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ScrollToTop
 */
class ScrollToTop extends BaseComponent
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
        return view('widget::scroll-to-top');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->merge(['class' => 'scroll-to-top-btn', 'href' => '#']);

        return parent::htmlAttributes();
    }
}

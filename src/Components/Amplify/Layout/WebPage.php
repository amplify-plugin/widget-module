<?php

namespace Amplify\Widget\Components\Amplify\Layout;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class WebPageLayout
 */
class WebPage extends BaseComponent
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
        return view('widget::amplify.layout.web-page');
    }

    /**
     * @throws \ErrorException
     */
    public function pageTitle(): string
    {
        return ucwords(store('pageTitle', 'Index')).' - '.config('app.name');
    }
}

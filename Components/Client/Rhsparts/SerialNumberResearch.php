<?php

namespace Amplify\Widget\Components\Client\Rhsparts;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class SerialNumberResearch
 */
class SerialNumberResearch extends BaseComponent
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
        return view('widget::client.rhsparts.serial-number-research');
    }
}

<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Checkout
 */
class Checkout extends BaseComponent
{
    public $component;

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
        $class = match (config('amplify.client_code')) {
            'ACT' => \Amplify\Widget\Components\Client\CalTool\Checkout::class,
            'RHS' => \Amplify\Widget\Components\Client\Rhsparts\Checkout::class,
            'DKL' => \Amplify\Widget\Components\Client\DKLOK\Checkout::class,
            'NUX' => \Amplify\Widget\Components\Client\Nudraulix\Checkout::class,
            default => \Amplify\Widget\Components\Client\SpiSafety\Checkout::class,
        };

        $this->component = new $class;

        $this->component->attributes = $this->attributes;

        return $this->component->render();
    }
}

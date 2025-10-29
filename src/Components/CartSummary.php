<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class CartSummary
 */
class CartSummary extends BaseComponent
{
    public BaseComponent $component;

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
            'MW' => \Amplify\Widget\Components\Client\MountainWest\CartSummary::class,
            'RHS' => \Amplify\Widget\Components\Client\Rhsparts\CartSummary::class,
            default => \Amplify\Widget\Components\Client\Demo\CartSummary::class,
        };

        $this->component = new $class;

        $this->component->attributes = $this->attributes;

        return $this->component->render();
    }
}

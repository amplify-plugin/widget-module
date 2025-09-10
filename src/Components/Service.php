<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Service
 */
class Service extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public $serviceData;

    public function __construct()
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
        return view('widget::service');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['padding-bottom-2x']);

        return parent::htmlAttributes();
    }
}

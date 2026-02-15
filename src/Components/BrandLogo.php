<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class BrandLogo
 */
class BrandLogo extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $imageAlt = null)
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
        return view('widget::brand-logo');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['site-branding']);

        return parent::htmlAttributes();
    }
}

<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Captcha
 */
class Captcha extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public bool $display = true,
        public string $id = 'captcha',
        public string $fieldName = 'captcha',
        public bool $reloadCaptcha = true,
    ) {
        parent::__construct();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return $this->display;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::captcha');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['form-group']);

        return parent::htmlAttributes();
    }
}

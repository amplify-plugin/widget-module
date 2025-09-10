<?php

namespace Amplify\Widget\Components\Auth;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ForgotPassword
 */
class ForgotPassword extends BaseComponent
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
        return view('widget::auth.forgot-password');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->merge(['id' => 'app']);

        return parent::htmlAttributes();
    }
}

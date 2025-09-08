<?php

namespace Amplify\Widget\Components\Auth\Login;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class InputRememeberForgotPassword
 */
class InputRememberForgotPassword extends BaseComponent
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

        return view('widget::auth.login.input-remember-forgot-password');
    }
}

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
    public function __construct(public string $title = 'Forgot Your Password?',
                                public string $buttonTitle = 'Submit',
                                public bool   $togglePassword = false)
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
        return view('widget::auth.forgot-password');
    }

    public function displayableTitle()
    {
        if ($this->title == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'title');

            return $titleAttribute['value'];
        }

        return trans($this->title);
    }

    public function submitButtonTitle()
    {
        if ($this->buttonTitle == '') {
            $buttonTitleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'button-title');

            return $buttonTitleAttribute['value'];
        }

        return trans($this->buttonTitle);
    }

}

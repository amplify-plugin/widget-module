<?php

namespace Amplify\Widget\Components\Auth;

use Amplify\System\Helpers\SecurityHelper;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Login
 */
class Login extends BaseComponent
{
    public $referrer;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $title = 'Login into you account',
        public string $buttonTitle = 'Login',
        public string $displayRegisterLink = 'false',
        public bool $togglePassword = false,
        public string $registerLinkText = 'Existing customer request online access?',
        public bool $honeyPotProtection = false
    ) {
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
        $this->referrer = session('url.intended', '/');

        $minPassLength = SecurityHelper::passwordLength();

        return view('widget::auth.login', compact('minPassLength'));
    }

    public function displayableTitle()
    {
        if ($this->title == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'title');

            return $titleAttribute['value'];
        }

        return trans($this->title);
    }

    public function loginButtonTitle()
    {
        if ($this->buttonTitle == '') {
            $buttonTitleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'button-title');

            return $buttonTitleAttribute['value'];
        }

        return trans($this->buttonTitle);
    }

    public function displayRegisterLink()
    {
        return UtilityHelper::typeCast($this->displayRegisterLink, 'bool');
    }

    public function registerTagLabel()
    {
        if ($this->registerLinkText == '') {
            $registerLinkText = collect($this->options['@attributes'])->firstWhere('name', '=', 'register-link-text');

            return $registerLinkText['value'];
        }

        return trans($this->registerLinkText);
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['login-box']);

        return parent::htmlAttributes();
    }
}

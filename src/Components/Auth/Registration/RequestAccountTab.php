<?php

namespace Amplify\Widget\Components\Auth\Registration;

use Amplify\System\Backend\Models\AccountTitle;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

/**
 * @class RequestAccountTab
 */
class RequestAccountTab extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title = 'Request Account Tab',
        public string $subtitle = 'Additional',
        public string $submitButtonLabel = 'Submit',
        public string $submitButtonColor = 'primary',
        public bool $newsletterSubscription = false,
        public bool $acceptTermsConfirmation = false,
        public bool $captchaVerification = false,
        public bool $active = false,
        public bool $withCustomerVerification = false,
        public string $newsletterLabel = 'Get Newsletter Subscription',
        public string $termsLabel = 'I agree the terms and conditions',
    ) {

        if (session()->has('tab')) {
            $this->active = session('tab') == 'request-account';
        }

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
        $accountTitles = AccountTitle::enabled()->get()->pluck('name', 'id')->toArray();

        return view('widget::auth.registration.request-account-tab', compact('accountTitles'));
    }

    public function htmlAttributes(): string
    {
        $classes = ['tab-pane'];

        if ($this->active) {
            $classes[] = 'fade show active';
        }

        $this->attributes = $this->attributes->merge([
            'class' => implode(' ', $classes),
            'role' => 'tabpanel',
            'aria-labelledby' => $this->slugTitle().'-tab',
            'id' => $this->slugTitle(),
        ]);

        return parent::htmlAttributes();
    }

    public function slugTitle(): string
    {
        return Str::slug($this->title);
    }

    public function displayableTitle()
    {
        if ($this->title == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'title');

            return $titleAttribute['value'];
        }

        return trans($this->title);
    }

    public function displayableSubTitle()
    {
        if ($this->subtitle == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'subtitle');

            return $titleAttribute['value'];
        }

        return trans($this->subtitle);
    }

    public function submitButtonLabel()
    {
        if ($this->submitButtonLabel == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'submit-button-label');

            return $titleAttribute['value'];
        }

        return trans($this->submitButtonLabel);
    }

    public function minPasswordLength()
    {
        return config('amplify.security.password_length', '8');
    }
}

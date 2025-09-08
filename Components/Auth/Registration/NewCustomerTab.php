<?php

namespace Amplify\Widget\Components\Auth\Registration;

use Amplify\System\Backend\Models\AccountTitle;
use Amplify\System\Backend\Models\Country;
use Amplify\System\Backend\Models\IndustryClassification;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

/**
 * @class NewCashCustomerTab
 */
class NewCustomerTab extends BaseComponent
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
        public bool $askIndustryClassification = false,
        public string $newsletterLabel = 'Get Newsletter Subscription',
        public string $termsLabel = 'I agree the terms and conditions',
        public bool $confirmPassword = true
    ) {
        parent::__construct();

        if (request()->filled('tab')) {
            $this->active = request('tab') == 'cash-customer';
        }

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
        $countries = Country::enabled()->pluck('name', 'iso2')->toArray();

        $industries = IndustryClassification::enabled()->pluck('name', 'id')->toArray();

        $accountTitles = AccountTitle::enabled()->get()->pluck('name', 'id')->toArray();

        return view('widget::auth.registration.new-cash-customer-tab', compact('countries', 'industries', 'accountTitles'));
    }

    public function htmlAttributes(): string
    {
        $classes = ['tab-pane'];

        if ($this->active) {
            $classes[] = 'active';
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
}

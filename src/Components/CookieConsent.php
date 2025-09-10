<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class CookieConsent
 */
class CookieConsent extends BaseComponent
{
    public string $title;

    public string $content;

    public function __construct(public string $modalWidth = 'xl')
    {
        parent::__construct();

        $this->title = config('amplify.security.cookie_title', 'We value your privacy');
        $this->content = config('amplify.security.cookie_content', <<<'HTML'
                <p>
                    This website stores cookies on your computer. These cookies are used to improve your website experience and provide more personalized services to you, both on this website and through other media. To find out more about the cookies we use, see our Privacy Policy.
                    <br>
                    <br>
                    We won't track your information when you visit our site. But in order to comply with your preferences, we'll have to use just one tiny cookie so that you're not asked to make this choice again.
                </p>
HTML);

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

        return view('widget::cookie-consent');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->merge([
            'class' => 'modal fade',
            'id' => 'cookie-consent-modal',
            'data-backdrop' => 'static',
            'data-keyboard' => 'false',
            'tabindex' => '-1',
            'aria-labelledby' => 'staticBackdropLabel',
            'aria-hidden' => 'true',
        ]);

        return parent::htmlAttributes();
    }
}

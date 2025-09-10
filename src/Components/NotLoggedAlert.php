<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class NotLoggedAlert
 */
class NotLoggedAlert extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $height = '300px',
        public string $width = '100%',
        public string $backgroundColor = '#ffffff',
        public string $textColor = '#000000',
        public string $message = 'You are not logged in. Do you like to log in now?',
        public string $frontSize = '16px',
        public string $buttonLabel = 'Go to Login',
        public string $buttonClass = '',
        public string $buttonColorClass = '',
    ) {
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return ! customer_check();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::not-logged-alert');
    }
}

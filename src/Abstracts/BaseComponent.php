<?php

namespace Amplify\Widget\Abstracts;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class BaseComponent extends Component
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->options = Config::get('amplify.widget.'.get_class($this), []);
    }

    public function htmlAttributes(): string
    {
        $this->appendComponentNameClass();

        return $this->attributes->toHtml();
    }

    private function appendComponentNameClass(): void
    {
        $componentClasses[] = Str::replace(['.', '_'], '-', 'x-'.$this->componentName);

        if (config('amplify.debug', false)) {
            $componentClasses[] = 'amplify-debug-bar';
            $this->attributes->offsetSet('data-component', 'x-'.$this->componentName);
        }

        $this->attributes = $this->attributes->class($componentClasses);
    }
}

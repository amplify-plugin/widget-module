<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Subscriber
 */
class Subscriber extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(private string $buttonTitle = 'Subscribe',
        private string $inputPlaceholder = 'Email Address')
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

        return view('widget::subscriber');
    }

    public function displayButtonTitle()
    {
        if ($this->buttonTitle == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'button-title');

            return $titleAttribute['value'];
        }

        return $this->buttonTitle;
    }

    public function displayPlaceholder()
    {
        if ($this->inputPlaceholder == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'input-placeholder');

            return $titleAttribute['value'];
        }

        return $this->inputPlaceholder;

    }
}

<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Image
 */
class Image extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * @var string|string
     */
    public $source;

    /**
     * @var string|string
     */
    public $alternativeText;

    /**
     * Create a new component instance.
     */
    public function __construct(string $source,
        string $alternativeText)
    {
        parent::__construct();

        $this->source = $source;
        $this->alternativeText = $alternativeText;
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

        return view('widget::image');
    }
}

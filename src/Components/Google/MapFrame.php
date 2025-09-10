<?php

namespace Amplify\Widget\Components\Google;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class GoogleMap
 */
class MapFrame extends BaseComponent
{
    public $address;

    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct($address)
    {
        parent::__construct();
        $this->address = $address;
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

        return view('widget::google-map');
    }
}

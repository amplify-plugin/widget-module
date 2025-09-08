<?php

namespace Amplify\Widget\Components;

use Amplify\Frontend\Store\StoreDataBus;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Poster
 */
class Poster extends BaseComponent
{
    public $zone;

    public $code;

    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct($zone, $code)
    {
        parent::__construct();
        $this->zone = $zone;
        $this->code = $code;
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
        $dataBus = StoreDataBus::init();

        return view('widget::poster', [
            'easyAskData' => $dataBus->eaProductsData,
        ]);
    }
}

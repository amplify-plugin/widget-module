<?php

namespace Amplify\Widget\Components\Customer\Quotation;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Details
 */
class Details extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private $component;

    /**
     * Create a new component instance.
     */
    public function __construct(public $showCreateOrderButton = true, public $showCartButton = true)
    {
        parent::__construct();

        // if string then convert to boolean
        $this->showCreateOrderButton = filter_var($showCreateOrderButton, FILTER_VALIDATE_BOOLEAN);
        $this->showCartButton = filter_var($showCartButton, FILTER_VALIDATE_BOOLEAN);

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer(true)->can('quote.view');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $class = match (config('amplify.basic.client_code')) {
            'RHS' => \Amplify\Widget\Components\Client\Rhsparts\Quotation\Details::class,
            'STV' => \Amplify\Widget\Components\Client\Steven\Quotation\Details::class,
            default => \Amplify\Widget\Components\Client\Demo\Quotation\Details::class,
        };
        $this->component = new $class;

        $this->component->attributes = $this->attributes;

        return $this->component->render();
    }
}

<?php

namespace Amplify\Widget\Components\Customer\Invoice;

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
    public function __construct(public $invoiceNumberTitle = 'Invoice No.', public $showSignedShipperBtn = true, public $showTrackingButton = false)
    {
        parent::__construct();

        // if string then convert to boolean
        $this->showSignedShipperBtn = filter_var($showSignedShipperBtn, FILTER_VALIDATE_BOOLEAN);
        $this->showTrackingButton = filter_var($showTrackingButton, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer(true)->can('invoices.view');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $class = match (config('amplify.client_code')) {
            default => \Amplify\Widget\Components\Client\Demo\Invoice\Details::class,
        };
        $this->component = new $class;

        $this->component->attributes = $this->attributes;

        return $this->component->render();

    }
}

<?php

namespace Amplify\Widget\Components\Customer;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Cylinders
 */
class Cylinders extends BaseComponent
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
        $customer = customer();
        $cylinders = erp()->getCylinders(['customer_number' => $customer->customer_code]);

        return view('widget::customer.cylinders', compact('cylinders'));
    }
}

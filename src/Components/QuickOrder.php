<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class QuickOrder
 */
class QuickOrder extends BaseComponent
{
    public $checkWarehouseQtyAvailability;

    public function __construct($checkWarehouseQtyAvailability = true)
    {
        parent::__construct();
        $this->checkWarehouseQtyAvailability = $checkWarehouseQtyAvailability;
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer_check();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $contact = customer(true);

        $userActiveWarehouseCode = null;
        $userActiveWarehouseName = null;

        if (isset($contact->warehouse)) {
            $userActiveWarehouseCode = $contact->warehouse->code;
            $userActiveWarehouseName = $contact->warehouse->name;
        }

        return view('widget::quick-order', compact('userActiveWarehouseCode', 'userActiveWarehouseName'));
    }
}

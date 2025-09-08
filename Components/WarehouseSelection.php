<?php

namespace Amplify\Widget\Components;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class WarehouseSelection
 */
class WarehouseSelection extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $productCode = '',
        public ?string $activeWarehouse = null
    ) {
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
        $warehouseString = ErpApi::getWarehouses()->pluck('WarehouseNumber')->implode(',');

        $inventory = ErpApi::getProductPriceAvailability([
            'items' => [['item' => $this->productCode]],
            'warehouse' => $warehouseString,
        ]);

        $customer = ErpApi::getCustomerDetail();

        return view('widget::warehouse-selection', compact('inventory', 'customer'));
    }

    public function isActiveWarehouse($warehouseID): bool
    {
        if ($this->activeWarehouse && $this->activeWarehouse == $warehouseID) {
            return true;
        }

        return false;
    }
}

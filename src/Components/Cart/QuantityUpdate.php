<?php

namespace Amplify\Widget\Components\Cart;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class QuantityUpdate
 */
class QuantityUpdate extends BaseComponent
{
    public array $data;

    public function __construct(public Product|ItemRow|null $product = null, public $index)
    {
        parent::__construct();

        $this->data = [
            'cart_item_id' => '{cart_item_id}',
            'code' => '{code}',
            'warehouse_code' => '{warehouse_code}',
            'quantity' => '{quantity}',
            'min_qty' => '{min_qty}',
            'qty_interval' => '{qty_interval}',
        ];
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
        if (! empty($this->product)) {

            if (isset($this->product->ERP?->WarehouseID)) {
                $defaultWarehouse = $this->product->ERP?->Warehouse;
            } else {
                $defaultWarehouse = customer_check()
                    ? ErpApi::getCustomerDetail()->DefaultWarehouse
                    : config('amplify.frontend.guest_checkout_warehouse');
            }

            $this->data = [
                'cart_item_id' => $this->index,
                'code' => $this->product->Product_Code,
                'warehouse_code' => $defaultWarehouse,
                'quantity' => $this->product->min_order_qty ?? 1,
                'min_qty' => $this->product->min_order_qty ?? 1,
                'qty_interval' => $this->product->qty_interval ?? 1,
            ];

            if (! config('amplify.pim.use_minimum_order_quantity')) {
                $this->data['min_qty'] = 1;
                $this->data['qty_interval'] = 1;
            }
        }

        return view('widget::cart.quantity-update', $this->data);
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class('d-flex justify-content-center product-count align-items-center');

        return parent::htmlAttributes();
    }
}

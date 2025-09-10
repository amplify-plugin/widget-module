<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\CustomPartNumber;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

/**
 * @class CustomerPartNumber
 */
class CustomerPartNumber extends BaseComponent
{
    public CustomPartNumber $customerPartNumber;

    public function __construct(public int $productId, public string $label = 'Customer Part Number')
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
        $this->customerPartNumber = new CustomPartNumber([
            'company_id' => null,
            'product_id' => $this->productId,
            'customer_id' => null,
            'customer_product_code' => null,
            'customer_product_uom' => 'EA',
            'customer_address_id' => null,

        ]);

        if (customer_check()) {
            if ($customerPartNumber = CustomPartNumber::where(['customer_id' => customer()->getKey(), 'product_id' => $this->productId])->first()) {
                $this->customerPartNumber = $customerPartNumber;
            }
        }

        $uuid = Str::uuid()->toString();

        return view('widget::product.customer-part-number', compact('uuid'));
    }

    public function hasPermission()
    {
        return true;
    }
}

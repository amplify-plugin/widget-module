<?php

namespace Amplify\Widget\Components\Client\Demo\Quotation;

use Amplify\ErpApi\Wrappers\OrderDetail;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Details
 */
class Details extends BaseComponent
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $quotation = store()->quotationWrapper;
        $quotation->QuoteDetail = $quotation->QuoteDetail->map(function (OrderDetail $quoteItem) {
            $quoteItem->product = Product::productCode($quoteItem->ItemNumber)->first();

            return $quoteItem;
        });

        return view('widget::customer.quotation.details', compact('quotation'));
    }
}

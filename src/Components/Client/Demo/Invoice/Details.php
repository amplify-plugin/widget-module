<?php

namespace Amplify\Widget\Components\Client\Demo\Invoice;

use Amplify\ErpApi\Facades\ErpApi;
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
        $invoice_id = request('invoice');

        $invoiceDetail = ErpApi::getInvoiceDetail(['invoice_number' => $invoice_id, 'invoice_suffix' => request('suffix', 0)]);

        if (! $invoiceDetail->InvoiceDetail || $invoiceDetail->InvoiceNumber != $invoice_id) {
            abort(404, 'Invoice Not Found');
        }

        $invoice = $invoiceDetail->InvoiceDetail->first();

        return view('widget::customer.invoice.details', [
            'invoice' => $invoice,
        ]);
    }
}

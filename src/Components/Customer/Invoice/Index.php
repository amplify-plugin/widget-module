<?php

namespace Amplify\Widget\Components\Customer\Invoice;

use Amplify\ErpApi\ErpApiService;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\ErpApi\Wrappers\Invoice;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class Index extends BaseComponent
{
    public bool $showContactDetail;

    public bool $showInvoiceSuffix;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $showContactDetail = 'false',
        string $showInvoiceSuffix = 'false',
        public string $serialLabel = '',
        public string $dateLabel = '',
        public string $purchaseOrderLabel = '',
        public string $amountLabel = '',
        public string $balanceLabel = '',
        public string $invoicePdfLabel = '',
        public string $shipSignPdfLabel = '',
        public string $statusLabel = '',
        public string $typeLabel = '',
        public string $dueDateLabel = '',
        public string $daysOpenLabel = '',

    ) {
        $this->showContactDetail = UtilityHelper::typeCast($showContactDetail, 'bool');
        $this->showInvoiceSuffix = UtilityHelper::typeCast($showInvoiceSuffix, 'bool');
        parent::__construct();
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
        $accountSummary = ErpApi::getCustomerARSummary();

        $to = request()->has('created_end_date') ? request('created_end_date') : now(config('app.timezone'))->format('Y-m-d');
        $from = request()->has('created_start_date') ? request('created_start_date') : now(config('app.timezone'))->subDays(7)->format('Y-m-d');
        $invoiceStatus = request()->has('invoice_status') ? request('invoice_status') : ErpApiService::INVOICE_STATUS_OPEN;

        if ($invoiceStatus == 'ALL') {
            $invoiceStatus = null;
        }

        $invoiceSummary = ErpApi::getInvoiceList([
            'invoice_status' => $invoiceStatus,
            'from_entry_date' => $from,
            'to_entry_date' => $to,
        ]);

        $columns = [
            'InvoiceNumber' => (strlen($this->serialLabel) != 0),
            'InvoiceDate' => (strlen($this->dateLabel) != 0),
            'CustomerPONumber' => (strlen($this->purchaseOrderLabel) != 0),
            'InvoiceAmount' => (strlen($this->amountLabel) != 0),
            'InvoiceBalance' => (strlen($this->balanceLabel) != 0),
            'InvoicePDF' => (strlen($this->invoicePdfLabel) != 0),
            'ShipSignPDF' => (strlen($this->shipSignPdfLabel) != 0),
            'InvoiceStatus' => (strlen($this->statusLabel) != 0),
            'InvoiceType' => (strlen($this->typeLabel) != 0),
            'DueDate' => (strlen($this->dueDateLabel) != 0),
            'DaysOpen' => (strlen($this->daysOpenLabel) != 0),

        ];

        return view('widget::customer.invoice.index', compact('accountSummary', 'invoiceSummary', 'columns'));
    }

    public function formatInvoiceNumber(Invoice $invoice): string
    {
        $invoiceNumber = $invoice->InvoiceNumber;

        if (! $this->showInvoiceSuffix) {
            return $invoiceNumber;
        }

        return $invoiceNumber.'-'.$invoice->InvoiceSuffix;

    }
}

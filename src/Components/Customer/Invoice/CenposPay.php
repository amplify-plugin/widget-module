<?php

namespace Amplify\Widget\Components\Customer\Invoice;

use Amplify\System\Backend\Facades\CenPos;
use Amplify\Widget\Abstracts\BaseComponent;
use Illuminate\Support\Facades\Validator;

class CenposPay extends BaseComponent
{
    public bool $shouldRender = true;

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

    public function render()
    {
        $errors = [];

        // Validate request data
        $validatedData = Validator::make(request()->all(), [
            'invoices' => 'required|array',
        ]);

        if ($validatedData->fails()) {
            $errors[] = 'Please provide a valid list of invoices.';
        }

        // Retrieve validated data if validation passed
        $data = $validatedData->fails() ? [] : $validatedData->validated();

        // Validate invoices with ERP
        $payableInvoices = $data ? $this->getValidatedInvoice($data['invoices']) : [];
        if ($data && count($payableInvoices) === 0) {
            $errors[] = 'Please select valid invoices.';
        }

        // Attempt to retrieve customer details from ERP
        try {
            $customer = \ErpApi::getCustomerDetail();
            $customerCode = $customer->CustomerNumber;
        } catch (\Exception $e) {
            $errors[] = 'Failed to retrieve customer details from ERP.';
            $customerCode = null;
        }

        // Attempt to retrieve verifying post from CenPos
        try {
            $verifyingPost = CenPos::getVerifyingPost(customer(true)->email, 0.00, null, null, null, $customer->CustomerAddress1, $customer->CustomerZipCode);
        } catch (\Exception $e) {
            $errors[] = 'CenPos verification failed. Please try again later.';
            $verifyingPost = null;
        }

        // Process invoices and calculate payable amount if no errors
        $payableInvoicesId = $data ? array_map(function ($item) {
            return $item['InvoiceBalance'];
        }, $payableInvoices) : [];

        $accountPayableAmount = $this->calculatePrice($payableInvoices);
        $payableInvoicesId = $data ? encrypt($data['invoices']) : null;

        return view('widget::customer.invoice.cenpos-pay',
            compact('verifyingPost', 'customer', 'accountPayableAmount', 'payableInvoices', 'payableInvoicesId', 'errors'));
    }

    private function getValidatedInvoice($payableInvoicesId)
    {
        $res = [];
        $invoiceList = \ErpApi::getInvoiceList();
        foreach ($invoiceList as $invoice) {
            if (in_array($invoice->InvoiceNumber, $payableInvoicesId)) {
                $res[] = $invoice;
            }
        }

        return $res;
    }

    private function calculatePrice($payableInvoices)
    {
        return array_reduce($payableInvoices, function ($pre, $item) {
            return $pre + str_replace(',', '', $item['InvoiceBalance'] ?? 1);
        }, 0);
    }
}

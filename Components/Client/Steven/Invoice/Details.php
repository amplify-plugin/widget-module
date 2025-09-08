<?php

namespace Amplify\Widget\Components\Client\Steven\Invoice;

use Amplify\ErpApi\Collections\OrderNoteCollection;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\CustomPartNumber;
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
        $param = request()->route()->invoice;

        $order = ErpApi::getInvoiceDetail([
            'invoice_number' => $param,
            'suffix' => (int) request('suffix'),
        ]);

        if ($order->InvoiceNumber != $param) {
            abort(404, 'Invalid Invoice List Id.');
        }

        // $order['localOrder'] = optional(CustomerOrder::firstWhere('erp_order_id', $param));

        // Get product codes from order details
        $productCodes = $order->InvoiceDetail->pluck('ItemNumber')->all();

        // Fetch products
        $products = Product::whereIn('product_code', $productCodes)->get();

        // Fetch customer details and ID
        $customer = ErpApi::getCustomerDetail();
        $customerId = $customer->CustomerNumber ?? null;

        // Preload custom part numbers for this customer
        $customParts = $this->getCustomPartsForCustomer($customerId, $products);

        // Loop and inject product and custom part number
        for ($key = 0; $key < count($order['InvoiceDetail']); $key++) {
            $product = $products->where('product_code', $order['InvoiceDetail'][$key]['ItemNumber'])->first();
            $order['InvoiceDetail'][$key]['product'] = $product;

            $order['InvoiceDetail'][$key]['custom_part_number'] = $product ? $customParts[$product->id] ?? null : null;
        }

        $noteList = ErpApi::getNotesList(['order_number' => $param]);
        $order->NoteList = $this->prepareNotes($noteList);
        $contact = customer(true);
        $status = match (request('status')) {
            'Due', 'Open' => 'O',
            null, 'Closed' => 'P',
            default => request('status'),
        };

        try {
            $invoiceTransactions = ErpApi::getInvoiceTransaction([
                'invoice_number' => $param,
                'suffix' => (int) request('suffix'),
                'transaction_type' => $status,
            ]);
        } catch (\Throwable $e) {

            $invoiceTransactions = [];
        }

        return view('widget::client.steven.invoice-details', [
            'order' => $order,
            'customer' => $customer,
            'contact' => $contact,
            'invoiceTransactions' => $invoiceTransactions,
        ]);
    }

    private function prepareNotes(OrderNoteCollection $noteCollection): array
    {
        $parsedNotes = [
            'order_note' => null,
            'internal_comment' => null,
        ];

        if ($noteCollection->isNotEmpty()) {
            foreach ($noteCollection as $note) {
                if (! empty($note->SecureFlag) && $note->SecureFlag === true) {
                    continue;
                }

                $text = trim($note->Note ?? '');
                if ($text === '') {
                    continue;
                }

                // Match "SEI Instructions" (stop at newline or Customer Comments)
                if (stripos($text, 'SEI Instructions:') !== false) {
                    if (preg_match('/SEI Instructions:\s*([^\r\n]*?)(?=\r|Customer Comments:|$)/is', $text, $matches) && ! empty($matches[1])) {
                        $parsedNotes['order_note'] = trim($matches[1]);
                    }
                }

                // Match "Customer Comments" (stop at newline or end)
                if (stripos($text, 'Customer Comments:') !== false) {
                    if (preg_match('/Customer Comments:\s*([^\r\n]*)(?=\r|$)/is', $text, $matches) && ! empty($matches[1])) {
                        $parsedNotes['internal_comment'] = trim($matches[1]);
                    }
                }
            }
        }

        return $parsedNotes;
    }

    private function getCustomPartsForCustomer(?int $customerId, \Illuminate\Support\Collection $products): array
    {
        if (! $customerId || $products->isEmpty()) {
            return [];
        }

        return CustomPartNumber::where('customer_id', $customerId)
            ->whereIn('product_id', $products->pluck('id')->toArray())
            ->pluck('customer_product_code', 'product_id')
            ->toArray();
    }
}

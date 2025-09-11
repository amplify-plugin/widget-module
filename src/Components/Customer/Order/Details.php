<?php

namespace Amplify\Widget\Components\Customer\Order;

use Amplify\ErpApi\Collections\OrderNoteCollection;
use Amplify\ErpApi\Collections\TrackShipmentCollection;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\CustomerOrder;
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
        return customer(true)->can('order.view');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $param = request()->route()->order;
        $order = ErpApi::getOrderDetail(['order_number' => $param, 'order_suffix' => (int) request('suffix')]);

        if ($order->OrderNumber != $param) {
            abort(404, 'Invalid Order List Id.');
        }

        $order['localOrder'] = optional(CustomerOrder::firstWhere('erp_order_id', $param));

        // Get product codes from order details
        $productCodes = $order->OrderDetail->pluck('ItemNumber')->all();

        // Fetch products
        $products = Product::whereIn('product_code', $productCodes)->get();

        // Fetch customer details and ID
        $customer = ErpApi::getCustomerDetail();
        $customerId = $customer->CustomerNumber ?? null;

        // Preload custom part numbers for this customer
        $customParts = $this->getCustomPartsForCustomer($customerId, $products);

        // Client code
        $clientCode = config('amplify.client_code');

        // Loop and inject product and custom part number
        for ($key = 0; $key < count($order['OrderDetail']); $key++) {
            $product = $products->where('product_code', $order['OrderDetail'][$key]['ItemNumber'])->first();
            $order['OrderDetail'][$key]['product'] = $product;

            $order['OrderDetail'][$key]['custom_part_number'] = $product ? $customParts[$product->id] ?? null : null;
        }

        if ($clientCode === 'STV') {
            $trackingResponse = ErpApi::getTrackShipment(['order_number' => $param, 'order_suffix' => (int) request('suffix')]);
            $order->TrackingShipments = $this->prepareTrackingShipment($trackingResponse);

            $noteList = ErpApi::getNotesList(['order_number' => $param]);
            $order->NoteList = $this->prepareNotes($noteList);
            $contact = customer(true);

            return view('widget::client.steven.order.order-details', [
                'order' => $order,
                'customer' => $customer,
                'contact' => $contact,
            ]);
        }

        if (in_array($clientCode, ['NUX', 'DKL'])) {
            $noteList = ErpApi::getNotesList(['order_number' => $param]);
            $order->NoteList = $this->prepareNotesDk($noteList);
            $trackingResponse = ErpApi::getTrackShipment(['order_number' => $param, 'order_suffix' => (int) request('suffix')]);
            $order->TrackingShipments = $this->prepareTrackingShipment($trackingResponse);

            return view('widget::client.dklok.order.order-details', [
                'order' => $order,
                'customer' => $customer,
            ]);
        }

        return view('widget::customer.order.details', [
            'order' => $order,
        ]);
    }

    public function prepareTrackingShipment(TrackShipmentCollection $trackingResponse): string
    {
        if ($trackingResponse->isNotEmpty()) {
            $trackingLinks = $trackingResponse
                ->map(function ($tracking) {
                    $trackerNo = $tracking->TrackerNo ?? null;
                    $shipViaType = $tracking->ShipViaType ?? null;
                    $trackingUrl = null;

                    if ($trackerNo && $shipViaType) {
                        $firstChar = strtolower(substr($shipViaType, 0, 1));

                        if ($firstChar === 'u') {
                            $trackingUrl = "https://www.ups.com/track?loc=en_US&tracknum={$trackerNo}";
                        } elseif ($firstChar === 'f') {
                            $trackingUrl = "https://www.fedex.com/fedextrack/?trknbr={$trackerNo}";
                        }
                    }

                    return $trackingUrl ? '<a href="'.e($trackingUrl).'" target="_blank">'.e($trackerNo).'</a>' : e($trackerNo);
                })
                ->filter()
                ->all();

            return implode(', ', $trackingLinks);
        }

        return 'N/A';
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

    private function prepareNotesDk(OrderNoteCollection $noteList)
    {
        $parsedNote = [
            'order_note' => null,
        ];

        if ($noteList->isNotEmpty()) {
            foreach ($noteList as $note) {
                if (! empty($note->SecureFlag) && $note->SecureFlag === true) {
                    continue;
                }

                $text = trim($note->Note ?? '');
                if ($text === '') {
                    continue;
                }

                $parsedNote['order_note'] = trim($text);
            }
        }

        return $parsedNote;
    }
}

<?php

namespace Amplify\Widget\Components\Client\Steven\Order;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;

class Index extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $filters = $this->buildFilters();

        if ($this->hasActiveFilters($filters)) {
            $orders = ErpApi::getOrderList($filters);

            if ($filters['suffix']) {
                $orders = $this->filterBySuffix($orders, $filters['order_number'], $filters['suffix']);
            }
        } else {
            $orders = collect();
        }

        return view('widget::client.steven.order.index', compact('orders'));
    }

    /**
     * Build filter data array from request.
     */
    private function buildFilters(): array
    {
        $poNumber = request()->get('poNo');

        // If PO number is provided, ignore everything else
        if (! empty($poNumber)) {
            return [
                'order_number' => null,
                'suffix' => null,
                'po_number' => $poNumber,
                'start_date' => '',
                'end_date' => '',
                'transaction_types' => [],
                'statuses' => [],
                'hold_only_flag' => false,
            ];
        }

        // Otherwise process normally
        $rawOrderNo = request()->get('orderNo');
        [$orderNo, $suffix] = $this->extractOrderAndSuffix($rawOrderNo);

        $statuses = collect(request('statuses', []))
            ->flatMap(fn ($s) => explode(',', $s))
            ->toArray();

        return [
            'order_number' => $orderNo,
            'suffix' => $suffix,
            'po_number' => null,
            'start_date' => $this->formatDate(request('startEntryDate')),
            'end_date' => $this->formatDate(request('endEntryDate')),
            'transaction_types' => request()->get('types', []),
            'statuses' => $statuses,
            'hold_only_flag' => filter_var(request('holdFlg', false), FILTER_VALIDATE_BOOLEAN),
        ];
    }

    /**
     * Extract base order number and suffix.
     */
    private function extractOrderAndSuffix(?string $rawOrderNo): array
    {
        if ($rawOrderNo && str_contains($rawOrderNo, '-')) {
            [$orderNo, $suffix] = explode('-', $rawOrderNo);

            return [$orderNo, str_pad((int) $suffix, 2, '0', STR_PAD_LEFT)];
        }

        return [$rawOrderNo, null];
    }

    /**
     * Format date safely (Y-m-d).
     */
    private function formatDate(?string $date): string
    {
        return ! empty($date) ? Carbon::parse($date)->format('Y-m-d') : '';
    }

    /**
     * Check if any filters are active.
     */
    private function hasActiveFilters(array $filters): bool
    {
        return collect($filters)->reject(fn ($value, $key) => $key === 'hold_only_flag' ? $value === false : (is_array($value) ? empty($value) : $value === '' || is_null($value)))->isNotEmpty();
    }

    /**
     * Filter orders by suffix after API response.
     */
    private function filterBySuffix($orders, $orderNo, $suffix)
    {
        return collect($orders)->filter(fn ($order) => $order->OrderNumber == $orderNo && $order->OrderSuffix == $suffix)->values();
    }
}

<?php

namespace Amplify\Widget\Components\Customer\Order;

use Amplify\ErpApi\ErpApiService;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @class Search
 */
class Search extends BaseComponent
{
    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer(true)->can('order.view');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @throws \ErrorException
     */
    public function render(): View|Closure|string
    {
        $data = [
            'lookup_type' => ErpApiService::LOOKUP_DATE_RANGE,
            'start_date' => request()->has('created_start_date') ? request('created_start_date') : now(config('app.timezone'))->subDays(7)->format('Y-m-d'),
            'end_date' => request()->has('created_end_date') ? request('created_end_date') : now(config('app.timezone'))->format('Y-m-d'),
            'contact_id' => request()->has('type') && request('type') == 'all_order' ? null : (customer(true)->contact_code ?: null),
            'transaction_types' => ErpApiService::TRANSACTION_TYPES_ORDER,
        ];

        $orders = ErpApi::getOrderList($data);

        $perPage = request('per_page', getPaginationLengths()[0]);

        $orderPaginate = new LengthAwarePaginator($orders, count($orders), $perPage);

        return view('widget::customer.order.search', compact('perPage', 'orderPaginate', 'orders'));
    }
}

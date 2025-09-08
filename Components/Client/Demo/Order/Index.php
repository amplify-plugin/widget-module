<?php

namespace Amplify\Widget\Components\Client\Demo\Order;

use Amplify\ErpApi\ErpApiService;
use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
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
        $data = [
            'lookup_type' => ErpApiService::LOOKUP_DATE_RANGE,
            'start_date' => request()->has('created_start_date') ? request('created_start_date') : now(config('app.timezone'))->subDays(7)->format('Y-m-d'),
            'end_date' => request()->has('created_end_date') ? request('created_end_date') : now(config('app.timezone'))->format('Y-m-d'),
            'contact_id' => request()->has('type') && request('type') == 'all_order' ? null : (customer(true)->contact_code ?: null),
            'transaction_types' => ErpApiService::TRANSACTION_TYPES_ORDER,
        ];

        $orders = ErpApi::getOrderList($data);

        return view('widget::customer.order.index', [
            'orders' => $orders,
        ]);
    }
}

<?php

namespace Amplify\Widget\Components\Customer;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\CustomerOrder;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Dashboard
 */
class Dashboard extends BaseComponent
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
        return customer(true)->can('dashboard.allow-dashboard');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $customer = customer(true);

        $orders = ErpApi::adapter()->getOrderList();
        //        [
        //            'start_date' => now()->subMonths(3)->format('Y-m-d'),
        //            'end_date' => now()->format('Y-m-d'),
        //        ]);

        $openOrdersCount = $orders->count();

        $monthlyTotal = $orders
            ->where('ContactId', $customer->id)
            ->filter(function ($item) {
                return strpos($item->EntryDate, date('Y-m')) !== false;
            })
            ->sum('TotalOrderValue');

        $pendingOrdersCount = CustomerOrder::where('contact_id', $customer->id)
            ->whereIn('order_status', ['Awaiting Approval', 'Pending'])
            ->count();

        return view('widget::customer.dashboard', [
            'customer' => $customer,
            'monthly_total' => $monthlyTotal,
            'open_orders_count' => $openOrdersCount,
            'pending_orders_count' => $pendingOrdersCount,
        ]);
    }
}

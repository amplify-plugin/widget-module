<?php

namespace Amplify\Widget\Components\Customer\OrderList;

use Amplify\System\Backend\Models\OrderList;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Details
 */
class Details extends BaseComponent
{
    public function __construct(public string $widgetTitle = 'Favourites')
    {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $param = request()->route('order_list');
        if (! is_numeric($param)) {
            abort(404, 'Not Found');
        }

        $search = request('search', '');

        $orderList = OrderList::find($param);

        $orderListItems = $orderList->orderListItems()
            ->with('product')
            ->whereHas('product', function ($q) use ($search) {
                return $q->where('product_name', 'like', "%{$search}%");
            })
            ->paginate(request('per_page', getPaginationLengths()[0]))
            ->withQueryString();

        $orderList = $orderList ?? [];
        $orderListItems = $orderListItems ?? [];

        return view('widget::customer.order-list.details', [
            'orderList' => $orderList,
            'orderListItems' => $orderListItems,
        ]);
    }
}

<?php

namespace Amplify\Widget\Components\Customer\Favourite;

use Amplify\System\Backend\Models\OrderList;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Details
 */
class Details extends BaseComponent
{
    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return true;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $param = request()->route('favourite');
        if (! is_numeric($param)) {
            abort(404, 'Page Not Found');
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

        return view('widget::customer.favourite.details', [
            'orderList' => $orderList,
            'orderListItems' => $orderListItems,
        ]);
    }
}

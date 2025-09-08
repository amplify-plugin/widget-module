<?php

namespace Amplify\Widget\Components\Customer\Favourite;

use Amplify\System\Backend\Models\OrderList;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

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
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);

    }

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

        $perPage = request()->has('per_page') ? request()->per_page : 10;
        $search = request()->has('search') ? request()->search : '';
        $orderList = OrderList::find($param);

        $orderListItems = $orderList->orderListItems()
            ->with('product')
            ->whereHas('product', function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%");
            })
            ->paginate($perPage)->withQueryString();

        $orderList = $orderList ?? [];
        $orderListItems = $orderListItems ?? [];

        return view('widget::customer.favourite.details', [
            'orderList' => $orderList,
            'orderListItems' => $orderListItems,
        ]);
    }
}

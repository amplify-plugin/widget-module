<?php

namespace Amplify\Widget\Components\Customer\QuickList;

use Amplify\System\Backend\Models\OrderList;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
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
     *
     * @throws \ErrorException
     */
    public function render(): View|Closure|string
    {
        $perPage = request('per_page', getPaginationLengths()[0]);
        $search = request('search');

        $quickList = store('orderListModel');

        $quickListItems = $quickList->orderListItems()
            ->with('product')
            ->whereHas('product', function (Builder $query) use ($search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString();

        $quickList = $quickList ?? new OrderList;

        $quickListItems = $quickListItems ?? new LengthAwarePaginator(
            collect(),
            0,
            $perPage,
            null
        );

        return view('widget::customer.quick-list.details', compact('quickList', 'quickListItems'));
    }
}

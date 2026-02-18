<?php

namespace Amplify\Widget\Components\Customer;

use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class Favourites extends BaseComponent
{
    public function __construct(public string $widgetTitle = 'Favorites')
    {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $favourite = OrderList::getFavoriteList();

        $items = OrderListItem::where('list_id', $favourite->id)
            ->with('product')
            ->where(function ($query) {})->orderBy('updated_at', 'desc')
            ->orderBy(request()->input('sort', 'id'), request()->input('dir', 'desc'))
            ->paginate(\request()->input('per_page', getPaginationLengths()[0]));

        return view('widget::customer.favourites', compact('favourite', 'items'));
    }
}

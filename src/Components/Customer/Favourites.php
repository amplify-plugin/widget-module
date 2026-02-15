<?php

namespace Amplify\Widget\Components\Customer;

use Amplify\System\Backend\Models\OrderList;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class Favourites extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $nameLabel = '',
        public string $listTypeLabel = '',
        public string $descriptionLabel = '',
        public string $productCountLabel = '',
        public string $widgetTitle = 'Favourites'
    ) {
        parent::__construct();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $request = request();

        $orderLists = OrderList::where('customer_id', customer()->id)
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('list_type', $request->input('type'));
            })->where(function ($query) {
                $query->when(customer(true)->canAny(['favorites.use-global-list', 'favorites.manage-global-list', 'favorites.manage-personal-list']), function ($q) {
                    if (customer(true)->canAny(['favorites.use-global-list', 'favorites.manage-global-list']) && customer(true)->can('favorites.manage-personal-list')) {
                        $q->where('list_type', 'global')->orWhere('list_type', 'personal');
                    } elseif (customer(true)->canAny(['favorites.use-global-list', 'favorites.manage-global-list'])) {
                        $q->where('list_type', 'global');
                    } elseif (customer(true)->can('favorites.manage-personal-list')) {
                        $q->where('list_type', 'personal');
                    }
                });
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%'.strtolower($request->search).'%')
                    ->orWhere('description', 'like', '%'.strtolower($request->search).'%');
            })
            // TODO: Last change wise filter.
            // ->when($request->filled('filtered_start_date'), function ($q) use ($request) {
            //     $q->where(function (Builder $query) use ($request) {
            //         $start = \Carbon\Carbon::parse($request->input('filtered_start_date'))->toDateTimeString();
            //         $query->where('created_at', '>=', $start)
            //             ->orWhere('updated_at', '>=', $start);
            //     });
            // })
            // ->when($request->filled('filtered_end_date'), function ($q) use ($request) {
            //     $q->where(function (Builder $query) use ($request) {
            //         $end = \Carbon\Carbon::parse($request->input('filtered_end_date'))->toDateTimeString();
            //         $query->where('created_at', '<=', $end)
            //             ->orWhere('updated_at', '<=', $end);
            //     });
            // })
            ->where('list_type', '!=', 'quick-list')
            ->orderBy($request->input('sort', 'id'), $request->input('dir', 'desc'))
            ->paginate($request->input('per_page', getPaginationLengths()[0]));

        $columns = [
            'name' => strlen($this->nameLabel) != 0,
            'list_type' => strlen($this->listTypeLabel) != 0,
            'description' => strlen($this->descriptionLabel) != 0,
            'product_count' => strlen($this->productCountLabel) != 0,
        ];

        return view('widget::customer.favourites', compact('orderLists', 'columns'));
    }
}

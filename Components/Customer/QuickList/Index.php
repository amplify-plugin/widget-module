<?php

namespace Amplify\Widget\Components\Customer\QuickList;

use Amplify\System\Backend\Models\OrderList;
use Amplify\Widget\Abstracts\BaseComponent;
use Carbon\Carbon;
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
     * Create a new component instance.
     */
    public function __construct(
        public string $nameLabel = '',
        public string $descriptionLabel = '',
        public string $productCountLabel = ''
    ) {
        parent::__construct();

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
        $request = request();

        $quickLists = OrderList::where('customer_id', customer()->id)
            ->where('list_type', 'quick-list')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%'.strtolower($request->search).'%')
                        ->orWhere('description', 'like', '%'.strtolower($request->search).'%');
                });
            })
            ->when($request->filled('filtered_start_date') && $request->filled('filtered_end_date'), function ($q) use ($request) {
                $start = Carbon::parse($request->input('filtered_start_date'))->toDateTimeString();
                $end = Carbon::parse($request->input('filtered_end_date'))->toDateTimeString();

                $q->where(function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end])
                        ->orWhereBetween('updated_at', [$start, $end]);
                });
            })
            ->orderBy($request->input('sort', 'id'), $request->input('dir', 'desc'))
            ->paginate($request->input('per_page', getPaginationLengths()[0]));

        $columns = [
            'name' => strlen($this->nameLabel) != 0,
            'description' => strlen($this->descriptionLabel) != 0,
            'product_count' => strlen($this->productCountLabel) != 0,
        ];

        return view('widget::customer.quick-list.index', compact('quickLists', 'columns'));
    }
}

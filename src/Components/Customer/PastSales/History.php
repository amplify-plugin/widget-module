<?php

namespace Amplify\Widget\Components\Customer\PastSales;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class History
 */
class History extends BaseComponent
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
        return customer(true)->can('past-items.past-items-history');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $request = request();

        $prod = $request->query('prod') ?? $request->query('product') ?? null;
        $year = $request->query('year') ?? date('Y');

        $sales = [];
        $product = null;

        if (!empty($prod)) {
            try {
                $sales = ErpApi::getPastSalesHistory(['prod' => $prod, 'year' => $year]);
            } catch (\Exception $e) {
                $sales = [];
            }

            // try to fetch local product details (only required fields)
            try {
                $product = Product::where('product_code', $prod)->first(['product_code', 'product_name', 'description']);
            } catch (\Exception $e) {
                $product = null;
            }
        }

        return view('widget::customer.past-sales.history', [
            'prod' => $prod,
            'year' => (int) $year,
            'sales' => $sales,
            'product' => $product,
        ]);
    }
}

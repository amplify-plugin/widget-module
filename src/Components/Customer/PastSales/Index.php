<?php

namespace Amplify\Widget\Components\Customer\PastSales;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\ErpApi\Wrappers\ProductPriceAvailability;
use Amplify\System\Backend\Models\Product;
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
        return customer(true)->can('past-items.past-items-list');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // $to = request()->has('created_end_date') ? request('created_end_date') : now(config('app.timezone'))->format('Y-m-d');
        // $from = request()->has('created_start_date') ? request('created_start_date') : now(config('app.timezone'))->subMonths(3)->format('Y-m-d');
        // $contact_code = request()->input('contact_code', customer(true)->contact_code);

        // $orders = ErpApi::getOrderList([
        //     'lookup_type' => ErpApiService::LOOKUP_DATE_RANGE,
        //     'list_detail' => 'Y',
        //     'start_date' => $from,
        //     'end_date' => $to,
        //     'contact_id' => $contact_code,
        // ])->sortByDesc('EntryDate');

        $orders = ErpApi::getPastItemList();
        $products = [];
        $erpProductDetails = [];
        // $contacts = customer()->contacts;

        foreach ($orders as $order) {
            $products[$order->ItemNumber] = $order->History;
        }

        $productsCodes = array_keys($products);
        $productsWithItemKey = array_map(function ($item) {
            return ['item' => $item, 'qty' => 1];
        }, $productsCodes);

        if (! empty($productsWithItemKey)) {
            $erpProductDetails = ErpApi::getProductPriceAvailability(['items' => $productsWithItemKey]);
        }

        $localProducts = Product::select()->whereIn('product_code', $productsCodes)->get()->map(function ($product) use (&$products, $erpProductDetails) {
            $productCode = trim($product->product_code);
            $product->orderInfo = $products[$productCode] ?? [];
            $product->ERP = $erpProductDetails->firstWhere('ItemNumber', $productCode) ?? new ProductPriceAvailability([]);

            return $product;
        });

        return view('widget::customer.past-sales.index', [
            'products' => $localProducts,
        ]);
    }
}

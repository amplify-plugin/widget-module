<?php

namespace Amplify\Widget\Components\Client\DKLOK\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Marketing\Models\CampaignProduct;
use Amplify\Widget\Abstracts\BaseComponent;
use Amplify\Widget\Traits\ProductDetailTrait;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductSkuTable
 */
class ProductSkuTable extends BaseComponent
{
    use ProductDetailTrait;

    /**
     * @var array
     */
    public $options;

    public $Product;

    public $seoPath;

    public $auth;

    public $userActiveWarehouseCode;

    protected $orderList;

    public $skuAttributes;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $product,
        $qtyConfig,
        string $seoPath
    ) {
        parent::__construct();
        $this->Product = $product;
        $this->seoPath = $seoPath;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $hasPermission = customer(true)?->canAny(['favorites.manage-global-list', 'favorites.manage-personal-list']) ?? false;
        $campaign_id = request()->campaign;
        $sku_list = $this->Product?->Sku_List ?? [];

        if (gettype($sku_list) === 'string') {
            $sku_list = json_decode($this->Product?->Sku_List, true);
        }

        $skuCodes = [];
        foreach ($sku_list as $sku) {
            $skuCodes[] = ['item' => $sku[1]];
        }

        $queries = request()->all();
        $locale = app()->getLocale();

        $filterQueries = array_filter($queries, fn ($value) => ! is_null($value) && $value !== '');

        $query = Product::with(['attributes', 'productImage'])
            ->whereIn('product_code', $skuCodes);

        foreach ($filterQueries as $slug => $expectedValue) {
            $query->whereHas('attributes', function ($q) use ($slug, $expectedValue, $locale) {
                $q->where('attributes.slug', $slug)
                    ->whereRaw(
                        "JSON_UNQUOTE(JSON_EXTRACT(attribute_product.attribute_value, '$.\"$locale\"')) = ?",
                        [$expectedValue]
                    );
            });
        }

        $sku_products = $query->get();

        $sku_products->each(function ($product) {
            $product->filtered_attributes = $this->getFilteredAttributes($product);

        });

        if (customer_check()) {
            $warehouses = ErpApi::getWarehouses();
            $warehouseString = $warehouses->pluck('WarehouseNumber')->implode(',');

            $this->orderList = OrderList::with('orderListItems')->whereCustomerId(customer()->getKey())->get();
            $erpProductDetails = ErpApi::getProductPriceAvailability([
                'warehouse' => $warehouseString,
                'items' => $skuCodes,
            ]);

            $sku_products?->each(function ($product) use ($erpProductDetails, $campaign_id) {
                $product->ERP = $erpProductDetails->first(function ($item) use ($product) {
                    return trim($item->ItemNumber) == trim($product->product_code);
                });

                if ($campaign_id) {
                    $product->campaignProduct = CampaignProduct::where([
                        'product_id' => $product->id,
                        'campaign_id' => $campaign_id,
                    ])->first();
                }

                $this->productExistOnFavorite($product->id, $product);
            });
        }

        return view('widget::client.dklok.product.product-sku-table', [
            'product' => $this->Product,
            'sku_products' => $sku_products,
            'hasPermission' => $hasPermission,
            'seoPath' => $this->seoPath,
        ]);
    }

    private function productExistOnFavorite($id, &$product): void
    {
        foreach ($this->orderList as $orderList) {
            if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                $product->exists_in_favorite = true;
                $product->favorite_list_id = $item->id;
            }
        }
    }
}

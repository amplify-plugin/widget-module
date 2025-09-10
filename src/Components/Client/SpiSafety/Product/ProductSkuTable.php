<?php

namespace Amplify\Widget\Components\Client\SpiSafety\Product;

use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Marketing\Models\CampaignProduct;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductSkuTable
 */
class ProductSkuTable extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $Product;

    public $auth;

    public $userActiveWarehouseCode;

    protected $orderList;

    /**
     * Create a new component instance.
     */
    public function __construct($product)
    {

        parent::__construct();
        $this->Product = $product;
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
        $hasPermission = customer(true)?->canAny(['favorites.manage-global-list', 'favorites.manage-personal-list']) ?? false;
        $campaign_id = request()->campaign;

        $sku_products = Product::with('attributes', 'productImage')->whereIn('product_code', array_map(fn ($item) => $item[1], $this->Product->skuList))->get();

        if (customer_check()) {
            $this->orderList = OrderList::with('orderListItems')->whereCustomerId(customer()->getKey())->get();
        }

        if (has_erp_customer()) {
            $sku_products?->each(function ($product) use ($campaign_id) {
                $product->ERP = $this->Product->erpProductList->first(function ($item) use ($product) {
                    return trim($item->ItemNumber) == trim($product->product_code);
                });

                if ($campaign_id) {
                    $product->campaignProduct = CampaignProduct::where([
                        'product_id' => $product->id,
                        'campaign_id' => $campaign_id,
                    ])->first();
                }

                // $this->productExistOnFavorite($product->id, $product);
            });
        }

        return view('widget::client.spi-safety.product.product-sku-table', [
            'sku_products' => $sku_products,
            'hasPermission' => $hasPermission,
        ]);
    }

    private function productExistOnFavorite($id, &$product): void
    {
        foreach ($this->orderList ?? [] as $orderList) {
            if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                $product->exists_in_favorite = true;
                $product->favorite_list_id = $item->id;
            }
        }
    }
}

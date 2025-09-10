<?php

namespace Amplify\Widget\Components\Client\Demo\Product;

use Amplify\ErpApi\Facades\ErpApi;
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
    public $userActiveWarehouseCode;

    protected $orderList;

    public $Product;

    public bool $qtyConfig;

    public function __construct($product, $qtyConfig)
    {
        parent::__construct();
        $this->Product = $product;
        $this->qtyConfig = $qtyConfig;
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
        $sku_list = json_decode($this->Product?->Sku_List, true) ?? [];
        $skuCodes = [];

        foreach ($sku_list as $sku) {
            $skuCodes[] = ['item' => $sku[1]];
        }

        $sku_products = Product::with('attributes', 'productImage')->whereIn('product_code', $skuCodes)->get();

        if (customer_check()) {
            $this->orderList = OrderList::with('orderListItems')->whereCustomerId(customer()->getKey())->get();
        }

        if (config('amplify.basic.enable_guest_pricing')) {
            $erpProductDetails = ErpApi::getProductPriceAvailability([
                'warehouse' => customer()->warehouse->code ?? '',
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

                if (customer_check()) {
                    $this->productExistOnFavorite($product->id, $product);
                }
            });
        }

        return view('widget::client.demo.product.product-sku-table', [
            'sku_products' => $sku_products,
            'hasPermission' => $hasPermission,
            'qtyConfig' => $this->qtyConfig,
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

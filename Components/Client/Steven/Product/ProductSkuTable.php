<?php

namespace Amplify\Widget\Components\Client\Steven\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Marketing\Models\CampaignProduct;
use Amplify\System\Sayt\Classes\ItemRow;
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
    public function __construct(
        public ItemRow|Product $product,
        public bool $qtyConfig = true,
        public ?string $seoPath = '')
    {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
                $hasPermission = customer(true)?->canAny(['favorites.manage-global-list', 'favorites.manage-personal-list']) ?? false;
                $campaign_id = request('campaign');
                $sku_list = json_decode($this->product?->Sku_List, true) ?? [];
                $skuIds = $skuCodes = [];

                foreach ($sku_list as $sku) {
                    $skuIds[] = $sku[0];
                    $skuCodes[] = ['item' => $sku[1]];
                }

                $sku_products = Product::with('attributes', 'productImage')->whereIn('id', $skuIds)->get();

                if (customer_check()) {
                    $this->orderList = OrderList::with('orderListItems')->whereCustomerId(customer()->getKey())->get();
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

                        $this->productExistOnFavorite($product->id, $product);
                    });
                }


        return view('widget::client.steven.product.product-sku-table', [
                        'sku_products' => $sku_products,
                        'hasPermission' => $hasPermission,
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

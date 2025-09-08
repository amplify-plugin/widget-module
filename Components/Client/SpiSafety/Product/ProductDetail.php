<?php

namespace Amplify\Widget\Components\Client\SpiSafety\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductDetail
 */
class ProductDetail extends BaseComponent
{
    use \Amplify\Widget\Traits\ProductDetailTrait;

    public $showSkuTable;

    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct($showSkuTable = true)
    {
        parent::__construct();
        $this->showSkuTable = UtilityHelper::typeCast($showSkuTable, 'bool');
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
        $dbProduct = store()->productModel;
        $response = $this->getProductFromEasyAsk($dbProduct->id, 'All');
        abort_if(isset($response['noResultsMessage']), 404);

        $productInfoTabs = [];
        $erpAdditionalImages = [];
        $erpMediaType = Product::MEDIA_TYPE;
        $Product = array_shift($response['products']->items);
        $Product->Sku_List_Attributes = $response['attributes']?->attribute ?? [];
        $Product->categories = $this->getCategories($Product->Product_Id);
        $Product->documents = $this->getDocuments($Product->Product_Id);

        $Product->description = $dbProduct->description;
        $Product->short_description = $dbProduct->short_description;
        $Product->product_image = $dbProduct->productImage;
        $Product->manufacturer = $dbProduct?->manufacturerRelation ?? null;
        $Product->skuList = json_decode($Product?->Sku_List, true) ?? [];

        if (has_erp_customer() && ! empty($Product->skuList)) {
            $Product->erpProductList = ErpApi::getProductPriceAvailability([
                'items' => array_map(fn ($item) => ['item' => $item[1]], $Product->skuList),
            ]);

            $productInfoTabs = $this->getProductInfoTabs($Product->erpProductList->first()?->ProductDetails ?? [], $erpAdditionalImages);
        }

        return view('widget::client.spi-safety.product.product-detail', [
            'Product' => $Product,
            'productInfoTabs' => $productInfoTabs,
            'erpAdditionalImages' => $erpAdditionalImages,
            'erpMediaType' => $erpMediaType,
        ]);
    }

    private function getProductInfoTabs($productDetails, &$erpAdditionalImages)
    {
        $productInfoTabs = [];

        foreach ($productDetails ?? [] as $productDetail) {
            $link_extension = pathinfo($productDetail['LinkValue'], PATHINFO_EXTENSION);
            $display_type = $productDetail['DisplayType'] === 'L' && $link_extension === 'pdf' ?
                'M' : $productDetail['DisplayType'];

            if ($display_type === 'I') {
                $erpAdditionalImages[] = [
                    'label' => $productDetail['DisplayText'],
                    'value' => $productDetail['LinkValue'],
                ];

                continue;
            }

            $productInfoTabs[$display_type][] = [
                'label' => $productDetail['DisplayText'],
                'value' => $productDetail['LinkValue'],
                'extension' => $link_extension,
            ];
        }

        return $productInfoTabs;
    }

    public function isMasterProduct($product): bool
    {
        return true;
    }
}

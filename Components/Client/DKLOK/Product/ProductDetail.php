<?php

namespace Amplify\Widget\Components\Client\DKLOK\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\System\Backend\Models\AttributeProduct;
use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Backend\Models\OrderListItem;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Backend\Models\SkuProduct;
use Amplify\System\Backend\Models\Warehouse;
use Amplify\Widget\Abstracts\BaseComponent;
use Amplify\Widget\Traits\ProductDetailTrait;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * @class ProductDetail
 */
class ProductDetail extends BaseComponent
{
    use ProductDetailTrait;

    private Collection $orderList;

    public function isMasterProduct($product): bool
    {
        return ! empty($product->Full_Sku_Count) && ! empty($product->Sku_Count);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @throws \ErrorException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function render(): View|Closure|string
    {
        $this->getOrderList();

        $dbProduct = store()->productModel;

        $response = $this->getProductFromEasyAsk($dbProduct->id);

        $productInfoTabs = [];
        $erpMediaType = Product::MEDIA_TYPE;
        $easyAskProducts = $response->getProducts();
        $seoPath = $response->getCurrentSeoPath();

        $Product = array_shift($easyAskProducts);

        abort_if(empty($Product) && empty($dbProduct), 404);

        if (empty($Product)) {
            $Product = $this->formatDBProductToEasyask($dbProduct);
        }

        $Product->Sku_List_Attributes = $response->getAttributes();
        $Product->categories = $this->getCategories($Product->Product_Id);
        $Product->documents = $this->getDocuments($Product->Product_Id);
        $Product->description = $dbProduct->description;
        $Product->features = json_decode($dbProduct->features);
        $Product->short_description = $dbProduct->short_description;
        $Product->product_image = $dbProduct->productImage;
        $Product->allow_back_order = $dbProduct->allow_back_order;
        $Product->manufacturer = $dbProduct?->manufacturerRelation ?? null;
        $Product->specifications = json_decode($dbProduct->specifications);

        if (customer_check() || config('amplify.basic.enable_guest_pricing')) {
            $warehouses = ErpApi::getWarehouses();
            $warehouseString = $warehouses->pluck('WarehouseNumber')->implode(',');

            $products = ErpApi::getProductPriceAvailability([
                'items' => [['item' => $Product->Sku_ProductCode ?? $Product->Product_Code]],
                'warehouse' => $warehouseString,
            ]);

            $warehouseNumber = $products->pluck('WarehouseID');
            $Product->warehouses = Warehouse::whereIn('code', $warehouseNumber)->get(['code', 'name'])->toArray();

            $Product->warehouses = array_map(fn ($warehouse, $index) => array_merge($warehouse, [
                'price' => $products[$index]['Price'],
                'quantity_available' => (int) $products[$index]['QuantityAvailable'],
            ]), $Product->warehouses, array_keys($Product->warehouses));

            $Product->ERP = $products->first();

            $productInfoTabs = $this->getProductInfoTabs($Product->ERP->ProductDetails ?? [], $erpAdditionalImages);
        }

        $ownProduct = $dbProduct;
        $orderList = $this->productExistOnFavorite($Product->Product_Id);
        $Product->exists_in_favorite = $orderList != null;
        $Product->favorite_list_id = $orderList->id ?? null;
        $Product->modelCodes = $ownProduct->modelCodes;
        $Product->prop65_message = $ownProduct->prop65_message;
        $Product->mpn = $ownProduct->manufacturer;
        $Product->brand_name = $ownProduct->brand_name;
        $Product->min_order_qty = $ownProduct->min_order_qty;
        $Product->qty_interval = $ownProduct->qty_interval;
        $Product->filtered_attributes = $this->getFilteredAttributes($ownProduct);
        $Product->attribute_filters = $this->getAttributeFilterData($ownProduct);

        $tabs = [];
        $first = true;

        if ($this->isMasterProduct($Product)) {
            $tabs[] = [
                'id' => 'skus',
                'label' => 'Skus',
                'active' => $first,
            ];
            $first = false;
        }

        if ($Product->filtered_attributes->count() > 0) {
            $tabs[] = [
                'id' => 'details',
                'label' => 'Product Details',
                'active' => $first,
            ];
            $first = false;
        }

        if ($Product->documents->count() > 0) {
            $tabs[] = [
                'id' => 'catalogs',
                'label' => 'Download Catalogs',
                'active' => $first,
            ];
            $first = false;
        }

        $cadView = $this->getCadView($dbProduct);
        if (!empty($cadView)) {
            $tabs[] = [
                'id' => 'view',
                'label' => 'Product View (2D/3D)',
                'active' => $first,
            ];
            $first = false;
        }

        return view('widget::client.dklok.product.product-detail', [
            'product' => $Product,
            'productImage' => $dbProduct->productImage,
            'productInfoTabs' => $productInfoTabs,
            'erpMediaType' => $erpMediaType,
            'qtyConfig' => config('amplify.pim.use_minimum_order_quantity'),
            'cadView' => $cadView,
            'seoPath' => $seoPath,
            'tabs' => $tabs,
        ]);
    }

    private function getAttributeFilterData($dbProduct)
    {
        if ($dbProduct->hasSku) {
            return [];
        }

        $locale = app()->getLocale();
        $skuProductIds = SkuProduct::where('parent_id', $dbProduct->id)->pluck('sku_id')->toArray();
        if (! empty($skuProductIds)) {
            return AttributeProduct::join('attributes', 'attribute_product.attribute_id', '=', 'attributes.id')
                ->whereIn('product_id', $skuProductIds)
                ->where('attributes.name', 'NOT LIKE', '%Item #%')
                ->where('attributes.name', 'NOT LIKE', '%Item Name%')
                ->where('attributes.name', 'NOT LIKE', '%CADPartID%')
                ->select('attributes.id', 'attributes.name', 'attributes.slug', 'attribute_product.attribute_value')
                ->get()
                ->groupBy('id')
                ->map(function ($group) use ($locale) {
                    $rawName = $group->first()->name;

                    // Decode JSON name
                    $nameArray = json_decode($rawName, true);
                    $name = $nameArray[$locale] ?? reset($nameArray); // fallback to first if locale not found

                    return [
                        'name' => $name,
                        'slug' => $group->first()->slug,
                        'values' => $group->pluck('attribute_value')->unique()->values()->all(),
                    ];
                })
                ->filter(function ($attribute) {
                    return count($attribute['values']) > 1;
                })
                ->values()
                ->toArray();
        }

        return [];

    }

    private function getProductInfoTabs($productDetails, &$erpAdditionalImages): array
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

    private function productExistOnFavorite($id): ?OrderListItem
    {
        if (! empty($this->orderList)) {
            foreach ($this->orderList as $orderList) {
                if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                    return $item;
                }
            }
        }

        return null;
    }

    /**
     * Get Customer OrderList
     */
    public function getOrderList(): void
    {
        if (customer_check()) {
            $this->orderList = OrderList::with('orderListItems')
                ->whereCustomerId(customer()->getKey())->get();
        }
    }

    private function getCadView($dbProduct)
    {
        $subscriptionKey = '946b668dbb194d14acaf5a19f2f87548';
        //        $productCode = 'dt-15m-sd';
        $productCode = $dbProduct->product_code;
        $soapUrl = 'https://api.navigator.traceparts.com/fciapi/?soapAction=http://www.thomanet.com/GetCADViews';

        // Build SOAP request body
        $soapBody = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
  <Body>
    <GetCADViews xmlns="http://www.thomanet.com/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <CID>7673</CID>
      <ItemShortName>{$productCode}</ItemShortName>
      <IsStageEnv>False</IsStageEnv>
    </GetCADViews>
  </Body>
</Envelope>
XML;

        // Send SOAP request using Laravel HTTP client
        $response = Http::withHeaders([
            'Content-Type' => 'text/xml',
            'Ocp-Apim-Subscription-Key' => $subscriptionKey,
        ])->withBody($soapBody, 'text/xml')->post($soapUrl);
        // Parse XML and extract views
        $xml = simplexml_load_string($response->body());
        $namespaces = $xml->getNamespaces(true);
        $body = $xml->children($namespaces['soap'])->Body;
        $responseNode = $body->children($namespaces[''])->GetCADViewsResponse;
        $views = $responseNode->Views->View;

        if (empty($views)) {
            return [];
        }

        $result = [];
        foreach ($views as $view) {
            $attrs = $view->attributes();
            $result[] = [
                'name' => (string) $attrs['name'],
                'description' => (string) $attrs['description'],
                'url' => (string) str_replace('http://', '//', $attrs['url']),
            ];
        }

        return $result;
    }
}

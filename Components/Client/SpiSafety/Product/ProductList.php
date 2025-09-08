<?php

namespace Amplify\Widget\Components\Client\SpiSafety\Product;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ProductList
 */
class ProductList extends BaseComponent
{
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
        $easyAskResult = store()->eaProductsData;
        $productView = active_shop_view();
        $productsData = new \StdClass;
        $productsData->items = $easyAskResult['products']?->items ?? [];
        $productsData->itemDescription = $easyAskResult['products']?->itemDescription ?? new \StdClass;

        $productsData->items = $productsData->items ?? [];
        $seoPath = $easyAskResult['seopath'] ?? '';
        $message = $easyAskResult['message'] ?? null;
        $orderList = $easyAskResult['orderList'] ?? [];
        $cellCount = 4;
        $productCodes = [];

        foreach ($productsData->items as $product) {
            $product->Sku_List = json_decode($product?->Sku_List, true) ?? [];

            if (! empty($product?->Full_Sku_Count) && $product?->Full_Sku_Count == $product?->Sku_Count) {
                $product->productCode = $product->Sku_ProductCode ?? ($product->Product_Code ?? '');
                $product->productName = $product->Sku_Name ?? ($product->Product_Name ?? '');
                $product->productImage = $product->Sku_ProductImage ?? ($product->Product_Image ?? '');
                $product->hasSku = 1;
            } else {
                $product->productCode = $product->Product_Code ?? '';
                $product->productName = $product->Product_Name ?? '';
                $product->productImage = $product->Product_Image ?? '';
                $product->hasSku = 0;
            }

            $productCodes = [...$productCodes, ...array_map(fn ($item) => ['item' => $item[1]], $product->Sku_List)];
        }

        if (has_erp_customer() && ! empty($productCodes)) {
            $cellCount += 4;
            $erpProductDetails = ErpApi::getProductPriceAvailability(['items' => $productCodes]);

            foreach ($productsData->items as $product) {
                $product->erpProductList = $erpProductDetails->whereIn('ItemNumber', array_map(fn ($item) => $item[1], $product->Sku_List));
            }
        }

        return view('widget::client.spi-safety.product.product-list', [
            'productsData' => $productsData,
            'seoPath' => $seoPath,
            'message' => $message,
            'productView' => $productView,
            'orderList' => $orderList,
            'cellCount' => $cellCount,
        ]);
    }

    public function isMasterProduct($product): bool
    {
        return true;
    }
}

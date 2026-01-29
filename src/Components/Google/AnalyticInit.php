<?php

namespace Amplify\Widget\Components\Google;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class GoogleAnalytic
 */
class AnalyticInit extends BaseComponent
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
        $analytics_id = config('amplify.google.google_analytics_id', '');

        if ($analytics_id == null) {
            $analytics_id = '';
        }

        $analytics_url = config('amplify.google.google_analytics_url', '');

        if ($analytics_url == null) {
            $analytics_url = '';
        }

        $analytics_url = str_replace("?id={$analytics_id}", '', $analytics_url);

        $tag_manager_id = config('amplify.google.google_tag_manager_id');

        return view('widget::google.google-analytic', [
            'analytics_id' => $analytics_id,
            'analytics_url' => $analytics_url,
            'tag_manager_id' => $tag_manager_id,
        ]);
    }

    public function pageSchemaForGoogle()
    {
        $type = $this->determineGooglePageType();

        $data = [
            '@context' => 'https://schema.org',
            '@type' => $type,
            '@id' => $this->determineGooglePageId($type),
            'name' => config('app.name'),
            'url' => request()->url(),
            'logo' => config('amplify.cms.logo_path', '#'),
        ];

        if ($type != 'Organization') {
            $data['publisher']['@id'] = $this->determineGooglePageId('Organization');
        }

        if ($type == 'WebSite') {
            $data['potentialAction']['@type'] = 'SearchAction';
            $data['potentialAction']['target'] = frontendShopURL(['search', 'q' => '{search_term_string}']);
            $data['potentialAction']['query-input'] = 'required name=search_term_string';
        }

        if ($type == 'BreadcrumbList') {
            $count = 0;
            $data['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => ++$count,
                'name' => 'Home',
                'item' => frontendHomeURL()
            ];
            $data['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => ++$count,
                'name' => \store()->dynamicPageModel->breadcrumb_title ?? \store()->dynamicPageModel->title,
                'item' => frontendHomeURL()
            ];

        }

        if ($type == 'Product') {

            $product = \store('productModel');

            $data['name'] = $product->product_name ?? 'Not Found';
            $data['description'] = $product->short_description ?? 'Not Found';
            $data['sku'] = $product->product_code ?? 'Not Found';
            $data['mpn'] = $product->manufacturer ?? '';

            if ($product?->brand()?->exists()) {
                $data['brand']['@type'] = 'Brand';
                $data['brand']['name'] = $product->brand->title ?? '';
            } else if (!empty($product->brand_name)) {
                $data['brand']['@type'] = 'Brand';
                $data['brand']['name'] = $product->brand_name ?? '';
            }

            if ($product?->manufacturerRelation()?->exists()) {
                $data['manufacturer']['@type'] = 'Organization';
                $data['manufacturer']['name'] = $product->manufacturerRelation->name ?? '';
            }

            $data['offers']['@type'] = 'Offer';
            $data['offers']['url'] = request()->url();
            $data['offers']['priceCurrency'] = config('amplify.basic.global_currency', 'USD');
            $data['offers']['availability'] = "https://schema.org/InStock";
            $data['offers']['seller']['@type'] = "Organization";
            $data['offers']['seller']['@id'] = $this->determineGooglePageId('Organization');
            $data['offers']['seller']['name'] = config('app.name');
        }


        return $data;

    }

    private function determineGooglePageType(): string
    {
        return match (request()->route()->getName()) {
            'frontend.index' => 'Organization',
            'frontend.shop.index' => 'WebSite',
            'frontend.shop.show' => 'Product',
            default => 'BreadcrumbList'
        };
    }

    private function determineGooglePageId(string $type): string
    {
        $baseUrl = trim(config('app.url'), '/');

        return match ($type) {
            'Organization' => "{$baseUrl}/#organization",
            'WebSite' => "{$baseUrl}/#website",
            'Product' => request()->url() . "/#product",
            default => request()->url() . "/#breadcrumb",
        };
    }
}

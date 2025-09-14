<?php

namespace Amplify\Widget\Components;

use Amplify\System\Cms\Models\Page;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\System\Sayt\Classes\BreadCrumbTrail;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use ErrorException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

/**
 * @class Breadcrumb
 */
class Breadcrumb extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public Collection $breadcrumbs;

    private bool $errorView = false;

    private ?Page $page;

    private mixed $navigateNode;

    public bool $dontShowTitle = false;

    /**
     * Create a new component instance.
     *
     * @throws ErrorException
     */
    public function __construct(public string $title = '', string $error = 'false', string $hideTitle = 'false')
    {
        parent::__construct();

        $error = $error === '' ? 'false' : $error;

        $this->errorView = UtilityHelper::typeCast($error, 'boolean');
        $this->dontShowTitle = UtilityHelper::typeCast($hideTitle, 'boolean');

        $this->breadcrumbs = new Collection;

        $this->navigateNode = null;

        $this->page = store('dynamicPageModel');
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        if ($this->errorView) {
            return true;
        }

        if ($this->page instanceof Page) {
            return (bool) $this->page->has_breadcrumb;
        }

        return true;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @throws ErrorException
     */
    public function render(): View|Closure|string
    {

        if ($this->errorView) {
            $this->push('Error '.$this->title, url()->current());
            $this->push('Home', $this->homeUrl());
        } else {
            if (in_array($this->page->page_type, ['shop', 'single_product'])) {
                $this->loadEasyAskBreadcrumbs($this->page);
            } else {
                $this->loadDynamicPageBreadcrumbs($this->page);
            }
        }

        $this->breadcrumbs = $this->breadcrumbs->reverse();

        return view('widget::breadcrumb', [
            'pageTitle' => $this->title,
        ]);
    }

    private function push(string $title, string $url): void
    {
        $node = new \stdClass;

        $node->title = $title;
        $node->url = $url;

        $this->breadcrumbs->push($node);
    }

    /**
     * @return string
     */
    public function homeUrl()
    {
        return getIsDynamicSiteFromCache()
            ? request()->getSchemeAndHttpHost().'/'.getDynamicSiteSlugFromCache()
            : route('frontend.index');
    }

    private function loadDynamicPageBreadcrumbs(?Page $page = null): void
    {
        $this->push(($page->breadcrumb_title != null ? $page->breadcrumb_title : $page->name), url($page->slug));

        if (! empty($page->parent)) {
            $this->loadDynamicPageBreadcrumbs($page->parent);
        }
    }

    /**
     * Return The Displayable Page Tile
     *
     * @return string
     *
     * @throws ErrorException
     */
    public function displayPageTitle()
    {
        if ($this->errorView) {
            return $this->errorTitle();
        }

        if (strlen($this->title) > 0) {
            return $this->title;
        }

        if ($this->navigateNode != null) {
            if ($this->page->page_type == 'shop') {
                return $this->navigateNode->getLabel().' <strong><em>\''.ucwords($this->navigateNode->getEnglishName()).'\'</em></strong>';
            } else {
                return store('pageTitle', 'Product Detail');
            }
        }

        return $this->breadcrumbs->first()?->title ?? 'No Name';
    }

    private function errorTitle(): string
    {
        return match ($this->title) {
            '400' => 'Bad Request',
            '401' => 'Unauthorized',
            '402' => 'Payment Required',
            '403' => 'Access Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
            '408' => 'Request Time-out',
            '419' => 'Page Expired',
            '429' => 'Too Many Requests',
            '500' => 'Internal Server Error',
            '503' => 'Service Unavailable',
            default => "Error {$this->title}"
        };
    }

    /**
     * @throws ErrorException
     */
    private function loadEasyAskBreadcrumbs(?Page $page): void
    {
        $easyAskResult = ($page->page_type == 'shop') ? store()->eaProductsData : store()->eaProductDetail;

        $breadcrumbTrails = $easyAskResult?->getBreadCrumbTrail() ?? new BreadCrumbTrail(null);

        $navigateNodes = $breadcrumbTrails->getSearchPath();

        $navigateNodes = array_reverse($navigateNodes);

        foreach ($navigateNodes as $breadcrumb) {

            if ($breadcrumb->getType() == 3) {
                $this->navigateNode = $breadcrumb;
                $title = ($page->page_type == 'shop') ? ucwords($breadcrumb->getValue()) : $page->name;
            }

            if ($breadcrumb->getType() == 2) {
                $title = collect(explode(' = ', $breadcrumb->getLabel()))->map(fn ($item) => trim($item, '\''))->join('=');
            }

            if ($breadcrumb->getType() == 1) {
                $title = $breadcrumb->getValue();
            }

            $url = route('frontend.shop.index', [
                $breadcrumb->getSEOPath(),
                'view' => request('view', config('amplify.frontend.shop_page_default_view')),
                'per_page' => request('per_page', getPaginationLengths()[0]),
                'sort_by' => request('sort_by', ''),
            ]);

            $this->push($title, $url);
        }

        $this->push('Home', $this->homeUrl());
    }
}

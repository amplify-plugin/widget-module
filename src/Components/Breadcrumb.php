<?php

namespace Amplify\Widget\Components;

use Amplify\System\Cms\Models\Page;
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

    protected ?Page $page;

    protected mixed $navigateNode;

    /**
     * Create a new component instance.
     *
     * @throws ErrorException
     */
    public function __construct(public string $title = '', public bool $error = false, public bool $hideTitle = false)
    {
        parent::__construct();

        $this->breadcrumbs = new Collection;

        $this->navigateNode = null;

        $this->page = store('dynamicPageModel');
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        if ($this->error) {
            return true;
        }

        if ($this->page instanceof Page) {
            return (bool)$this->page->has_breadcrumb;
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

        if ($this->error) {
            $this->push('Error ' . $this->title, url()->current());
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

    protected function push(string $title, string $url): void
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
            ? request()->getSchemeAndHttpHost() . '/' . getDynamicSiteSlugFromCache()
            : route('frontend.index');
    }

    protected function loadDynamicPageBreadcrumbs(?Page $page = null): void
    {
        $this->push(($page->breadcrumb_title != null ? $page->breadcrumb_title : $page->name), url($page->slug));

        if (!empty($page->parent)) {
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
        if ($this->error) {
            return $this->errorTitle();
        }

        if (strlen($this->title) > 0) {
            return $this->title;
        }

        if ($this->navigateNode != null) {
            if ($this->page->page_type == 'shop') {
                return $this->navigateNode->getLabel() . ' <strong><em>\'' . ucwords($this->navigateNode->getEnglishName()) . '\'</em></strong>';
            } else {
                return store('pageTitle', 'Product Detail');
            }
        }

        return $this->breadcrumbs->first()?->title ?? 'No Name';
    }

    protected function errorTitle(): string
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
    protected function loadEasyAskBreadcrumbs(?Page $page): void
    {
        $easyAskResult = ($page->page_type == 'shop') ? store()->eaProductsData : store()->eaProductDetail;

        $breadcrumbTrails = $easyAskResult?->getBreadCrumbTrail() ?? new BreadCrumbTrail(null);

        $navigateNodes = $breadcrumbTrails->getSearchPath();

        $this->processEANodes($navigateNodes);
    }

    protected function processEANodes(array $navigateNodes = []): void
    {
        foreach (array_reverse($navigateNodes) as $breadcrumb) {
            $this->push($breadcrumb->getLabel(), frontendShopURL([
                $breadcrumb->getSEOPath(),
                'view' => active_shop_view(),
                'per_page' => request('per_page', getPaginationLengths()[0]),
                'sort_by' => request('sort_by', ''),
            ]));
        }

        $this->push('Home', $this->homeUrl());
    }
}

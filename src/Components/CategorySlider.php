<?php

namespace Amplify\Widget\Components;

use Amplify\System\Backend\Models\Category;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

/**
 * @class CategorySlider
 */
class CategorySlider extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $title;

    public $show_title;

    private int $limit;

    public bool $showName;

    public string $parentCategoryIds;

    private bool $showFeatured;

    private bool $showNavigation;

    private $slider_item_gap = '30';

    private string $sortOrder = 'asc';

    public bool $showStartingPrice = false;

    public Collection $categoryData;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $showTitle = 'true',
        $title = 'Manufacturers',
        $items = '10',
        $showName = 'true',
        $parentCategoryIds = null,
        $showFeatured = 'true',
        $sortOrder = 'asc',
        $showNavigation = 'true',
        $sliderItemGap = '15',
        $showStartingPrice = 'false'
    ) {
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);
        $this->show_title = UtilityHelper::typeCast($showTitle, 'bool');
        $this->title = $title;
        $this->limit = UtilityHelper::typeCast($items, 'integer');
        $this->slider_item_gap = UtilityHelper::typeCast($sliderItemGap, 'integer');
        $this->showFeatured = UtilityHelper::typeCast($showFeatured, 'bool');
        $this->showName = UtilityHelper::typeCast($showName, 'bool');
        $this->parentCategoryIds = UtilityHelper::typeCast($parentCategoryIds) ?? '';
        $this->showNavigation = UtilityHelper::typeCast($showNavigation, 'bool');
        $this->showStartingPrice = UtilityHelper::typeCast($showStartingPrice, 'bool');
        $this->sortOrder = ($sortOrder == 'asc') ? 'asc' : 'desc';

        $this->categoryData = new Collection;
        $this->prepareData();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return ! $this->categoryData->isEmpty();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $view = match (config('amplify.client_code')) {
            'ACT' => 'widget::client.cal-tool.widget.category-slider',
            'HAN' => 'widget::client.hanco.widget.category-slider',
            default => 'widget::category-slider',
        };

        return view($view)->with('heading', $this->title);
    }

    private function prepareData(): void
    {

        $this->categoryData = Category::query()
            ->when($this->parentCategoryIds !== '', function (Builder $query) {
                $query->whereIn('parent_id', explode(',', $this->parentCategoryIds));
            }, function (Builder $query) {
                // $query->whereNull('parent_id');
            })
            ->when($this->showFeatured, function ($query) {
                $query->where('featured', $this->showFeatured ? 1 : 0);
            })
            ->orderBy('created_at', $this->sortOrder)
            ->limit($this->limit)->get();
    }

    public function carouselOptions(): string
    {
        $responsive = match (config('amplify.client_code')) {
            'ACT' => [
                '0' => ['items' => 1],
                '470' => ['items' => 2],
                '630' => ['items' => 3],
                '991' => ['items' => 4],
                '1200' => ['items' => 4],
            ],
            'HAN' => [
                '0' => ['items' => 1],
                '470' => ['items' => 2],
                '630' => ['items' => 3],
                '991' => ['items' => 4],
                '1200' => ['items' => 4],
            ],
            default => [
                '0' => ['items' => 1],
                '470' => ['items' => 1],
                '630' => ['items' => 2],
                '991' => ['items' => 3],
                '1200' => ['items' => 3],
            ],
        };

        return json_encode([
            'lazyLoad' => true,
            'animateIn' => 'fadeIn',
            'animateOut' => 'fadeOut',
            'dots' => true,
            'loop' => true,
            'autoplay' => true,
            'autoplayTimeout' => 4000,
            'nav' => $this->showNavigation,
            'margin' => intval($this->slider_item_gap),
            'responsive' => $responsive,
        ]);
    }
}

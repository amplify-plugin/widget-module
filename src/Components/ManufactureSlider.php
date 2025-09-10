<?php

namespace Amplify\Widget\Components;

use Amplify\System\Backend\Models\Manufacturer;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * @class ManufactureSlider
 */
class ManufactureSlider extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $title;

    public $show_title;

    private int $limit;

    public bool $showName;

    private bool $showFeatured;

    private bool $showNavigation;

    private $slider_item_gap = '30';

    private string $sortOrder = 'asc';

    public Collection $manufactureData;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $showTitle = 'true',
        $title = 'Manufacturers',
        $items = '10',
        $showName = 'true',
        $showFeatured = 'true',
        $sortOrder = 'asc',
        $showNavigation = 'true',
        $sliderItemGap = '15'
    ) {
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);
        $this->show_title = UtilityHelper::typeCast($showTitle, 'bool');
        $this->title = $title;
        $this->limit = UtilityHelper::typeCast($items, 'integer');
        $this->slider_item_gap = UtilityHelper::typeCast($sliderItemGap, 'integer');
        $this->showFeatured = UtilityHelper::typeCast($showFeatured, 'bool');
        $this->showName = UtilityHelper::typeCast($showName, 'bool');
        $this->showNavigation = UtilityHelper::typeCast($showNavigation, 'bool');
        $this->sortOrder = ($sortOrder == 'asc') ? 'asc' : 'desc';

        $this->manufactureData = new Collection;

        $this->prepareData();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return ! $this->manufactureData->isEmpty();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::manufacture-slider', [
            'heading' => $this->title,
        ]);
    }

    private function prepareData(): void
    {
        $this->manufactureData = Cache::remember('site-manufacture-data', HOUR, function () {
            return Manufacturer::when($this->showFeatured, function ($query) {
                return $query->where('featured', $this->showFeatured);
            })->orderBy('created_at', $this->sortOrder)
                ->limit($this->limit)->get();
        });
    }

    public function carouselOptions(): string
    {
        return json_encode([
            'lazyLoad' => true,
            'animateIn' => 'fadeIn',
            'animateOut' => 'fadeOut',
            'dots' => false,
            'loop' > true,
            'autoplay' => true,
            'autoplayTimeout' => 4000,
            'nav' => $this->showNavigation,
            'margin' => intval($this->slider_item_gap),
            'responsive' => [
                '0' => ['items' => 2],
                '470' => ['items' => 3],
                '630' => ['items' => 4],
                '991' => ['items' => 5],
                '1200' => ['items' => 6],
            ],
        ]);
    }
}

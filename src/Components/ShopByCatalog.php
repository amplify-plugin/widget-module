<?php

namespace Amplify\Widget\Components;

use Amplify\System\Backend\Models\Category;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

/**
 * @class ShopByCatalog
 */
class ShopByCatalog extends BaseComponent
{
    public $categories;

    /**
     * @var array
     */
    public $options;

    /**
     * @var string
     */
    public $parentCategoryIds;

    public $showParentCategories;

    /**
     * Create a new component instance.
     */
    public function __construct($parentCategoryIds = '', $showParentCategories = false)
    {
        parent::__construct();
        $this->parentCategoryIds = UtilityHelper::typeCast($parentCategoryIds) ?? '';
        $this->showParentCategories = UtilityHelper::typeCast($showParentCategories, 'boolean');
        $this->parentCategoryIds = collect(explode(',', $this->parentCategoryIds))
            ->filter(function ($value) {
                return ! empty($value);
            })->unique()->toArray();
        $this->prepareData();
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
        return view('widget::shop-by-catalog');
    }

    private function prepareData()
    {
        $this->categories = Cache::remember('site-shop-by-catalog-data', HOUR, function () {
            $catalogId = config('amplify.sayt.default_catalog');

            return Category::query()
                ->when(! empty($this->parentCategoryIds), function ($query) {
                    $query->whereIn('id', $this->parentCategoryIds);
                }, function ($query) use ($catalogId) {
                    if (empty($catalogId)) {
                        $query->whereNull('parent_id');

                        return;
                    }

                    if ($this->showParentCategories) {
                        $query->where('id', $catalogId);

                        return;
                    }

                    $query->where('parent_id', $catalogId);
                })
                ->with('children')
                ->get()
                ->toArray();
        });
    }
}

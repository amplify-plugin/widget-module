<?php

namespace Amplify\Widget\Components;

use Amplify\System\Backend\Models\Brand;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

/**
 * @class ShopByBrand
 */
class ShopByBrand extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private bool $onlyFeatured;

    public bool $nameOnly;

    public string $placeholder;

    /**
     * Create a new component instance.
     */
    public function __construct(string $onlyFeatured = 'false', string $nameOnly = 'false', string $searchPlaceholder = 'Search site')
    {
        parent::__construct();

        $this->onlyFeatured = UtilityHelper::typeCast($onlyFeatured, 'bool');
        $this->nameOnly = UtilityHelper::typeCast($nameOnly, 'bool');
        $this->placeholder = $searchPlaceholder;
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
        return view('widget::shop-by-brand', $this->getDataForShopByBrand());
    }

    private function getDataForShopByBrand()
    {
        $initialQuery = Brand::query();

        $filteredGroupedBrands = $groupedBrands = $initialQuery
            ->when($this->onlyFeatured == true, function (Builder $builder) {
                return $builder->where('featured', true);
            })
            ->get()
            ->groupBy(function ($item, $key) {
                $firstLetter = $item->title[0];
                if (! ctype_alnum($firstLetter)) {
                    return '@';
                }

                return ucfirst($firstLetter);     // treats the name string as an array

            })
            ->sortBy(function ($item, $key) {      // sorts A-Z at the top level
                return $key;
            });

        if (! request()->has('key') && ! request()->has('search')) {
            $filteredGroupedBrands = $filteredGroupedBrands->map(function ($brandItems) {
                return [
                    'totalItems' => count($brandItems),
                    'brands' => $brandItems->take(6),
                ];
            });
        }

        if (Request::has('key')) {
            $filteredGroupedBrands = array_filter($filteredGroupedBrands->toArray(), function ($item) {
                if (request()->key == '*') {
                    if (! ctype_alnum((string) $item)) {
                        return true;
                    }
                }

                return $item == request()->key;
            }, ARRAY_FILTER_USE_KEY);
        }

        if (Request::has('search')) {
            $filteredGroupedBrands = $initialQuery
                ->where('title', 'like', '%'.request()->search.'%')
                ->get()
                ->groupBy(function ($item, $key) {
                    return $item->title[0];     // treats the name string as an array
                })
                ->sortBy(function ($item, $key) {      // sorts A-Z at the top level
                    return $key;
                })->map(function ($brandItems) {
                    return [
                        'totalItems' => count($brandItems),
                        'brands' => $brandItems->take(6),
                    ];
                })
                ->toArray();
        }

        $class = match (config('amplify.client_code')) {
            'RHS' => ['bg' => 'btn-rhs-bg', 'bg-outline' => 'btn-rhs-bg-outline', 'hide_number_row' => 1],
            default => ['bg' => 'btn-warning', 'bg-outline' => 'btn-outline-warning'],
        };

        return ['filtered_brands' => $filteredGroupedBrands, 'brands' => $groupedBrands, 'btn_class' => $class];
    }
}

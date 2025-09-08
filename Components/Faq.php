<?php

namespace Amplify\Widget\Components;

use Amplify\System\Backend\Models\FaqCategory;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @class Faq
 */
class Faq extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public Collection $faqs;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->faqs = collect();
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
        $this->loadFaqCategories();

        $currentFaqCategory = store()->faqCategoryModel;

        return view('widget::faq', [
            'current' => $currentFaqCategory,
        ]);
    }

    private function loadFaqCategories()
    {
        FaqCategory::all()->each(function ($category) {
            $item = new \stdClass;
            $item->id = $category->id;
            $item->name = $category->name;
            $item->slug = $category->slug;
            $item->link = route('frontend.faqs.show', $category->slug);
            $item->group = $this->getGroup($category->name);
            $this->faqs->push($item);
        });
    }

    private function getGroup($name)
    {
        $firstLetter = Str::lower($name[0]);

        $groups = [
            'ae' => range('a', 'e'),
            'fj' => range('f', 'j'),
            'ko' => range('k', 'o'),
            'pt' => range('p', 't'),
            'uz' => range('u', 'z'),
        ];

        foreach ($groups as $group => $entries) {
            if (in_array($firstLetter, $entries)) {
                return $group;
            }
        }

        return 'all';
    }
}

<?php

namespace Amplify\Widget\Components\EasyAsk;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class CategoryLinkList
 */
class CategoryLinkList extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private $categories;

    /**
     * @var null
     */
    private $sub_cat;

    private int $levelDepth;

    /**
     * Create a new component instance.
     *
     * @param  null  $sub_cat
     * @param  int  $levelDepth
     */
    public function __construct($categories,
        $sub_cat = null,
        $levelDepth = 0)
    {
        parent::__construct();

        $this->categories = $categories;
        $this->sub_cat = $sub_cat;
        $this->levelDepth = $levelDepth;
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

        return view('widget::easy-ask.category-link-list', [
            'categories' => $this->categories,
        ]);
    }
}

<?php

namespace Amplify\Widget\Components\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class FavoriteList
 */
class FavoriteList extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Summary of title
     */
    public string $title;

    public function __construct($title = 'Favorites List')
    {
        $this->title = $title;
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer_check();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::product.favorite-list');
    }

    public function listTypeOptions(): string
    {
        $listTypes = config('amplify.constant.favorite_list_type', []);

        if (! config('amplify.basic.enable_quick_list', true)) {
            unset($listTypes['quick-list']);
        }

        $html = '';

        foreach ($listTypes as $key => $item) {
            $html .= "<option value='{$key}'>{$item}</option>".PHP_EOL;
        }

        return $html;
    }
}

<?php

namespace Amplify\Widget\Components\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class FavouriteManageButton
 */
class FavouriteManageButton extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     *
     * @param  bool  $alreadyExists
     */
    public function __construct(public $productId = null,
        public $alreadyExists = false,
        public $favouriteListId = null)
    {
        parent::__construct();

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

        return view('widget::product.favourite-manage-button');
    }

    public function hasPermission(): bool
    {
        return customer(true)->canAny(['favorites.manage-global-list', 'favorites.manage-personal-list']);
    }

    public function htmlAttributes(): string
    {
        $class = 'btn btn-sm btn-favorite';

        $class = ($this->alreadyExists) ? $class.' active' : $class.' btn-outline-secondary';

        $this->attributes = $this->attributes->class([$class]);

        return parent::htmlAttributes();
    }
}

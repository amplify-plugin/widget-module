<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class InformationTabs
 */
class InformationTabs extends BaseComponent
{
    public array $tabs = [];

    public function __construct(
        public Product|ItemRow $product,
        public string $featureSpecsView = 'list',
        public array $only = [],
        public array $skip = [])
    {
        parent::__construct();

        $this->tabs = [
            Product::TAB_DESCRIPTION,
            Product::TAB_FEATURE,
            Product::TAB_SPECIFICATION,
            Product::TAB_DOCUMENT,
            Product::TAB_SKU,
            Product::TAB_RELATED_ITEM,
        ];
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return true;
    }

    private function configureTabs()
    {
        if (! empty($this->only)) {
            $this->tabs = array_unique($this->only);

            return;
        }

        if (! empty($this->skip)) {
            $this->tabs = array_diff($this->tabs, $this->skip);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->configureTabs();

        return view('widget::product.information-tabs');
    }

    public function displayTab(string $name = ''): bool
    {
        return in_array($name, $this->tabs);
    }
}

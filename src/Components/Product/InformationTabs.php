<?php

namespace Amplify\Widget\Components\Product;

use Amplify\System\Backend\Models\Product;
use Amplify\System\Sayt\Classes\ItemRow;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

/**
 * @class InformationTabs
 */
class InformationTabs extends BaseComponent
{
    public array $entries = [];

    public array $views = [];

    public function __construct(
        public Product|ItemRow $product,
        public array $tabs = ['description', 'feature', 'specification', 'document', 'sku', 'related-products'],
        public string $headerClass = 'nav-justified'
    ) {
        parent::__construct();
    }

    private function configureTabs(): void
    {
        foreach ($this->tabs as $key => $tab) {
            if (is_string($tab) && is_int($key)) {
                $viewPath = Str::contains($tab, '::') ? $tab : "widget::product.tabs.{$tab}";
                $this->entries[$viewPath] = [
                    'name' => $tab,
                    'label' => Str::title(Str::replace(['_', '-'], ' ', $tab)),
                    'active' => false,
                ];

                continue;
            }

            if (is_string($key) && is_bool($tab)) {
                $viewPath = Str::contains($key, '::') ? $key : "widget::product.tabs.{$key}";
                $this->entries[$viewPath] = [
                    'name' => $key,
                    'label' => Str::title(Str::replace(['_', '-'], ' ', $key)),
                    'active' => $tab,
                ];

                continue;
            }

            if (is_string($key) && is_array($tab)) {
                $viewPath = Str::contains($key, '::') ? $key : "widget::product.tabs.{$key}";

                $tab['name'] = $key;
                $tab['active'] = $tab['active'] ?? false;
                $tab['label'] = $tab['label'] ?? Str::title(Str::replace(['_', '-'], ' ', $key));
                $this->entries[$viewPath] = $tab;
            }
        }

        $this->views = array_keys($this->entries);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->configureTabs();

        return view('widget::product.information-tabs');
    }
}

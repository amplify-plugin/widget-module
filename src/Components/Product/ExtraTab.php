<?php

namespace Amplify\Widget\Components\Product;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

/**
 * @class InformationExtraTab
 */
class ExtraTab extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title = 'Additional',
        public bool $shouldRender = true,
    ) {
        parent::__construct();

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return $this->shouldRender;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::product.tabs.extra-tab');
    }

    public function htmlAttributes(): string
    {
        $classes = ['tab-pane'];

        $this->attributes = $this->attributes->merge([
            'class' => implode(' ', $classes),
            'role' => 'tabpanel',
            'aria-labelledby' => $this->slugTitle().'-tab',
            'id' => $this->slugTitle(),
        ]);

        return parent::htmlAttributes();
    }

    public function slugTitle(): string
    {
        return Str::slug($this->title);
    }

    public function displayableTitle()
    {
        if ($this->title == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'title');

            return $titleAttribute['value'];
        }

        return $this->title;
    }
}

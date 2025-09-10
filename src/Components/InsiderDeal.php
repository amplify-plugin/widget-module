<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class InsiderDeal
 */
class InsiderDeal extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $secondaryBtnText,
        public string $secondaryBtnUrl,
        public string $primaryBtnText,
        public string $primaryBtnUrl,
        public string $showOnlyVisitor
    ) {
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
        return view('widget::insider-deal');
    }

    public function htmlAttributes(): string
    {
        $class = 'bg-danger rounded text-white p-4 p-md-14 -m-14';

        $this->attributes = $this->attributes->class([$class]);

        return parent::htmlAttributes();
    }
}

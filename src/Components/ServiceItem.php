<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Illuminate\Contracts\View\View;

/**
 * @class ServiceItem
 */
class ServiceItem extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public string $imageUrl;

    public string $alternativeText;

    public string $title;

    public string $subtitle;

    public function __construct(
        $imageUrl = '#',
        $alternativeText = '',
        $title = '',
        $subtitle = ''
    ) {

        parent::__construct();
        $this->imageUrl = $imageUrl;
        $this->alternativeText = $alternativeText;
        $this->title = $title;
        $this->subtitle = $subtitle;
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
    public function render()
    {
        return view('widget::service-item');
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['col-md-3 col-sm-6']);

        return parent::htmlAttributes();
    }
}

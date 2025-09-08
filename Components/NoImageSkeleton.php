<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class NoImageSkeleton
 */
class NoImageSkeleton extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct()
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
        $filepath = public_path(config('amplify.frontend.fallback_image_path'));

        if (! file_exists($filepath)) {
            $filepath = public_path('img/No-Image-Placeholder-min.png');
        }

        $imagesrc = 'data:image/png;base64, '.base64_encode(file_get_contents($filepath));

        return view('widget::no-image-skeleton', compact('imagesrc'));
    }
}

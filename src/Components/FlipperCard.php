<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class FlipperCard
 */
class FlipperCard extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $contentItem;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $contentId = '',
        public string $image = '',
        public string $link = '',
        public string $buttonLabel = '',
    ) {
        parent::__construct();
        $this->contentItem = \Amplify\System\Cms\Models\Content::find($this->contentId);
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return $this->contentItem != null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::flipper-card');
    }

    public function image()
    {
        return url(assets_image($this->image));
    }

    public function sliceContent()
    {
        $originalContent = $this->contentItem->content ?? '';
        if (! isset($originalContent)) {
            return null;
        }
        // $desiredLength = 120;
        // $plainTextContent = strip_tags($originalContent);
        // $slicedContent = mb_substr($plainTextContent, 0, $desiredLength);

        // if (str_starts_with($slicedContent, '&nbsp;')) {
        //     $slicedContent = substr($slicedContent, 6);
        // }
        // if (strlen($originalContent) > $desiredLength) {
        //     $slicedContent .= '...';
        // }

        return $originalContent;
    }

    public function htmlAttributes(): string
    {
        $this->attributes = $this->attributes->class(['flip-card']);

        return parent::htmlAttributes();
    }
}

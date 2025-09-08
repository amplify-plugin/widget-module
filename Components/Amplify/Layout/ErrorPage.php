<?php

namespace Amplify\Widget\Components\Amplify\Layout;

use Amplify\Frontend\Traits\HasDynamicPage;
use Amplify\System\Cms\Models\Page;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ErrorPageLayout
 */
class ErrorPage extends BaseComponent
{
    use HasDynamicPage {
        render as dynamicRender;
    }

    public function __construct(public string $pageTitle)
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
        return function (array $data) {

            store()->dynamicPageModel = new Page([
                'styles' => '',
                'content' => (string) $data['slot'],
                'meta_tags' => [],
                'has_footer' => true,
                'name' => $this->pageTitle,
                'page_type' => 'static',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->dynamicRender();
        };
    }
}

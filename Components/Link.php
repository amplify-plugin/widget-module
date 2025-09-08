<?php

namespace Amplify\Widget\Components;

use Amplify\System\Cms\Models\Page;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class Url
 */
class Link extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public string $linkType;

    public string $openInNewWindow;

    public string $displayAsButton;

    private ?int $page;

    private ?string $externalFullUrl;

    /**
     * Create a new component instance.
     */
    public function __construct(string $linkType = 'page',
        string $page = '',
        string $externalFullUrl = '',
        string $openInNewWindow = 'false',
        string $displayAsButton = 'false')
    {
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);

        $this->linkType = $linkType;
        $this->page = UtilityHelper::typeCast($page, 'integer');
        $this->externalFullUrl = (! is_numeric($this->page)) ? $externalFullUrl : null;
        $this->openInNewWindow = UtilityHelper::typeCast($openInNewWindow, 'boolean');
        $this->displayAsButton = UtilityHelper::typeCast($displayAsButton, 'boolean');
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
        return view('widget::link');
    }

    public function link(): string
    {
        return is_numeric($this->page)
            ? Page::find($this->page)?->full_url ?? url('#')
            : url($this->externalFullUrl);
    }
}

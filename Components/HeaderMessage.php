<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class HeaderMessage
 */
class HeaderMessage extends BaseComponent
{
    public ?string $headerMessage;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->headerMessage = config('amplify.cms.header_content', null);

        parent::__construct();
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return ! empty($this->headerMessage);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('widget::header-message');
    }
}

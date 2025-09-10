<?php

namespace Amplify\Widget\Components\Event;

use Amplify\System\Backend\Models\Webinar;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class EventContent
 */
class EventContent extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public ?Webinar $webinar;

    public bool $showShortDesc;

    /**
     * Create a new component instance.
     */
    public function __construct($showShortDesc = true)
    {
        $this->showShortDesc = UtilityHelper::typeCast($showShortDesc, 'bool');
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);
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
        $this->webinar = store('webinar');

        return view('widget::event.event-content');
    }
}

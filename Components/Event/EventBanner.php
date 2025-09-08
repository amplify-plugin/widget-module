<?php

namespace Amplify\Widget\Components\Event;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class EventBanner
 */
class EventBanner extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $nav = 'false',
        public string $dots = 'true',
        public string $pauseOnHover = 'false',
        public string $showOnMobile = 'false',
        public string $height = '200px'
    ) {
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
        $webinar = store()->webinar;
        $bannerZoneCode = $webinar->bannerZone?->code ?? '';

        return view('widget::event.event-banner', [
            'bannerZoneCode' => $bannerZoneCode,
        ]);
    }
}

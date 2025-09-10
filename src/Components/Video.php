<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class Video
 */
class Video extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $playerHeight = '640',
        public string $playerWidth = '960',
        public string $videoUrl = '',
        public string $thumbnailImage = '')
    {
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

        return view('widget::video');
    }

    public function videoPlayerConfiguration()
    {
        return <<<HTML
            <div class="wrapper">
                <div class="video-wrapper">
                    <iframe class="pswp__video"
                        width="{$this->playerWidth}"
                        height="{$this->playerHeight}"
                        src="{$this->videoUrl}"
                        frameborder="0" allowfullscreen>
                    </iframe>
               </div>
            </div>
HTML;

    }
}

<?php

namespace Amplify\Widget\Components\Google;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class GoogleAnalytic
 */
class AnalyticInit extends BaseComponent
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
        $analytics_id = config('amplify.google.google_analytics_id', '');

        if ($analytics_id == null) {
            $analytics_id = '';
        }

        $analytics_url = config('amplify.google.google_analytics_url', '');

        if ($analytics_url == null) {
            $analytics_url = '';
        }

        $analytics_url = str_replace("?id={$analytics_id}", '', $analytics_url);

        $tag_manager_id = config('amplify.google.google_tag_manager_id');

        return view('widget::google.google-analytic', [
            'analytics_id' => $analytics_id,
            'analytics_url' => $analytics_url,
            'tag_manager_id' => $tag_manager_id,
        ]);
    }
}

<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Favicon
 */
class Favicon extends BaseComponent
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $imagePath = config('amplify.cms.favicon_path', config('amplify.cms.logo_path', '#'));

        return view('widget::favicon', [
            'favicon' => $imagePath,
        ]);
    }
}

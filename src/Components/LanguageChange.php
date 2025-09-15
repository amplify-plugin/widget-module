<?php

namespace Amplify\Widget\Components;

use Amplify\System\Support\Language;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class LanguageChange
 */
class LanguageChange extends BaseComponent
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
        return config('amplify.frontend.enable_language', false);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $languages = new Language();

        $active = $languages->where('code', session('locale_lang', config('app.locale')))->first();

        return view('widget::language-change', [
            'languages' => $languages,
            'active' => $active,
        ]);
    }
}

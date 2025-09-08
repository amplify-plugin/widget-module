<?php

namespace Amplify\Widget\Components;

use Amplify\System\Backend\Models\Language;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

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
        $languages = Cache::remember('site-language', 86400, function () {
            return Language::all();
        });

        $active = $languages->where('code', session('locale_lang', config('app.fallback_locale')))->first();

        return view('widget::language-change', [
            'languages' => $languages,
            'active' => $active,
        ]);
    }
}

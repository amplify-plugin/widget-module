<?php

namespace Amplify\Widget\Components\Auth;

use Amplify\System\Helpers\SecurityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ForceResetPassword
 */
class ForceResetPassword extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private string $title = '';

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
        $minPassLength = SecurityHelper::passwordLength();

        return view('widget::auth.force-reset-password', compact('minPassLength'));
    }

    public function displayableTitle()
    {
        if ($this->title == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'title');

            return $titleAttribute['value'];
        }

        return $this->title;
    }
}

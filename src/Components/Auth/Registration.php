<?php

namespace Amplify\Widget\Components\Auth;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Registration
 */
class Registration extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private $buttonTitle;

    /**
     * Create a new component instance.
     */
    public function __construct(
        private string $title = '',
        private string $subtitle = ''
    ) {
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
        return view('widget::auth.registration');
    }

    public function displayableTitle()
    {
        if ($this->title == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'title');

            return $titleAttribute['value'];
        }

        return trans($this->title);
    }

    public function displayableSubTitle()
    {
        if ($this->subtitle == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'subtitle');

            return $titleAttribute['value'];
        }

        return trans($this->subtitle);
    }

    public function registerButtonTitle()
    {
        if ($this->buttonTitle == '') {
            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'button-title');

            return $titleAttribute['value'];
        }

        return trans($this->buttonTitle);
    }

    //    public function displayCompanyTabTitle()
    //    {
    //        if ($this->companyTabTitle == '') {
    //            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'company-tab-title');
    //
    //            return $titleAttribute['value'];
    //        }
    //
    //        return $this->companyTabTitle;
    //    }
    //
    //    public function displayAdminTabTitle()
    //    {
    //        if ($this->adminTabTitle == '') {
    //            $titleAttribute = collect($this->options['@attributes'])->firstWhere('name', '=', 'admin-tab-title');
    //
    //            return $titleAttribute['value'];
    //        }
    //
    //        return $this->adminTabTitle;
    //    }
}

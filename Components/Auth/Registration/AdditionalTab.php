<?php

namespace Amplify\Widget\Components\Auth\Registration;

use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

/**
 * @class AdditionalTab
 */
class AdditionalTab extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title = 'Additional',
        public string $subtitle = 'Additional',
        public string $submitButtonLabel = 'Submit',
        public string $active = 'false')
    {
        $this->active = UtilityHelper::typeCast($active, 'bool');
        //        if (request('tab') == 'request-account') {
        //            $this->active = true;
        //        }
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
        return view('widget::auth.registration.additional-tab');
    }

    public function htmlAttributes(): string
    {
        $classes = ['tab-pane'];

        if ($this->active) {
            $classes[] = 'active';
        }

        $this->attributes = $this->attributes->merge([
            'class' => implode(' ', $classes),
            'role' => 'tabpanel',
            'aria-labelledby' => $this->slugTitle().'-tab',
            'id' => $this->slugTitle(),
        ]);

        return parent::htmlAttributes();
    }

    public function slugTitle(): string
    {
        return Str::slug($this->title);
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
}

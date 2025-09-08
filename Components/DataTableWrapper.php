<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class DataTableWrapper
 */
class DataTableWrapper extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private ?string $id;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $id = null)
    {
        parent::__construct();

        $this->id = $id;
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
        return view('widget::data-table-wrapper', [
            'id' => $this->id,
        ]);
    }
}

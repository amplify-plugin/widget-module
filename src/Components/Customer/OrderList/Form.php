<?php

namespace Amplify\Widget\Components\Customer\OrderList;

use Amplify\System\Backend\Models\Contact;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Create
 */
class Form extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * @var Contact|null
     */
    public $favourite;

    private bool $editable;

    /**
     * Create a new component instance.
     *
     * @param  bool  $editable
     *
     * @throws \ErrorException
     */
    public function __construct($editable)
    {
        parent::__construct();

        $this->editable = UtilityHelper::typeCast($editable, 'boolean');

        if ($this->editable) {
            $this->favourite = store()->favouriteModel;
        }
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
        $action_route = route('frontend.favourites.store');
        $action_method = 'POST';

        if ($this->editable) {
            $action_route = route('frontend.favourites.update', ($this->favourite->id ?? ''));
            $action_method = 'PUT';
        }

        return view('widget::customer.favourite.form', [
            'favourite' => $this->favourite,
            'action_route' => $action_route,
            'action_method' => $action_method,
        ]);
    }
}

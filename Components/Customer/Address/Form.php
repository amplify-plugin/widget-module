<?php

namespace Amplify\Widget\Components\Customer\Address;

use Amplify\System\Backend\Models\Country;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Update
 */
class Form extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $address;

    /**
     * @var bool|float|int|mixed|string|null
     */
    private bool $editable;

    /**
     * Create a new component instance.
     *
     * @throws \ErrorException
     */
    public function __construct($editable)
    {
        parent::__construct();

        $this->editable = UtilityHelper::typeCast($editable, 'boolean');

        if ($this->editable) {
            $this->address = store('addressModel');
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
        $action_route = route('frontend.addresses.store');
        $action_method = 'POST';

        if ($this->editable) {
            $action_route = route('frontend.addresses.update', ($this->address->id ?? ''));
            $action_method = 'PUT';
        }

        $countries = Country::enabled()->pluck('name', 'iso2')->toArray();

        return view('widget::customer.address.form', [
            'address' => $this->address,
            'editable' => $this->editable,
            'action_route' => $action_route,
            'action_method' => $action_method,
            'countries' => $countries,
        ]);
    }
}

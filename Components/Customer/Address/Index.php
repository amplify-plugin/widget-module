<?php

namespace Amplify\Widget\Components\Customer\Address;

use Amplify\System\Backend\Models\CustomerAddress;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class Index extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $addressNameLabel = '',
        public string $addressLineLabel = '',
        public string $zipCodeLabel = '',
        public string $cityLabel = '',
        public string $stateLabel = '',
        public string $countryLabel = '',
        public string $addressCodeLabel = ''
    ) {
        parent::__construct();

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer(true)->can('ship-to-addresses.list');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $perPage = request('per_page', getPaginationLengths()[0]);

        $search = request('search');

        $columns = [
            'address_code' => (strlen($this->addressCodeLabel) != 0),
            'address_name' => (strlen($this->addressNameLabel) != 0),
            'address_line' => (strlen($this->addressLineLabel) != 0),
            'zip_code' => (strlen($this->zipCodeLabel) != 0),
            'city' => (strlen($this->cityLabel) != 0),
            'state' => (strlen($this->stateLabel) != 0),
            'country' => (strlen($this->countryLabel) != 0),
        ];

        $addresses = CustomerAddress::whereCustomerId(customer()->getKey())
            ->where(function ($query) use ($search) {
                $query->where('address_name', 'like', "%{$search}%")
                    ->orWhere('address_code', 'like', "%{$search}%")
                    ->orWhere('address_1', 'like', "%{$search}%")
                    ->orWhere('address_2', 'like', "%{$search}%")
                    ->orWhere('address_3', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('country_code', 'like', "%{$search}%")
                    ->orWhere('zip_code', 'like', "%{$search}%");
            })
            ->latest()->paginate($perPage)->withQueryString();

        return view('widget::customer.address.index', compact('columns', 'addresses'));
    }
}

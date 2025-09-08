<?php

namespace Amplify\Widget\Components\Customer\Contact\Login;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;

/**
 * @class Index
 */
class Index extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $photoLabel = '',
        public string $customerLabel = '',
        public string $nameLabel = '',
        public string $emailLabel = '',
        public string $phoneLabel = '',
        public string $roleLabel = '')
    {
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);

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
        $contacts = store('contactLoginPaginate', new LengthAwarePaginator(
            collect(),
            0,
            getPaginationLengths()[0],
        ));

        $columns = [
            'photo' => (strlen($this->photoLabel) != 0),
            'customer_name' => (strlen($this->customerLabel) != 0),
            'name' => (strlen($this->nameLabel) != 0),
            'email' => (strlen($this->emailLabel) != 0),
            'phone' => (strlen($this->phoneLabel) != 0),
            'role' => (strlen($this->roleLabel) != 0),
        ];

        return view('widget::customer.contact.login.index', compact('contacts', 'columns'));
    }
}

<?php

namespace Amplify\Widget\Components\Customer\Contact;

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
        public string $nameLabel = '',
        public string $emailLabel = '',
        public string $phoneLabel = '',
        public string $roleLabel = '',
        public string $orderLimitLabel = '',
        public string $dailyBudgetLimitLabel = '',
        public string $monthlyBudgetLimitLabel = '',
        public string $photoLabel = ''
    ) {

        parent::__construct();

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer(true)->can('contact-management.list');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $contacts = $this->getContactResults();

        $columns = [
            'name' => (strlen($this->nameLabel) != 0),
            'email' => (strlen($this->emailLabel) != 0),
            'phone' => (strlen($this->phoneLabel) != 0),
            'role' => (strlen($this->roleLabel) != 0),
            'order_limit' => (strlen($this->orderLimitLabel) != 0),
            'daily_budget_limit' => (strlen($this->dailyBudgetLimitLabel) != 0),
            'monthly_budget_limit' => (strlen($this->monthlyBudgetLimitLabel) != 0),
            'photo' => (strlen($this->photoLabel) != 0),
        ];

        return view('widget::customer.contact.index', compact('contacts', 'columns'));
    }

    private function getContactResults()
    {
        $perPage = request('per_page', 10);
        $search = request('search');
        $direction = request('dir', 'asc');
        $column = request('column', 'name');
        $filters = request()->query();
        $customer = customer();

        return \Amplify\System\Backend\Models\Contact::when(isset($filters['isAdmin']) && $filters['isAdmin'], function ($query) {
            return $query->where('is_admin', true);
        })
            ->when(isset($filters['isApprover']) && $filters['isApprover'], function ($query) {
                return $query->where('is_approver', true);
            })
            ->where(['customer_id' => $customer->id, ['id', '<>', $customer->id]])
            ->where(function ($query) use ($search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('order_limit', 'LIKE', "%{$search}%");
            })
            ->orderBy($column, $direction)
            ->where('id', '!=', customer(true)->id)
            ->paginate($perPage)
            ->withQueryString();
    }
}

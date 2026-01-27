<?php

namespace Amplify\Widget\Components\Customer\OrderList;

use Amplify\System\Backend\Models\OrderList;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Create
 */
class Form extends BaseComponent
{
    /**
     * @var OrderList|null
     */
    public $orderlist;

    /**
     * Create a new component instance.
     *
     *
     * @throws \ErrorException
     */
    public function __construct(public bool $editable = true)
    {
        parent::__construct();

        if ($this->editable) {
            $this->orderlist = store()->orderListModel;
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
        $action_route = route('frontend.order-lists.store');
        $action_method = 'POST';

        if ($this->editable) {
            $action_route = route('frontend.order-lists.update', ($this->orderlist->id ?? ''));
            $action_method = 'PUT';
        }

        return view('widget::customer.order-list.form', [
            'favourite' => $this->orderlist,
            'action_route' => $action_route,
            'action_method' => $action_method,
        ]);
    }
}

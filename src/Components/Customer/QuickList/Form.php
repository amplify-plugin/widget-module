<?php

namespace Amplify\Widget\Components\Customer\QuickList;

use Amplify\System\Backend\Models\Contact;
use Amplify\System\Backend\Models\Product;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

/**
 * @class Form
 */
class Form extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->options = Config::get('amplify.widget.'.__CLASS__, []);

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return (bool) store()->orderListModel;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $quickListModel = store('orderListModel');

        $productIds = $quickListModel->orderListItems->pluck('product_id')?->toArray() ?? [];

        $productList = Product::filterProduct(['q' => $productIds])->get()->toJson();

        $contactList = Contact::select('id', 'name')->where('customer_id', customer(true)->customer_id)->get()->toJSON();

        return view('widget::customer.quick-list.form', compact('quickListModel', 'productList', 'contactList'));
    }
}

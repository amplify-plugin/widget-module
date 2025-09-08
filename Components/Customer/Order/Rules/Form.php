<?php

namespace Amplify\Widget\Components\Customer\Order\Rules;

use Amplify\System\Helpers\UtilityHelper;
use Amplify\System\OrderRule\Models\CustomerOrderRule;
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
    public function __construct(
        public $editable = false
    ) {
        $this->editable = UtilityHelper::typeCast($this->editable, 'bool');
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
        $method = 'post';
        $customer_rule = [];

        if ($this->editable) {
            $method = 'put';
            $customer_rule = CustomerOrderRule::where('id', request()->order_rule)
                ->where('customer_id', customer()->id)
                ->first();
        }

        return view('widget::customer.order.rules.form', [
            'method' => $method,
            'customer_rule' => $customer_rule,
        ]);
    }
}

<?php

namespace Amplify\Widget\Components\Customer\Order;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class Index extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    private $component;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return customer(true)->can('order.view');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $class = match (config('amplify.client_code')) {
            default => \Amplify\Widget\Components\Client\Demo\Order\Index::class,
        };
        $this->component = new $class;

        $this->component->attributes = $this->attributes;

        return $this->component->render();
    }

    public function orderStatusOptions(): array
    {
        if (ErpApi::currentErp() == 'facts-erp') {
            return [
                'In Process' => 'In Process',
                'Ordered' => 'Ordered',
                'Picked' => 'Picked',
                'Shipped' => 'Shipped',
                'Invoiced' => 'Invoiced',
                'Paid' => 'Paid',
                'Cancelled' => 'Cancelled',
            ];
        } else {
            return [
                'Ordered' => 'Ordered',
                'Picked' => 'Picked',
                'Shipped' => 'Shipped',
                'Invoiced' => 'Invoiced',
                'Paid' => 'Paid',
                'Cancelled' => 'Cancelled',
            ];
        }
    }
}

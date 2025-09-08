<?php

namespace Amplify\Widget\Components\Checkout;

use Amplify\ErpApi\Facades\ErpApi;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Review
 */
class Review extends BaseComponent
{
    public function __construct(public bool $isActive = false, public string $id = 'review', public ?int $index = null)
    {
        parent::__construct();
    }

    /**
     * @var array
     */
    public $options;

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
        $customer = ErpApi::getCustomerDetail();

        return view('widget::checkout.review', compact('customer'));
    }
}

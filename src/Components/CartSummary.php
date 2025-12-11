<?php

namespace Amplify\Widget\Components;

use Amplify\System\Sayt\Facade\Sayt;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class CartSummary
 */
class CartSummary extends BaseComponent
{
    public function __construct(public string $backToUrl = 'home',
                                public bool   $createFavoriteFromCart = true,
                                public string $createFavoriteLabel = 'Create Shopping List'
    )
    {
        parent::__construct();
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
//        $class = match (config('amplify.client_code')) {
//            'RHS' => \Amplify\Widget\Components\Client\Rhsparts\CartSummary::class,
//            default => \Amplify\Widget\Components\Client\Demo\CartSummary::class,
//        };
//
//        $this->component = new $class;
//
//        $this->component->attributes = $this->attributes;

        $templateBrandColor = theme_option(key: 'primary_color', default: '#002767');

        $isCartEmpty = ! getCart()->cartItems()->exists();

        $cartId = getCart()->getKey();

        return view('widget::cart-summary', compact('templateBrandColor', 'isCartEmpty', 'cartId'));
    }

    public function createShoppingListLabel(): string
    {
        return $this->createFavoriteLabel;
    }

    public function backToShoppingUrl(): string
    {
        if ($this->backToUrl == 'home') {
            return frontendHomeURL();
        }

        return frontendShopURL(Sayt::getDefaultCatPath());
    }
}

<?php

namespace Amplify\Widget\Components\Campaign;

use Amplify\System\Backend\Models\OrderList;
use Amplify\System\Helpers\UtilityHelper;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class CampaignProductList
 */
class CampaignProductList extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    public $showShopToolbar;

    private $productView;

    protected $orderList;

    /**
     * @var bool|float|int|mixed|string|null
     */
    public mixed $allowFavorite;

    /**
     * Create a new component instance.
     */
    public function __construct(string $showShopToolbar = 'true', string $productView = '', string $allowFavorite = 'false')
    {
        parent::__construct();
        $this->showShopToolbar = UtilityHelper::typeCast($showShopToolbar, 'bool');
        $this->allowFavorite = UtilityHelper::typeCast($allowFavorite, 'bool');
        $this->productView = in_array($productView, ['list', 'grid']) ? $productView : active_shop_view();

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
     *
     * @throws \ErrorException
     */
    public function render(): View|Closure|string
    {
        $sortBy = explode('--', request()->input('short_by', 'product_code--ASC'));
        $productView = request()->input('view', $this->productView);
        $perPage = request()->input('per_page', 10);
        $hasPermission = customer(true)?->canAny(['favorites.manage-global-list', 'favorites.manage-personal-list']) ?? false;
        $authenticated = customer_check();

        $campaign = store()->campaign;
        $products = $campaign->products()
            ->where(function ($query) use ($sortBy) {
                switch ($sortBy[0]) {
                    case 'product_code':
                        $query->orderBy('product_code', $sortBy[1] ?? 'DESC');
                        break;

                    case 'product_name':
                        $query->orderBy('product_name', $sortBy[1] ?? 'DESC');
                        break;

                    default:
                        $query->latest();
                        break;
                }
            })
            ->paginate($perPage)->withQueryString();

        if ($authenticated) {
            $this->orderList = OrderList::with('orderListItems')->whereCustomerId(customer()->getKey())->get();

            if (! $this->orderList->isEmpty()) {
                $products->map(function ($item) {
                    $this->productExistOnFavorite($item->id, $item);

                    return $item;
                });
            }
        }

        $sortBy = implode('--', $sortBy);

        return view('widget::campaign.campaign-product-list', [
            'authenticated' => $authenticated,
            'hasPermission' => $hasPermission,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'productView' => $productView,
            'campaign' => $campaign,
            'products' => $products,
        ]);
    }

    private function productExistOnFavorite($id, &$product): void
    {
        foreach ($this->orderList as $orderList) {
            if ($item = $orderList->orderListItems->firstWhere('product_id', $id)) {
                $product->exists_in_favorite = true;
                $product->favorite_list_id = $item->id;
            }
        }
    }
}

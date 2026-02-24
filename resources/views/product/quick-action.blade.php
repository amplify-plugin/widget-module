<div {!! $htmlAttributes !!}>
    @if($isMasterProduct($product))
        <div class="d-flex gap-2">
            <a href="{{ frontendSingleProductURL($product, $seoPath) }}?has_sku=1"
               class="btn m-0 btn-primary btn-sm btn-block">
                {{ __($detailLabel) }}
            </a>
            {{--            <button class="btn btn-sm btn-outline-info m-0 p-0"--}}
            {{--                    data-toggle="tooltip"--}}
            {{--                    title="View SKUs"--}}
            {{--                    style="width: 42px; height: 36px">--}}
            {{--                <i class="icon-stack-2"></i>--}}
            {{--            </button>--}}
        </div>
    @else
        <x-cart.quantity-update :product="$product" :index="$index"/>
        <div class="d-flex gap-2">
            @if(class_exists(\Amplify\Wishlist\Widgets\WishlistButton::class))
                <x-wishlist-button :product="$product" class="btn m-0 btn-sm px-0">
                    <x-slot:add-label>
                        <i class="icon-heart text-primary"></i>
                    </x-slot>
                    <x-slot:remove-label>
                        <i class="icon-heart"></i>
                    </x-slot>
                </x-wishlist-button>
            @endif
            <button
                    data-warehouse="{{ $defaultWarehouse }}"
                    onclick="Amplify.addSingleItemToCart(this, '#cart-item-{{$index}}', {{ json_encode($extras) }})"
                    data-options="{{ json_encode($cartData) }}"
                    class="btn btn-block btn-sm btn-primary m-0">
                {{ __($cartLabel) }}
            </button>
        </div>
        <x-product-shopping-list :product-id="$product->Amplify_Id" class="w-100 m-0"/>
    @endif
</div>

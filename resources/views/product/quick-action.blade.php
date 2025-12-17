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
            <x-wishlist-button :product="$product" style="width: 42px; height: 36px"
                               class="btn m-0 btn-sm btn-outline-info px-0">
                <x-slot:add-label>
                    <i class="icon-heart text-primary"></i>
                </x-slot>
                <x-slot:remove-label>
                    <i class="icon-heart text-danger"></i>
                </x-slot>
            </x-wishlist-button>
            <button
                data-warehouse="{{ $defaultWarehouse }}"
                onclick="Amplify.addSingleItemToCart(this, '#cart-item-{{$index}}', {{ json_encode($extras) }})"
                data-options="{{ json_encode($cartData) }}"
                class="btn btn-block btn-sm btn-primary m-0">
                {{ __($cartLabel) }}
            </button>
        </div>
    @endif
</div>

@pushonce('internal-style')
    <style>
        .spinner {
            animation: spin 2.5s linear infinite;
        }
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpushonce

<div {!! $htmlAttributes() !!}>
    {!! $slot ?? '' !!}
    <button type="button"
            onclick="Amplify.handleQuantityChange('#cart-item-{{ $cart_item_id ?? '{cart_item_id}' }}', 'decrement');"
            class="item-decrease">
        <i class="icon-minus"></i>
    </button>

    <input
            type="text"
            inputmode="numeric"
            data-product-code="{{ $code ?? '{code}' }}"
            data-warehouse-code="{{ $warehouse_code ?? '{warehouse_code}' }}"
            id="cart-item-{{ $cart_item_id ?? '{cart_item_id}' }}"
            class="form-control item-quantity text-center"
            value="{{ $quantity ?? '{quantity}' }}"
            @if(empty($name))
                name="cart-item-qty[{{ $cart_item_id ?? '{cart_item_id}' }}]"
            @else
                name="{{trim($name)}}"
            @endif
            data-min-order-qty="{{ $min_qty ?? '{min_qty}' }}"
            data-qty-interval="{{ $qty_interval ?? '{qty_interval}' }}"
    />

    <button type="button"
            onclick="Amplify.handleQuantityChange('#cart-item-{{ $cart_item_id ?? '{cart_item_id}' }}', 'increment');"
            class="item-increase">
        <i class="icon-plus"></i>
    </button>
</div>

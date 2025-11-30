<div {!! $htmlAttributes() !!}>
    <button type="button"
            onclick="Amplify.handleQuantityChange('#cart-item-{cart_item_id}', 'decrement');"
            class="item-decrease">
        <i class="icon-minus"></i>
    </button>

    <input
        type="text"
        inputmode="numeric"
        data-product-code="{code}"
        data-warehouse-code="{warehouse_code}"
        id="cart-item-{cart_item_id}"
        class="form-control item-quantity"
        value="{quantity}"
        name="cart-item-qty[{cart_item_id}]"
        data-min-order-qty="{min_qty}"
        data-qty-interval="{qty_interval}"
        oninput="Amplify.handleQuantityChange('#cart-item-{cart_item_id}', 'input')"
    />

    <button type="button"
            onclick="Amplify.handleQuantityChange('#cart-item-{cart_item_id}', 'increment');"
            class="item-increase">
        <i class="icon-plus"></i>
    </button>
</div>

<div {!! $htmlAttributes() !!}>
    <button type="button" onclick="decreaseCartQuantity({cart_item_id});"
            class="item-decrease">
        <i class="icon-minus"></i>
    </button>
    <input
        type="text"
        inputmode="numeric"
        class="form-control item-quantity cart-item-{cart_item_id}"
        value="{quantity}" name="cart-item-qty[{cart_item_id}]"
        data-min-qty="{min_qty}"
        data-qty-interval="{qty_interval}"
        oninput="this.value = this.value.replace(/[^0-9\.]/g, '');"
    />
    <button type="button" onclick="increaseCartQuantity({cart_item_id});"
            class="item-increase">
        <i class="icon-plus"></i>
    </button>
</div>

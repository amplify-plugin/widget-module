<div class="d-flex gap-3 justify-content-center">
    <div class="item-count align-items-center d-flex">
        <button type="button" onclick="decreaseCartQuantity({cart_item_id});"
                class="text-dark item-decrease rounded">
            <i class="icon-minus fw-700"></i>
        </button>

        <input
            type="text"
            class="item-quantity cart-item-{cart_item_id} font-weight-bold mx-2 p-2 text-center border rounded"
            value="{quantity}" name="cart-item-qty[{cart_item_id}]"
            data-min-qty="{min_qty}"
            data-qty-interval="{qty_interval}"
            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
        />

        <button type="button" onclick="increaseCartQuantity({cart_item_id});"
                class="item-increase text-white rounded">
            <i class="icon-plus fw-700"></i>
        </button>
    </div>
</div>

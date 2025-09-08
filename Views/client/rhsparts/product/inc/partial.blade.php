<span id="customer_back_order_code d-none" data-status="Y"></span>

<input id="product_code_{{ $key }}" name="product_code[]" type="hidden" value="{{ $product->Product_Code }}">

<input id="product_id_{{ $key }}" name="product_id[]" type="hidden"
    value="{{ isset($product->Product_Id) ? $product->Product_Id : (isset($product->Product_Id) ? $product->Product_Id : '') }}">

<input type="hidden" id="{{ 'product_warehouse_' . $key }}"
    value="{{ optional(optional(customer(true))->warehouse)->code }}" />

    <input id="product_qty_{{ $key }}"
    class="form-control-sm form-control w-100 text-center product-qty-{{ $product->Product_Code }}"
    type="number" name="qty[]" value="1" min="1"
    oninput="this.value = (parseInt(this.value) > 0) ? parseInt(this.value) : 0"
    @erp max="0" @enderp />

    <span id="product_back_order_{{ $key }} d-none" data-status="Y"></span>
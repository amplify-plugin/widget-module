<span class="d-none" id="customer_back_order_code" data-status="{{ optional(customer())->allow_backorder ? 'Y' : 'N' }}">

</span>

<input id="product_code_{{ $key }}" name="product_code[]" type="hidden" value="{{ $product->Product_Code }}">

<input id="product_id_{{ $key }}" name="product_id[]" type="hidden" value="{{ $product->Product_Id ?? '' }}">

<input id="{{ 'product_warehouse_' . $key }}" type="hidden"
    value="{{ optional(optional(customer(true))->warehouse)->code }}" />

<label class="sr-only" for="product_qty_{{ $key }}">product qty</label>
<input class="form-control-sm form-control w-110 fw-500 product-qty-{{ $product->Product_Code }} text-center"
    id="product_qty_{{ $key }}" name="qty[]" type="number" value="{{ $product?->min_order_qty ?? 1 }}"
    min="{{ $product?->min_order_qty ?? 1 }}" step="{{ $product?->qty_interval }}" required  />

<span class="d-none" id="product_back_order_{{ $key }}"
    data-status="{{ $product->allow_back_order ? 'Y' : 'N' }}"></span>

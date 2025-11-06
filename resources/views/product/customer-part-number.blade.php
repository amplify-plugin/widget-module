<div {!! $htmlAttributes !!}>
    <span class="d-inline-block text-nowrap">{{ $label }}</span>
    <input type="text"
           id="{{ $uuid }}"
           data-uom="{{ $customerPartNumber->customer_product_uom ?? 'EA' }}"
           data-current="{{ $customerPartNumber->customer_product_code }}"
           class="form-control form-control-sm custom-part-number"
           value="{{ $customerPartNumber->customer_product_code }}"
           maxlength="255">
    <button class="btn btn-outline-primary btn-sm mr-0"
            type="submit"
            data-route="{{ route('frontend.customer-part-numbers') }}"
            data-product-id="{{ $productId }}"
            onclick="Amplify.syncCustomPartNumber(this, '{{ $uuid }}');">
        {{ __('Update') }}
    </button>
</div>

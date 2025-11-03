<div {!! $htmlAttributes !!}>
    <span class="d-inline-block text-nowrap">{{ $label }}</span>
    <input type="text"
           id="{{ $uuid }}"
           data-current="{{ $customerPartNumber->customer_product_code }}"
           class="form-control form-control-sm custom-part-number"
           value="{{ $customerPartNumber->customer_product_code }}"
           maxlength="255">
    <button class="btn btn-outline-primary btn-sm mr-0"
            type="submit"
            @if(customer_check())
                @if($hasPermission())
                    onclick="syncCustomerPartNumber($('#{{$uuid}}'), '{{ $productId }}');"
            @else
                data-toast
            data-toast-type="warning"
            data-toast-position="topRight"
            data-toast-icon="icon-circle-cross"
            data-toast-title="Customer Part Number"
            data-toast-message="You don't have permission to use this feature"
            @endif
            @else
                data-toast
            data-toast-type="warning"
            data-toast-position="topRight"
            data-toast-icon="icon-circle-cross"
            data-toast-title="Customer Part Number"
            data-toast-message="You need to be logged in to access this feature."
        @endif
    >
        {{ __('Update') }}
    </button>
</div>

@pushonce('footer-script')
    <script>
        function syncCustomerPartNumber(input, productId) {
            let current = input.attr('data-current').toString();
            let value = input.val().toString();

            if (!value) {
                ShowNotification('error', 'Customer Part Number', '{{ $label }} field is required.');
                return;
            }

            if(current.length > 0 && value.length === 0) {
                ShowNotification('error', 'Customer Part Number', '{{ $label }} field is required.');
                return;
            }

            if (current === value) {
                ShowNotification('warning', 'Customer Part Number', 'Old and new one is same. Skipping');
                return;
            }

            $.ajax({
                url: '{{ route('frontend.customer-part-number.update') }}',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    product_id: productId,
                    customer_product_uom: 'EA',
                    customer_product_code: value,
                },
                success: function(res) {
                    ShowNotification('success', 'Customer Part Number', res.message);
                    input.attr('data-current', value);
                },
                error: function(xhr, status, err) {
                    ShowNotification('error', 'Customer Part Number', JSON.parse(xhr.responseText)?.message ?? '{{ __('SSomething went wrong. Please try again later!') }}');
                },
            });
        }
    </script>
@endpushonce

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
            data-route="{{ route('frontend.customer-part-numbers') }}"
            data-product-id="{{ $productId }}"
            onclick="Amplify.syncCustomPartNumber(this, '{{ $uuid }}');">
        {{ __('Update') }}
    </button>
</div>

@pushonce('footer-script')
{{--    <script>
        function syncCustomerPartNumber(input, productId) {
            if (!Amplify.authenticated()) {
                Amplify.notify('warning', '{{ __('You need to be logged in to access this feature.') }}');
                return;
            }

            let current = input.attr('data-current').toString();
            let value = input.val().toString();

            if (!current && !value) {
                Amplify.notify('warning', '{{ __("{$label} field is required") }}', 'Customer Part Number');
                return;
            }

            if (current === value) {
                Amplify.notify('warning', '{{ __("{$label}  old and new value is same.") }}', 'Customer Part Number');
                return;
            }

            //current exist and new empty
            if (current && !value) {
                Amplify.confirm(
                    '{{ __("Are you sure you want to remove this part number?") }}',
                    'Customer Part Number', 'Remove', {
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        preConfirm : async function (value) {
                            return $.ajax({
                                url: '{{ route('frontend.customer-part-number.destroy') }}'
                            })
                        }
                    }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('frontend.customer-part-number.update') }}',
                            type: 'DELETE',
                            data: {
                                product_id: productId,
                                customer_product_uom: 'EA',
                                customer_product_code: '',
                            },
                            success: function (res) {
                                ShowNotification('success', 'Customer Part Number', res.message);
                                input.attr('data-current', false);
                            },
                            error: function (xhr, status, err) {
                                ShowNotification('error', 'Customer Part Number', JSON.parse(xhr.responseText)?.message ?? '{{ __('SSomething went wrong. Please try again later!') }}');
                            },
                        });
                    }
                });
            }
            else {

                $.ajax({
                    url: '{{ route('frontend.customer-part-number.update') }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        customer_product_uom: 'EA',
                        customer_product_code: value,
                    },
                    success: function (res) {
                        ShowNotification('success', 'Customer Part Number', res.message);
                        input.attr('data-current', value);
                    },
                    error: function (xhr, status, err) {
                        ShowNotification('error', 'Customer Part Number', JSON.parse(xhr.responseText)?.message ?? '{{ __('SSomething went wrong. Please try again later!') }}');
                    },
                });
            }
        }
    </script>--}}
@endpushonce

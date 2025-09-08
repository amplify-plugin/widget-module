@if ($message)
    <p class="font-weight-bold my-2" style="font-size: 1rem">{!! $message !!}</p>
@endif

@php
    $authenticated = customer_check();
    $hasPermission =
        customer(true)?->canAny(['favorites.manage-global-list', 'favorites.manage-personal-list']) ?? false;
@endphp
<div {!! $htmlAttributes !!}>
    @if (!empty($productsData->items))
        <x-product-favorite-list />
        @if ($productView === 'list')
            @include('widget::client.nudraulix.product.inc.product-list')
        @else
            @include('widget::client.nudraulix.product.inc.product-grid')
        @endif
        <x-shop-pagination />
    @else
        <x-shop-empty-result />
    @endif
</div>
<script>
    function productQuantity(id, type, interval, min) {
        let item = document.getElementById(`product_qty_${id}`);
        let val = parseInt(item.value);
        switch (type) {
            case 'plus':
                item.value = val + interval;
                break;
            case 'minus':
                if (val > min) {
                    item.value = val - interval;
                }
                break;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Get all quantity input fields
        const quantityInputs = document.querySelectorAll('input[name="qty[]"]');

        quantityInputs.forEach(input => {
            // Prevent invalid values on manual input
            input.addEventListener('input', function(event) {
                validateAndCorrectValue(input);
            });

            // Prevent users from typing manually in the input
            // input.addEventListener('keydown', function(event) {
            //     // Allow navigation keys: backspace, delete, arrow keys, etc.
            //     const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Delete', 'Tab'];
            //     if (!allowedKeys.includes(event.key)) {
            //         event.preventDefault(); // Prevent all other keys
            //     }
            // });

            // Validate and correct the value
            function validateAndCorrectValue(input) {
                const min = parseFloat(input.min);
                const step = parseFloat(input.step);
                const value = parseFloat(input.value);

                if (isNaN(value) || value < min || (value - min) % step !== 0) {
                    input.value = min; // Reset to minimum value if invalid
                }
            }
        });
    });
</script>

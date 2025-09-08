@if ($message)
    <p class="font-weight-bold my-2" style="font-size: 1rem">{!! $message !!}</p>
@endif
@php
    $authenticated = customer_check();
    $hasPermission = customer(true)?->canAny(['favorite.allow-favorite','favorite.allow-add-item']) ?? false;
@endphp
<div {!! $htmlAttributes !!}>
    @if (!empty($productsData->items))
        <x-product-favorite-list/>
        @if ($productView === 'list')
            @include('widget::client.rhsparts.product.inc.product-list')
        @else
            @include('widget::client.rhsparts.product.inc.product-grid')
        @endif
        <x-shop-pagination/>
    @else
        <x-shop-empty-result/>
    @endif
</div>
<script>

    function productQuentity(id, type) {
        let item = document.getElementById(`product_qty_${id}`);
        let val = parseInt(item.value);
        switch (type) {
            case 'plus':
                item.value = val + 1;
                break;
            case 'minus':
                if (val > 1) {
                    item.value = val - 1;
                }
                break;
        }

    }
</script>

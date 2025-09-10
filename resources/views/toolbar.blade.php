<div {!! $htmlAttributes !!}>
    <div class="inner w-100">
        <div
            class="tools d-flex align-items-center @if (customer_check() && !customer(true)->can('shop.browse')) justify-content-end @endif">
            {{ $slot }}
        </div>
    </div>
</div>

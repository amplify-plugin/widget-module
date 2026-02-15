<div {!! $htmlAttributes !!}>
    <div class="inner">
        <div @class(["tools align-items-center", 'justify-content-end' => customer_check() && !customer(true)->can('shop.browse')])>
            {{ $slot }}
        </div>
    </div>
</div>

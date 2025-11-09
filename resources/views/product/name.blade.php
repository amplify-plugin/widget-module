<{{ $element }} {!! $htmlAttributes !!}>
    <a data-toggle="tooltip" data-placement="top"
       title="{!! $productName ?? '' !!}"
       href="{{ frontendSingleProductURL($product, $currentSeoPath) }}">
        {!! $productName ?? '' !!}
    </a>
</{{ $element }}>

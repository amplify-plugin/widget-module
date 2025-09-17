<div {!! $htmlAttributes !!}>
    @if(!empty($slot))
        {!! $slot !!} / <span>{{ $uomLabel() }}</span>
    @endif
</div>

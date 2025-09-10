@if(!empty($discount))
    <div class="d-inline-flex">
        @if($displayListPrice)
            <small class="text-muted del" style="text-decoration: line-through">
                {{ price_format($listPrice) }}
            </small>
        @endif
        <div {!! $htmlAttributes !!}>
            {{ $discount ?? null }}
        </div>
    </div>
@endif

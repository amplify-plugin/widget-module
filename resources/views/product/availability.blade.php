<span {!! $htmlAttributes !!}>
{{--    @if($value > 0)--}}
        @switch($availability)
            @case('I')
                @if($value > 500)
                    {{ '500+' }}
                @elseif($value > 250)
                    {{ '250+' }}
                @elseif($value > 100)
                    {{ '100+' }}
                @elseif($value > 50)
                    {{ '50+' }}
                @elseif($value > 25)
                    {{ '25+' }}
                @else
                    {{ number_format($value) }}
                @endif
                @break

            @case('R')
                {{ $value <= $restrictLimit ? number_format($value) : __("{$restrictLimit}+") }}
                @break

            @case('S')
                {{ $value > 0 ? number_format($value) : __('Special Order') }}
                @break

            @default {{-- A --}}
            {{ number_format($value ?? 0) }}
        @endswitch
{{--    @else--}}
{{--        {{ product_out_stock_message() }}--}}
{{--    @endif--}}
    
</span>

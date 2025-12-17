<{{$element}} {!! $htmlAttributes !!}>
@if(customer_check() || config('amplify.basic.enable_guest_pricing'))
    @if($product->pricing && $value != null)
        {!! $slot ?? '' !!}
        <span class="amount">{{ currency_format($value, null, true) }}</span>
        <span class="separator">/</span>
        <span class="uom">{{ unit_of_measurement($uom, 'Each') }}</span>
        @if(!empty($stdPrice) && $stdPrice > $value)
            <span style="text-decoration:line-through;" class="standard">
                {{ currency_format($stdPrice, null, true) }}/{{ unit_of_measurement($uom, 'Each') }}
            </span>
        @endif
    @else
        {{ product_not_avail_message() }}
    @endif
@else
    {{ __('Login for Price & Availability') }}
@endif
</{{$element}}>

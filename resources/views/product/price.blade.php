<{{$element}} {!! $htmlAttributes !!}>
@if(customer_check() || config('amplify.basic.enable_guest_pricing'))

    @if((!empty($product) && $product->pricing) || $value != null)
        {!! $slot ?? '' !!}
        <span class="amount">{{ currency_format($value, null, true) }}</span>
        <span class="separator">/</span>
        <span class="uom">{{ unit_of_measurement($uom) }}</span>
        @if(!empty($stdPrice) && $stdPrice > $value)
            <span style="text-decoration:line-through;" class="standard">
                {{ currency_format($stdPrice, null, true) }}/{{ unit_of_measurement($uom) }}
            </span>
        @endif
    @else
        <span>{{ product_not_avail_message() }}</span>
    @endif
@else
    <span>{{ __('Login for Price & Availability') }}</span>
@endif
</{{$element}}>

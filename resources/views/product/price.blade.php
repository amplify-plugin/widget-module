<{{$element}} {!! $htmlAttributes !!}>
@if(customer_check() || config('amplify.basic.enable_guest_pricing'))
    @if($product->pricing && $value != null)
        <span class="amount">{{ currency_format($value, null, true) }}</span><span class="separator">/</span><span
            class="uom">{{ unit_of_measurement($uom, 'Each') }}</span>
    @else
        {{ product_not_avail_message() }}
    @endif
@else
    {{ __('Login for Price & Availability') }}
@endif
</{{$element}}>

@php
    push_js([
        'js/jquery-migrate.js',
        'https://www.cenpos.com/Plugins/porthole.min.js',
        'https://www.cenpos.com/Plugins/jquery.simplewebpay.js',
    ], 'custom-script');
@endphp

<div {!! $htmlAttributes !!}>
    <div id="app">
        @if ($cartItemCount > 0)
        <div class="row">
            <div class="col-md-12">
                <customer-checkout
                    is_auth_user="{{ customer_check() }}"
                    is_redirect_order_complete="{{ config('amplify.erp.default') != 'default' }}"
                    customer_info="{{ json_encode($customer->toArray()) }}"
                    steps_info="{{ json_encode($steps) }}"
                    cart_info="{{ json_encode($cart->toArray()) }}"
                    addresses_info="{{ json_encode($addresses) }}"
                    states_info="{{ json_encode($states) }}"
                    countries_info="{{ json_encode($countries) }}"
                    ship_options_info="{{ json_encode($shipOptions) }}"
                    cenpos_info="{{ json_encode($cenposInfo) }}"
                    client_code="{{$clientCode}}"
                    client_name="{{config('app.name')}}"
                />
            </div>
        </div>
        @else
            @include('widget::checkout.inc.empty-checkout')
        @endif
    </div>
</div>

@include('cms::inc.full-page-loader')

@pushonce('plugin-script')
    <script src="{{ asset('packages/select2/dist/js/select2.min.js') }}"></script>
@endpushonce
@pushonce('plugin-script')
    <script src="{{ asset('vendor/jp-card/credit-card.min.js') }}"></script>
@endpushonce
@pushonce('plugin-script')
    <script src="{{ asset("assets/js/app.js") }}"></script>
@endpushonce

<script>
    const ROUTE_ORDER_SUBMIT = "{{ route('frontend.order.submit-order') }}";
</script>

<div {!! $htmlAttributes !!}>

    @if ($cartItemCount > 0)
        <div id="app">
            <div class="row">
                <div class="col-md-12">
                    <cal-tool-checkout is_auth_user="{{ customer_check() }}"
                        is_redirect_order_complete="{{ config('amplify.erp.default') != 'default' }}"
                        customer_info="{{ json_encode($customer->toArray()) }}"
                        contact_info="{{ json_encode(customer(true)) }}" steps_info="{{ json_encode($steps) }}"
                        cart_info="{{ json_encode($cart->load('cartItems')) }}"
                        addresses_info="{{ json_encode($addresses) }}" states_info="{{ json_encode($states) }}"
                        countries_info="{{ json_encode($countries) }}"
                        ship_options_info="{{ json_encode($shipOptions) }}"
                        cenpos_payment_url="{{ $cenposPaymentUrl }}" client_code="{{ $clientCode }}"
                        cenpos_css_url="{{ asset('css/cenpos.css') }}"
                        :is_credit_card_required={{ json_encode($isCreditCardRequired) }}
                        cenpos_merchant_id = "{{ $cenposMerchantId }}"
                        cenpos_ach_payment_url="{{ $cenposAchPaymentUrl }}"
                        is_ach_required = "{{ $isAchOnly }}" />
                </div>
                {{-- <div class="col-md-3">
                    @include('widget::checkout.summary')
                </div> --}}
            </div>
        </div>
    @else
        @include('widget::checkout.inc.empty-checkout')
    @endif
</div>

@include('cms::inc.full-page-loader')


@pushonce('plugin-script')
    <script src="{{ asset('packages/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/jp-card/credit-card.min.js') }}"></script>
    <script src="{{ asset("vendor/backend/js/backend.js") }}"></script>
@endpushonce

@pushonce('custom-script')
    <script src="{{ asset('assets/js/jquery-migrate.js') }}"></script>
    <script src="https://www.cenpos.com/Plugins/porthole.min.js"></script>
    <script src="https://www.cenpos.com/Plugins/jquery.simplewebpay.js"></script>
    <script src="{{ asset('assets/js/cenpos-payment.js') }}"></script>
    <script src="{{ asset('packages/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('packages/select2/dist/js/select2.min.js') }}"></script>
@endpushonce

<script>
    const ROUTE_ORDER_SUBMIT = "{{ route('frontend.order.submit-order') }}";
</script>

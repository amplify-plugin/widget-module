@pushonce('custom-script')
	<script src="{{ asset('js/jquery-migrate.js') }}"></script>
@endpushonce

<div {!! $htmlAttributes !!}>
    <div id="app">
        @if ($cartItemCount > 0)
        <div class="row">
            <div class="col-md-9">
                <order-checkout
                    is_auth_user="{{ customer_check() }}"
                    is_redirect_order_complete="true"
                    customer_info="{{ json_encode($customer->toArray()) }}"
                    contact_info="{{ json_encode(customer(true)) }}"
                    steps_info="{{ json_encode($steps) }}"
                    cart_info="{{ json_encode($cart->load('cartItems')) }}"
                    addresses_info="{{ json_encode($addresses) }}"
                    states_info="{{ json_encode($states) }}"
                    countries_info="{{ json_encode($countries) }}"
                    ship_options_info="{{ json_encode($shipOptions) }}"
                    choose_ship_permission="{{ $hasChooseShipPermission }}"
                />
            </div>
            <div class="col-md-3">
                {{-- @include('widget::checkout.summary') --}}
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
    <script src="{{ asset('vendor/jp-card/credit-card.min.js') }}"></script>
@endpushonce
@pushonce('footer-script')
    <script src="{{ asset("vendor/backend/js/backend.js") }}"></script>
@endpushonce

<script>
    const ROUTE_ORDER_SUBMIT = "{{ route('frontend.order.submit-order') }}";
</script>

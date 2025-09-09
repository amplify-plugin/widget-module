@pushonce('custom-script')
	<script src="{{ asset('js/jquery-migrate.js') }}"></script>
@endpushonce

<div {!! $htmlAttributes !!}>
    <div id="app">
        @if ($itemCount > 0)
        <div class="row">
            <div class="col-md-9">
                <customer-order-checkout
                    customer_info="{{ json_encode($customer->toArray()) }}"
                    contact_info="{{ json_encode(customer(true)) }}"
                    steps_info="{{ json_encode($steps) }}"
                    order_info="{{ json_encode($order) }}"
                    addresses_info="{{ json_encode($addresses) }}"
                    states_info="{{ json_encode($states) }}"
                    countries_info="{{ json_encode($countries) }}"
                    ship_options_info="{{ json_encode($shipOptions) }}"
                    choose_ship_permission="{{ $hasChooseShipPermission }}"
                />
            </div>
            <div class="col-md-3">
                @include('widget::order-checkout.summary')
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
    <script src="{{ asset('assets/js/app.js') }}"></script>
@endpushonce

<script>
    const ROUTE_ORDER_SUBMIT = "{{ route('frontend.order.submit-order') }}";
</script>

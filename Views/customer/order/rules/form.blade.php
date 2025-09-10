@php
    $params = request()->route()->parameters;
    $pathname = explode('/', request()->path())[0];
@endphp
@pushonce("footer-script")
    <script src="{{ asset("vendor/backend/js/backend.js") }}"></script>
@endpushonce
<div {!! $htmlAttributes !!}>
    <div id="app">
        <div class="padding-top-2x mt-2 hidden-lg-up"></div>
        <create-customer-order-rule
            back_url="{{ url('order-rules') }}"
            :from_frontend="true"
            :frontend_customer_id="{{ customer()->id }}"
            class_name="col-md-12"
            axios_url="{{ route('frontend.save-order-rule') }}"
            method="{{ $method }}"
            customer_list="{{ json_encode([customer()]) }}"
            customer_order_rule_data="{{ json_encode($customer_rule) }}"
            save_action="{{ json_encode($saveAction ?? ['active' => ['value' => 'save_and_back', 'label' => 'Save and Back'], 'options' => [] ]) }}">
        </create-customer-order-rule>
    </div>
</div>

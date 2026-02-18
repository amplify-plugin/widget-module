<div {!! $htmlAttributes !!}>
    @if ($customer)
        {{-- @include('components.item-quick-list ') --}}

        <div class="modal fade" id="draft-modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Draft Info') }}</h4>
                        <button class="close" type="button" role="button" data-dismiss="modal" aria-label="Close"
                                onclick="setPositionOffCanvas()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="draft_name">{{ __('Draft Name') }} <span class="text-danger ">*</span></label>
                            <input name="draft_name" class="form-control" type="text" id="draft_name" required>
                            <small class="text-danger" id="draft_name_error"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary btn-sm" id="list-modal-close" type="button"
                                data-dismiss="modal" onclick="setPositionOffCanvas()">{{ __('Close') }}
                        </button>
                        <button class="btn btn-primary btn-sm" id="draft_button" type="button"
                                onclick="submitOrder('draft')">{{ __('Save changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clear Confirm -->
        <div class="modal fade" id="clear-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Cart Clear Confirmation') }}</h5>
                        <button class="close" type="button" role="button" data-dismiss="modal" aria-label="Close"
                                onclick="setPositionOffCanvas()"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <span class="text-danger d-block text-center mb-3">
                        <i class="fa fa-exclamation-triangle fa-4x"></i>
                    </span>
                        <p class="mt-3 text-center font-weight-bold ">
                            Are you sure to cancel you order and delete your cart items?
                        </p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button class="btn btn-outline-secondary btn-sm" id="list-modal-close" type="button"
                                data-dismiss="modal" onclick="setPositionOffCanvas()">{{ __('Close') }}
                        </button>
                        <button class="btn btn-danger btn-sm" id="clear_button" type="button" data-dismiss="modal"
                                onclick="deleteCartsOrder()">Delete

                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Info-->
        <div class="modal fade" id="different-address" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Address Info') }}</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                                onclick="setPositionOffCanvas()"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="different_address_name">{{ __('Ship To Name') }} <span
                                    class="text-danger ">*</span></label>
                            <input name="different_address_name" class="form-control" type="text"
                                   id="different_address_name" required>
                            <span class="text-danger d-block" id="different_address_name_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="different_address_country_code">{{ __('Ship To Country Code') }} <span
                                    class="text-danger ">*</span></label>
                            <input name="different_address_country_code" class="form-control" type="text"
                                   id="different_address_country_code" required>
                            <span class="text-danger d-block" id="different_address_country_code_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="different_address_1">{{ __('Ship To Address 1') }} <span
                                    class="text-danger ">*</span></label>
                            <input name="different_address_1" class="form-control" type="text"
                                   id="different_address_1" required>
                            <span class="text-danger d-block" id="different_address_1_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="different_address_2">{{ __('Ship To Address 2') }}</label>
                            <input name="different_address_2" class="form-control" type="text"
                                   id="different_address_2">
                        </div>

                        <div class="form-group">
                            <label for="different_address_3">{{ __('Ship To Address 3') }}</label>
                            <input name="different_address_3" class="form-control" type="text"
                                   id="different_address_3">
                        </div>

                        <div class="form-group">
                            <label for="different_address_city">{{ __('Ship To City') }} <span
                                    class="text-danger ">*</span></label>
                            <input name="different_address_city" class="form-control" type="text"
                                   id="different_address_city" required>
                            <span class="text-danger d-block" id="different_address_city_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="different_address_state">{{ __('Ship To State') }} <span
                                    class="text-danger ">*</span></label>
                            <input name="different_address_state" class="form-control" type="text"
                                   id="different_address_state" required>
                            <span class="text-danger d-block" id="different_address_state_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="different_address_zip_code">{{ __('Ship To Zip Code') }} <span
                                    class="text-danger ">*</span></label>
                            <input name="different_address_zip_code" class="form-control" type="text"
                                   id="different_address_zip_code" required>
                            <span class="text-danger d-block" id="different_address_zip_code_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            id="different-address-modal-close"
                            class="btn btn-outline-secondary btn-sm"
                            data-dismiss="modal"
                            onclick="setPositionOffCanvas()"
                        >{{ __('Close') }}</button>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            onclick="addDifferentAddress()"
                        >{{ __('Save changes') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Custome Tab  --}}
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <form role="form">
                    <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">

                        <li role="presentation" class="nav-item">
                            <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1"
                               role="tab" title="Step 1">
                                <h4>Customer</h4>
                            </a>
                        </li>

                        <li role="presentation" class="nav-item">
                            <a href="#step2" class="nav-link" data-toggle="tab" aria-controls="step2" role="tab"
                               title="Step 2">
                                <h4>Product</h4>
                            </a>
                        </li>

                        {{-- <li role="presentation" class="nav-item">
                            <a href="#complete" class="nav-link" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">Payment</a>
                        </li> --}}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" id="step1">
                            <div class="row">
                                <!-- Customer -->
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Customer') }}</label>
                                        <div class="col-sm-12 col-md-8">
                                            <input @if ($customer) readonly @endif class="form-control"
                                                   placeholder="Enter Customer Name"
                                                   value="{{ $customer->customer->customer_name ?? '' }}" type="text">
                                        </div>
                                    </div>
                                </div>

                                <!-- User -->
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-4 col-form-label"
                                               for="text-input">{{ __('User') }} </label>
                                        <div class="col-sm-12 col-md-8">
                                            <input @if ($customer) readonly @endif class="form-control"
                                                   placeholder="Enter User" value="{{ $customer->name ?? '' }}"
                                                   type="text">
                                        </div>
                                    </div>
                                </div>

                                <!-- Web Order Number -->
                                <div class="col-lg-6 ">
                                    <div class="form-group row ">
                                        <label class="col-sm-12 col-md-4 col-form-label" for="text-input">
                                            {{ __('Order Reference') }}
                                            @if ($customer_details->PoRequired == 'Y')
                                                <span class="text-danger text-bold">*</span>
                                            @endif
                                        </label>

                                        <div class="col-sm-12 col-md-8">
                                            <input type="text" name="customer_order_ref"
                                                   placeholder="Customer order reference"
                                                   @if ($customer_details->PoRequired == 'Y') required
                                                   @endif id="customer_order_ref"
                                                   class="form-control">
                                            <span class="text-danger d-block" id="customer_order_ref_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shipping Option -->
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-md-4 col-form-label" for="text-input">
                                            {{ __('Ship Method') }}
                                            <span class="text-danger text-bold">*</span>
                                        </label>
                                        <div class="col-sm-12 col-md-8">
                                            <select class="form-control custom-select" id="shipping_method"
                                                    name="shipping_method">
                                                <option value="">Select an Option</option>
                                                @foreach (erp()->getShippingOption() ?? [] as $option)
                                                    <option {{ $customer_carrier_code == $option->CarrierCode ? 'selected' : '' }} value="{{ $option->CarrierCode }}">
                                                        {{ $option->CarrierDescription }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger d-block" id="shipping_method_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shipping Address -->
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-lg-2" for="text-input">
                                            {{ __('Ship Address') }}
                                            <span class="text-danger text-bold">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12 col-lg-10">
                                            <div class="d-flex justify-content-between">
                                                <select class="form-control custom-select rounded-left rounded-right-0"
                                                        id="shipping_addr" style="padding-left: 0.75rem;">
                                                    <option value="">Select an Address</option>
                                                    @foreach (erp()->getCustomerShippingLocationList() ?? [] as $location)
                                                        <option value="{{ $location['ShipToNumber'] }}">
                                                            {{ $location['ShipToNumber'] }}
                                                            - {{ $location['ShipToAddress1'] ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" value="" name="address_name"
                                                       id="address_name"/>
                                                <input type="hidden" value="" name="address_country_code"
                                                       id="address_country_code"/>
                                                <input type="hidden" value="" name="address_1" id="address_1"/>
                                                <input type="hidden" value="" name="address_2" id="address_2"/>
                                                <input type="hidden" value="" name="address_3" id="address_3"/>
                                                <input type="hidden" value="" name="address_city"
                                                       id="address_city"/>
                                                <input type="hidden" value="" name="address_state"
                                                       id="address_state"/>
                                                <input type="hidden" value="" name="address_zip_code"
                                                       id="address_zip_code"/>

                                                <button class="btn btn-info m-0 rounded-left-0 rounded-right"
                                                        type="button" data-toggle="modal"
                                                        data-target="#different-address"
                                                        onclick="setPositionOffCanvas(false)">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                            <span class="text-danger d-block" id="shipping_addr_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Note -->
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="text-input">{{ __('Order Notes:') }}</label>
                                        <textarea name="notes" class="form-control" id="additional-notes" rows="5"
                                                  placeholder="Write orders note (optional)"></textarea>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary next-step mr-0 px-md-4" id="n-btn1">
                                        Next <i class="pe-7s-next ml-2 nav-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" role="tabpanel" id="step2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="border table-responsive shopping-cart tableFixHead pb-4 pb-md-0"
                                         id="order-summary">
                                        @include('widget::partials.order-summary')
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-around pr-0">
                                    <div class="btn-group flex-fill flex-wrap gap-3 pr-0">
                                        <button class="btn btn-secondary prev-step rounded mb-2 mr-5" type="button"
                                                id="p-btn1">
                                            <i class="pe-7s-prev mr-2 nav-icon"></i> Previous
                                        </button>

                                        @if ($cart?->cartItems->isNotEmpty())
                                            <button class="btn btn-primary rounded mb-2" type="button"
                                                    data-toggle="modal"
                                                    data-target="#draft-modal" onclick="setPositionOffCanvas(false)">
                                                Save as Draft
                                            </button>
                                            <!-- <button class="btn btn-primary rounded mb-2" type="button"
                                                    onclick="submitOrder('quote');">
                                                Request Quote
                                            </button> -->
                                            <button class="btn btn-primary rounded mb-2" type="button"
                                                    onclick="submitOrder();">
                                                Submit Order
                                            </button>
                                            @if ($customer && false)
                                                <button class="btn btn-primary rounded mb-2 " type="button"
                                                        data-toggle="modal" data-target="#list-addons"
                                                        onclick="setPositionOffCanvas(false);">
                                                    Save as list
                                                </button>
                                            @endif

                                            <button class="btn btn-danger mr-0 rounded mb-2 ml-5" type="button"
                                                    data-toggle="modal" data-target="#clear-modal"
                                                    onclick="setPositionOffCanvas(false);">
                                                <i class="pe-7s-close mr-2 nav-icon"></i> Cancel
                                            </button>
                                        @endif
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" role="tabpanel" id="complete">
                            <div id="payment-content">
                                <div class="container mb-3">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-xs-12 col-md-8 col-lg-5">
                                            <p id="cenpos-error-message" style="display: none;">
                                                N.B: Double clicking on the Credit card also initiate the payment.
                                            </p>
                                            <div id="CenposPlugin" style="min-height: 428px;">
                                                <div class="w-100 h-100 justify-content-center align-items-center"
                                                     style="padding-top: 214px;">
                                                    <img src="{{ asset('assets/img/loading.gif') }}"
                                                         style="margin-left: 47%;"/>
                                                </div>
                                            </div>
                                            <form method="POST" action="">
                                                <input type="hidden" name="cardname" value="">
                                                <input type="hidden" name="cc_token" value="">
                                                <input type="hidden" name="last_four" value="">
                                                <input type="hidden" name="cc_cardtype" value="">
                                                <input type="hidden" name="cardexpmonth" value="">
                                                <input type="hidden" name="cardexpyear" value="">
                                                <input type="hidden" name="cardtype" value="">
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-primary" type="button" id="paySubmit">Submit
                                                    </button>
                                                    <a class="btn btn-danger" id="cancelBtn"
                                                       href="{{ url('shop') }}">Cancel</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div id="processPayment" class="modal fade" data-backdrop="static"
                                     data-keyboard="false">
                                    <div class="modal-dialog modal-confirm">
                                        <div class="modal-content">
                                            <form
                                                action="{{ route('customer.cenpos-order-credit-card-pay.submit', $customerOrder->id ?? '#') }}"
                                                method="post">
                                                <input type="hidden" class="card-token" name="card_token"/>
                                                @csrf

                                                <div class="modal-header">
                                                    <h4 class="modal-title">Are you sure?</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Do you really want to use this card? This process cannot be
                                                        undone.</p>
                                                    <table class="table table-bordered mt-2">
                                                        <tbody>
                                                        <tr>
                                                            <th scope="row">Name On Card</th>
                                                            <td class="card-name"></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Card Number</th>
                                                            <td class="card-number"></td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Card Expire</th>
                                                            <td class="card-expire"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <p>Billing info:</p>
                                                                <table class="w-100">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Product Code</th>
                                                                        <th>Amount</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach ($customerOrder->orderLines ?? [] as $orderLine)
                                                                        <tr>
                                                                            <td>{{ $orderLine->product_code }}</td>
                                                                            <td>${{ $orderLine->customer_price }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                        <td>Sub Total:</td>
                                                                        <td>=${{ $customerOrder->total_amount ?? '' }}
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-info">Process with this card
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="d-none btn btn-primary" data-toggle="modal"
                                        data-target="#processPayment">
                                    Launch demo modal
                                </button>
                                <div id="modelBackdrop"></div>

                                <div id="cenpos-failed-body" class="d-none">
                                    <h3 class="h3 text-center text-danger mb-4">Sorry something has gone wrong!</h3>
                                    <p>
                                        We are sorry, but we are unable to connect with the payment processing system
                                        for
                                        your
                                        credit card.
                                        Please contact Mountain West with the following information.
                                    </p>
                                    <table class="table table-bordered mt-4">
                                        <thead>
                                        <tr>
                                            <th width="30%">
                                                Customer
                                            </th>
                                            <td colspan="2">
                                                {{ $customer_details->CustomerName }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="30%">
                                                Customer Code
                                            </th>
                                            <td colspan="2">
                                                {{ $customer_details->CustomerNumber }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="30%">
                                                Payment Type
                                            </th>
                                            <td colspan="2">
                                                {{ $customer_details->CreditCardOnly == 'Y' ? 'Credit Card' : 'Standard' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Web Order Number</th>
                                            <th class="text-right">{{ $customerOrder->web_order_number ?? '' }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-center">Billing Information</th>
                                        </tr>
                                        <tr class="thead-light text-center">
                                            <th colspan="2">Product Code</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($customerOrder->orderLines ?? [] as $orderLine)
                                            <tr>
                                                <th colspan="2">{{ $orderLine->product_code ?? '' }}</th>
                                                <td class="text-right">${{ $orderLine->customer_price ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-primary">
                                            <th colspan="2" style="font-size: 1.5rem">Sub Total</th>
                                            <td class="text-right" style="font-size: 1.5rem">
                                                ${{ $customerOrder->total_amount ?? '' }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mb-5">
                                        <a href="{{ url()->to('/shop') }}" class="btn btn-outline-primary">Return to
                                            Shop</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('cms::inc.full-page-loader')
</div>

<script>
    const CUSTOMER = {!! $customer_details !!};
    const ROUTE_ORDER_SUMMERY = "{{ route('frontend.order.summary') }}";
    const ROUTE_ORDER_SUBMIT = "{{ route('frontend.order.submit-order') }}";
    const ROUTE_ORDER_LIST_SAVE = "{{ route('frontend.order.order-list.save') }}";
    const ROUTE_ORDER_CHECK_NAME_AVAILABILITY = "{{ route('frontend.order.order-list.check-name-availability') }}";

    let temporaryAddress = {};
</script>

@pushonce('footer-script')
    <script src="{{ asset('assets/js/widget/customer-order.js') }}"></script>
@endpushonce
@php
    push_css(
        '
        .nav-icon {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .form-group label {
            font-size: 1rem !important;
        }
        .nav-link.active h4 {
            color: var(--primary) !important;
        }

        .nav-link h4 {
            color: var(--gray) !important;
        }

        select.full-width {
            width: 100% !important;
        }

        select.form-control {
            width: 100% !important;
        }

        .shopping-cart .product-item .product-title>a {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tableFixHead {
            max-height: 642px !important;
            overflow-y: auto !important;
            width: auto !important;
        }

        .tableFixHead table {
            border-collapse: collapse;
            width: 100%;
        }

        .tableFixHead table th {
            position: sticky;
            top: -1px;
            z-index: 1;
            background: #eee !important;
        }

        .tableFixHead table th,
        .tableFixHead table td {
            padding: 8px 16px !important;
        }',
        'custom-style',
    )
@endphp

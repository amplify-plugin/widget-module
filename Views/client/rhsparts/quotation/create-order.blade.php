@php
    function getOptions($quotation) {
        $options = '';
        foreach($quotation->shippingList as $shipping) {
            $value = $shipping["ShipVia"];
            $label = $shipping["ShipViaDescription"];
            $options.= "<option value='$value'>$label</option> \n";
        }
        return $options;
    };

    push_html(function () use($quotation) {
            $options = getOptions($quotation);
            return <<<HTML
                <div class="modal fade" id="rhs-order-confirm" tabindex="-1" aria-labelledby="orderConfirm" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content shadow-sm">
                            <div class="modal-body">
                                <h3 class="text-center">{{ __('Create Order From Quote') }} - #{{ __($quotation->QuoteNumber) }}</h3>
                                <div class="d-flex gap-2">
                                    <p><strong>Quoted To: </strong>{{ __("$quotation->QuotedTo") }}</p>
                                    <p><strong>Mail To: </strong>{{ __("$quotation->QuotedByEmail") }}</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="input-group">
                                        <label class="sr-only" for="po-number">PO Number</label>
                                        <input type="text" class="form-control mb-2 mr-sm-2" id="po-number" placeholder="PO Number">
                                    </div>
                                    <div class="input-group">
                                        <label class="sr-only" for="shipping-method">Name</label>
                                        <select class="form-control mr-sm-2" id="shipping-method">
                                            <option selected>Shipping Method...</option>
                                            {!! "$options" !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="special-instruction">Example textarea</label>
                                    <textarea class="form-control" id="special-instruction" rows="2" placeholder="Special Instruction" ></textarea>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-around pt-0 border-top-0">
                                <button type="button" data-dismiss="modal" class="btn btn-dark">{{ __('No') }}</button>
                                <button
                                    id="create-order-from-quote-btn"
                                    type="button"
                                    data-dismiss="modal"
                                    onclick="createOrder({{ config('amplify.order.send_email_to_create_order_from_quote') }})"
                                    class="btn btn-success"
                                >
                                    {{ __('Yes') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            HTML;
        });
    push_js(
        "
            $(document).on('click', '#create-order-from-quote', function () {
                $('#rhs-order-confirm').modal('show');
            });
        ",
        'footer-script',
    );
@endphp

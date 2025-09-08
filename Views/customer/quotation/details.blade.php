<script>
    function processedQuoteData(min_qty = null) {
        return {
            source_type: "Quote",
            source: "{{ $quotation->QuoteNumber }}",
            expiry_date: "{{ $quotation->ExpirationDate }}",
            additional_info: {
                minimum_quantity: min_qty
            }
        };
    }
</script>
<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            @if (customer(true)->can('quote.view'))
                <div class="col-md-12 text-right mb-3">
                    <a href="{{ route('frontend.index') }}/quotations">{{ __('Back to Quotations') }}</a>
                </div>
            @endif
            @if ($quotation)
                <div class="row mb-4">
                    <div class="col-4">
                        <span> <b>{{ __('Created By :') }}</b> {{ $quotation->CustomerName ?? 'N/A' }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Created Date :') }}</b>
                            {{ carbon_date($quotation->EntryDate) }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Effective Date :') }}</b>
                            {{ carbon_date($quotation->EffectiveDate) }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Expiration Date :') }}</b>
                            {{ carbon_date($quotation->ExpirationDate) }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Approved By :') }}</b> {{ '' }}</span></div>
                    <div class="col-4"><span> <b>{{ __('Order Reference :') }}</b> {{ '' }}</span></div>
                    <div class="col-4"><span> <b>{{ __('PO Number :') }} </b>
                            {{ $quotation->CustomerPurchaseOrdernumber }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Shipping Method :') }}
                            </b>{{ $quotation->CarrierCode }}</span>
                    </div>
                </div>

                <x-site.data-table-wrapper id="order-item-table">
                    @if (customer(true)->canAny(['order.create', 'order.add-to-cart']))
                        <x-slot name="rightside">
                            <div class="d-flex justify-content-center justify-content-md-end">
                                @if (customer(true)->can('order.create') && $showCreateOrderButton)
                                    <button type="button" class="btn btn-sm btn-primary btn-right m-2 create-order"
                                        @if ($quotation->ExpirationDate < date('Y-m-d')) disabled @endif>
                                        {{ __('Create Order') }}
                                    </button>
                                @endif

                                @if (customer(true)->can('order.add-to-cart') && $showCartButton)
                                    <button type="button" class="btn btn-sm btn-primary btn-right my-2 ml-2 mr-0"
                                        onclick="addToCart(processedQuoteData())"
                                        @if ($quotation->ExpirationDate < date('Y-m-d')) disabled @endif>
                                        {{ __('add all items to the cart') }}
                                    </button>
                                @endif
                            </div>
                        </x-slot>
                    @endif

                    <table class="table table-bordered table-striped table-hover" id="product-item-list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Total') }}</th>
                                @if (customer(true)->can('order.add-to-cart') && $showCartButton)
                                    <th>{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 0 @endphp
                            @foreach ($quotation['QuoteDetail'] ?? [] as $item)
                                @continue($item['ActualSellPrice'] == '')
                                <tr>
                                    <input type="hidden" id="{{ 'product_code_' . $counter }}"
                                        value="{{ $item->ItemNumber }}" />
                                    <input type="hidden" id="{{ 'product_qty_' . $counter }}"
                                        value="{{ $item->QuantityOrdered }}" />
                                    <input type="hidden" id="{{ 'minimum_order_qty_' . $counter }}"
                                        value="{{ $item->QuantityOrdered }}" />

                                    <th scope="row">{{ $counter + 1 }}</th>
                                    <td>
                                        <a class="product-thumb"
                                            href="{{ frontendSingleProductURL($item->product ?? '#') }}">
                                            <img title="View Product"
                                                src="{{ assets_image($item?->product?->productImage?->main ?? '') }}"
                                                alt="{{ $item?->product?->product_name }}">
                                        </a>
                                    </td>
                                    <td>{{ str()->limit($item?->product?->product_name, 40, '...') }}</td>
                                    <td>{{ price_format($item->ActualSellPrice) }}</td>
                                    <td><span class="text-medium">{{ $item->QuantityOrdered }}</span></td>
                                    <td><span class="text-medium">{{ $item->TotalLineAmount }}</span></td>
                                    @if (customer(true)->can('order.add-to-cart') && $showCartButton)
                                        <td>
                                            <button class="btn btn-sm btn-warning" href="javascript:void(0)"
                                                onclick="addSingleProductToOrder({{ $counter }}, processedQuoteData('{{ $item->QuantityOrdered }}'))"
                                                @if ($quotation->ExpirationDate < date('Y-m-d')) disabled @endif>
                                                <i class="icon-bag mr-1"></i> Add to Cart
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                                @php $counter++ @endphp
                            @endforeach
                        </tbody>
                    </table>
                </x-site.data-table-wrapper>
            @else
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger">
                            <strong>{{ __('No Data Found!') }}</strong>
                        </div>
                    </div>
                </div>
            @endif

{{--                    <div class="mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold mb-3">{{__('Order Notes: ')}}</span>
                        </div>

                        <div id="note-items">
                            @foreach ($quotation->OrderNotes as $key => $quotationNote)
                                <div class="card note-wrapper my-2 bg-light">
                                    <div class="card-body">
                                        <div class="note-item row">
                                            <div class="col-md-8">
                                                <label> Subject: <span
                                                        class="font-weight-bold">{{ $quotationNote->Subject ?? '' }}</span></label>
                                                <input type="hidden"
                                                       name="subject"
                                                       value="{{ $quotationNote->Subject ?? "" }}">
                                            </div>
                                            <div class="col-md-4 d-flex justify-content-end">
                                                <label> Date: <span
                                                        class="font-weight-bold">{{ carbon_date($quotationNote->Date ?? now()) }}</span></label>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <label style="font-size: 1rem"> Note</label>
                                                    <div class="input-group">
                                                        <textarea
                                                            class="form-control" style="padding-left: 0.75rem; height: 75px;"
                                                            rows="2" placeholder="Enter your message here..."
                                                            data-order-number="{{ $quotation->OrderNumber }}"
                                                            data-note-number="{{ $quotationNote->NoteNum }}"
                                                            @if ($quotationNote->Editable == 'N') disabled @endif
                                                            onblur="IsSaveOrUpdateNote(this, '{{ $quotationNote->Note }}')"
                                                        >{{ $quotationNote->Note ?? "" }}</textarea>

                                                        @if ($quotationNote->Editable == 'Y')
                                                            <div class="input-group-prepend">
                                                                <button
                                                                    class="btn btn-info rounded-right my-0 btn-block py-0 px-2"
                                                                    style="font-size: 1.25rem; height: 75px"
                                                                    onclick="saveOrUpdateNote(this)"
                                                                ><i class="fa fa-edit"></i></button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" id="add-items" class="btn btn-outline-primary">
                            <i class="icon-circle-plus" aria-hidden="true"></i> Add Note
                        </button>
                    </div> --}}

        </div>
    </div>
</div>
@php

    push_html(function () {
        return <<<HTML
                <div class="modal fade" id="update-confirm" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content shadow-sm">
                    <div class="modal-body">
                        <h3 class="text-center">{{ __('Are you sure?') }}</h3>
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" data-dismiss="modal" class="btn btn-dark">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-info" onclick="confirmUpdate()">{{ __('Update') }}</button>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    });

    push_css(
        "
    ",
        'internal-style',
    );

    push_js(
        "
        var order_number = '$quotation->OrderNumber';

        function resetSearch() {
            let uri = window.location.href;
            let order_id = '" .
            (request()->has('order_id') ? request()->order_id : 0) .
            "';
            window.location = uri.split('?')[0] + '?order_id=' + order_id;
        }
    ",
        'internal-script',
    );
@endphp
@pushonce('footer-script')
    <script src="{{ asset('assets/js/widget/order.js') }}"></script>
@endpushonce

{{-- <div class="modal fade" id="update-confirm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow-sm">
            <div class="modal-body">
                <h3 class="text-center">{{ __('Are you sure?') }}</h3>
            </div>
            <div class="modal-footer justify-content-around pt-0 border-top-0">
                <button type="button" data-dismiss="modal" class="btn btn-dark">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-info" onclick="confirmUpdate()">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        let template = `
            <div class="card note-wrapper my-2 bg-light">
                <div class="card-body">
                    <a href="javascript:void(0)" class="text-danger remove">
                        <i class="fa fa-remove"></i>
                    </a>
                    <div class="note-item row">
                        <div class="col-md-8">
                            <label> Subject: <span
                                    class="font-weight-bold">Order Note</span></label>
                            <input type="hidden"
                                   name="subject" value="">
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            <label> Date: <span
                                    class="font-weight-bold">{{ carbon_date(now()) }}</span></label>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label style="font-size: 1rem"> Note</label>
                                <div class="input-group">
                                    <textarea
                                        class="form-control" style="padding-left: 0.75rem; height: 75px;"
                                        rows="2" placeholder="Enter your message here..."
                                        data-order-number="{{ $quotation->OrderNumber }}"
                                        onblur="IsSaveOrUpdateNote(this, '')"
                                    ></textarea>

                                    <div class="input-group-prepend">
                                        <button
                                            class="btn btn-primary rounded-right my-0 btn-block py-0 px-2"
                                            style="font-size: 1.25rem; height: 75px"
                                            onclick="saveOrUpdateNote(this)"
                                        ><i class="fa fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $("#add-items").on("click", () => {
            const textArea = $("#note-items").children().first().find('textarea')
            if (textArea.length === 0 || textArea.val().length > 0) {
                $("#note-items").prepend(template);
            } else {
                ShowNotification('error', 'Note', "Already had a blank note field.");
                textArea.trigger('focus');
            }
        })
        $("body").on("click", ".remove", (e) => {
            $(e.target).parents(".note-wrapper").remove();
        })

    });

    var actionElement = null;

    function IsSaveOrUpdateNote(el, pre_val = null) {
        actionElement = el;
        const element = $(el).parents('.note-item');
        const textArea = element.find('textarea');

        if (textArea.val() !== "" && pre_val != textArea.val().trim()) {
            $('#update-confirm').modal();
        }
    }

    function confirmUpdate() {
        $('#update-confirm').modal('hide');
        if (actionElement) {
            saveOrUpdateNote(actionElement);
        } else {
            ShowNotification('error', 'Note', "Something went wrong!");
        }
    }

    function saveOrUpdateNote(el) {
        const element = $(el).parents('.note-item');
        const textArea = element.find('textarea');
        const subject = element.find('input[name="subject"]');

        let reqData = {
            _token: $('#csrf-token').data('token'),
            note: textArea.val(),
            subject: subject.val(),
            ...textArea.data(),
        };

        if (reqData.note.length <= 0) {
            ShowNotification('error', 'Note', "Note field not will be empty.");
            return 0;
        }

        $.ajax({
            url: "{{ route('update.order-note') }}",
            type: 'POST',
            data: reqData,
            success: function (response) {
                ShowNotification('success', 'Note', response.message);

                setTimeout(function () {
                    if (response.redirect_to) {
                        window.location.href = response.redirect_to;
                        console.log(response.redirect_to);
                    }
                }, 300);
            },
            error: function (error) {
                ShowNotification('error', 'Note', (JSON.parse(error.responseText)).message ?? '');
            }
        });
    }
</script>
@php
    push_css("
        .product-thumb > img {
            width: auto;
            max-width: 45px;
            max-height: 50px;
            margin: 5px auto;
        }
        .note-wrapper .remove {
            position: absolute;
            top: 2px;
            right: 10px;
        }
    ", "internal-style");

    push_js("
        var timeout;
        var delay = 500;

        function debounceSearch() {
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(function () {
                searchOnOrderItems();
            }, delay);
        }

        function resetSearch() {
            let uri = window.location.href;
            let order_id = '". (request()->has('order_id') ? request()->order_id : 0) ."';
            window.location = uri.split('?')[0] + '?order_id=' + order_id;
        }

        function searchOnOrderItems(e) {
            let search = $('#search_string').val();
            let uri = window.location.href;
            let new_param = updateQueryStringParameter(uri, 'search', search);
            window.location = new_param;
        }

        function onPerPage(e) {
            let uri = window.location.href;
            let new_param = updateQueryStringParameter(uri, 'per_page', e.target.value);
            window.location = new_param;
        }

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
            var separator = uri.indexOf('?') !== -1 ? '&' : '?';
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + '=' + value + '$2');
            } else {
                return uri + separator + key + '=' + value;
            }
        }

    ", "internal-script")
@endphp --}}

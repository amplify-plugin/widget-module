<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 text-right mb-3">
                <a href="{{ route('frontend.index') }}/orders">Back to Orders</a>
            </div>
            @if ($order)
                <div class="row mb-4">

                    <div class="col-4">
                        <span> <b>{{ __('Created By :') }}</b> {{ $order['CustomerName'] ?? 'N/A' }}</span>
                    </div>

                    <div class="col-4"><span> <b>{{ __('Created Date :') }}</b>
                        {{ carbon_date($order['EntryDate']) }}</span>
                    </div>

                    <div class="col-4"><span> <b>{{ __('Last Changed :') }}</b> {{ '' }}</span></div>

                    <div class="col-4"><span> <b>{{ __('Approved By :') }}</b> {{ '' }}</span></div>

                    <div class="col-4"><span> <b>{{ __('Order Reference :') }}</b>
                        {{ $order['localOrder']->customer_order_number }}</span>
                    </div>

                    <div class="col-4">
                        <span> <b>{{ __('PO Number :') }} </b> {{ config('amplify.basic.web_order_prefix', '') . $order['CustomerPurchaseOrdernumber'] }}</span>
                    </div>

                    <div class="col-4"><span> <b>{{ __('Shipping Method :') }} </b> {{ $order['CarrierCode'] }}</span>
                    </div>
                    @if(!empty($order['InHouseDeliveryDate']))
                        <div class="col-4"><span> <b>InHouseDeliveryDate :</b> {{ carbon_date($order['InHouseDeliveryDate']) }}</span>
                        </div>
                    @endif
                </div>

                <x-site.data-table-wrapper id="order-item-table">
                    <x-slot name="rightside">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            @if(customer(true)->can('order.create'))
                                <button type="button" class="btn btn-sm btn-primary btn-right m-2  create-order">
                                    {{__('Create Order')}}
                                </button>
                            @endif
                            @if(customer(true)->can('order.add-to-cart'))
                                <button type="button" class="btn btn-sm btn-primary btn-right my-2 ml-2 mr-0"
                                        onclick="addToCart()">
                                    {{__('add all items to the cart')}}
                                </button>
                            @endif
                        </div>
                    </x-slot>

                    <table class="table table-bordered table-striped table-hover" id="product-item-list">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Product Code') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Qty') }}</th>
                            @if(customer(true)->can('order.add-to-cart'))
                                <th style="width: 125px">{{ __('Action') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @php $counter = 0 @endphp
                        @foreach ($order->OrderDetail ?? [] as $item)
                            @continue($item['LineNumber'] == null || $item['ItemNumber'] == 'M')
                            <tr>
                                <input type="hidden" id="{{ 'product_code_' . $counter }}"
                                       value="{{ $item->ItemNumber }}"/>
                                <input type="hidden" id="{{ 'product_qty_' . $counter }}"
                                       value="{{ $item->QuantityOrdered }}"/>

                                <th scope="row">{{ $counter+1 }}</th>
                                <td>{{ $item->ItemNumber }}</td>
                                <td>
                                    <a class="product-thumb"
                                       href="{{ frontendSingleProductURL($item['product'] ?? '#') }}">
                                        <img title="View Product"
                                             src="{{ assets_image(optional($item['product'])->productImage->main ?? '') }}"
                                             alt="{{ optional($item['product'])->product_name }}" width="100">
                                    </a>
                                </td>
                                <td>
                                    {{ str()->limit(optional($item['product'])->product_name, 40, '...') }}
                                </td>
                                @if(config('amplify.client_code') =='RHS')
                                <td>{{ price_format($item['TotalLineAmount'] ?? 0) }}</td>
                                @else
                                <td>{{ price_format($item['ActualSellPrice'] ?? 0) }}</td>
                                @endif
                                <td><span class="text-medium">{{ $item['QuantityOrdered'] }}</span></td>
                                @if(customer(true)->can('order.add-to-cart'))
                                    <td style="width: 125px">
                                        <button class="btn btn-sm btn-warning btn-add-to-cart" type="button"
                                                onclick="addSingleProductToOrder({{ $counter }})">
                                            <i class="icon-bag mr-1"></i> Add to Cart
                                        </button>
                                    </td>
                                @endif
                            </tr>
                            @php $counter++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </x-site.data-table-wrapper>

                @if (customer(true)->is_approver)
                    @if ($order->order_status == 'Pending')
                        <form class="d-flex justify-content-end"
                              action="{{ route('approve-order', $order['OrderNumber']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order['OrderNumber'] }}">
                            <button class="btn btn-sm btn-success btn-right mr-0">Approve Order</button>
                        </form>
                    @else
                        <div class="d-flex justify-content-end mt-2">
                            <span class="badge badge-success">{{ __('Approved') }}</span>
                        </div>
                    @endif
                @endif
            @else
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger">
                            <strong>{{ __('No Data Found!') }}</strong>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold mb-3">{{ __('Order Notes: ') }}</span>
                </div>

                <div id="note-items">
                    @foreach ($order->OrderNotes as $orderNote)
                        <div class="card note-wrapper my-2 bg-light">
                            <div class="card-body">
                                <div class="note-item row">
                                    <div class="col-md-8">
                                        <label> Subject: <span
                                                class="font-weight-bold">{{ $orderNote->Subject ?? '' }}</span></label>
                                        <input type="hidden" name="subject" value="{{ $orderNote->Subject ?? '' }}">
                                    </div>
                                    <div class="col-md-4 d-flex justify-content-end">
                                        <label> Date: <span
                                                class="font-weight-bold">{{ carbon_date($orderNote->Date ?? now()) }}</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label style="font-size: 1rem"> Note</label>
                                            <div class="d-flex">
                                            <textarea
                                                class="form-control ordernote"
                                                rows="2" placeholder="Enter your message here..."
                                                data-order-number="{{ $order->OrderNumber }}"
                                                data-note-number="{{ $orderNote->NoteNum }}"
                                                @if ($orderNote->Editable == 'N') disabled @endif
                                                onblur="IsSaveOrUpdateNote(this, '{{ $orderNote->Note }}')"
                                            >{{ $orderNote->Note ?? '' }}</textarea>

                                                @if ($orderNote->Editable == 'Y')
                                                    <div class="input-group-prepend">
                                                        <button
                                                            class="rounded-left-0 btn btn-primary rounded-right my-0 py-0 px-2"
                                                            style="font-size: 1.25rem; height: 75px"
                                                            onclick="saveOrUpdateNote(this)"
                                                        ><i class="pe-7s-note"></i></button>
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
            </div>

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

    push_js(
        "
        var order_number = '$order->OrderNumber';

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

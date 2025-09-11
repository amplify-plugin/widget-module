<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 text-right mb-3">
                <a href="{{ route('frontend.index') }}/orders">Back to Orders</a>
            </div>
            @if ($order)
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <strong>Order Number:</strong>
                            {{ $order->OrderNumber }}-{{ $order->OrderSuffix }}<br>
                            <strong>Purchase Order Number:</strong>
                            {{ $order->CustomerPurchaseOrdernumber }}<br>
                            <strong>Order Status:</strong> {{ $order->OrderStatus }}<br>
                            <strong>Order Type:</strong> {{ $order->OrderType }}<br>
                            <strong>Order Value:</strong> {{ price_format($order->TotalOrderValue) }}<br>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Date Entered:</strong> {{ carbon_date($order->EntryDate) }}<br>
                            <strong>Invoice Date:</strong> {{ carbon_date($order->InvoiceDate) }}<br>
                            <strong>Invoice Amount:</strong> {{ price_format($order->InvoiceAmount) }}<br>
                            <strong>Carrier Code:</strong> {{ $order->CarrierCode }}<br>
                            <strong>Ship Warehouse:</strong> {{ $order->WarehouseID }}<br>
                            @if ($order->TrackingShipments !== 'N/A')
                                <strong>Tracking Number:</strong> {!! $order->TrackingShipments !!}
                            @endif
                        </p>
                    </div>
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
                                    {{__('Add all items to the cart')}}
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
                            <th>{{ __('PO #') }}</th>
                            <th class="text-nowrap">{{ __('Exp. Rec. Date') }}</th>
                            <th style="width: 85px">{{ __('Price') }}</th>
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
                                <td class="text-nowrap">{{ $item->ItemNumber }}</td>
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
                                <td>
                                    {{ $item['TiedOrder'] }}
                                </td>
                                <td>
                                    @if(! empty($item['PODetails']['ReqShipDt']))
                                        {{ $item['PODetails']['ReqShipDt'] }}
                                    @endif
                                </td>
                                @if(config('amplify.client_code') =='RHS')
                                    <td style="width: 85px">{{ price_format($item['TotalLineAmount'] ?? 0) }}</td>
                                @else
                                    <td style="width: 85px">{{ price_format($item['ActualSellPrice'] ?? 0) }}</td>
                                @endif
                                <td><span class="text-medium">{{ $item['QuantityOrdered'] }}</span></td>
                                @if(customer(true)->can('order.add-to-cart'))
                                    <td style="width: 125px">
                                        <button class="btn btn-sm btn-primary btn-add-to-cart" type="button"
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
                    <span class="font-weight-bold mb-1">{{ __('Order Notes: ') }}</span>
                </div>

                <div class="mb-2">
                    @if(!empty($order->NoteList['order_note']))
                        {{ $order->NoteList['order_note'] }}
                    @else
                        —
                    @endif
                </div>
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

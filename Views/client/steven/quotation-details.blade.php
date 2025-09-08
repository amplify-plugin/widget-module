<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 text-right mb-3">
                <a href="{{ route('frontend.index') }}/quotations">{{ __('Back to Quotations') }}</a>
            </div>
            @if ($order)
                <div class="card mb-4">
                    <div class="card-header" data-toggle="collapse" data-target="#orderHeaderInfo" aria-expanded="true"
                        style="cursor: pointer;">
                        <strong>Quotation Header Information</strong>
                        <span class="float-right"><i class="fas fa-chevron-down"></i></span>
                    </div>

                    <div id="orderHeaderInfo" class="collapse show">
                        <div class="card-body">

                            {{-- 1. ADDRESS INFORMATION --}}
                            <h5>ADDRESS INFORMATION</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Billing Address</h6>
                                    <p>{{ $customer->CustomerName ?? '' }}<br>
                                        {{ $customer->CustomerAddress1 }}<br>
                                        {{ $customer->CustomerCity }}, {{ $customer->CustomerState }},
                                        {{ $customer->CustomerZipCode }}, {{ $customer->CustomerCountry }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Shipping Address</h6>
                                    <p>{{ $order->ShipToName ?? '' }}<br>
                                        {{ $order->ShipToAddress1 }}<br>
                                        {{ $order->ShipToCity }}, {{ $order->ShipToState }},
                                        {{ $order->ShipToZipCode }}, {{ $order->ShipToCountry }}
                                    </p>
                                </div>
                            </div>

                            {{-- 2. ORDER INFORMATION --}}
                            <h5 class="mt-2">QUOTATION INFORMATION</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        <strong>Quote Number:</strong>
                                        {{ $order->QuoteNumber }}-{{ $order->Suffix }}<br>
                                        <strong>Purchase Order Number:</strong>
                                        {{ $order->CustomerPurchaseOrdernumber }}<br>
                                        <strong>Quote Status:</strong> {{ $order->OrderStatus }}<br>
                                        <strong>Type:</strong> {{ $order->QuoteType }}<br>
                                        <strong>Quote Value:</strong>{{ price_format($order->TotalOrderValue) }}<br>
                                        <strong>Ship Warehouse:</strong> {{ $order->WarehouseID }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <strong>Date Entered:</strong> {{ carbon_date($order->EntryDate) }}<br>
                                        <strong>Order Disposition:</strong> {{ $order->OrderDisposition }}<br>
                                        <strong>Carrier Code:</strong> {{ $order->CarrierCode }}<br>
                                        <strong>Freight Account Number:</strong> {{ $order->FreightAccountNumber }}
                                    </p>
                                </div>
                            </div>

                            {{-- 3. ORDER COMMENTS --}}
                            <h5 class="mt-2">ORDER COMMENTS</h5>
                            <hr>
                            <div class="mb-2">
                                <strong>Your Internal Comments:</strong><br>
                                @if(!empty($order->NoteList['order_note']))
                                    {{ $order->NoteList['order_note'] }}
                                @else
                                    —
                                @endif
                            </div>

                            {{-- 4. COMMENTS TO STEVEN ENGINEERING --}}
                            <h5 class="mt-2">COMMENTS TO STEVEN ENGINEERING</h5>
                            <hr>
                            <div>
                                <strong>Comments to Steven Engineering:</strong><br>
                                @if(!empty($order->NoteList['internal_comment']))
                                    {{ $order->NoteList['internal_comment'] }}
                                @else
                                    —
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                <x-site.data-table-wrapper id="order-item-table">
                    <x-slot name="rightside">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            @if (customer(true)->can('order.create'))
                                <button type="button" class="btn btn-sm btn-primary btn-right m-2  create-order">
                                    {{ __('Create Order') }}
                                </button>
                            @endif
                            @if (customer(true)->can('order.add-to-cart'))
                                <button type="button" class="btn btn-sm btn-primary btn-right my-2 ml-2 mr-0"
                                    onclick="addToCart()">
                                    {{ __('add all items to the cart') }}
                                </button>
                            @endif
                        </div>
                    </x-slot>

                    <table class="table table-bordered table-striped table-hover" id="product-item-list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Product Code') }}</th>
                                <th>{{ __('Customer Item Number') }}</th>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Qty Ordered') }}</th>
                                <th>{{ __('Qty Shipped') }}</th>
                                <th>{{ __('Back Ordered') }}</th>
                                <th>{{ __('Estimated Delivery Date') }}</th>
                                <th>{{ __('UOM') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Extended') }}</th>
                                @if (customer(true)->can('order.add-to-cart'))
                                    <th style="width: 125px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 0 @endphp
                            @foreach ($order->QuoteDetail ?? [] as $item)
                                @continue($item['LineNumber'] == null || $item['ItemNumber'] == 'M')
                                <tr>
                                    <input type="hidden" id="{{ 'product_code_' . $counter }}"
                                        value="{{ $item->ItemNumber }}" />
                                    <input type="hidden" id="{{ 'product_qty_' . $counter }}"
                                        value="{{ $item->QuantityOrdered }}" />

                                    <th scope="row">{{ $counter + 1 }}</th>
                                    <td>{{ $item->ItemNumber }}</td>
                                    <td>{{ $item['custom_part_number'] ?? '-' }}</td>
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
                                    <td><span class="text-medium">{{ $item['QuantityOrdered'] }}</span></td>
                                    <td><span class="text-medium">{{ $item['QuantityShipped'] }}</span></td>
                                    <td><span class="text-medium">{{ $item['QuantityBackordered'] }}</span></td>
                                    <td><span class="text-medium">{{ $item['InHouseDeliveryDate'] }}</span></td>
                                    <td><span class="text-medium">{{ $item['UnitOfMeasure'] }}</span></td>
                                    <td class="text-right"><span
                                            class="text-medium">{{ price_format($item['PricingUM'] ?? 0) }}</span></td>
                                    <td class="text-right"><span
                                            class="text-medium">{{ price_format($item['TotalLineAmount'] ?? 0) }}</span>
                                    </td>
                                    @if (customer(true)->can('order.add-to-cart'))
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
            @else
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger">
                            <strong>{{ __('No Data Found!') }}</strong>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


@pushonce('footer-script')
    <script src="{{ asset('assets/js/widget/order.js') }}"></script>
@endpushonce

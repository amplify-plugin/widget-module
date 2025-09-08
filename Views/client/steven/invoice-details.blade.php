<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 text-right mb-3">
                <a href="javascript:void(0)" onclick="history.back()">{{ __('Back to Invoices') }}</a>
            </div>
            @if ($order)
                <div class="card mb-4">
                    <div class="card-header" data-toggle="collapse" data-target="#orderHeaderInfo" aria-expanded="true"
                        style="cursor: pointer;">
                        <strong>Invoice Header Information</strong>
                        <span class="float-right"><i class="fas fa-chevron-down"></i></span>
                    </div>

                    <div id="orderHeaderInfo" class="collapse show">
                        <div class="card-body">
                            {{-- 1.BASIC INFORMATION--}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="section-title">Billing Address</div>
                                    <p>{{ $customer->CustomerName ?? '' }}<br>
                                        {{ $customer->CustomerAddress1 }}<br>
                                        {{ $customer->CustomerCity }}, {{ $customer->CustomerState }},
                                        {{ $customer->CustomerZipCode }}, {{ $customer->CustomerCountry }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <div class="section-title">Shipping Address</div>
                                    <p>{{ $order->ShipToName ?? '' }}<br>
                                        {{ $order->ShipToAddress1 }}<br>
                                        {{ $order->ShipToCity }}, {{ $order->ShipToState }},
                                        {{ $order->ShipToZipCode }}, {{ $order->ShipToCountry }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <div class="section-title">Contact Information</div>
                                    <p>Full Name: {{ $contact->name ?? '' }}<br>
                                        Email: {{ $contact->email }}<br>
                                        Phone: {{ $contact->phone }}
                                    </p>
                                </div>
                            </div>

                            {{-- 2. ORDER INFORMATION --}}
                            {{-- <h5 class="mt-2">INVOICE INFORMATION</h5>
                            <hr> --}}
                            <div class="section-title mt-3">Invoice Information</div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p>
                                        <strong>Invoice Number:</strong>
                                        {{ $order->InvoiceNumber }}-{{ $order->InvoiceSuffix }}<br>
                                        <strong>Purchase Order Number:</strong>
                                        {{ $order->CustomerPONumber }}<br>
                                        <strong>Status:</strong> {{ $order->InvoiceStatus }}<br>
                                        <strong>Type:</strong> {{ $order->InvoiceType }}<br>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <strong>Order Value:</strong> {{ price_format($order->TotalOrderValue) }}<br>
                                        <strong>Invoice Amount:</strong> {{ price_format($order->InvoiceAmount) }}<br>
                                        <strong>Date Entered:</strong> {{ carbon_date($order->EntryDate) }}<br>
                                        <strong>Invoice Date:</strong> {{ carbon_date($order->InvoiceDate) }}<br>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <strong>Order Disposition:</strong> {{ $order->OrderDisposition }}<br>
                                        <strong>Carrier Code:</strong> {{ $order->CarrierCode }}<br>
                                        <strong>Freight Account Number:</strong> {{ $order->FreightAccountNumber }}<br>
                                        <strong>Ship Warehouse:</strong> {{ $order->WarehouseID }}<br>
                                    </p>
                                </div>
                            </div>

                            {{-- 3. ORDER COMMENTS --}}
                            {{-- <h5 class="mt-2">ORDER COMMENTS</h5>
                            <hr> --}}
                            <div class="mt-4">
                                <div class="section-title">Comments</div>
                                <p><strong>Internal Comment:</strong>
                                    @if(!empty($order->NoteList['internal_comment']))
                                        {{ $order->NoteList['internal_comment'] }}
                                    @else
                                        —
                                    @endif
                                </p>
                                <p><strong>Comments to Steven Engineering:</strong>
                                    @if(!empty($order->NoteList['order_note']))
                                        {{ $order->NoteList['order_note'] }}
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>


                            {{-- 5. PAID INVOICE TRANSACTIONS --}}
                            {{-- <h5 class="mt-2">INVOICE TRANSACTIONS</h5>
                            <hr> --}}
                            <div class="mt-4">
                                <div class="section-title">Transactions</div>
                                @if(!empty($invoiceTransactions) && count($invoiceTransactions))
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Transaction Date') }}</th>
                                                    <th>{{ __('Transaction Type') }}</th>
                                                    <th>{{ __('Transaction Amount') }}</th>
                                                    <th>{{ __('Payment Amount') }}</th>
                                                    <th>{{ __('Cash Discount Amount') }}</th>
                                                    <th>{{ __('Check Number') }}</th>
                                                    <th>{{ __('Adjustment Number') }}</th>
                                                    <th>{{ __('Order Number') }}</th>
                                                    <th>{{ __('Purchase Order Number') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoiceTransactions as $txn)
                                                    <tr>
                                                        <td>{{ carbon_date($txn['TransactionDate'] ?? '') }}</td>
                                                        <td>{{ $txn['TransactionType'] ?? '' }}</td>
                                                        <td class="text-right">{{ price_format($txn['TransactionAmount'] ?? 0) }}</td>
                                                        <td class="text-right">{{ price_format($txn['PaymentAmount'] ?? 0) }}</td>
                                                        <td class="text-right">{{ price_format($txn['CashDiscountAmount'] ?? 0) }}</td>
                                                        <td>{{ $txn['CheckNumber'] ?? '' }}</td>
                                                        <td>{{ $txn['AdjustmentNumber'] ?? '' }}</td>
                                                        <td>
                                                            @if(!empty($txn['OrderNumber']))
                                                                <a href="{{ route('frontend.index') }}/orders/{{ $txn['OrderNumber'] }}?suffix={{ $txn['OrderSuffix'] }}">
                                                                    {{ $txn['OrderNumber'] }}-{{ $txn['OrderSuffix'] }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>{{ $txn['PurchaseOrderNumber'] ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>No transactions found</p>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
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
                            @foreach ($order->InvoiceDetail ?? [] as $item)
                                @continue($item['LineNumber'] == null || $item['ItemNumber'] == 'M')
                                <tr>
                                    <input type="hidden" id="{{ 'product_code_' . $counter }}"
                                        value="{{ $item->ItemNumber }}" />
                                    <input type="hidden" id="{{ 'product_qty_' . $counter }}"
                                        value="{{ $item->QuantityOrdered }}" />

                                    <th scope="row">{{ $counter + 1 }}</th>
                                    <td>
                                        <a class="text-decoration-none"
                                            href="{{ frontendSingleProductURL($item['product'] ?? '#') }}">
                                            {{ $item->ItemNumber }}
                                        </a>
                                    </td>
                                    <td>{{ $item['custom_part_number'] ?? '-' }}</td>
                                    <td>
                                        {{ str()->limit(optional($item['product'])->product_name, 20, '...') }}
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

                    <div class="row justify-content-end mt-3">
                        <div class="col-md-4">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="text-right">Subtotal:</th>
                                        <td class="text-right">{{ price_format($order->ItemSalesAmount ?? 0) }}</td>
                                    </tr>
                                     @if(!empty($order->SalesTaxAmount))
                                        <tr>
                                            <th class="text-right">Taxes:</th>
                                            <td class="text-right">{{ price_format($order->SalesTaxAmount ?? 0) }}</td>
                                        </tr>
                                    @endif
                                    @foreach ($order->ExtraCharges ?? [] as $title => $amount)
                                        <tr>
                                            <th class="text-right">{{ $title }}:</th>
                                            <td class="text-right">{{ price_format($amount) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="border-top">
                                        <th class="text-right">Grand Total:</th>
                                        <td class="text-right">{{ price_format($order->InvoiceAmount ?? 0) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                        </x-site.data-table-wrapper>
                    </div>
                </div>

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

@push('custom-style')
    <style>
        .section-title {
            background-color: #f8f9fa;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
            font-weight: bold;
        }
    </style>
@endpush
@pushonce('footer-script')
    <script src="{{ asset('assets/js/widget/order.js') }}"></script>
@endpushonce

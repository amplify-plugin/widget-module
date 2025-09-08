<script>
    function processedQuoteData(min_qty = null) {
        return {
            source_type: 'Quote',
            source: "{{ $quotation->QuoteNumber }}",
            expiry_date: "{{ $quotation->ExpirationDate }}",
            additional_info: {
                minimum_quantity: min_qty,
            },
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
                    <div class="col-4">
                        <span> <b>{{ __('Quote Number :') }}</b> {{ $quotation->QuoteNumber ?? 'N/A' }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Effective Date :') }}</b>
                            {{ carbon_date($quotation->EffectiveDate) }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Expiration Date :') }}</b>
                            {{ carbon_date($quotation->ExpirationDate) }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('PO Number :') }} </b>
                            {{ $quotation->CustomerPurchaseOrdernumber }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Shipping Method :') }}
                            </b>{{ $quotation->CarrierCode }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{ __('Mail To :') }}
                            </b>{{ $quotation->QuotedByEmail }}</span>
                    </div>
                </div>

                <x-site.data-table-wrapper id="order-item-table">
                    @if (customer(true)->canAny(['order.create', 'order.add-to-cart']))
                        <x-slot name="rightside">
                            <div class="d-flex justify-content-center justify-content-md-end">
                                @if (customer(true)->can('order.create') && $showCreateOrderButton)
                                    <button
                                        type="button"
                                        id="create-order-from-quote"
                                        class="btn btn-sm btn-primary btn-right m-2"
                                        @if ($quotation->ExpirationDate < date('Y-m-d')) disabled @endif
                                    >
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
                            <th>{{ __('Item') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Qty') }}</th>
                            <th>{{ __('UM') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('UM') }}</th>
                            <th>{{ __('Total') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $counter = 0 @endphp
                        @foreach ($quotation['QuoteDetail'] ?? [] as $item)
                            <tr>
                                <input type="hidden" id="{{ 'product_code_' . $counter }}"
                                       value="{{ $item->ItemNumber }}" />
                                <input type="hidden" id="{{ 'product_qty_' . $counter }}"
                                       value="{{ $item->QuantityOrdered }}" />
                                <input type="hidden" id="{{ 'minimum_order_qty_' . $counter }}"
                                       value="{{ $item->QuantityOrdered }}" />

                                <td>
                                    {{ $item->ItemType === 'I' ? 'Special' : '' }}
                                </td>
                                <td>{{ $item->ItemDescription1 }}</td>
                                <td><span class="text-medium">{{ $item->QuantityOrdered }}</span></td>
                                <td><span class="text-medium">{{ $item->UnitOfMeasure }}</span></td>
                                <td>{{ price_format($item->ActualSellPrice) }}</td>
                                <td><span class="text-medium">{{ $item->PricingUM }}</span></td>
                                <td><span class="text-medium">{{ price_format($item->TotalLineAmount) }}</span></td>
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-6 ml-auto">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Merchandise</th>
                                    <td>{{ price_format($quotation->ItemSalesAmount) }}</td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td>{{ price_format($quotation->DiscountAmountTrading) }}</td>
                                </tr>
                                <tr>
                                    <th>Tax</th>
                                    <td>{{ price_format($quotation->SalesTaxAmount) }}</td>
                                </tr>
                                <tr>
                                    <th>Freight</th>
                                    <td>{{ price_format($quotation->FreightAmount) }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>{{ price_format($quotation->TotalOrderValue) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
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
@include('widget::client.rhsparts.quotation.create-order');
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

@endphp
@pushonce('internal-script')
    <script>

        var order_number = '{{ $quotation->OrderNumber }}';

        function resetSearch() {
            let uri = window.location.href;
            let order_id = '{{ request()->has('order_id') ? request('order_id') : 0 }}';
            window.location = uri.split('?')[0] + '?order_id=' + order_id;
    </script>
@endpushonce
@pushonce('footer-script')
    <script src="{{ asset('assets/js/widget/order.js') }}"></script>
@endpushonce

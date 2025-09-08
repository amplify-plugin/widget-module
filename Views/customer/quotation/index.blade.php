<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="order-table">
                <x-slot name="rightside">
                    <form method="get" action="{{ route('frontend.quotations.index') }}" id="order-search-form">
                        <input type="hidden" name="created_start_date"
                            value="{{ request('created_start_date', now(config('app.timezone'))->subDays(value: 7)->format('Y-m-d')) }}"
                            id="created_start_date">

                        <input type="hidden" name="created_end_date"
                            value="{{ request('created_end_date', now(config('app.timezone'))->format('Y-m-d')) }}"
                            id="created_end_date">

                        <div class="d-flex justify-content-center justify-content-md-end">
                            <label>
                                <div id="created_date_range" class="border form-control form-control-sm py-2 d-flex">
                                    <i class="mr-2 pe-7s-date" style="font-weight: bold; font-size: 1.25rem;"></i>
                                    <span></span>
                                </div>
                            </label>
                        </div>
                    </form>
                </x-slot>
                <table class="table table-bordered table-striped table-hover" id="order-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if ($columns['QuoteNumber'])
                                <th>{{ $quoteNumberLabel ?? 'Quote Number' }}</th>
                            @endif

                            @if ($columns['QuoteType'])
                                <th>{{ $quoteTypeLabel ?? 'Quote Type' }}</th>
                            @endif

                            @if ($columns['QuoteTo'])
                                <th>{{ $quoteToLabel ?? 'Quote To' }}</th>
                            @endif

                            @if ($columns['PurchaseOrderNumber'])
                                <th>{{ $purchaseOrderNumberLabel ?? 'Purchange Order Number' }}</th>
                            @endif

                            @if ($columns['Wharehouse'])
                                <th>{{ $wharehouseLabel ?? 'Wharehouse' }}</th>
                            @endif

                            @if ($columns['QuoteAmount'])
                                <th>{{ $quoteAmountLabel ?? 'Quote Amount' }}</th>
                            @endif

                            @if ($columns['TotalOrderValue'])
                                <th>{{ $orderValueLabel ?? 'Total Order Value' }}</th>
                            @endif

                            @if ($columns['EntryDate'])
                                <th>{{ $entryDateLabel ?? 'Created At' }}</th>
                            @endif

                            @if ($columns['ExpirationDate'])
                                <th>{{ $expiredDateLabel ?? 'Expired At' }}</th>
                            @endif
                            @if (customer(true)->can('quote.view'))
                                <th>{{ __('Actions') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotations as $index => $quotation)
                            <tr>
                                <th>{{ $index + 1 }}</th>
                                @if ($columns['QuoteNumber'])
                                    <td>
                                        <a class="text-medium navi-link"
                                            href="{{ route('frontend.quotations.show', $quotation->QuoteNumber) . $getAdditionalQueryParam($quotation) }}">
                                            {{ $getQuoteDisplayNumber($quotation) }}
                                        </a>
                                    </td>
                                @endif

                                @if ($columns['QuoteType'])
                                    <td>{{ $quotation['QuoteType'] ?? '' }}</td>
                                @endif

                                @if ($columns['QuoteTo'])
                                    <td>{{ $quotation['QuotedTo'] ?? '' }}</td>
                                @endif

                                @if ($columns['PurchaseOrderNumber'])
                                    <td>{{ $quotation['CustomerPurchaseOrdernumber'] ?? '' }}</td>
                                @endif

                                @if ($columns['Wharehouse'])
                                    <td>{{ $quotation['WarehouseID'] ?? '' }}</td>
                                @endif

                                @if ($columns['QuoteAmount'])
                                    <td>{{ price_format($quotation['QuoteAmount'] ?? 0) }}</td>
                                @endif

                                @if ($columns['TotalOrderValue'])
                                    <td>{{ price_format($quotation['TotalOrderValue'] ?? 0) }}</td>
                                @endif

                                @if ($columns['EntryDate'])
                                    <td data-order="{{ $quotation['EntryDate'] }}">
                                        {{ carbon_date($quotation['EntryDate'] ?? '') }}</td>
                                @endif

                                @if ($columns['ExpirationDate'])
                                    <td data-order="{{ $quotation['ExpirationDate'] }}">
                                        {{ carbon_date($quotation['ExpirationDate'] ?? '') }}</td>
                                @endif
                                @if (customer(true)->can('quote.view'))
                                    <td class="d-flex flex-column justify-content-center m-0">
                                        <a class="badge btn-info text-decoration-none mb-1"
                                            href="{{ route('frontend.quotations.show', $quotation->QuoteNumber) . $getAdditionalQueryParam($quotation) }}">
                                            <i class="fa fa-eye"></i>
                                            View
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-site.data-table-wrapper>
        </div>
    </div>
    @pushonce('footer-script')
        <script>
            const ORDER_DATE_RANGER = '#created_date_range';

            $(document).ready(function() {
                const startDate = $('#created_start_date').val();

                const endDate = $('#created_end_date').val();

                initOrderCreatedDateRangePicker(startDate, endDate);
            });
        </script>
    @endpushonce

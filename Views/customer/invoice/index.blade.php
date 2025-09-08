<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="row">
                @if ($showContactDetail)
                    <div class="col-md-7">
                        <p class="mb-3">Customer: {{ $accountSummary['CustomerName'] ?? '' }}</p>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <p>Terms: {{ $accountSummary['TermsDescription'] ?? '' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <p class="mb-3">Credit Limit: {{ price_format($accountSummary['CreditLimit'] ?? 0) }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="mb-3">Last Payment: {{ carbon_date($accountSummary['DateOfLastPayment']) }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="mb-3">Amount: {{ price_format($accountSummary['LastPayAmount'] ?? 0) }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="mb-3">Open Order
                            Amount: {{ price_format($accountSummary['OpenOrderAmount'] ?? 0) }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="mb-3">Total Due: {{ price_format($accountSummary['TradeAmountDue'] ?? 0) }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="mb-3">Will pay: <span id="will-pay">{{ price_format(0) }}</span></p>
                    </div>
                @endif

                <div class="col-md-12">

                    <x-site.data-table-wrapper id="invoice-table">
                        <x-slot name="rightside">
                            <form method="get" action="{{ url()->current() }}" id="order-search-form">
                                <input type="hidden" name="created_start_date"
                                       value="{{ request('created_start_date', now(config('app.timezone'))->subDays(7)->format('Y-m-d')) }}"
                                       id="created_start_date">
                                <input type="hidden" name="created_end_date"
                                       value="{{ request('created_end_date', now(config('app.timezone'))->format('Y-m-d')) }}"
                                       id="created_end_date">


                                <div class="d-md-flex d-block justify-content-around justify-content-md-end gap-3">
                                    <select class="form-control-inline form-control-sm border rounded"
                                            name="invoice_status"
                                            onchange="$('#order-search-form').submit()">
                                        <option value="ALL">ALL TYPE</option>
                                        <option
                                            value="PAST" {{ request('invoice_status') == 'PAST'? "selected" : "" }} >
                                            PAST
                                        </option>
                                        <option
                                            value="OPEN" {{ request('invoice_status', 'OPEN') == 'OPEN'? "selected" : "" }} >
                                            OPEN
                                        </option>
                                    </select>

                                    <label>
                                        <div id="created_date_range"
                                             class="border form-control form-control-sm py-2 d-flex">
                                            <i class="mr-2 pe-7s-date"
                                               style="font-weight: bold; font-size: 1.25rem;"></i><span></span>
                                        </div>
                                    </label>
                                    @if (customer(true)->can('invoices.pay') && config('amplify.payment.allow_bulk_invoice_payments', false))
                                        <div class="d-flex justify-content-center justify-content-md-end">
                                            <button class="btn btn-primary btn-sm d-block mr-0 mt-0 text-white"
                                                    disabled
                                                    onclick="redirectToInvoicePayment(event);"
                                                    id="make-pay"
                                                    type="button">
                                                <i class="icon-circle-check"></i> Pay Selected Invoices
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </form>
                            @if(config('amplify.basic.client_code') == 'STV')
                                {{-- 🆕  Status dropdown (not part of form) --}}
                                <div class="mt-2 d-flex justify-content-center justify-content-md-end">
                                    <label class="mb-0 pl-2">
                                        <select id="status_filter" class="form-control form-control-sm">
                                            <option value="">{{ __('STATUS') }}</option>
                                            <option value="Due">Due</option>
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                        </select>
                                    </label>
                                </div>
                            @endif
                        </x-slot>
                        <form method="get" id="invoice-bulk-payment" action="{{ url('customer/cenpos-invoices') }}">
                            <table id="invoice-table" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    @if ($columns['InvoiceNumber'])
                                        <th> {{ $serialLabel }}</th>
                                    @endif
                                    @if ($columns['InvoicePDF'])
                                        <th> {{ $invoicePdfLabel }}</th>
                                    @endif
                                    @if ($columns['CustomerPONumber'])
                                        <th> {{ $purchaseOrderLabel }}</th>
                                    @endif
                                    @if ($columns['InvoiceStatus'])
                                        <th> {{ $statusLabel }}</th>
                                    @endif
                                    @if ($columns['InvoiceType'])
                                        <th> {{ $typeLabel }}</th>
                                    @endif
                                    @if ($columns['InvoiceDate'])
                                        <th> {{ $dateLabel }}</th>
                                    @endif
                                    @if ($columns['InvoiceAmount'])
                                        <th> {{ $amountLabel }}</th>
                                    @endif
                                    @if ($columns['InvoiceBalance'])
                                        <th> {{ $balanceLabel }}</th>
                                    @endif
                                    @if ($columns['DueDate'])
                                        <th> {{ $dueDateLabel }}</th>
                                    @endif
                                    @if( $columns['DaysOpen'])
                                        <th> {{ $daysOpenLabel }}</th>
                                    @endif
                                    @if ($columns['ShipSignPDF'])
                                        <th> {{ $shipSignPdfLabel }}</th>
                                    @endif
                                    @if (customer(true)->can('invoices.pay') && config('amplify.payment.allow_payments'))
                                        <th class="text-center">Pay</th>
                                    @endif
                                    @if (customer(true)->canAny(['invoices.view', 'invoices.pay']))
                                        <th>Actions</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoiceSummary ?? [] as $iteration => $summary)
                                    @php $amount = floatval($summary['InvoiceBalance'] ?? 0) @endphp
                                    <tr>
                                        @if ($columns['InvoiceNumber'])
                                            <td class="py-1">
                                                <a class="text-decoration-none" href="{{ route('frontend.invoices.show',['invoice' => $summary['InvoiceNumber'], 'status' => $summary['InvoiceStatus'], 'suffix' => $summary['InvoiceSuffix'] ?? '']) }}">
                                                    {{ $formatInvoiceNumber($summary) }}
                                                </a>
                                            </td>
                                        @endif
                                        @if ($columns['InvoicePDF'])
                                            <td class="text-center py-1">
                                                @if(!empty($summary['InvoiceNumber']))
                                                    @php
                                                        $type = $summary['InvoiceType'] === 'DC' ? 'R' : 'I';
                                                    @endphp
                                                    <a href="{{ route('frontend.invoices.document.download', [$type, $summary['InvoiceNumber']])  }}">
                                                        <img
                                                            src="{{ assets_image('images/pdf-download.png') }}"
                                                            alt="{{ $invoicePdfLabel }}"
                                                            style="height: 32px; width: auto">
                                                    </a>
                                                @endif
                                            </td>
                                        @endif
                                        @if ($columns['CustomerPONumber'])
                                            <td class="py-1">{{ !empty($summary['CustomerPONumber']) ? $summary['CustomerPONumber'] : 'N/A' }}</td>
                                        @endif
                                        @if ($columns['InvoiceStatus'])
                                            <td class="py-1">{{ $summary['InvoiceStatus'] ?? '' }}</td>
                                        @endif
                                        @if ($columns['InvoiceType'])
                                            <td class="py-1">{{ $summary['InvoiceType'] ?? '' }}</td>
                                        @endif
                                        @if ($columns['InvoiceDate'])
                                            <td class="py-1" data-order="{{ $summary['InvoiceDate'] }}"
                                                style="width:100px">
                                                {{ carbon_date($summary['InvoiceDate']) }}</td>
                                        @endif
                                        @if ($columns['InvoiceAmount'])
                                            <td class="text-right py-1">{{ price_format($summary['InvoiceAmount'] ?? 0) }}</td>
                                        @endif
                                        @if ($columns['InvoiceBalance'])
                                            <td class="text-right py-1">{{ price_format($summary['InvoiceBalance'] ?? 0) }}</td>
                                        @endif
                                        @if ($columns['DueDate'])
                                            <td class="py-1" data-order="{{ $summary['DueDate'] }}"
                                                style="width:100px">
                                                {{ carbon_date($summary['InvoiceDueDate']) }}</td>
                                        @endif
                                        @if( $columns['DaysOpen'])
                                            <td class="text-center py-1">{{ $summary['DaysOpen'] ?? '' }}</td>
                                        @endif
                                        @if ($columns['ShipSignPDF'])
                                            <td class="text-center py-1">
                                                @if(!empty($summary['OrderNumber']))
                                                    <a href="{{ route('frontend.invoices.document.download', ['P', $summary['OrderNumber']])  }}">
                                                        <img
                                                            src="{{ assets_image('images/pdf-download.png') }}"
                                                            alt="{{ $shipSignPdfLabel }}"
                                                            style="height: 32px; width: auto">
                                                    </a>
                                                @endif
                                            </td>
                                        @endif
                                        @if (config('amplify.payment.allow_payments'))
                                            <td class="text-center py-1">
                                                @if ($amount > 0 && $summary['AllowArPayments'] === 'Yes')
                                                    <input type="checkbox" name="invoices[]" class="form-control-lg"
                                                           value="{{ $summary['InvoiceNumber'] }}"
                                                           data-amount="{{ $amount }}"
                                                           onchange="addInvoiceToPayment(this)">
                                                @endif
                                            </td>
                                        @endif
                                        @if (customer(true)->canAny(['invoices.view', 'invoices.pay']))
                                            <td class="flex-column justify-content-center m-0">
                                                @if (customer(true)->can('invoices.view'))
                                                    <a class="btn btn-outline-warning btn-sm text-decoration-none m-0"
                                                       href="{{ route('frontend.invoices.show',['invoice' => $summary['InvoiceNumber'], 'status' => $summary['InvoiceStatus'], 'suffix' => $summary['InvoiceSuffix'] ?? '']) }}">
                                                        <i class="icon-eye mr-1"></i> Details
                                                    </a>
                                                @endif
                                                @if (customer(true)->can('invoices.pay') && config('amplify.payment.allow_payments'))
                                                    @if ($amount > 0 && $summary['AllowArPayments'] === 'Yes')
                                                        <a href="{{ route('customer.cenpos-invoices-pay.index', ['invoices[]' => $summary['InvoiceNumber']]) }}"
                                                           class="btn btn-primary btn-sm d-block mr-0 mt-2">
                                                            <i class="icon-usd mr-1"></i> Pay
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </x-site.data-table-wrapper>
                </div>
            </div>
        </div>
    </div>
</div>
@pushonce('footer-script')
    <script>
        const ORDER_DATE_RANGER = '#created_date_range';
        var invoices = [];
        var index;

        function redirectToInvoicePayment(e) {
            e.preventDefault();
            document.getElementById('invoice-bulk-payment').submit();
        }


        $(document).ready(function() {
            var startDate = $('#created_start_date').val();
            var endDate = $('#created_end_date').val();

            initOrderCreatedDateRangePicker(startDate, endDate);
        });
    </script>
@endpushonce

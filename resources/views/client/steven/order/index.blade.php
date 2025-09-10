<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">

            @php
                // Capture GET parameters to keep values after submit
                $orderNo = request('orderNo', '');
                $poNo = request('poNo', '');
                $startEntryDate = request('startEntryDate', '');
                $endEntryDate = request('endEntryDate', '');
                $types = request('types', []); // array for checkboxes
                $statusesInput = request('statuses', []); // ["1,2", "3,4"]
                $statuses = collect($statusesInput)
                    ->flatMap(fn($s) => explode(',', $s)) // → ["1","2","3","4"]
                    ->toArray();
                $holdFlg = request('holdFlg', false);
            @endphp


            <form id="searchForm" class="p-2 border bg-light" method="get" action="{{ url()->current() }}">
                <div class="form-row">
                    <!-- ORDER/QUOTE INFORMATION -->
                    <div class="col-md-4">
                        <h6 class="mb-2">ORDER/QUOTE INFORMATION</h6>
                        <div class="form-group mb-2">
                            <label class="small mb-1">Order/Quote Number</label>
                            <input type="text" name="orderNo" class="form-control form-control-sm"
                                value="{{ $orderNo }}" placeholder="XXXXXX-XX">
                        </div>
                        <div class="form-group mb-2">
                            <label class="small mb-1">Purchase Order Number</label>
                            <input type="text" name="poNo" class="form-control form-control-sm"
                                placeholder="Enter PO Number" value="{{ $poNo }}">
                        </div>
                    </div>


                    <!-- ENTRY DATE -->
                    <div class="col-md-4">
                        <h6 class="mb-2">ENTRY DATE</h6>
                        <div class="form-group mb-2">
                            <label class="small mb-1">FROM:</label>
                            <input type="text" name="startEntryDate" id="fromDate"
                                class="form-control form-control-sm" placeholder="Select start date" readonly
                                value="{{ $startEntryDate }}">
                        </div>
                        <div class="form-group mb-2">
                            <label class="small mb-1">TO:</label>
                            <input type="text" name="endEntryDate" id="toDate"
                                class="form-control form-control-sm" placeholder="Select end date" readonly
                                value="{{ $endEntryDate }}">
                        </div>
                        <button type="button" id="clearDates" class="btn btn-outline-secondary btn-sm">Clear</button>
                    </div>

                    <!-- TYPE -->
                    <div class="col-md-2">
                        <h6 class="mb-2">TYPE</h6>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="types[]" value="SO" id="order"
                                {{ in_array('SO', $types) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="order">Order</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="types[]" value="QU" id="quote"
                                {{ in_array('QU', $types) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="quote">Quote</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="types[]" value="RM" id="return"
                                {{ in_array('RM', $types) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="return">Return</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="types[]" value="CR" id="credit"
                                {{ in_array('CR', $types) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="credit">Credit</label>
                        </div>
                    </div>

                    <!-- ORDER STATUS -->
                    <div class="col-md-2">
                        <h6 class="mb-2">ORDER STATUS</h6>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="statuses[]" value="1,2"
                                id="onorder"
                                {{ in_array('1', $statuses) || in_array('2', $statuses) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="onorder">On Order</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="statuses[]" value="3,4"
                                id="shipped"
                                {{ in_array('3', $statuses) || in_array('4', $statuses) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="shipped">Shipped/Invoice</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="holdFlg" value="true"
                                id="hold_flg" {{ $holdFlg ? 'checked' : '' }}>
                            <label class="form-check-label small" for="hold_flg">Order Held</label>
                        </div>

                        <!-- Search Button placed here -->
                        <button type="submit" class="btn btn-primary btn-sm"
                            style="margin-top: 60px !important">SEARCH</button>
                    </div>
                </div>
            </form>
            <!-- Top-right button wrapper -->

            <div id="buttons_wrapper"></div>

            <table class="table table-bordered table-striped table-hover" id="order-table">
                <thead>
                    <tr>
                        <th>{{ __('Order/Quote Number') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Purchase Order Number') }}</th>
                        <th>{{ __('Warehouse') }}</th>
                        <th>{{ __('Entry Date') }}</th>
                        <th>{{ __('Invoice Date') }}</th>
                        <th>{{ __('Order/Quote Value') }}</th>
                        <th>{{ __('Shipped Amount') }}</th>
                        <th>{{ __('Invoice Amount') }}</th>
                        @if (customer(true)->can('order.view'))
                            <th>{{ __('Action') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>
                                <a class="text-medium navi-link"
                                    href="{{ route('frontend.orders.show', [$order['OrderNumber'], 'suffix' => $order['OrderSuffix']]) }}">
                                    {{ $order['OrderNumber'] }}-{{ $order['OrderSuffix'] }}
                                </a>
                            </td>
                            <td>
                                {{ $order['OrderType'] }}
                            </td>
                            <td>
                                <span class="text-info">
                                    @if (in_array($order['OrderStatus'], ['Invoiced']))
                                        Shipped/Invoiced
                                    @else
                                        {{ $order['OrderStatus'] ?? '' }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                {{ $order['CustomerPurchaseOrdernumber'] ?? '' }}
                            </td>
                            <td>
                                {{ $order['WarehouseID'] ?? '' }}
                            </td>
                            <td class="text-center" data-order="{{ $order['EntryDate'] }}">
                                {{ carbon_date($order['EntryDate']) }}
                            </td>
                            <td class="text-center" data-order="{{ $order['InvoiceDate'] }}">
                                {{ carbon_date($order['InvoiceDate']) }}
                            </td>
                            <td class="text-right">
                                <span class="text-medium">{{ price_format($order['TotalOrderValue'] ?? 0) }}</span>
                            </td>
                            <td class="text-right">
                                <span class="text-medium">
                                    @unless (in_array($order['OrderStatus'], ['Ordered', 'Quote']))
                                        {{ price_format($order['ItemSalesAmount'] ?? 0) }}
                                    @endunless
                                </span>
                            </td>
                            <td class="text-right">
                                <span class="text-medium">
                                    @unless (in_array($order['OrderStatus'], ['Ordered', 'Quote']))
                                        {{ price_format($order['InvoiceAmount'] ?? 0) }}
                                    @endunless
                                </span>
                            </td>
                            @if (customer(true)->can('order.view'))
                                <td class="d-flex flex-column justify-content-center m-0">
                                    <a class="btn btn-outline-primary btn-sm text-decoration-none mb-1"
                                        href="{{ route('frontend.orders.show', [$order['OrderNumber'], 'suffix' => $order['OrderSuffix']]) }}">
                                        <i class="icon-eye mr-1"></i>
                                        View Items
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@push('custom-style')
    <style>
        .dt-buttons {
                float: right !important;
            }
        /* Make the search form compact */
        #searchForm {
            margin-bottom: 10px;
            /* gap between form & table */
            padding: 10px;
            /* smaller padding inside */
        }

        /* Smaller inputs for date & PO */
        #searchForm #fromDate,
        #searchForm #toDate {
            max-width: 140px;
            /* adjust date field width */
        }

        #searchForm input[name="orderNo"],
        #searchForm input[placeholder="Enter PO Number"] {
            width: 200px;
            /* same width for both */
        }

        /* Compact form elements */
        #searchForm .form-control {
            height: calc(1.5em + 0.5rem + 2px);
            padding: .25rem .5rem;
            font-size: 0.875rem;
        }

        /* Reduce space in headings and labels */
        #searchForm h6 {
            font-size: 0.9rem;
            margin-bottom: .5rem;
        }

        #searchForm label {
            font-size: 0.8rem;
            margin-bottom: .2rem;
        }
    </style>
@endpush

@push('plugin-style')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@pushonce('footer-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- DataTables & Buttons -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        $(function() {
            // Datepicker initialization
            $('#fromDate').datepicker({
                    format: 'yyyy/mm/dd',
                    autoclose: true,
                    orientation: 'bottom auto',
                    container: 'body'
                })
                .on('changeDate', function(selected) {
                    var minDate = new Date(selected.date.valueOf());
                    $('#toDate').datepicker('setStartDate', minDate);
                });

            $('#toDate').datepicker({
                    format: 'yyyy/mm/dd',
                    autoclose: true
                })
                .on('changeDate', function(selected) {
                    var maxDate = new Date(selected.date.valueOf());
                    $('#fromDate').datepicker('setEndDate', maxDate);
                });

            $('#clearDates').click(function() {
                $('#fromDate').val('').datepicker('update', '');
                $('#toDate').val('').datepicker('update', '');
                $('#fromDate').datepicker('setEndDate', null);
                $('#toDate').datepicker('setStartDate', null);
            });

            // DataTable initialization
            $('#order-table').DataTable({
                paging: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50],
                searching: false,
                ordering: true,
                // dom: '<"top"lB>rt<"bottom"ip><"clear">',
                dom: 'Brt<"bottom d-flex justify-content-between align-items-center"lip>',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: 'Export',
                        className: 'btn btn-sm btn-outline-info',
                        exportOptions: {
                            columns: ':not(:last-child)' // Exclude Actions column
                        }
                    }
                ]
            });
        });
    </script>
@endpushonce

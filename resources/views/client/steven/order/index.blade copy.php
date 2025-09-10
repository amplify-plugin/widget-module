<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="order-table">
                <x-slot name="rightside">
                    <form method="get" action="{{ url()->current() }}" id="order-search-form">
                        <input type="hidden" name="created_start_date"
                            value="{{ request('created_start_date', now(config('app.timezone'))->subDays(7)->format('Y-m-d')) }}"
                            id="created_start_date">

                        <input type="hidden" name="created_end_date"
                            value="{{ request('created_end_date', now(config('app.timezone'))->format('Y-m-d')) }}"
                            id="created_end_date">
                        <div class="d-flex justify-content-center justify-content-md-end">

                            <label class="pr-2">
                                <select name="type" onchange="$('#order-search-form').submit();"
                                    class="form-control  form-control-sm">
                                    <option {{ request('type') == 'so' ? 'selected' : '' }}
                                        value="so">
                                        ORDER
                                    </option>
                                    <option {{ request('type') == 'qu' ? 'selected' : '' }} value="qu">
                                        QUOTE
                                    </option>
                                     <option {{ request('type') == 'rm' ? 'selected' : '' }} value="rm">
                                        RETURN
                                    </option>
                                     <option {{ request('type') == 'cr' ? 'selected' : '' }} value="cr">
                                        CREDIT
                                    </option>
                                </select>
                            </label>

                            <label>
                                <div id="created_date_range" class="border form-control form-control-sm py-2 d-flex">
                                    <i class="mr-2 pe-7s-date" style="font-weight: bold; font-size: 1.25rem;"></i>
                                    <span></span>
                                </div>
                            </label>
                        </div>
                    </form>
                    {{-- 🆕 Order Status dropdown (not part of form) --}}
                    <div class="mt-2 d-flex justify-content-center justify-content-md-end">
                        <label class="mb-0 pl-2">
                            <select id="status_filter" class="form-control form-control-sm">
                                <option value="">{{ __('ORDER STATUS') }}</option>
                                <option value="Ordered">Ordered</option>
                                <option value="Picked">Picked</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Invoiced">Invoiced</option>
                                <option value="Paid">Paid</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </label>
                    </div>
                </x-slot>
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
                                        {{ $order['OrderNumber'] }} @if(isset($order['OrderSuffix']) && $order['OrderSuffix'] !== '')-{{ $order['OrderSuffix'] }}@endif
                                    </a>
                                </td>
                                <td>
                                    {{ $order['OrderType'] }}
                                </td>
                                <td>
                                    <span class="text-info">
                                        @if(in_array($order['OrderStatus'], ['Invoiced']))
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
                                        @unless(in_array($order['OrderStatus'], ['Ordered']))
                                            {{ price_format($order['ItemSalesAmount'] ?? 0) }}
                                        @endunless
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="text-medium">
                                        @unless (in_array($order['OrderStatus'], ['Ordered']))
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
            </x-site.data-table-wrapper>
        </div>
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

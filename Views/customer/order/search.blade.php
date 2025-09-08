@pushonce('plugin-script')
    <script src="{{ asset("packages/select2/dist/js/select2.min.js") }}"></script>
@endpushonce
<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form method="get" action="{{ route('frontend.orders.index') }}" id="order-search-form">
                <div class="row">
                    <div class="col-md-3 my-2 mb-md-0 text-center text-md-left">
                        {!! \Form::rSearch('search', __('Search'), request('search'), false, ['placeholder' => 'Search...']) !!}
                    </div>
                    <div class="col-md-3 my-2 mb-md-0 text-center text-md-left">
                        {!! \Form::rSelectMulti('types[]', __('Type'), ['A' => 'All Orders', 'O' => 'Order', 'Q' => 'Quote', 'R' => 'Return', 'C' => 'Credit'], [], false, ['id' => 'select2-type-dropdown']) !!}
                    </div>
                    <div class="col-md-3 my-2 mb-md-0 text-center text-md-left">
                        {!! \Form::rSelectMulti('status[]', __('Status'), [ 'Ordered' => 'Ordered', 'Picked' => 'Picked', 'Shipped' => 'Shipped',
     'Invoiced' => 'Invoiced', 'Paid' => 'Paid', 'Cancelled' => 'Cancelled'], [], false, ['id' => 'select2-status-dropdown']) !!}
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0 my-2">
                        <input type="hidden" name="created_start_date" value="2025-08-06" id="created_start_date">
                        <input type="hidden" name="created_end_date" value="2025-08-13" id="created_end_date">
                        <div class="form-group">
                            <label>Entry Date(s)</label>
                            <div class="d-flex justify-content-center">
                                <div id="search_date_range" class="border form-control form-control-sm py-2 d-flex" style="height: 44px">
                                    <i class="mr-2 pe-7s-date" style="font-weight: bold; font-size: 1.25rem;"></i>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive pb-4 pb-md-0">
                <table class="table table-bordered table-striped table-hover" id="order-table">
                    <thead>
                    <tr>
                        <th>{{ __('Customer Order #') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Order Reference Number#') }}</th>
                        <th>{{ __('Warehouse') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th>{{ __('Total') }}</th>
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
                                    {{ $order['OrderNumber'] }} @if(isset($order['OrderSuffix']) && $order['OrderSuffix'] !== '')
                                        -{{ $order['OrderSuffix'] }}
                                    @endif
                                </a>
                            </td>
                            <td>
                                @if (isset($order['OrderStatus']))
                                    <span class="text-info">{{ $order['OrderStatus'] ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $order['OrderType'] }}
                            </td>
                            <td>
                                {{ $order['CustomerPurchaseOrdernumber'] ?? '' }}
                            </td>
                            <td>
                                {{ $order['WarehouseID'] ?? '' }}
                            </td>
                            <td class="text-center" data-order="{{ $order['EntryDate'] }}">
                                {{ carbon_date($order['EntryDate']) }}</td>
                            <td class="text-right">
                                <span class="text-medium">{{ price_format($order['TotalOrderValue'] ?? 0) }}</span>
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
            <div class="row mt-2">
                <div class="col-sm-12 col-md-5">
                    <label
                        class="d-flex justify-content-center justify-content-md-start align-items-center"
                        style="font-weight: 200;">
                        Show
                        <select name="per_page"
                                onchange="$('#customer-item-list-search-form').submit();"
                                class="form-control form-control-sm mx-1"
                                style="width: 65px; background-position: 85%">
                            @foreach (getPaginationLengths() as $length)
                                <option value="{{ $length }}"
                                        @if ($length == request('per_page')) selected @endif>
                                    {{ $length }}
                                </option>
                            @endforeach
                        </select>
                        entries
                    </label>
                </div>
                <div class="col-sm-12 col-md-7">
                    {!! $orderPaginate->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@pushonce('footer-script')
    <script>
        const ORDER_DATE_RANGER = '#search_date_range';

        $(document).ready(function() {
            $('#select2-type-dropdown').select2({
                placeholder: 'Select Type(s)',
                allowClear: true,
            });
            $('#select2-status-dropdown').select2({
                placeholder: 'Select Status(s)',
                allowClear: true,
            });

            const startDate = $('#created_start_date').val();

            const endDate = $('#created_end_date').val();

            initOrderCreatedDateRangePicker(startDate, endDate);
        });
    </script>
@endpushonce

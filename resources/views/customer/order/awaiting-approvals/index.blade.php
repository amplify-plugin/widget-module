@php
    push_js(
        '
        const ORDER_DATE_RANGER = \'#created_date_range\';

        $(document).ready(function () {
            var startDate = $("#created_start_date").val();

            var endDate = $("#created_end_date").val();

            initOrderCreatedDateRangePicker(startDate, endDate);
        });
    ',
        'footer-script',
    );
@endphp

<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="order-table">
                <x-slot name="rightside">
                    <form method="get" action="{{ url()->current() }}" id="order-search-form">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <label class="pr-2">
                                <select name="status" onchange="$('#order-search-form').submit();"
                                    class="form-control form-control-sm">
                                    <option value="">All Orders</option>
                                    <option {{ request('status') == 'Pending' ? 'selected' : '' }} value="Pending">
                                        Pending</option>
                                    <option {{ request('status') == 'Awaiting Approval' ? 'selected' : '' }}
                                        value="Awaiting Approval">Awaiting Approval</option>
                                </select>
                            </label>
                        </div>
                    </form>
                </x-slot>

                <table class="table table-bordered table-striped table-hover" id="order-table">
                    <thead>
                        <tr>
                            <th>{{ __('Order Number') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Ordered By') }}</th>
                            <th>{{ __('Status') }}</th>
                            @if (customer(true)->can('order-approval.approve'))
                                <th>{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_awaiting_approval as $obj)
                            <tr>
                                <td>{{ $obj->web_order_number }}</td>
                                <td>{{ carbon_date($obj->created_at) }}</td>
                                <td>{{ $obj->contact->name }}</td>
                                <td>{{ $obj->order_status }}</td>
                                <td class="d-flex flex-column justify-content-center m-0">
                                    <a class="badge btn-info text-decoration-none mb-1"
                                        href="{{ route('frontend.order-awaiting-approvals.show', $obj->id) }}">
                                        <i class="fa fa-eye"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-site.data-table-wrapper>
        </div>
    </div>
</div>

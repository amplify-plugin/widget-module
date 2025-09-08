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

@foreach ($order_waiting_approval as $item)
    <div class="modal fade" id="draftModal{{ $item['OrderNumber'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-sm">
                <form action="{{ route('draft.submit-as-order', $item['OrderNumber'] ?? '#') }}" method="POST"
                    class="d-inline">
                    @csrf
                    <div class="modal-body">
                        <h3 class="text-center">{{ __('Are you sure?') }}</h3>
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="setPositionOffCanvas()">{{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-success" name="delete_user">{{ __('Confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="order-table">

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
                        @foreach ($order_waiting_approval as $obj)
                            <tr>
                                <td>{{ $obj->customerOrder->web_order_number }}</td>
                                <td>{{ carbon_date($obj->customerOrder->created_at) }}</td>
                                <td>{{ $obj->customerOrder->contact->name }}</td>
                                <td>{{ ucfirst($obj->status) }}</td>
                                @if (customer(true)->can('order-approval.approve'))
                                    <td class="d-flex flex-column justify-content-center m-0">
                                        <a class="badge btn-info text-decoration-none mb-1"
                                            href="{{ route('frontend.order-approvals.show', $obj->id) }}">
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
</div>

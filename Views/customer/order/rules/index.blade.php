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

    $perPageOptions = getPaginationLengths();

@endphp

@foreach ($order_rule as $item)
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
                @if (customer(true)->can('order-processing-rules.manage-rules'))
                    <x-slot name="rightside">
                        <form method="get" action="{{ url()->current() }}" id="order-search-form">
                            <div class="d-flex justify-content-center justify-content-md-end">
                                <a href="{{ url()->full() . '/create' }}" class="btn btn-primary btn-sm mr-0 px-4">
                                    New Order Rule
                                </a>
                            </div>
                        </form>
                    </x-slot>
                @endif
                <table class="table table-bordered table-striped table-hover" id="order-table">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Order Rule') }}</th>
                            <th>{{ __('Enabled') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Created') }}</th>
                            @if (customer(true)->can('order-processing-rules.manage-rules'))
                                <th>{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_rule as $rule)
                            <tr>
                                <td>{{ $rule->name }}</td>
                                <td>{{ $rule->orderRule->name ?? '' }}</td>
                                <td>{{ $rule->enabled == 0 ? 'No' : 'Yes' }}</td>
                                <td>{{ $rule->description }}</td>
                                <td>{{ $rule->created_at->diffForHumans() }}</td>
                                @if (customer(true)->can('order-processing-rules.manage-rules'))
                                    <td class="d-flex flex-column justify-content-center m-0">
                                        <a class="badge btn-info text-decoration-none mb-1"
                                            href="{{ url()->full() . '/' . $rule->id . '/edit' }}">
                                            <i class="fa fa-eye"></i>
                                            Edit
                                        </a>
                                        {{-- <a href="{{ route('order-rule.destroy', $rule->id) }}"
                                        class="badge btn-danger btn-delete text-decoration-none mb-1">
                                        <i class="fa fa-trash-alt"></i>
                                        Delete
                                    </a> --}}
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

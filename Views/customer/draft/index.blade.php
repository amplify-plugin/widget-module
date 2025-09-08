<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="order-table">
                <x-slot name="rightside">
                    <form method="get" action="{{ url()->current() }}" id="order-search-form">
                        <input type="hidden" name="created_start_date"
                            value="{{ request('created_start_date', now(config('app.timezone'))->subDays(6)->format('Y-m-d')) }}"
                            id="created_start_date">

                        <input type="hidden" name="created_end_date"
                            value="{{ request('created_end_date', now(config('app.timezone'))->format('Y-m-d')) }}"
                            id="created_end_date">
                    </form>
                </x-slot>
                <table class="table table-bordered table-striped table-hover" id="order-table">
                    <thead>
                        <tr>
                            <th>{{ __('Draft Name') }}</th>
                            <th>{{ __('Web Order #') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Created At') }}</th>
                            @if (customer(true)->can('order.view'))
                                <th>{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    {{ $order->draft_name ?? '' }}
                                </td>
                                <td>
                                    {{ $order->web_order_number ?? '' }}
                                </td>
                                <td class="text-right">
                                    <span class="text-medium">{{ price_format($order->total_amount ?? 0) }}</span>
                                </td>
                                <td class="text-center" data-order="{{ $order->created_at }}">
                                    {{ carbon_date($order->created_at) }}
                                </td>

                                @if (customer(true)->can('order.view'))
                                    <td class="d-flex flex-column justify-content-center m-0">
                                        <a class="btn btn-outline-warning btn-sm text-decoration-none mb-1"
                                            href="{{ route('frontend.drafts.show', $order->id) }}">
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

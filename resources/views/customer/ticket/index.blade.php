<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="ticket-table">
                @if (customer(true)->can('ticket.tickets'))
                    <x-slot name="rightside">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <a href="{{ route('frontend.tickets.create') }}" class="btn btn-success btn-sm mr-0 px-4">
                                <i class="icon-plus"></i>Add New Ticket
                            </a>
                        </div>
                    </x-slot>
                @endif
                <table class="table table-bordered table-striped table-hover" id="ticket-table">
                    <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th width="45%">{{ __('Subject') }}</th>
                        <th>{{ __('Priority') }}</th>
                        <th>{{ __('Last Updated') }}</th>
                        @if (customer(true)->can('ticket.tickets'))
                            <th>{{ __('Options') }}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $iteration = 1;
                    @endphp
                    @foreach ($threads as $key => $thread)
                        @php
                            $ticket = optional(optional($thread->tickets)->first());
                        @endphp
                        <tr>
                            <td scope="row">{{ $iteration }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($ticket->subject ?? '', 60, '...') }}</td>
                            <td>
                                {{ !empty($ticket->priority) ? \Amplify\System\Ticket\Models\Ticket::PRIORITY_LABEL[$ticket->priority] : '' }}
                            </td>
                            <td data-order="{{ $ticket->updated_at->format('Y-m-d H:i:s') }}">
                                {{ carbon_datetime($ticket->updated_at) }}
                            </td>
                            @if (customer(true)->can('ticket.tickets'))
                                <td class="d-flex flex-column justify-content-center m-0">
                                    <a class="btn btn-info text-decoration-none mb-1"
                                       href="{{ route('frontend.tickets.show', $thread->id) }}">
                                        Conversations
                                    </a>
                                </td>
                            @endif
                        </tr>
                        @php
                            $iteration++;
                        @endphp
                    @endforeach
                    </tbody>
                </table>
            </x-site.data-table-wrapper>
        </div>
    </div>
</div>

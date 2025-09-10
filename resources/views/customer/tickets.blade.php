<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="ticket-table">
                <x-slot name="rightside">
                    <div class="d-flex justify-content-center justify-content-md-end">
                        <a href="{{ url('tickets') }}" class="btn btn-primary btn-sm mr-0 px-4">
                            New Ticket
                        </a>
                    </div>
                </x-slot>
                <table class="table table-bordered table-striped table-hover" id="ticket-table">
                    <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th width="45%">{{ __('Subject') }}</th>
                        <th>{{ __('Priority') }}</th>
                        <th>{{ __('Last Updated') }}</th>
                        <th>{{ __('Options') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $iteration = 1
                    @endphp
                    @foreach($threads as $key => $thread)
                        @php
                            $ticket = optional(optional($thread->tickets)->first())
                        @endphp
                        <tr>
                            <td scope="row">{{ $iteration }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($ticket->subject ?? '', 60, '...') }}</td>
                            <td>
                                {{ !empty($ticket->priority) ? \Amplify\System\Backend\Models\Ticket::PRIORITY_LABEL[$ticket->priority] : '' }}
                            </td>
                            <td> {{ carbon_datetime($ticket->updated_at) }}</td>
                            <td class="text-center">
                                <a class="text-center badge btn-primary mb-1 text-decoration-none"
                                   href="{{ url('tickets/' . $thread->id) }}">
                                    Conversations
                                </a>
                            </td>
                        </tr>
                        @php
                            $iteration++
                        @endphp
                    @endforeach
                    </tbody>
                </table>
            </x-site.data-table-wrapper>
        </div>
    </div>
</div>

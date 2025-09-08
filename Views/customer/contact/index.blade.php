@php
    $iteration = 1;

    $clearUrl = url()->current();
        $keep = request()->except(['search', 'page']); // keep everything except search & page
        if (!empty($keep)) {
            $clearUrl .= '?' . http_build_query($keep);
        }

@endphp

@foreach ($contacts as $contact)
    {{-- <div class="modal fade" id="exampleModal{{ $contact->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-sm">
                <form action="" method="POST" class="d-inline">
                    @method('delete')
                    @csrf
                    <div class="modal-body">
                        <h3 class="text-center">{{__('Are you sure?')}}</h3>
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                onclick="setPositionOffCanvas()">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-danger" name="delete_user">{{__('Delete')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-sm">
                <form action="#" method="POST" class="d-inline" id="delete-form">
                    @method('delete')
                    @csrf
                    <div class="modal-body">
                        <h3 class="text-center">{{ __('Are you sure?') }}</h3>
                    </div>
                    <div class="modal-footer justify-content-around pt-0 border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="setPositionOffCanvas()">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}" autocomplete="off">
                <div class="row">
                    <div class="col-md-6 my-2 mb-md-0">
                        <div class="d-flex justify-content-center justify-content-md-start">
                            <div class="d-flex justify-content-center justify-content-md-start align-items-center">
                                <label class="mb-0">
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                                </label>

                                @if (filled(request('search')))
                                    <a href="{{ $clearUrl }}" class="btn btn-link btn-sm ml-2 p-0" id="clear-search">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (customer(true)->can('contact-management.add'))
                        <div class="col-md-6 mb-2 mb-md-0">
                            <div class="d-flex justify-content-center justify-content-md-end">
                                <a class="btn btn-sm btn-primary mr-0" href="{{ route('frontend.contacts.create') }}">
                                    <i class="icon-plus"></i> Add New Contact
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="table-responsive-md pb-4 pb-md-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover my-1">
                                            <thead>
                                                <tr>
                                                    <th width="20">#</th>

                                                    @if ($columns['photo'])
                                                        <th>{{ $photoLabel ?? 'Photo' }}</th>
                                                    @endif

                                                    @if ($columns['name'])
                                                        <th>{{ $nameLabel ?? 'Name' }}</th>
                                                    @endif

                                                    @if ($columns['email'])
                                                        <th>{{ $emailLabel ?? 'Email Address' }}</th>
                                                    @endif

                                                    @if ($columns['phone'])
                                                        <th>{{ $phoneLabel ?? 'Phone' }}</th>
                                                    @endif

                                                    @if ($columns['role'])
                                                        <th>{{ $roleLabel ?? 'Role(s)' }}</th>
                                                    @endif

                                                    @if ($columns['order_limit'])
                                                        <th>{{ $orderLimitLabel ?? 'Order Limit' }}</th>
                                                    @endif

                                                    @if ($columns['daily_budget_limit'])
                                                        <th>{{ $dailyBudgetLimitLabel ?? 'Daily Limit' }}</th>
                                                    @endif

                                                    @if ($columns['monthly_budget_limit'])
                                                        <th>{{ $monthlyBudgetLimitLabel ?? 'Monthly Limit' }}</th>
                                                    @endif
                                                    @if (customer(true)->canAny(['contact-management.view', 'contact-management.update', 'contact-management.remove']))
                                                        <th style="width: 125px">{{ __('Actions') }}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($contacts as $key => $contact)
                                                    <tr>
                                                        <th scope="row">{{ $iteration }}</th>
                                                        @if ($columns['photo'])
                                                            <td>
                                                                <div class="user-ava"
                                                                    style="height: 44px; width: 44px;">
                                                                    <img src="{{ assets_image($contact->photo) }}"
                                                                        alt="{{ $contact->name ?? '' }}"
                                                                        class="rounded-circle"
                                                                        style="width: 100%; height: 100%; object-fit: contain">
                                                                </div>
                                                            </td>
                                                        @endif

                                                        @if ($columns['name'])
                                                            <td>{{ $contact->name ?? 'N/A' }}</td>
                                                        @endif

                                                        @if ($columns['email'])
                                                            <td>{{ $contact->email ?? 'N/A' }}</td>
                                                        @endif

                                                        @if ($columns['phone'])
                                                            <td>{{ $contact->phone ?? 'N/A' }}</td>
                                                        @endif

                                                        @if ($columns['role'])
                                                            <td class="text-center">
                                                                @if ($contact->roles->isEmpty())
                                                                    -
                                                                @else
                                                                    {{ implode(', ', $contact->roles->pluck('name')->toArray()) }}
                                                                @endif
                                                            </td>
                                                        @endif

                                                        @if ($columns['order_limit'])
                                                            <td>{{ $contact->order_limit ?? 'N/A' }}</td>
                                                        @endif

                                                        @if ($columns['daily_budget_limit'])
                                                            <td>{{ $contact->daily_budget_limit ?? 'N/A' }}</td>
                                                        @endif

                                                        @if ($columns['monthly_budget_limit'])
                                                            <td>{{ $contact->monthly_budget_limit ?? 'N/A' }}</td>
                                                        @endif
                                                        @if (checkPermissionLength(['contact-management.view', 'contact-management.update', 'contact-management.remove']) > 1)
                                                            <td class="text-right" style="width: 125px">
                                                                <div class="btn-group m-0">
                                                                    <button type="button"
                                                                        class="btn btn-outline-warning mx-0 dropdown-toggle btn-sm"
                                                                        data-toggle="dropdown" aria-expanded="false">
                                                                        Actions
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        @if (customer(true)->can('contact-management.view'))
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('frontend.contacts.show', $contact->id) }}">
                                                                                <i class="icon-eye mr-1"></i> Preview
                                                                            </a>
                                                                        @endif
                                                                        @if (customer(true)->can('contact-management.update'))
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('frontend.contacts.edit', $contact->id) }}">
                                                                                <i class="icon-paper-clip mr-1"></i>
                                                                                Edit
                                                                            </a>
                                                                        @endif
                                                                        @if (customer(true)->can('contact-management.remove'))
                                                                            <a href="{{ route('frontend.contacts.destroy', $contact->id) }}"
                                                                                class="delete-modal dropdown-item"
                                                                                onclick="setPositionOffCanvas(false); updateModal(this)"
                                                                                data-toggle="modal"
                                                                                data-target="#delete-modal"><i
                                                                                    class="icon-trash mr-1"></i> Delete
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @else
                                                            @if (customer(true)->can('contact-management.view'))
                                                                @include(
                                                                    'widget::customer.permission-component',
                                                                    [
                                                                        'data' => $contact,
                                                                        'label' => 'Preview',
                                                                        'route' => route(
                                                                            'frontend.contacts.show',
                                                                            $contact->id),
                                                                    ]
                                                                )
                                                            @endif
                                                            @if (customer(true)->can('contact-management.update'))
                                                                @include(
                                                                    'widget::customer.permission-component',
                                                                    [
                                                                        'data' => $contact,
                                                                        'label' => 'Edit',
                                                                        'route' => route(
                                                                            'frontend.contacts.edit',
                                                                            $contact->id),
                                                                    ]
                                                                )
                                                            @endif
                                                            @if (customer(true)->can('contact-management.remove'))
                                                                @include(
                                                                    'widget::customer.permission-component',
                                                                    [
                                                                        'data' => $contact,
                                                                        'label' => 'Delete',
                                                                        'route' => route(
                                                                            'frontend.contacts.destroy',
                                                                            $contact->id),
                                                                        'is_delete' => true,
                                                                    ]
                                                                )
                                                            @endif
                                                        @endif
                                                    </tr>
                                                    @php
                                                        $iteration++;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="20" class="text-center">
                                                            No data available in table
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
                                            style="width: 80px; background-position: 85%">
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
                                    {!! $contacts->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function updateModal(e) {
        let element = $(e);
        $('#delete-form').attr('action', element.attr('href'));
    }
</script>

@push('internal-script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('select[name=list_type]').change(function () {
                $('#order_list_filter_form').submit();
            });
            $('#sorting').change(function () {
                $('#order_list_filter_form').submit();
            });
        });
    </script>
@endpush


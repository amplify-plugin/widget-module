@php
    $iteration = 1;

    push_js(
        "
    document.addEventListener('DOMContentLoaded', function () {
        $('select[name=list_type]').change(function () {
            $('#order_list_filter_form').submit();
        });
        $('#sorting').change(function () {
            $('#order_list_filter_form').submit();
        });
    });",
        'internal-script',
    );

@endphp

<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-between justify-content-md-start align-items-center gap-3">
                        <label class="mb-0">Filters: </label>
                        <input type="search" name="customer_name" class="form-control form-control-sm"
                            placeholder="Customer name...." value="{{ request('customer_name') }}">
                        <input type="search" name="contact_name" class="form-control form-control-sm"
                            placeholder="Contact name...." value="{{ request('contact_name') }}">
                        <input type="search" name="contact_email" class="form-control form-control-sm"
                            placeholder="Contact Email...." value="{{ request('contact_email') }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary mr-0">
                        <i class="icon-search"></i> Find
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive-md pb-4 pb-md-0">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-striped table-hover my-1">
                                        <thead>
                                            <tr>
                                                @if ($columns['photo'])
                                                    <th>{{ $photoLabel ?? 'Photo' }}</th>
                                                @endif

                                                @if ($columns['customer_name'])
                                                    <th>{{ $customerLabel ?? 'Name' }}</th>
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
                                                @if (customer(true)->canAny(['login-management.manage-logins', 'login-management.impersonate']))
                                                    <th style="width: 125px">{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($contacts as $key => $contact)
                                                <tr>
                                                    @if ($columns['photo'])
                                                        <td>
                                                            <div class="user-ava" style="height: 44px; width: 44px;">
                                                                <img src="{{ assets_image($contact->photo) }}"
                                                                    alt="{{ $contact->name ?? '' }}"
                                                                    class="rounded-circle"
                                                                    style="width: 100%; height: 100%; object-fit: contain">
                                                            </div>
                                                        </td>
                                                    @endif

                                                    @if ($columns['customer_name'])
                                                        <td>{{ @$contact->customer->customer_name ?? 'N/A' }}</td>
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

                                                    @if (checkPermissionLength(['login-management.manage-logins', 'login-management.impersonate']) > 1)
                                                        <td class="text-right" style="width: 125px">
                                                            <div class="btn-group m-0">
                                                                <button type="button"
                                                                    class="btn btn-outline-warning mx-0 dropdown-toggle btn-sm"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if (customer(true)->can('login-management.manage-logins'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('frontend.contact-logins.edit', $contact->id) }}">
                                                                            <i class="icon-paper-clip mr-1"></i>
                                                                            Manage Login
                                                                        </a>
                                                                    @endif
                                                                    @if (customer(true)->can('login-management.impersonate'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('frontend.contact-logins.impersonate', $contact->id) }}">
                                                                            <i class="icon-open mr-1"></i>
                                                                            Impersonate
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @else
                                                        @if (customer(true)->can('login-management.manage-logins'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $contact,
                                                                    'label' => 'Manage Login',
                                                                    'route' => route(
                                                                        'frontend.contact-logins.edit',
                                                                        $contact->id),
                                                                ]
                                                            )
                                                        @endif
                                                        @if (customer(true)->can('login-management.impersonate'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $contact,
                                                                    'label' => 'Impersonate',
                                                                    'route' => route(
                                                                        'frontend.contact-logins.impersonate',
                                                                        $contact->id),
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
                                                    <td colspan="5" class="text-center">
                                                        No data available in table
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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

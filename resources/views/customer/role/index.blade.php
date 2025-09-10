@php
    $iteration = 1;
@endphp

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
<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}">
                <div class="row">
                    <div class="col-md-6 my-2 mb-md-0">
                        <div class="d-flex justify-content-center justify-content-md-start">
                            <label aria-label="search">
                                <input type="search" aria-label="search" name="search" class="form-control form-control-sm"
                                    placeholder="Search...." value="{{ $search }}">
                            </label>
                        </div>
                    </div>
                    @if (customer(true)->can('role.manage'))
                        <div class="col-md-6 mb-2 mb-md-0">
                            <div class="d-flex justify-content-center justify-content-md-end">
                                <a class="btn btn-sm btn-success mr-0" href="{{ route('frontend.roles.create') }}">
                                    <i class="icon-plus"></i> Add Role
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="table-responsive-md pb-4 pb-md-0">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-striped table-hover my-1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Assigned') }}</th>
                                                @if (customer(true)->canAny(['role.view', 'role.manage']))
                                                    <th>{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($roles as $key => $role)
                                                <tr>
                                                    <th scope="row">{{ $iteration }}</th>
                                                    <td>{{ $role->name }}</td>
                                                    <td>{{ $role->users->count() }}</td>
                                                    @if (checkPermissionLength(['role.view', 'role.manage']) > 1)
                                                        <td class="text-right" width="125">
                                                            <div class="btn-group m-0">
                                                                <button type="button"
                                                                    class="btn mx-0 btn-outline-warning dropdown-toggle btn-sm"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if (customer(true)->can('role.view'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('frontend.roles.show', $role->id) }}">
                                                                            <i class="icon-eye mr-1"></i> Preview
                                                                        </a>
                                                                    @endif
                                                                    @if (customer(true)->can('role.manage'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('frontend.roles.edit', $role->id) }}">
                                                                            <i class="icon-paper-clip mr-1"></i> Edit
                                                                        </a>
                                                                    @endif
                                                                    @if (customer(true)->can('role.manage'))
                                                                        <a href="{{ route('frontend.roles.destroy', $role->id) }}"
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
                                                        @if (customer(true)->can('role.view'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $role,
                                                                    'label' => 'Preview',
                                                                    'route' => route(
                                                                        'frontend.roles.show',
                                                                        $role->id),
                                                                ]
                                                            )
                                                        @endif
                                                        @if (customer(true)->can('role.manage'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $role,
                                                                    'label' => 'Edit',
                                                                    'route' => route(
                                                                        'frontend.roles.edit',
                                                                        $role->id),
                                                                ]
                                                            )
                                                        @endif
                                                        @if (customer(true)->can('role.manage'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $role,
                                                                    'label' => 'Delete',
                                                                    'route' => route(
                                                                        'frontend.roles.destroy',
                                                                        $role->id),
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
                                            style="width: 65px; background-position: 85%;">
                                            @foreach ($perPageOptions as $length)
                                                <option value="{{ $length }}"
                                                    @if ($length == $perPage) selected @endif>
                                                    {{ $length }}
                                                </option>
                                            @endforeach
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    {!! $roles->links() !!}
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

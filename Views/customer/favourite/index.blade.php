<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form id="customer-item-list-search-form" method="get" action="{{ route('frontend.favourites.index') }}">
                {{-- <input type="hidden" name="filtered_start_date"
                       value="{{ request('filtered_start_date',now(config('app.timezone'))->subDays(29)->format('Y-m-d')) }}"
                       id="filtered_start_date">

                <input type="hidden" name="filtered_end_date"
                       value="{{ request('filtered_end_date', now(config('app.timezone'))->format('Y-m-d')) }}"
                       id="filtered_end_date"> --}}

                <div class="d-flex justify-content-between row">
                    <div class="col-md-6 d-flex mb-md-0 my-2 sp-buttons">
                        <label>
                            <input type="text" name="search" id="search"
                                class="form-control form-control-sm" placeholder="Search...."
                                value="{{ request('search') }}">
                        </label>

                        @if (!empty(request()->all()))
                            <label>
                                <a class="border btn btn-sm border" href="{{ Request::url() }}">
                                    Reset
                                </a>
                            </label>
                        @endif
                    </div>
                    <div class="col-md-6 mb-2 mb-md-0 my-2">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            @if (customer(true)->can('favorites.manage-personal-list') && customer(true)->can('favorites.use-global-list'))
                                <label>
                                    <select name="type" onchange="$('#customer-item-list-search-form').submit();"
                                        class="form-control  form-control-sm">
                                        <option {{ request('type') == '' ? 'selected' : '' }} value="">All Type
                                        </option>
                                        <option {{ request('type') == 'personal' ? 'selected' : '' }} value="personal">
                                            Personal
                                        </option>
                                        <option {{ request('type') == 'global' ? 'selected' : '' }} value="global">
                                            Global
                                        </option>

                                    </select>
                                </label>
                            @endif

                            {{-- <label class="ml-0 ml-md-4">
                                <div id="filtered_date_range" class="border form-control form-control-sm py-2 d-flex">
                                    <i class="mr-2 pe-7s-date"
                                       style="font-weight: bold; font-size: 1.25rem;"></i><span></span>
                                </div>
                            </label> --}}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive-md pb-4 pb-md-0">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-striped table-hover my-1">
                                        <thead>
                                            <tr>
                                                <th width="20">{{ sort_link('id', '#') }}</th>
                                                @if ($columns['list_type'])
                                                    <th>{{ sort_link('list_type', 'Type') }}</th>
                                                @endif

                                                @if ($columns['name'])
                                                    <th class="35%">{{ sort_link('name', 'Name') }}</th>
                                                @endif


                                                @if ($columns['description'])
                                                    <th>{{ __('Description') }}</th>
                                                @endif

                                                @if ($columns['product_count'])
                                                    <th>{{ __('Items') }}</th>
                                                @endif

                                                <th width="125">{{ sort_link('updated_at', 'Last Changed') }}</th>
                                                @if (customer(true)->canAny([
                                                        'favorite.allow-details',
                                                        'favorite.allow-personal-details',
                                                        'favorite.allow-update',
                                                        'favorite.allow-personal-update',
                                                        'favorite.allow-delete',
                                                        'favorite.allow-personal-delete',
                                                    ]))
                                                    <th>{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($orderLists as $key => $orderList)
                                                <tr>
                                                    <th scope="row">
                                                        {{ $orderLists->firstItem() + $key }}
                                                    </th>

                                                    @if ($columns['list_type'])
                                                        <td width="100">{{ ucwords($orderList->list_type) }}</td>
                                                    @endif

                                                    @if ($columns['name'])
                                                        <td>{{ $orderList->name }}</td>
                                                    @endif


                                                    @if ($columns['description'])
                                                        <td>
                                                            <p class="cs-truncate-1">
                                                                {{ $orderList->description }}
                                                            </p>
                                                        </td>
                                                    @endif

                                                    @if ($columns['product_count'])
                                                        <td> {{ $orderList->orderListItems->count() }}</td>
                                                    @endif

                                                    <td>{{ carbon_date($orderList->updated_at) }}</td>
                                                    @if (checkPermissionLength([
                                                            'favorites.manage-global-list',
                                                            'favorites.use-global-list',
                                                            'favorites.manage-personal-list',]) > 1)
                                                        <td width="125">
                                                            <div class="btn-group m-0">
                                                                <button type="button"
                                                                    class="btn btn-outline-warning mx-0 dropdown-toggle btn-sm"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if (customer(true)->can('favorites.manage-global-list') || customer(true)->can('favorites.manage-personal-list'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('frontend.favourites.show', $orderList->id) }}">
                                                                            <i class="icon-bag mr-1"></i> View
                                                                        </a>
                                                                    @endif
                                                                    <!-- @if (customer(true)->can('favorites.manage-personal-list') || customer(true)->can('favorites.manage-personal-list'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('frontend.favourites.edit', $orderList->id) }}">
                                                                            <i class="icon-paper-clip mr-1"></i> Edit
                                                                        </a>
                                                                    @endif -->
                                                                    @if (customer(true)->can('favorites.manage-global-list') || customer(true)->can('favorites.manage-personal-list'))
                                                                        <a class="dropdown-item delete-modal"
                                                                            href="{{ route('frontend.favourites.destroy', $orderList->id) }}"
                                                                            data-target="#delete-modal"
                                                                            data-toggle="modal"
                                                                            onclick="setFormAction(this)">
                                                                            <i class="icon-trash mr-1"></i> Delete
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @else
                                                        @if (customer(true)->can('favorites.manage-global-list') || customer(true)->can('favorites.manage-personal-list'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $orderList,
                                                                    'label' => 'View',
                                                                    'route' => route(
                                                                        'frontend.favourites.show',
                                                                        $orderList->id),
                                                                ]
                                                            )
                                                        @endif
                                                        @if (customer(true)->can('favorites.manage-personal-list') || customer(true)->can('favorites.manage-personal-list'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $orderList,
                                                                    'label' => 'View',
                                                                    'route' => route(
                                                                        'frontend.favourites.edit',
                                                                        $orderList->id),
                                                                ]
                                                            )
                                                        @endif
                                                        @if (customer(true)->can('favorites.manage-global-list') || customer(true)->can('favorites.manage-personal-list'))
                                                            <a class="dropdown-item delete-modal"
                                                                href="{{ route('frontend.favourites.destroy', $orderList->id) }}"
                                                                data-target="#delete-modal" data-toggle="modal"
                                                                onclick="setFormAction(this)">
                                                                <i class="icon-trash mr-1"></i> Delete
                                                            </a>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">
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
                                            style="width: 80px; background-position: 85%;">
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
                                    {!! $orderLists->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('html-default')
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger d-flex align-items-center p-3">
                    <h5 class="modal-title text-white">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" class="d-inline" id="form-delete">
                    @method('delete')
                    @csrf
                    <div class="modal-body">
                        <p class="text-center">{{ __('Are you sure you want to delete this item?') }}</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger" name="delete_user">{{ __('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('internal-script')
    <script>
        function setFormAction(e) {
            const form = $('#form-delete');
            const deleteBtn = $(e);

            form.attr('action', deleteBtn.attr('href'));
        }
    </script>
@endpush

@push('internal-style')
    <style>
        .options > * {
            margin-bottom: 3px !important;
        }
    </style>
@endpush

@push('footer-script')
    <script>
        const CUSTOMER_LIST_DATE_RANGE = '#filtered_date_range';

        $(document).ready(function () {
            var startDate = $("#filtered_start_date").val();

            var endDate = $("#filtered_end_date").val();

            initcustomerItemListDateRangePicker(startDate, endDate);
        });

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        $(document).on("keyup change", "#search", delay(function(e) {
            $("#customer-item-list-search-form").submit();
            // console.log("Time elapsed!", this.value);
        },500));
    </script>
@endpush

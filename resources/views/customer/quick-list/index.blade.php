<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form id="customer-item-list-search-form" method="get" action="{{ route('frontend.quick-lists.index') }}">
                <input type="hidden" name="filtered_start_date"
                    value="{{ request('filtered_start_date', now(config('app.timezone'))->subDays(29)->format('Y-m-d')) }}"
                    id="filtered_start_date">

                <input type="hidden" name="filtered_end_date"
                    value="{{ request('filtered_end_date', now(config('app.timezone'))->format('Y-m-d')) }}"
                    id="filtered_end_date">

                <div class="d-flex justify-content-between row">
                    <div class="col-md-4 d-flex mb-md-0 my-2 sp-buttons">
                        <label>
                            <input type="search" name="search" id="search"
                                onkeypress="return event.keyCode !== 13;" class="form-control form-control-sm"
                                placeholder="Search...." value="{{ request('search') }}">
                        </label>
                    </div>
                    <div class="col-md-8 mb-2 mb-md-0 my-2">
                        <div class="d-flex justify-content-center justify-content-md-end gap-3">
                            <label class="ml-0 ml-md-4">
                                <div id="filtered_date_range" class="border form-control form-control-sm py-2 d-flex">
                                    <i class="mr-2 pe-7s-date"
                                        style="font-weight: bold; font-size: 1.25rem;"></i><span></span>
                                </div>
                            </label>
                            @if (customer(true)->can('favorites.manage-personal-list'))
                                <a class="btn btn-sm btn-success mr-0 mt-3 mt-md-0"
                                    href="{{ route('frontend.quick-lists.create') }}">
                                    <i class="icon-plus"></i> Add New Quick List
                                </a>
                            @endif
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
                                                @if (customer(true)->canAny(['favorites.manage-global-list', 'favorites.use-global-list', 'favorites.manage-personal-list']))
                                                    <th>{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($quickLists as $key => $quickList)
                                                <tr>
                                                    <th scope="row">
                                                        {{ $quickLists->firstItem() + $key }}
                                                    </th>

                                                    @if ($columns['name'])
                                                        <td>{{ $quickList->name }}</td>
                                                    @endif


                                                    @if ($columns['description'])
                                                        <td>
                                                            <p class="cs-truncate-1">
                                                                {{ $quickList->description }}
                                                            </p>
                                                        </td>
                                                    @endif

                                                    @if ($columns['product_count'])
                                                        <td> {{ $quickList->orderListItems->count() }}</td>
                                                    @endif

                                                    <td>{{ carbon_date($quickList->created_at) }}</td>
                                                    @if (checkPermissionLength(['favorites.manage-global-list', 'favorites.use-global-list', 'favorites.manage-personal-list']) > 1)
                                                        <td width="125">
                                                            <div class="btn-group m-0">
                                                                <button type="button"
                                                                    class="btn btn-outline-warning mx-0 dropdown-toggle btn-sm"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if (customer(true)->can('favorites.manage-personal-list'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('frontend.quick-lists.show', $quickList->id) }}">
                                                                            <i class="icon-bag mr-1"></i> View Items
                                                                        </a>
                                                                    @endif

                                                                    @if (customer(true)->can('favorites.manage-personal-list'))
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('frontend.quick-lists.edit', $quickList->id) }}">
                                                                            <i class="icon-paper-clip mr-1"></i> Edit
                                                                        </a>
                                                                    @endif

                                                                    @if (customer(true)->can('favorites.manage-personal-list'))
                                                                        <a class="dropdown-item delete-modal"
                                                                            href="{{ route('frontend.quick-lists.destroy', $quickList->id) }}"
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
                                                        @if (customer(true)->can('favorites.manage-personal-list'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $quickList,
                                                                    'label' => 'View Items',
                                                                    'route' => route(
                                                                        'frontend.quick-lists.show',
                                                                        $quickList->id),
                                                                ]
                                                            )
                                                        @endif
                                                        @if (customer(true)->can('favorites.manage-personal-list'))
                                                            @include(
                                                                'widget::customer.permission-component',
                                                                [
                                                                    'data' => $quickList,
                                                                    'label' => 'Edit',
                                                                    'route' => route(
                                                                        'frontend.quick-lists.edit',
                                                                        $quickList->id),
                                                                ]
                                                            )
                                                        @endif
                                                        @if (customer(true)->can('favorites.manage-personal-list'))
                                                            <a class="dropdown-item delete-modal"
                                                                href="{{ route('frontend.quick-lists.destroy', $quickList->id) }}"
                                                                data-target="#delete-modal" data-toggle="modal"
                                                                onclick="setFormAction(this)">
                                                                <i class="icon-trash mr-1"></i> Delete
                                                            </a>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
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
                                    {!! $quickLists->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@php
    push_html(function () {
        return <<<HTML
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
        HTML;
    });

    push_js(
        "
        function setFormAction(e) {
            const form = $('#form-delete');
            const deleteBtn = $(e);

            form.attr('action', deleteBtn.attr('href'));
        }
        ",
        'internal-script',
    );

    push_css(
        ".options > * {
            margin-bottom: 3px !important;
        }",
        'internal-style',
    );

    push_js(
        '
            const CUSTOMER_LIST_DATE_RANGE = \'#filtered_date_range\';

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
        ',
        'footer-script',
    );
@endphp

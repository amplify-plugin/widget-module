<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <p class="font-weight-bold mt-2">{{ __('Name') }}</p>
                <p class="rounded border p-2" style="border:1px solid #e1e7ec;">{{ $quickList->name }}</p>
            </div>
            <div class="form-group">
                <p class="font-weight-bold mt-2">{{ __('Description') }}</p>
                <p class="rounded border p-2" style="min-height: 100px; border:1px solid #e1e7ec;">
                    {{ $quickList->description }}</p>
            </div>
            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}">
                <input type="hidden" value="{{ $quickList->id }}" name="list_id">
                <div class="row">
                    <div class="col-md-6 my-2 mb-md-0">
                        <div class="d-flex justify-content-center justify-content-md-start">
                            <label aria-label="search">
                                <input type="search" name="search" aria-label="search" class="form-control form-control-sm"
                                    placeholder="Search...." value="{{ request('search') }}">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 mb-md-0">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            @if (customer(true)->can('favorites.manage-personal-list'))
                                <button type="button" class="btn btn-sm btn-primary btn-right m-2  create-order">
                                    <i class="icon-bag"></i> {{ __('Create Order') }}
                                </button>
                            @endif

                            @if (customer(true)->can('favorites.manage-personal-list'))
                                <button type="button" class="btn btn-sm btn-success btn-right my-2 ml-2 mr-0"
                                    onclick="addToCart()">
                                    <i class="icon-upload"></i> {{ __('add all items to the cart') }}
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive-md pb-4 pb-md-0">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-striped table-hover my-1"
                                        id="product-item-list">
                                        <thead>
                                            <tr>
                                                <th width="20">#</th>
                                                <th width="10%">{{ __('Image') }}</th>
                                                <th width="150">{{ __('Item Number') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th width="5%">{{ __('Quantity') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($quickListItems as $key => $item)
                                                <tr>
                                                    <input type="hidden" id="{{ 'product_code_' . $key }}"
                                                        value="{{ $item->product->product_code }}" />
                                                    <input type="hidden" id="{{ 'product_qty_' . $key }}"
                                                        value="{{ $item->qty }}" />
                                                    <input type="hidden" id="{{ 'product_id_' . $key }}"
                                                        value="{{ $item->product_id }}" />
                                                    <input type="hidden" id="{{ 'product_warehouse_' . $key }}"
                                                        value="{{ optional(optional(customer(true))->warehouse)->code }}" />
                                                    <input type="hidden" id="{{ 'product_back_order_' . $key }}"
                                                        data-status="" />

                                                    <th scope="row">{{ $quickListItems->firstItem() + $key }}</th>
                                                    <td width="60">
                                                        <a class="product-thumb"
                                                            href="{{ frontendSingleProductURL(optional($item->product)) }}">
                                                            <img title="View Product" class="img-thumbnail img-fluid"
                                                                src="{{ assets_image(optional($item->product)->productImage->main ?? '') }}"
                                                                alt="{{ optional($item->product)->Product_Name }}">
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->product->product_code }}</td>
                                                    <td>
                                                        <a class="text-decoration-none"
                                                            href="{{ frontendSingleProductURL(optional($item->product)) }}">
                                                            <p class="cs-truncate-1">
                                                                {{ optional($item->product)->product_name ?? '' }}
                                                            </p>
                                                        </a>
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $item->qty }}
                                                    </td>
                                                </tr>
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
                                            @foreach (getPaginationLengths() as $length)
                                                <option value="{{ $length }}"
                                                    @if ($length == request('par_page')) selected @endif>
                                                    {{ $length }}
                                                </option>
                                            @endforeach
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div
                                    class="col-sm-12 col-md-7 d-flex justify-content-center justify-content-md-end pt-2 pt-md-0">
                                    {!! $quickListItems->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

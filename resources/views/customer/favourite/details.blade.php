<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-content-around gap-2">
                <label class="font-weight-bold">Name:</label>
                <p>{{ $orderList->name ?? '' }}</p>
            </div>
            <div class="d-flex align-content-around gap-2">
                <label class="font-weight-bold">Description:</label>
                <p>{{ $orderList->description ?? '' }}</p>
            </div>
            <p class="my-2 font-weight-bold">Items:</p>
            <form id="customer-item-list-search-form" method="get" action="{{ url()->current() }}">
                <input type="hidden" value="{{ $orderList->id }}" name="list_id">
                <div class="row">
                    <div class="col-md-6 my-2 mb-md-0">
                        <div class="d-flex justify-content-center justify-content-md-start">
                            <label aria-label="search">
                                <input type="search" aria-label="search" name="search"
                                       class="form-control form-control-sm"
                                       placeholder="Search...." value="{{ request('search') }}">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 mb-md-0">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <!-- <button type="button" class="btn btn-sm btn-primary btn-right m-2  create-order">
                                {{__('Create Order')}}
                            </button> -->

                            <button type="button"
                                    class="btn btn-sm btn-success btn-right my-2 ml-2 mr-0"
                                    onclick="Amplify.addMultipleItemToCart(this, '#customer-item-list-search-form')">
                                {{__('Add all items to cart')}}
                            </button>
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
                                            <th width="150">{{__('Product Code')}}</th>
                                            <th>{{__('Product')}}</th>
                                            <th width="200">{{__('Quantity')}}</th>
                                            <th width="125px">{{__('Options')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($orderListItems as $key => $item)
                                            <tr class="added_products align-center" id="product-{{ $key }}">
                                                <th scope="row">{{ $orderListItems->firstItem() + $key }}</th>
                                                <td>
                                                    <input type="hidden" name="products[{{ $key }}][product_warehouse_code]"
                                                           value="{{\ErpApi::getCustomerDetail()->DefaultWarehouse }}"/>
                                                    <input type="hidden" name="products[{{ $key }}][product_id]"
                                                           value="{{ $item->product->id }}"/>
                                                    <input type="text"
                                                           id="product-code-{{$key}}"
                                                           class="form-control form-control-sm"
                                                           readonly
                                                           name="products[{{$key}}][product_code]"
                                                           value="{{ $item->product->product_code }}"/>
                                                </td>
                                                <td class="align-baseline">
                                                    <div class="d-grid">
                                                        <div class="d-flex gap-2 justify-content-start">
                                                            <a class="text-decoration-none"
                                                               href="{{ frontendSingleProductURL(optional($item->product)) }}">
                                                                <img title="View Product"
                                                                     class="img-thumbnail product-thumb"
                                                                     style="object-fit: contain"
                                                                     src="{{ assets_image(optional($item->product)->productImage->main ?? '') }}"
                                                                     alt="{{ optional($item->product)->Product_Name }}">
                                                            </a>
                                                            <a class="text-decoration-none"
                                                               href="{{ frontendSingleProductURL(optional($item->product)) }}">
                                                                <p>{{ optional($item->product)->product_name ?? '' }}</p>
                                                            </a>
                                                        </div>
                                                        <span class="text-danger" id="product-{{ $key }}-error"></span>
                                                    </div>
                                                </td>
                                                <td width="200">
                                                    <x-cart.quantity-update
                                                            name="products[{{ $key }}][qty]"
                                                            :product="$item->product"
                                                            :index="$key"
                                                            :value="$item?->qty"/>
                                                </td>
                                                <td>
                                                    <div class="btn-group m-0">
                                                        <button type="button"
                                                                class="btn btn-outline-warning mx-0 dropdown-toggle btn-sm"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item delete-modal"
                                                               href="javascript:void(0);"
                                                               onclick="setFormAction(this)"
                                                               data-toggle="modal" data-target="#remove-item"
                                                               data-action="{{ route('frontend.favourites.destroy-item', $item->id) }}">
                                                                <i class="icon-ban mr-1"></i> {{ __('Remove') }}
                                                            </a>
                                                            <a class="dropdown-item"
                                                               href="javascript:void(0)"
                                                               data-warehouse="{{ \ErpApi::getCustomerDetail()->DefaultWarehouse }}"
                                                               data-options="{{ json_encode(['code' => $item->product?->product_code ?? '']) }}"
                                                               onclick="Amplify.addSingleItemToCart(this, '{{ "#cart-item-{$key}" }}');"
                                                            ><i class="icon-bag mr-1"></i> {{ __('Add to Cart') }}</a>
                                                        </div>
                                                    </div>
                                                </td>
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
                                                        @if ($length == request('par_page')) selected @endif>
                                                    {{ $length }}
                                                </option>
                                            @endforeach
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    {!! $orderListItems->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="remove-item" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow-sm">
            <form method="POST" class="d-inline" id="form-delete">
                @method('delete')
                @csrf
                <div class="modal-body">
                    <h3 class="text-center">{{__('Are you sure?')}}</h3>
                </div>
                <div class="modal-footer justify-content-around pt-0 border-top-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="setPositionOffCanvas()">Close
                    </button>
                    <button type="submit" class="btn btn-danger" name="delete_user">{{__('Delete')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('internal-script')
    <script>
        function setFormAction(e) {
            setPositionOffCanvas(false);
            const form = $('#form-delete');
            const deleteBtn = $(e);

            form.attr('action', deleteBtn.data('action'));
        }
    </script>
@endpush

@push('internal-style')
    <style>
        .product-thumb > img {
            width: auto;
            max-width: 45px;
            max-height: 50px;
            margin: 5px auto;
        }

        .product-thumb {
            position: relative;
            overflow: hidden;
            width: 65px;
            height: 50px;
            padding: 0px 10px;
            border-right: 1px solid #e1e7ec;
        }

        .options > * {
            margin-bottom: 3px !important;
        }

        .pagination .pages > li.active > a {
            position: absolute;
            left: -1px;
            top: -1px;
        }
    </style>
@endpush

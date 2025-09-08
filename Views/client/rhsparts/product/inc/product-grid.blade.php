<div class="isotope-grid cols-3 mb-2">
    <div class="gutter-sizer"></div>
    <div class="grid-sizer"></div>
    @foreach($productsData->items ?? [] as $key => $product)
    <!-- Product-->
    <div class="grid-item">
        <div class="product-card">
            <x-product.favourite-manage-button
            class="m-0 position-relative btn-sm btn-wishlist"
            :already-exists="isset($product->exists_in_favorite)?$product->exists_in_favorite:''"
            :favourite-list-id="isset($product->favorite_list_id)?$product->favorite_list_id:''"
            :product-id="$product->Product_Id"
        />

            <a class="product-thumb" href="{{ frontendSingleProductURL($product, $seoPath) }}">
                <img src="{{ $product->productImage ?? "" }}"
                    alt="Product">
            </a>

            <div class="product-info">
                <p class="mb-0">
                    @if(!empty($product->manufacturer))
                        <a
                            class="manufacturer-name text-decoration-none"
                            href="{{ frontendSingleProductURL($product, $seoPath) }}"
                        >
                            {{ $product->manufacturer }}
                        </a>
                    @endif
                </p>
                <small class="short-desc">
                    {!! $product->short_description !!}
                </small>

                <p class="product-title text-truncate d-block">
                    <a href="{{ frontendSingleProductURL($product, $seoPath) }}">{{ $product->Product_Name ?? "" }}</a>
                </p>
                <small>MFR Part #: {{$product->MPN ?? ''}} <span class="ml-3">RHS Part #: {{$product->productCode ?? " "}}</span></small>
                @if(! empty($product->ERP[0]))
                    <div class="product-price text-left mt-2">
                        <b>Your Price: </b>
                        <span class="text-danger fw-600">
                            $ {{ $product->ERP[0]->Price ? number_format($product->ERP[0]->Price, 2, '.', '') : '' }}
                        </span>
                        <br>
                        <span>List Price: </span>
                        <span class="">$ {{$product->ERP[0]->ListPrice ?: $product->ERP[0]->ListPrice }}</span>
                    </div>
                @else
                    <p class="text-danger">{{ customer_check() ? "Upcoming..." : "Please login to see the price and availability." }}</p>
                @endif

                <div class="product-buttons">
                    <div class="d-flex gap-3">
                        @if($product->wareHouse->count() > 0)
                        <b class="text-nowrap">In Stock</b>
                        <div class="row pl-2">
                            @foreach($product->wareHouse as $warehouse)
                                <div class="col-6 px-1 d-flex flex-column w-100 gap-2 mb-3">
                                    <div class="d-flex justify-content-between gap-2 border rounded px-2 py-1">
                                        <span>
                                            {{
                                                ! empty($warehouse->WarehouseName) ?
                                                    strtoupper(substr($warehouse->WarehouseName, 0, 2)) :
                                                    ''
                                            }}
                                        </span>
                                        @if(customer_check())
                                            <span>
                                                {{
                                                    ! empty($warehouse->warehouseQty) ?
                                                        $warehouse->warehouseQty :
                                                        0
                                                }}
                                            </span>
                                        @else
                                            <span>{{ ! empty($warehouse->warehouseQty) ? 'YES' : 'NO' }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <div class="product-count d-flex justify-content-between">
                            <span
                                class="flex-shrink-0 text-dark border rounded d-flex align-items-center justify-content-center fw-600" onclick="productQuentity({{$key}},'minus')"><i
                                    class="icon-minus fw-700"></i></span>
                            <div
                                class="mx-2 px-2 w-100 fw-600 d-flex align-items-center justify-content-center">
                                @include('widget::client.rhsparts.product.inc.partial',['product'=>$product,'key'=>$key])
                            </div>
                            <span
                                class="flex-shrink-0 text-dark border rounded d-flex align-items-center justify-content-center fw-600" onclick="productQuentity({{$key}},'plus')"><i
                                    class="icon-plus fw-700"></i></span>
                        </div>
                        <button class="btn btn-primary btn-sm m-0"
                        id="add_to_order_btn_{{ $key }}"
                            onclick="addSingleProductToOrder('{{ $key }}')"
                            data-toast-position="topRight">Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

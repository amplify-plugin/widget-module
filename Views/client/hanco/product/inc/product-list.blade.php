<div class="products">
    <div class="product-list-head row p-2 mb-3">
        <div class="col-md-3">Item Number</div>
        <div class="col-md-3">Available Quantity</div>
        <div class="col-md-2">Price</div>
        <div class="col-md-2">Quantity</div>
        <div class="col-md-2"></div>
    </div>
    @foreach ($productsData ?? [] as $key => $product)
        <div class="product-list-item row mb-3">
            <div class="product-details w-100">
                <div class="d-flex gap-3">
                    <div>
                        <a href="{{ frontendSingleProductURL($product, $seoPath) }}">
                            <img class="rounded-left aspect-square object-cover"
                                src="{{ $product->productImage ?? '' }}" alt="Product">
                        </a>
                    </div>
                    <div class="d-flex flex-column justify-content-around pl-2 w-100">
                        <div class="align-items-center d-flex gap-3 information justify-content-around gap-6">
                            <h5 class="">{{ $product->productCode }}</h5>
                            <h5 class="">{{ customer_check() ? $product?->ERP?->QuantityAvailable : '-' }}</h5>
                            <h5 class="">{{ customer_check() ? price_format($product->ERP?->Price) : '-' }}/each</h5>
                            <div class="product-count align-items-center d-flex gap-2">
                                <span
                                    class="text-dark d-flex align-items-center justify-content-center fw-600 rounded border"
                                    onclick="productQuantity({{ $key }},'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"><i
                                        class="icon-minus fw-700"></i></span>
                                <div class="h-100 fw-600 d-flex align-items-center justify-content-center">

                                    @include('widget::client.hanco.product.inc.partial', [
                                        'product' => $product,
                                        'key' => $key,
                                    ])
                                </div>
                                <span
                                    class="text-dark d-flex flex-wrap align-items-center align-items-center justify-content-center fw-600 bg-primary rounded border text-white"
                                    onclick="productQuantity({{ $key }},'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"><i
                                        class="icon-plus fw-700"></i></span>
                            </div>
                            <button class="btn btn-danger btn-sm fw-600 m-0" id="add_to_order_btn_{{ $key }}"
                                data-toast-icon="icon-circle-check"
                                onclick="addSingleProductToOrder('{{ $key }}')">Add to Cart
                            </button>
                        </div>
                        <div>
                            <p>{{ $product->Product_Name }}</p>
                        </div>
                    </div>
                </div>

            </div>


            {{-- <div class="product-thumb position-relative">
                <a href="{{ frontendSingleProductURL($product, $seoPath) }}">
                    <img class="rounded-left aspect-square object-cover" src="{{ $product->productImage ?? '' }}"
                         alt="Product">
                </a>
            </div> --}}
            {{-- <div class="product-info"> --}}
            {{-- <h2 class="product-title">
                    <a class="fw-700"
                       href="{{ frontendSingleProductURL($product, $seoPath) }}">{{ $product->Product_Name ?? '' }}</a>
                </h2> --}}
            {{-- <div class="product-description">
                    <div class="d-flex flex-wrap fw-600 gap-3">
                        <span>Part Number: <span class="text-danger">{{ $product->productCode ?? '' }}</span></span>
                        <span>MPN: <span class="text-danger">{{ $product->manufacturer ?? '' }}</span></span>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-2">
                        <div class="flex-1">
                            @if (isset($product?->ERP))
                                <div class="product-price">
                                    <b>Your Price: </b>
                                    <span class="text-danger fw-600">{{ price_format($product->ERP?->Price) }}</span>
                                </div>

                                @if ((int) $product?->ERP->QuantityAvailable > 0)
                                    <div class="text-danger">{{ config('amplify.frontend.product_available_text') }}</div>
                                @else
                                    <div class="text-danger">{{ config('amplify.frontend.product_not_available_text') }}</div>
                                @endif
                            @else
                                <span class="text-danger">{{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}</span>
                            @endif
                        </div>
                    </div>
                </div> --}}

            {{-- <div class="product-buttons d-flex justify-content-between flex-wrap gap-3">
                    <div class="product-count align-items-center d-flex gap-2">
                        <div class="fw-600 mr-2">Quantity:</div>
                        <span
                            class="text-dark d-flex align-items-center justify-content-center fw-600 rounded border"
                            onclick="productQuantity({{ $key }},'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"><i
                                class="icon-minus fw-700"></i></span>
                        <div class="h-100 fw-600 d-flex align-items-center justify-content-center">

                            @include('widget::client.hanco.product.inc.partial', [
                                'product' => $product,
                                'key' => $key,
                            ])
                        </div>
                        <span
                            class="text-dark d-flex flex-wrap align-items-center align-items-center justify-content-center fw-600 bg-primary rounded border text-white"
                            onclick="productQuantity({{ $key }},'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"><i
                                class="icon-plus fw-700"></i></span>
                    </div>

                    <div class="d-flex gap-3">
                        @if ($allowFavourites() && !$isMasterProduct($product))
                            <x-product.favourite-manage-button class="position-relative position-static btn-sm btn-wishlist m-0"
                                                               :already-exists="$product->exists_in_favorite ?? ''"
                                                               :favourite-list-id="$product->favorite_list_id ?? ''"
                                                               :product-id="$product->Product_Id" />
                        @else
                            <div></div>
                        @endif
                        <button class="btn btn-danger btn-sm fw-600 m-0" id="add_to_order_btn_{{ $key }}"
                                data-toast-icon="icon-circle-check"
                                onclick="addSingleProductToOrder('{{ $key }}')">Add to Cart
                        </button>
                    </div>
                </div> --}}
            {{-- </div> --}}
        </div>
    @endforeach
</div>

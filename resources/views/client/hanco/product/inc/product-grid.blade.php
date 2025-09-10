<div class="d-grid grid-md-cols-3 mb-4 gap-6 row">
    @foreach ($productsData as $key => $product)
        <!-- Product-->
        <div class="product-card product-grid rounded">
            <div class="product-thumb">
                <a href="{{ frontendSingleProductURL($product, $seoPath) }}">
                    <img class="rounded-top aspect-square object-contain" src="{{ $product->productImage ?? '' }}"
                        alt="Product">
                </a>
            </div>

            <div class="product-info">
                <h3 class="product-title text-center">
                    <a class="fw-700"
                        href="{{ frontendSingleProductURL($product, $seoPath) }}">{{ $product->productCode ?? '' }}</a>
                </h3>
                <p class="text-left m-0">{{ $product->Product_Name }}</p>
                <p class="fw-700 text-bold text-center"> {{ customer_check() ? price_format($product?->ERP?->Price) : '-' }}/each</p>
                <p class="text-left"> Available Quantity: {{ customer_check() ? $product?->ERP?->QuantityAvailable : '-' }} </p>

                <div class="product-count align-items-center d-flex justify-content-center gap-2 mb-2">
                    <span class="text-dark d-flex align-items-center justify-content-center fw-600 rounded border"
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

                <div class="product-buttons d-flex justify-content-between">
                    @if ($allowFavourites() && !$isMasterProduct($product))
                        <x-product.favourite-manage-button class="btn-wishlist" :already-exists="$product->exists_in_favorite ?? ''" :favourite-list-id="$product->favorite_list_id ?? ''"
                            :product-id="$product->Product_Id" />
                    @endif

                    @if (is_array($product->Sku_List) && count($product->Sku_List, true) > 0)
                        <a class="btn btn-warning btn-sm btn-block text-capitalize"
                            href="{{ frontendSingleProductURL($product, $seoPath) }}">
                            View Details
                        </a>
                    @else
                        <div class="d-none">
                            @include('widget::client.hanco.product.inc.partial', [
                                'product' => $product,
                                'key' => $key,
                            ])
                        </div>
                        <button class="btn btn-primary btn-sm btn-block fw-600 m-0"
                            id="add_to_order_btn_{{ $key }}" data-toast-position="topRight"
                            onclick="addSingleProductToOrder('{{ $key }}')">Add
                            to Cart
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- <div class="product-card product-grid border border-1 rounded p-3">
            <div class="product-thumb">
                <a href="{{ frontendSingleProductURL($product, $seoPath) }}">
                    <img class="rounded-top aspect-square object-contain" src="{{ $product->productImage ?? '' }}"
                        alt="Product">
                </a>
            </div>

            <div class="product-info">
                <h3 class="product-title text-left">
                    <a class="fw-700"
                        href="{{ frontendSingleProductURL($product, $seoPath) }}">{{ $product->Product_Name ?? '' }}</a>
                </h3>
                <p class="m-0">Part Number: <span class="text-danger">{{ $product->productCode ?? '' }}</span></p>
                <p class="m-0 mt-1">MPN: <span class="text-danger">{{ $product->manufacturer ?? '' }}</span>
                    @if (isset($product?->ERP))
                        <h6 class="mt-2">
                            <b>Your Price: </b>
                            <span class="text-danger fw-600">{{ price_format($product->ERP?->Price) }}</span>
                        </h6>

                        @if ((int) $product?->ERP->QuantityAvailable > 0)
                            <p class="text-center text-danger">{{ config('amplify.frontend.product_available_text') }}
                            </p>
                        @else
                            <p class="text-center text-danger">
                                {{ config('amplify.frontend.product_not_available_text') }}</p>
                        @endif
                    @else
                        <p class="text-center text-danger mt-2">
                            {{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}
                        </p>
                    @endif

                <div class="product-buttons d-flex justify-content-between">
                    @if ($allowFavourites() && !$isMasterProduct($product))
                        <x-product.favourite-manage-button class="btn-wishlist" :already-exists="$product->exists_in_favorite ?? ''" :favourite-list-id="$product->favorite_list_id ?? ''"
                            :product-id="$product->Product_Id" />
                    @endif

                    @if (count($product->Sku_List, true) > 0)
                        <a class="btn btn-warning btn-sm btn-block text-capitalize"
                            href="{{ frontendSingleProductURL($product, $seoPath) }}">
                            View Details
                        </a>
                    @else
                        <div class="d-none">
                            @include('widget::client.hanco.product.inc.partial', [
                                'product' => $product,
                                'key' => $key,
                            ])
                        </div>
                        <button class="btn btn-primary btn-sm btn-block fw-600 m-0"
                            id="add_to_order_btn_{{ $key }}" data-toast-position="topRight"
                            onclick="addSingleProductToOrder('{{ $key }}')">Add
                            to Cart
                        </button>
                    @endif
                </div>
            </div>
        </div> --}}
    @endforeach
</div>

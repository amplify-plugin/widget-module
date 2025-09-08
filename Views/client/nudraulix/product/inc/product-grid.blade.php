<div class="d-grid grid-md-cols-3 mb-4 gap-6">
    @foreach ($productsData->items ?? [] as $key => $product)
        <!-- Product-->
        <div class="product-card product-grid">
            <div class="product-thumb">
                <a href="{{ frontendSingleProductURL($product, $seoPath) }}">
                    <img class="rounded-top aspect-square object-contain" src="{{ $product->productImage ?? '' }}"
                         alt="Product">
                </a>
            </div>

            <div class="product-info">
                <h3 class="product-title text-left">
                    <a
                        class="fw-700"
                        href="{{ frontendSingleProductURL($product, $seoPath) }}"
                    >
                        {{ $product->Product_Name ?? '' }}
                    </a>
                </h3>
                <p class="m-0">Part Number: <span class="text-primary text-uppercase">{{ $product->productCode ?? '' }}</span></p>

                @if(! empty($product->manufacturer))
                    <p class="m-0 mt-1">MPN: <span class="text-primary">{{ $product->manufacturer }}</span>
                @endempty

                @if (!$isMasterProduct($product) && isset($product?->ERP))
                    <h6 class="mt-2">
                        <b>Your Price: </b>
                        <span class="text-primary fw-600">{{ (price_format($product->ERP?->Price). "/{$product->ERP?->UnitOfMeasure}") }}</span>
                    </h6>

                    @if((int)$product?->ERP->QuantityAvailable > 0)
                        <p class="text-center text-primary">{{ config('amplify.frontend.product_available_text') }}</p>
                    @else
                        <p class="text-center text-primary">{{ config('amplify.frontend.product_not_available_text') }}</p>
                    @endif
                @endif

                <div class="product-buttons d-flex justify-content-between mt-2">
                    @if ($allowFavourites() && !$isMasterProduct($product))
                        <x-product.favourite-manage-button
                            class="btn-wishlist"
                            :already-exists="$product->exists_in_favorite ?? ''"
                            :favourite-list-id="$product->favorite_list_id ?? ''"
                            :product-id="$product->Product_Id"
                        />
                    @endif

                    @if ($isMasterProduct($product) || !customer_check())
                        <a class="btn btn-warning btn-sm btn-block text-capitalize"
                           href="{{ frontendSingleProductURL($product, $seoPath) }}">
                            View Details
                        </a>
                    @else
                        <div class="flex flex-row row-gap-2 w-100">
                            <div class="product-count d-flex gap-2 align-items-center mb-4">
                                <div class="fw-600 mr-2">Qty:</div>
                                <span
                                    class="text-dark d-flex flex-1 align-items-center justify-content-center fw-600 rounded border product-qty-btn"
                                    onclick="productQuantity({{ $key }},'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                                >
                                    <i class="icon-minus fw-700"></i>
                                </span>

                                <div class="h-100 fw-600 d-flex align-items-center justify-content-center">
                                    @include('widget::client.nudraulix.product.inc.partial', [
                                        'product' => $product,
                                        'key' => $key,
                                    ])
                                </div>
                                <span
                                    class="d-flex flex-1 flex-wrap align-items-center justify-content-center fw-600 bg-primary rounded border text-white product-qty-btn"
                                    onclick="productQuantity({{ $key }},'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                                >
                                    <i class="icon-plus fw-700"></i>
                                </span>
                            </div>
                            <button
                                class="btn btn-primary btn-sm btn-block fw-600 m-0"
                                id="add_to_order_btn_{{ $key }}" data-toast-position="topRight"
                                onclick="addSingleProductToOrder('{{ $key }}')"
                            >
                                Add to Cart
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

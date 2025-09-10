<div class="products">
    @foreach ($productsData->items ?? [] as $key => $product)
        <div class="product-card product-list h-100">
            <div class="product-thumb position-relative">
                <a href="{{ frontendSingleProductURL($product, $seoPath) }}">
                    <img class="rounded-left aspect-square object-cover" src="{{ $product->productImage ?? '' }}"
                         alt="Product">
                </a>
            </div>
            <div class="product-info d-flex flex-column justify-content-between h-100">
                <div>
                    <h2 class="product-title">
                        <a class="fw-700" href="{{ frontendSingleProductURL($product, $seoPath) }}">
                            {{ $product->Product_Name ?? '' }}
                        </a>
                    </h2>
                    <div class="product-description">
                        <div class="d-flex flex-wrap fw-600 gap-3">
                            <span>Part Number:
                                <span class="text-uppercase text-primary">{{ $product->productCode ?? '' }}</span>
                            </span>
                            @if(! empty($product->manufacturer))
                                <span>
                                    MPN:
                                    <span class="text-primary">{{ $product->manufacturer }}</span>
                                </span>
                            @endempty
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-2">
                            <div class="flex-1">
                                @if (!$isMasterProduct($product) && isset($product?->ERP))
                                    <div class="product-price">
                                        <b>Your Price: </b>
                                        <span class="text-primary fw-600">{{ (price_format($product->ERP?->Price). "/{$product->ERP?->UnitOfMeasure}") }}</span>
                                    </div>

                                    @if((int)$product?->ERP->QuantityAvailable > 0)
                                        <div class="text-primary">
                                            {{ config('amplify.frontend.product_available_text') }}
                                        </div>
                                    @else
                                        <div class="text-primary">
                                            {{ config('amplify.frontend.product_not_available_text') }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if ($isMasterProduct($product) || !customer_check())
                    <div class="row">
                        @foreach ($product->filtered_attributes as $attribute)
                            @php
                                $attrVal = json_decode($attribute->pivot->attribute_value, true)['en'] ?? "";
                            @endphp
                            <p class="mb-1 col-4"
                               filter-attribute="{{ $attribute->local_name . '-' . $attrVal }}">
                                <b>{{ $attribute->local_name }}:</b> {{ $attrVal }}
                            </p>
                        @endforeach
                    </div>
                    <a class="btn btn-primary btn-sm btn-block text-capitalize"
                       href="{{ frontendSingleProductURL($product, $seoPath) }}">
                        View Details
                    </a>
                @else
                    <div class="product-buttons d-flex justify-content-between flex-wrap gap-3">
                        <div class="product-count align-items-center d-flex gap-2">
                            <div class="fw-600 mr-2">Quantity:</div>
                            <span
                                class="text-dark d-flex align-items-center justify-content-center fw-600 rounded border product-qty-btn"
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
                                class="d-flex flex-wrap align-items-center justify-content-center fw-600 bg-primary rounded border text-white product-qty-btn"
                                onclick="productQuantity({{ $key }},'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                            >
                                <i class="icon-plus fw-700"></i>
                            </span>
                        </div>

                        <div class="d-flex gap-3">
                            @if ($allowFavourites() && !$isMasterProduct($product))
                                <x-product.favourite-manage-button
                                    class="position-relative position-static btn-sm btn-wishlist m-0"
                                    :already-exists="$product->exists_in_favorite ?? ''"
                                    :favourite-list-id="$product->favorite_list_id ?? ''"
                                    :product-id="$product->Product_Id"
                                />
                            @else
                                <div></div>
                            @endif

                            {{-- add to cart area start --}}
                            <button
                                class="btn btn-primary btn-sm fw-600 m-0" id="add_to_order_btn_{{ $key }}"
                                data-toast-icon="icon-circle-check"
                                onclick="addSingleProductToOrder('{{ $key }}')"
                            >
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

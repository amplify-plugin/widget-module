<div class="products">
    @foreach ($productsData->items ?? [] as $key => $product)
        <div class="product-card product-list">
            <div class="product-thumb position-relative">
                {{-- <span class="badge badge-sm badge-secondary position-absolute"> --}}
                {{--     Best Seller --}}
                {{-- </span> --}}
                <a href="{{ frontendSingleProductURL($product, $seoPath) }}">
                    <img class="rounded-left aspect-square object-cover" src="{{ $product->productImage ?? '' }}"
                        alt="Product">
                </a>
            </div>
            <div class="product-info">
                <h2 class="product-title">
                    <a class="fw-700"
                        href="{{ frontendSingleProductURL($product, $seoPath) }}">{{ $product->Product_Name ?? '' }}</a>
                </h2>
                <div class="product-description">
                    <div class="d-flex flex-wrap fw-600 gap-3">
                        <span>Part Number: <span class="text-danger">{{ $product->productCode ?? '' }}</span></span>
                        <span>MPN: <span class="text-danger">{{ $product->manufacturer ?? '' }}</span></span>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-2">
                        <div class="flex-1">
                            @if (isset($product?->ERP))
                                <div class="product-price">
                                    <b>Your Price: </b>
                                    <span
                                        class="text-danger fw-600">{{ price_format($product->ERP?->Price) }}@isset($product?->ERP->PricingUnitOfMeasure)
                                        /{{ strtoupper($product->ERP->PricingUnitOfMeasure) }}
                                    @endisset
                                </span>
                                {{-- <span class="ml-3">List Price: </span> --}}
                                {{-- <span class="">{{ price_format($product->ERP?->ListPrice) }}</span> --}}
                            </div>

                            @if ((int) $product?->ERP->QuantityAvailable > 0)
                                <div class="text-danger">{{ config('amplify.frontend.product_available_text') }}
                                </div>
                            @else
                                <div class="text-danger">{{ config('amplify.frontend.product_not_available_text') }}
                                </div>
                            @endif
                        @else
                            <span
                                class="text-danger">{{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}</span>
                        @endif
                    </div>

                    <div class="product-count align-items-center d-flex gap-2">
                        <div class="fw-600 mr-2">Quantity:</div>
                        <span
                            class="text-dark d-flex align-items-center justify-content-center fw-600 rounded border"
                            onclick="productQuantity({{ $key }},'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"><i
                                class="icon-minus fw-700"></i></span>
                        <div class="h-100 fw-600 d-flex align-items-center justify-content-center">

                            @include('widget::client.cal-tool.product.inc.partial', [
                                'product' => $product,
                                'key' => $key,
                            ])
                        </div>
                        <span
                            class="text-dark d-flex align-items-center justify-content-center fw-600 bg-primary rounded border text-white"
                            onclick="productQuantity({{ $key }},'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"><i
                                class="icon-plus fw-700"></i></span>
                    </div>
                </div>
            </div>

            <div class="product-buttons d-flex justify-content-between flex-row gap-3">
                @if ($allowFavourites() && !$isMasterProduct($product))
                    <x-product.favourite-manage-button
                        class="position-relative position-static btn-sm btn-wishlist m-0" :already-exists="$product->exists_in_favorite ?? ''"
                        :favourite-list-id="$product->favorite_list_id ?? ''" :product-id="$product->Product_Id" />
                @else
                    <div></div>
                @endif

                <div class="d-flex gap-3">
                    {{-- add to cart area start --}}
                    <button class="btn btn-danger btn-sm fw-600 m-0" id="add_to_order_btn_{{ $key }}"
                        data-toast-icon="icon-circle-check"
                        onclick="addSingleProductToOrder('{{ $key }}')">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>

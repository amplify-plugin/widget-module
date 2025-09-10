<div class="d-grid grid-md-cols-3 mb-4 gap-6">
    @foreach ($productsData->items ?? [] as $key => $product)
        <!-- Product-->
        <div class="product-card product-grid">
            <div class="product-thumb">
                {{-- <span class="badge badge-sm badge-secondary position-absolute m-2"> --}}
                {{--     Best Seller --}}
                {{-- </span> --}}
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
                            <span
                                class="text-danger fw-600">{{ price_format($product->ERP?->Price) }}@isset($product?->ERP->PricingUnitOfMeasure)
                                /{{ strtoupper($product->ERP->PricingUnitOfMeasure) }}
                            @endisset
                        </span>
                        {{-- <br> --}}
                        {{--  <span>List Price: </span>  --}}
                        {{--  <span>{{ price_format($product->ERP?->ListPrice) }}</span>  --}}
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

                @if (count(json_decode($product->Sku_List, true)))
                    <a class="btn btn-warning btn-sm btn-block text-capitalize"
                        href="{{ frontendSingleProductURL($product, $seoPath) }}">
                        View Details
                    </a>
                @else
                    <div class="d-none">
                        @include('widget::client.cal-tool.product.inc.partial', [
                            'product' => $product,
                            'key' => $key,
                        ])
                    </div>
                    <button class="btn btn-primary btn-sm btn-block fw-600 m-0"
                        id="add_to_order_btn_{{ $key }}" data-toast-position="topRight"
                        onclick="addSingleProductToOrder('{{ $key }}')">
                        Add to Cart
                    </button>
                @endif
            </div>
        </div>
    </div>
@endforeach
</div>

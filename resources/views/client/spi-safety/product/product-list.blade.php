<div {!! $htmlAttributes !!}>
    @if (isset($message))
        <p class="font-weight-bold my-2" style="font-size: 1rem">{!! $message !!}</p>
    @endif

    @if (!empty($productsData->items))
        @if ($productView === 'list')
            <div class="products product-list-view">
                @foreach ($productsData->items ?? [] as $key => $product)
                    <div class="product-card product-list">
                        <a class="product-thumb" href="{{ frontendSingleProductURL($product, $seoPath) }}">
                            {{-- @if ($displayDiscountBadge())
                                <div class="product-badge text-danger">
                                    {{ discount_badge_label($product->ERP?->Price?: $product->ERP?->StandardPrice, $product->ERP?->Price?: $product->ERP?->StandardPrice) }}
                                </div>
                            @endif --}}
                            <img src="{{ $product->Product_Image ?? '' }}" alt="Product">
                            @if ($allowFavourites() && !$isMasterProduct($product))
                                <x-product.favourite-manage-button class="position-absolute" style="top:0; right: 1rem"
                                    :already-exists="false" :favourite-list-id="null" :product-id="$product->Product_Id" />
                            @endif
                        </a>
                        <div class="product-info">
                            <h3 class="product-title">
                                <a
                                    href="{{ frontendSingleProductURL($product, $seoPath) }}">{{ $product->Product_Name ?? '' }}</a>
                            </h3>
                            @if ($allowDisplayProductCode())
                                <p class="product-code"><b>Group Code:</b> {{ $product->Product_Code }}</p>
                            @endif

                            <h4 class="product-price mb-0 mb-md-5">
                                @if (isset($product?->erpProductList))
                                    <span class="fw-700">From - </span>
                                    <span
                                        class="text-warning">{{ price_format($product->erpProductList->min('Price')) }}</span>
                                    <small
                                        class="d-inline">/{{ $product->erpProductList->first()?->PricingUnitOfMeasure }}</small>
                                @else
                                    {{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}
                                @endif
                            </h4>

                            <p class="hidden-xs-down mt-4">
                            </p>
                            <div class="product-buttons">
                                @if (!customer_check() || customer(true)->can('shop.add-to-cart'))
                                    <a href="{{ frontendSingleProductURL($product, $seoPath) }}"
                                        class="btn btn-warning btn-sm text-capitalize">View All Items</a>
                                @endif
                                @if (!customer_check() || customer(true)->can('shop.add-to-cart'))
                                    <button class="btn btn-outline-primary btn-sm quick-cart-btn" title="Quick Order"
                                        data-toggle="modal" data-target="#quick-view"
                                        onclick="getQuickDetails('{{ $product->Product_Id }}'); setPositionOffCanvas(false)"><i
                                            class="icon-stack-2"></i></button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                @foreach ($productsData->items ?? [] as $key => $product)
                    <div class="col-md-4 col-sm-6 mb-3 px-2">
                        <div class="grid-item">
                            <div class="product-card px-0">
                                {{-- <div class="product-badge text-warning text-capitalize">Best Seller</div> --}}
                                <a class="product-thumb" href="{{ frontendSingleProductURL($product, $seoPath) }}">
                                    <img src="{{ $product->Product_Image ?? '' }}" alt="Product">
                                </a>

                                <h3 class="product-title">
                                    <a href="{{ frontendSingleProductURL($product, $seoPath) }}" data-toggle="tooltip"
                                        data-placement="auto" title="{{ $product->Product_Name ?? '' }}">
                                        {{ $product->Product_Name ?? '' }}
                                    </a>
                                </h3>
                                <!-- <p class="text-center"><b>Group Code:</b> {{ $product->Product_Code }}</p> -->

                                <h4 class="product-price">
                                    @if (isset($product?->erpProductList))
                                        <span class="fw-700">From - </span>
                                        <span
                                            class="text-warning">{{ price_format($product->erpProductList->min('Price')) }}</span>
                                        <small
                                            class="d-inline">/{{ $product->erpProductList->first()?->PricingUnitOfMeasure }}</small>
                                </h4>
                            @else
                                {{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}
                @endif
                </h4>

                <div class="product-buttons">
                    @if (!customer_check() || customer(true)->can('shop.add-to-cart'))
                        <a href="{{ frontendSingleProductURL($product, $seoPath) }}"
                            class="btn btn-warning btn-sm text-capitalize">
                            View All Items
                        </a>
                    @endif
                    @if (!customer_check() || customer(true)->can('shop.add-to-cart'))
                        <button class="btn btn-outline-primary btn-sm quick-cart-btn" title="Quick Order"
                            data-toggle="modal" data-target="#quick-view"
                            onclick="getQuickDetails('{{ $product->Product_Id }}'); setPositionOffCanvas(false)"><i
                                class="icon-stack-2"></i></button>
                    @endif
                </div>
            </div>
</div>
</div>
@endforeach
</div>
@endif
<x-shop-pagination />
@else
<x-shop-empty-result />
@endif
</div>

@include('widget::client.spi-safety.quick-view')

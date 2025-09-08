<div {!! $htmlAttributes !!}>
    @if(!$products->isEmpty())
        <x-product-favorite-list />
        @if($show_title)
            <h3 class="product-slider-title">
                {{ __($title) }}
            </h3>
        @endif

        <div class="owl-carousel" data-owl-carousel="{{ $carouselOptions() }}">
            <!-- Product-->
            @foreach ($products as $key => $product)
                <div class="grid-item">
                    <div class="product-card">
                        @if ($show_top_discount_badge)
                            <div class="product-badge text-danger">
                                {{ discount_badge_label($product->price, $product->old_price) }}
                            </div>
                        @endif
                        <a class="product-thumb" href="{{ $product->detail_link }}">
                            <img src="{{ $product->image }}"
                                 alt="{{ __($product->name ?? '') }}">
                        </a>
                        <div class="product-body">
                            <div class="product-description {{ $show_price ? "slider-product-info": ""}}">
                                <p class="mb-0">
                                    <a
                                        class="manufacturer-name text-decoration-none"
                                        href="{{ $product->detail_link }}"
                                    >
                                        {{ $product->manufacturer ?? "" }}
                                    </a>
                                    <small class="short-desc">
                                        {!! $product->short_description ?? "" !!}
                                    </small>
                                </p>
                                <p class="product-title mb-0">
                                    <a href="{{ $product->detail_link }}"
                                       title="{{ __($product->name ?? '') }}">
                                        {{ __($product->name ?? '') }}
                                    </a>
                                </p>
                                @if ($displayProductCode)
                                    <p class="product-code"><span>Product Code:</span> {{ $product->product_code }}</p>
                                @endif
                                @if ($show_price)
                                    <h4 class="product-price">
                                        {{ $product->price ?? '' }}@isset($product?->ERP->PricingUnitOfMeasure)/{{ strtoupper($product->ERP->PricingUnitOfMeasure) }}@endisset
                                        <span class="product-old-price @if (!$show_top_discount_badge) d-none @endif">
                                        {{ $product->old_price ?? '' }}@isset($product?->ERP->PricingUnitOfMeasure)/{{ strtoupper($product->ERP->PricingUnitOfMeasure) }}@endisset
                                    </span>
                                    </h4>
                                @endif
                                <x-product-hidden-fields :product="$product" :input="$key"/>
                                <input id="product_qty_{{ $key }}" type="hidden" name="qty[]" value="1"
                                       min="1" max="" class="form-control">
                            </div>
                            @if ($show_customer_list || $show_cart_btn)
                                <div class="product-buttons d-flex justify-content-between">

                                    @if ($show_customer_list)
                                        <x-product.favourite-manage-button
                                            class="mr-2"
                                            :already-exists="$product->exists_in_favorite"
                                            :favourite-list-id="$product->favorite_list_id"
                                            :product-id="$product->id"
                                        />
                                    @endif

                                    @if ($show_cart_btn)
                                        <a class="btn btn-outline-primary btn-block @if ($small_button) btn-sm @endif"
                                           href="{{ $product->detail_link }}">
                                            <i class="pe-7s-cart"
                                               style="font-weight: bolder"></i> {{ __($cart_button_label) }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

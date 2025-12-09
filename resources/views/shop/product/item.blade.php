@if($productView =='list')

@else
    @if($showDiscountBadge)
        <div class="product-badge text-danger">50% Off</div>
    @endif
    @if ($allowFavourites() && !$isMasterProduct($product))
        <x-product.favourite-manage-button class="btn-wishlist position-absolute"
                                           :already-exists="$product->exists_in_favorite ?? false"
                                           :favourite-list-id="$product->favorite_list_id ?? ''"
                                           :product-id="$product->Amplify_Id"/>
    @endif

    <x-product.main-image :product="$product" :seo-path="$seoPath" :wrap-link="true"/>

    <x-product.item-number :product="$product" format="<b>Product Code:</b> {product_code}"/>

    <x-product.name element="span" :product="$product" class="product-title"/>

    <x-product.price
        element="div"
        class="d-block fw-700 w-100 d-flex justify-content-center product-price"
        :product="$product"
        :value="$product->ERP?->Price"
        :uom="$product->ERP?->UnitOfMeasure ?? 'EA'">
        <span class="d-inline-block mr-1">MSRP:</span>
    </x-product.price>

    <x-cart.quantity-update :product="$product" :index="$loop->index"/>
    <div class="product-buttons">
        @if ($isMasterProduct($product))
            <a href="{{ frontendSingleProductURL($product, $seoPath) }}"
               class="btn m-0 btn-primary btn-sm text-capitalize">
                {{ $productDetailBtnLabel() }}
            </a>
            <button class="btn btn-block btn-sm btn-primary m-0">{{ $addToCartBtnLabel() }}</button>
        @else
            <x-wishlist-button :product="$product" style="width: 42px; height: 36px"
                               class="btn m-0 btn-sm btn-outline-primary px-0">
                <x-slot:add-label>
                    <i class="icon-heart text-primary"></i>
                </x-slot>
                <x-slot:remove-label>
                    <i class="icon-heart text-danger"></i>
                </x-slot>
            </x-wishlist-button>
            <button class="btn btn-block btn-sm btn-primary m-0">{{ $addToCartBtnLabel() }}</button>
        @endif
    </div>
@endif

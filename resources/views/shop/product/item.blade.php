@if($productView =='list')
    <x-product.main-image :product="$product" :seo-path="$seoPath" :wrap-link="true">
        @if(!$showDiscountBadge)
            <div class="product-badge product-discount" style="left: 0">50% Off</div>
        @endif

        @if(!$showFavourite)
            <div class="product-badge" style="right: 0">
                {{--                @if(class_exists(\Amplify\Wishlist\Widgets\WishlistButton::class))--}}
                {{--                    <x-wishlist-button :product="$product" class="btn-wishlist">--}}
                {{--                        <x-slot:add-label>--}}
                {{--                            <i title="Add to Favorites" class="icon-file-add text-primary"></i>--}}
                {{--                        </x-slot>--}}
                {{--                        <x-slot:remove-label>--}}
                {{--                            <i title="Remove From Favorites" class="icon-file-subtract"></i>--}}
                {{--                        </x-slot>--}}
                {{--                    </x-wishlist-button>--}}
                {{--                @endif--}}
            </div>
        @endif
    </x-product.main-image>
    <div class="product-info">
        @if($allowDisplayProductCode())
            <x-product.item-number
                    :product="$product"
                    format="<a href='{{ frontendSingleProductURL($product, $seoPath) }}'><b>{product_code}</b></a>"
                    element="span"/>
        @endif
        <x-product.name element="span" :product="$product" class="product-title d-block"/>
        <p class="hidden-xs-down">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempore odit officiis illo
            perferendis deserunt, ipsam dolor ad dolorem eaque veritatis harum facilis aliquid id doloribus incidunt
            quam beatae, soluta magni alori sedum quanto.</p>
        <x-product.price
                element="div"
                class="w-100 d-flex justify-content-md-start justify-content-center product-price"
                :product="$product"
                :value="$product->ERP?->Price"
                :uom="$product->ERP?->UnitOfMeasure ?? 'EA'"
                :std-price="$product->Msrp->toFloat()"/>
        <x-product.quick-action :product="$product" :seo-path="$seoPath" :index="$loop->index"/>
    </div>
    {{--    <div class="row">--}}
    {{--        <div class="col-md-3 col-12">--}}
    {{--            @if($showDiscountBadge)--}}
    {{--                <div class="product-badge text-danger">50% Off</div>--}}
    {{--            @endif--}}
    {{--            @if ($showFavourite && !$isMasterProduct($product))--}}
    {{--                --}}{{--                <x-product.favourite-manage-button--}}
    {{--                --}}{{--                    class="btn-wishlist position-absolute"--}}
    {{--                --}}{{--                    :already-exists="$product->exists_in_favorite ?? false"--}}
    {{--                --}}{{--                    :favourite-list-id="$product->favorite_list_id ?? ''"--}}
    {{--                --}}{{--                    :product-id="$product->Amplify_Id"/>--}}
    {{--            @endif--}}
    {{--            <x-product.main-image :product="$product" :seo-path="$seoPath" :wrap-link="true">--}}
    {{--                @if(!$showDiscountBadge)--}}
    {{--                    <div class="product-badge product-discount" style="left: 0">50% Off</div>--}}
    {{--                @endif--}}

    {{--                @if(!$showFavourite)--}}
    {{--                    <div class="product-badge" style="right: 0">--}}
    {{--                        @if(class_exists(\Amplify\Wishlist\Widgets\WishlistButton::class))--}}
    {{--                            <x-wishlist-button :product="$product" class="btn-wishlist" data-add-variant="secondary">--}}
    {{--                                <x-slot:add-label>--}}
    {{--                                    <i title="Add to Favorites" class="icon-file-add text-primary"></i>--}}
    {{--                                </x-slot>--}}
    {{--                                <x-slot:remove-label>--}}
    {{--                                    <i title="Remove From Favorites" class="icon-file-subtract"></i>--}}
    {{--                                </x-slot>--}}
    {{--                            </x-wishlist-button>--}}
    {{--                        @endif--}}
    {{--                    </div>--}}
    {{--                @endif--}}
    {{--            </x-product.main-image>--}}
    {{--        </div>--}}
    {{--        <div class="col-md-9 col-12 product-info">--}}
    {{--            @if($allowDisplayProductCode())--}}
    {{--                <x-product.item-number :product="$product"--}}
    {{--                                       format="<a href='{{ frontendSingleProductURL($product, $seoPath) }}'><b>{product_code}</b></a>"--}}
    {{--                                       element="span"--}}
    {{--                                       class="d-block"/>--}}
    {{--            @endif--}}
    {{--            <x-product.name element="span" :product="$product" class="product-title"/>--}}
    {{--            <x-product.price--}}
    {{--                    element="div"--}}
    {{--                    class="d-block fw-700 w-100 d-flex justify-content-center product-price"--}}
    {{--                    :product="$product"--}}
    {{--                    :value="$product->ERP?->Price"--}}
    {{--                    :uom="$product->ERP?->UnitOfMeasure ?? 'EA'">--}}
    {{--            </x-product.price>--}}
    {{--            <x-product.quick-action :product="$product" :seo-path="$seoPath" :index="$loop->index"/>--}}
    {{--        </div>--}}
    {{--    </div>--}}
@else
    @if ($allowFavourites() && !$isMasterProduct($product))
        {{--        <x-product.favourite-manage-button class="btn-wishlist position-absolute"--}}
        {{--                                           :already-exists="$product->exists_in_favorite ?? false"--}}
        {{--                                           :favourite-list-id="$product->favorite_list_id ?? ''"--}}
        {{--                                           :product-id="$product->Amplify_Id"/>--}}
    @endif
    <x-product.main-image :product="$product" :seo-path="$seoPath" :wrap-link="true">
        @if(!$showDiscountBadge)
            <div class="product-badge product-discount" style="left: 0">50% Off</div>
        @endif

        @if(!$showFavourite)
            <div class="product-badge" style="right: 0">
                {{--                @if(class_exists(\Amplify\Wishlist\Widgets\WishlistButton::class))--}}
                {{--                    <x-wishlist-button :product="$product" class="btn-wishlist">--}}
                {{--                        <x-slot:add-label>--}}
                {{--                            <i title="Add to Favorites" class="icon-file-add text-primary"></i>--}}
                {{--                        </x-slot>--}}
                {{--                        <x-slot:remove-label>--}}
                {{--                            <i title="Remove From Favorites" class="icon-file-subtract"></i>--}}
                {{--                        </x-slot>--}}
                {{--                    </x-wishlist-button>--}}
                {{--                @endif--}}
            </div>
        @endif
    </x-product.main-image>
    @if($allowDisplayProductCode())
        <x-product.item-number
                :product="$product"
                format="<a href='{{ frontendSingleProductURL($product, $seoPath) }}'><b>{product_code}</b></a>"
                element="span"/>
    @endif
    <x-product.name element="span" :product="$product" class="product-title d-block text-center"/>
    <x-product.price
            element="div"
            class="d-block w-100 d-flex justify-content-center product-price"
            :product="$product"
            :value="$product->ERP?->Price"
            :uom="$product->ERP?->UnitOfMeasure ?? 'EA'"
            :std-price="$product->Msrp->toFloat()"/>

    <x-product.quick-action :product="$product" :seo-path="$seoPath" :index="$loop->index"/>
@endif

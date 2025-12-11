@if($productView =='list')
    <div class="row">
        <div class="col-md-3 col-12">
            @if($showDiscountBadge)
                <div class="product-badge text-danger">50% Off</div>
            @endif
            @if ($allowFavourites() && !$isMasterProduct($product))
                <x-product.favourite-manage-button
                    class="btn-wishlist position-absolute"
                    :already-exists="$product->exists_in_favorite ?? false"
                    :favourite-list-id="$product->favorite_list_id ?? ''"
                    :product-id="$product->Amplify_Id"/>
            @endif
            <x-product.main-image
                :product="$product"
                :seo-path="$seoPath"
                :wrap-link="true"/>
        </div>
        <div class="col-md-9 col-12 product-info">
            @if($allowDisplayProductCode())
                <x-product.item-number :product="$product" format="<b>Product Code:</b> {product_code}" element="span"
                                       class="d-block"/>
            @endif
            <x-product.name element="span" :product="$product" class="product-title"/>
            <x-product.price
                element="div"
                class="d-block fw-700 w-100 d-flex justify-content-center product-price"
                :product="$product"
                :value="$product->ERP?->Price"
                :uom="$product->ERP?->UnitOfMeasure ?? 'EA'">
            </x-product.price>
            <x-product.quick-action :product="$product" :seo-path="$seoPath" :index="$loop->index"/>
        </div>
    </div>
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
    @if($allowDisplayProductCode())
        <x-product.item-number
            :product="$product"
            format="<a href='{{ frontendSingleProductURL($product, $seoPath) }}'><b>{product_code}</b></a>"
            element="p"/>
    @endif
    <x-product.name element="span" :product="$product" class="product-title d-block text-center"/>
    <x-product.price
        element="div"
        class="d-block fw-700 w-100 d-flex justify-content-center product-price"
        :product="$product"
        :value="$product->ERP?->Price"
        :uom="$product->ERP?->UnitOfMeasure ?? 'EA'"
        :std-price="$product->Msrp->toFloat()"/>

    <x-product.quick-action :product="$product" :seo-path="$seoPath" :index="$loop->index"/>
@endif

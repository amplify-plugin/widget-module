<div {!! $htmlAttributes !!}>

    @if (isset($message))
        <p class="font-weight-bold my-2" style="font-size: 1rem">{!! $message !!}</p>
    @endif

    @if (!empty($productsData->items))
        @if ($productView === 'list')
            <div class="products product-list-view">
                @foreach($productsData->items ?? [] as $key => $product)
                    @php
                        $product_code_id = str_replace(' ', '-', $product->productCode);
                    @endphp

                    <div class="product-card product-list">
                        <a class="product-thumb" href="{{ frontendSingleProductURL($product, $seoPath) }}">
                            {{-- <div class="product-badge text-danger">BEST SELLER</div> --}}
                            <img src="{{ $product->productImage ?? '' }}" alt="Product">
                        </a>
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="{{ frontendSingleProductURL($product, $seoPath) }}" data-toggle="tooltip"
                                    data-placement="auto" title="{{ $product->productName ?? '' }}">
                                    {{ $product->productName ?? '' }}
                                </a>
                            </h3>
                            @if ($allowDisplayProductCode() && isset($product->productCode))
                                <p class="product-code text-center text-md-left mb-0">
                                    <b>Product Code:</b> {{ $product->productCode }}
                                </p>
                            @endif

                            @if ($allowDisplayProductBrand() && isset($product->Manufacturer))
                                <p class="product-brand text-center text-md-left mb-0">
                                    <b>Brand:</b>
                                    <a
                                        href="{{ frontendShopURL("-Manufacturer:{$product->Manufacturer}") }}">{{ $product->Manufacturer }}</a>
                                </p>
                            @endif

                            <h4 class="product-price justify-content-center justify-content-md-start d-flex gap-2 mt-2">
                                @if (isset($product?->ERP))
                                    @if (!$isMasterProduct($product))
                                        <span
                                            id="product_price_{{ $product_code_id ?? ''}}"
                                            class="text-danger font-weight-bold"
                                            style="font-size: 1.25rem"
                                        >
                                            {{ price_format(customer_check()? $product->ERP?->Price : ($product->ERP?->ListPrice ?? $product->ERP?->Price)) }}
                                            <small class="d-inline">/{{ $product->ERP?->PricingUnitOfMeasure }}</small>
                                        </span>
                                        @if ($displayDiscountBadge() && customer_check())
                                            <x-product.discount-badge class="ml-2 h-100 badge-primary py-1"
                                                :list-price="$product->ERP?->ListPrice ?: null" :price="$product->ERP?->Price ?: null" :display-list-price="true" />
                                        @endif
                                    @endif
                                @else
                                    {{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}
                                @endif
                            </h4>

                            <div class="hidden-xs-down">{{ $product->Description ?? '' }}</div>

                            @if (isset($product?->ERP))
                                @if ($isShowMultipleWarehouse($product))
                                    <div class="text-center text-md-left mb-3">
                                        <a class="mb-0 mb-md-5" href="#" title="Warehouse Selection"
                                            data-toggle="modal" data-target="#warehouse-selection"
                                            onclick="getWarehouseSelection('{{ $product->productCode ?? '' }}'); setPositionOffCanvas(false)">Select
                                            Warehouse</a>
                                    </div>
                                @endif
                            @endif

                            <div class="product-buttons">
                                @if ($allowFavourites() && !$isMasterProduct($product))
                                    <x-product.favourite-manage-button style="display: inline-flex" :already-exists="$product->exists_in_favorite ?? false"
                                        :favourite-list-id="$product->favorite_list_id ?? ''" :product-id="$product->Product_Id" />
                                @endif

                                @if ($isMasterProduct($product))
                                    <a href="{{ frontendSingleProductURL($product, $seoPath) }}"
                                        class="btn btn-warning btn-sm text-capitalize">
                                        View Details
                                    </a>
                                @else
                                    <input type="hidden" id="product_code_{{ $key }}" value="{{ $product->productCode ?? ''}}"/>
                                    <input type="hidden" id="product_qty_{{ $key }}" value="{{ config('amplify.pim.use_minimum_order_quantity')? ($product->entry?->min_order_qty ?: 1) : 1 }}"/>
                                    <input type="hidden" id="product_warehouse_{{ $product_code_id ?? ''}}" value="{{ $product?->ERP?->WarehouseID ?? '' }}"/>

                                    <button type="button" onclick="addSingleProductToOrder({{ $key }})"
                                        class="btn btn-warning btn-sm text-capitalize">
                                        Add To Cart
                                    </button>
                                @endif

                                @if ($allowQuickView() && $isMasterProduct($product))
                                    @if (!customer_check() || customer(true)->can('shop.add-to-cart'))
                                        <button type="button" class="btn btn-outline-primary btn-sm quick-cart-btn"
                                            title="Quick Order" data-toggle="modal" data-target="#quick-view"
                                            onclick="getQuickDetails('{{ $product->Product_Id }}'); setPositionOffCanvas(false)"><i
                                                class="icon-stack-2"></i></button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row  product-grid-view">
                @foreach($productsData->items ?? [] as $key => $product)
                    @php
                        $product_code_id = str_replace(' ', '-', $product->productCode);
                    @endphp

                    <div class="col-md-4 col-sm-6 mb-3 px-2">
                        <div class="grid-item">
                            <div class="product-card">
                                <a class="product-thumb" href="{{ frontendSingleProductURL($product, $seoPath) }}">
                                    {{-- <div class="product-badge text-danger">BEST SELLER</div> --}}
                                    <img src="{{ $product->productImage ?? '' }}" alt="Product">
                                </a>
                                <div class="product-info">
                                    <h3 class="product-title">
                                        <a href="{{ frontendSingleProductURL($product, $seoPath) }}"
                                            data-toggle="tooltip" data-placement="auto"
                                            title="{{ $product->productName ?? '' }}">
                                            {{ $product->productName ?? '' }}
                                        </a>
                                    </h3>

                                    @if ($allowDisplayProductCode() && isset($product->productCode))
                                        <p class="product-code text-center mb-0">
                                            <b>Product Code:</b> {{ $product->productCode }}
                                        </p>
                                    @endif

                                    @if ($allowDisplayProductBrand() && isset($product->Manufacturer))
                                        <p class="product-brand text-center mb-0">
                                            <b>Brand:</b>
                                            <a
                                                href="{{ frontendShopURL("-Manufacturer:{$product->Manufacturer}") }}">{{ $product->Manufacturer }}</a>
                                        </p>
                                    @endif

{{--                                    <h4 class="product-price mt-2">--}}
                                        @if (isset($product?->ERP))
                                            @if (!$isMasterProduct($product))
                                                @if (config('amplify.pim.use_minimum_order_quantity'))
                                                    <p class="text-sm text-center mb-0">
                                                        <b>Min Order Qty: </b>{{ $product->entry?->min_order_qty ?: 1}}
                                                    </p>

                                                    <p class="text-sm text-small text-center mb-0">
                                                        <b>Qty Interval: </b>{{ $product->entry?->qty_interval ?: 1}}
                                                    </p>
                                                @endif
                                                <h4 class="product-price mt-2">
                                                    <span class="text-danger font-weight-bold" style="font-size: 1.25rem">
                                                        <span id="product_price_{{ $product_code_id ?? ''}}">
                                                            {{ price_format(customer_check()? $product->ERP?->Price : ($product->ERP?->ListPrice ?? $product->ERP?->Price)) }}
                                                        </span>
                                                        <small
                                                            class="d-inline">/{{ $product->ERP?->PricingUnitOfMeasure }}</small>
                                                    </span>
                                                    @if ($displayDiscountBadge() && customer_check())
                                                        <x-product.discount-badge class="ml-2 h-100 badge-primary py-1"
                                                            :list-price="$product->ERP?->ListPrice ?: null" :price="$product->ERP?->Price ?: null" :display-list-price="true" />
                                                    @endif
                                                </h4>
                                            @endif
                                        @else
                                            <h4>{{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}</h4>
                                        @endif
{{--                                    </h4>--}}

                                    @if (isset($product?->ERP))
                                        @if ($isShowMultipleWarehouse($product))
                                            <div class="text-center d-block mt-3">
                                                <a href="#" title="Warehouse Selection" data-toggle="modal"
                                                    data-target="#warehouse-selection"
                                                    onclick="getWarehouseSelection('{{ $product->productCode ?? '' }}'); setPositionOffCanvas(false)">Select
                                                    Warehouse</a>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="product-buttons  d-flex justify-content-between gap-3">
                                        @if ($allowFavourites() && !$isMasterProduct($product))
                                            <x-product.favourite-manage-button :already-exists="$product->exists_in_favorite ?? false" :favourite-list-id="$product->favorite_list_id ?? ''"
                                                :product-id="$product->Product_Id" />
                                        @endif

                                        @if ($isMasterProduct($product))
                                            <a href="{{ frontendSingleProductURL($product, $seoPath) }}"
                                                class="btn btn-warning btn-sm btn-block text-capitalize">
                                                View Details
                                            </a>
                                        @else
                                            <input type="hidden" id="product_code_{{ $key }}" value="{{ $product->productCode ?? ''}}"/>
                                            <input type="hidden" id="product_qty_{{ $key }}" value="{{ config('amplify.pim.use_minimum_order_quantity')? ($product->entry?->min_order_qty ?: 1) : 1 }}"/>
                                            <input type="hidden" id="product_warehouse_{{ $product_code_id ?? ''}}" value="{{ $product?->ERP?->WarehouseID ?? '' }}"/>

                                            <a href="#" onclick="addSingleProductToOrder({{ $key }})"
                                                class="btn btn-warning btn-sm btn-block text-capitalize">
                                                Add To Cart
                                            </a>
                                        @endif

                                        @if ($allowQuickView() && $isMasterProduct($product))
                                            @if (!customer_check() || customer(true)->can('shop.add-to-cart'))
                                                <button class="btn btn-outline-primary btn-sm quick-cart-btn"
                                                    title="Quick Order" data-toggle="modal" data-target="#quick-view"
                                                    onclick="getQuickDetails('{{ $product->Product_Id }}'); setPositionOffCanvas(false)"><i
                                                        class="icon-stack-2"></i></button>
                                            @endif
                                        @endif
                                    </div>
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
@include('widget::inc.modal.warehouse-selection')

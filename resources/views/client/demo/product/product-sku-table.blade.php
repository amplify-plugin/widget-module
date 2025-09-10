<div {!! $htmlAttributes !!}>
    @if(count($sku_products) > 0)
        <!-- Product Table-->
        <div class="row mb-3">
            <div class="col-12 mb-4 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                <h2 class="fw-700 mb-0">Products</h2>
                <div class="d-flex product-btn-group">
                    <button
                        class="flex-grow-1 btn fw-600 btn-outline-warning clear-attributes-filter"
                        onclick="resetFilterAttribute()"
                    >Show all
                    </button>

                    @if(customer_check() || config('amplify.basic.enable_guest_pricing'))
                        <button
                            style="background: #F3702110;color: #2B2B2B"
                            class="flex-grow-1 btn fw-600 btn-warning"
                            onclick="resetQuantity()"
                        >Reset Qty.
                        </button>

                        <button class="flex-grow-1 btn fw-600 btn-warning"
                                onclick="addMultipleProductToOrder()"
                                id="add_to_order_btn_"
                        >Add to Cart
                        </button>
                    @endif
                </div>
            </div>

            <x-product-favorite-list/>

            <div class="col-12">
                <div class="products-table border rounded" id="sku_details_table_body">
                    @foreach($sku_products as $product)
                        @php
                            $product_code_id = str_replace(' ', '-', $product->product_code);
                        @endphp

                        <div
                            class="sku-item border-bottom gap-3 p-3 d-flex flex-wrap flex-md-nowrap justify-content-between"
                            data-product-code="{{ $product->product_code }}"
                            data-product-id="{{ $product->id }}"
                            data-qty="" data-warehouse=""
                        >
                            <div class="d-flex gap-3">
                                <div class="position-relative">
                                    <img class="w-120" src="{{ $product->productImage->main }}" alt="product">
                                    @if($allowFavourites())
                                        <x-product.favourite-manage-button
                                            style="top: 0; right: 0"
                                            class="position-absolute"
                                            :already-exists="$product->exists_in_favorite"
                                            :favourite-list-id="$product->favorite_list_id"
                                            :product-id="$product->id"
                                        />
                                    @endif
                                </div>
                                <div>
                                    <p class="text-uppercase">
                                    <span
                                        class="d-block d-md-inline font-weight-bold mr-md-2 mb-2 mb-md-0">{{ $product->local_product_name }}</span>
                                        {!! $product->description !!}
                                    </p>
                                    <p><b>Product Code:</b> {{ $product->product_code }}</p>


                                    <div class="d-flex flex-wrap gap-col-3">
                                        @foreach ($product->attributes as $attribute)
                                            @php $attrVal = json_decode($attribute->pivot->attribute_value, true)['en'] ?? ""; @endphp
                                            <p class="mb-1"
                                               filter-attribute="{{ $attribute->local_name.'-'.$attrVal }}">
                                                <b>{{ $attribute->local_name }}:</b> {{ $attrVal }}
                                            </p>
                                        @endforeach
                                    </div>

                                    <input type="hidden" id="product_code" value="{{ $product->product_Code }}" />
                                    <input type="hidden" id="product_warehouse_{{ $product_code_id }}" value="{{ $product?->ERP?->WarehouseID ?? '' }}"/>

                                    @if ($isShowMultipleWarehouse())
                                        <a
                                            href="#"
                                            title="Warehouse Selection"
                                            data-toggle="modal"
                                            data-target="#warehouse-selection"
                                            onclick="getWarehouseSelection('{{ $product->product_code }}'); setPositionOffCanvas(false)"
                                        >Select Warehouse</a>
                                    @endif
                                </div>

                                @include('widget::inc.modal.warehouse-selection')

                            </div>
                            <div class="d-flex gap-3">
                                @if ($product?->ERP)
                                    <div class="w-260 flex-shrink-0">
                                        <span id="product_sku_qty_break_{{ $product_code_id }}">
                                            @for ($i = 1; $i <= 6; $i++)
                                            @if ($product?->ERP["QtyPrice_{$i}"])
                                                <p class="d-flex justify-content-between mb-2">
                                                    <span>{{ $product?->ERP["QtyBreak_{$i}"] }}+</span>
                                                    <span>{{ price_format($product?->ERP["QtyPrice_{$i}"] ?? 0) }}</span>
                                                </p>
                                            @endif
                                        @endfor
                                        </span>

                                        <p class="d-flex justify-content-between mb-2">
                                            <span><b>Your Price</b>/ {{ $product->ERP->PricingUnitOfMeasure }}</span>
                                            <span id="product_price_{{ $product_code_id }}">
                                            @if ($product?->campaignProduct)
                                                    <del>
                                                    {{ price_format($product->ERP->Price) }}
                                                </del>
                                                    <b>{{ price_format($product->campaignProduct->discount) }}</b>
                                                @else
                                                    {{ price_format($product->ERP->Price) }}
                                                @endif
                                        </span>
                                        </p>

                                        @if (config('amplify.pim.use_minimum_order_quantity'))
                                            <p class="d-flex justify-content-between mb-2">
                                                <span><b>Min. Order Qty</b></span>
                                                <span class="sku-min-qty">{{ $product->min_order_qty ?: 1 }}</span>
                                            </p>

                                            <p class="d-flex justify-content-between mb-2">
                                                <span><b>Qty. Interval</b></span>
                                                <span class="sku-qty-intrvl">{{ $product->qty_interval ?: 1}}</span>
                                            </p>
                                        @endif

                                        <div class="d-flex align-items-center justify-content-between cs-w-420">
                                            <div><b>Quantity/{{ $product->ERP->PricingUnitOfMeasure }}</b></div>
                                            <div class="gap-3 count align-items-center p-2 border rounded d-flex">
                                            <span
                                                class="qty-minus bg-secondary text-dark d-flex align-items-center justify-content-center fw-600">
                                                <i class="icon-minus"></i>
                                            </span>
                                                <p class="mb-0 mx-2 fw-600 qty-field">0</p>
                                                <span
                                                    class="qty-plus bg-warning text-white d-flex align-items-center justify-content-center fw-600">
                                                <i class="icon-plus"></i>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function resetFilterAttribute() {
        $('.product-attribute').val('').trigger('change');
    }

    function resetQuantity() {
        $('.sku-item').data('qty', 0);
        $('.qty-field').html(0);
    }
</script>

@php

    push_js("
        $('.qty-plus').on('click', function (e) {
            let target = $(e.currentTarget);
            let parentItem = target.closest('.sku-item');
            let qtyField = target.prev();
            let qty = parseInt(qtyField.html());

            let minQty = parseInt(parentItem.find('.sku-min-qty').text()) || 1;
            let interval = parseInt(parentItem.find('.sku-qty-intrvl').text()) || 1;

            if (qty === 0) {
                qty = minQty;
            } else {
                qty += interval;
            }

            qtyField.html(qty);
            parentItem.data('qty', qty);
        });

        $('.qty-minus').on('click', function (e) {
            let target = $(e.currentTarget);
            let parentItem = target.closest('.sku-item');
            let qtyField = target.next();
            let qty = parseInt(qtyField.html());

            let minQty = parseInt(parentItem.find('.sku-min-qty').text()) || 1;
            let interval = parseInt(parentItem.find('.sku-qty-intrvl').text()) || 1;

            if ((qty - interval) >= minQty) {
                qty -= interval;
            } else {
                qty = 0;
            }

            qtyField.html(qty);
            parentItem.data('qty', qty);
        });
    ", 'footer-script');

@endphp


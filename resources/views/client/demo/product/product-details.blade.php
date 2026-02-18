@php
    $product_code_id = str_replace(' ', '-', $Product->productCode);
@endphp
    <!-- Product Tabs-->
<div {!! $htmlAttributes !!}>
    <div class="row mb-4">

        {!! $before ?? '' !!}

        <div class="col-12">
            <div class="row">
                <!-- Product Gallery-->
                <div class="col-md-5">
                    <x-product.product-gallery :image="$Product->product_image"
                                               :erp-additional-images="$erpAdditionalImages" />
                </div>

                <!-- Product Info-->
                <div class="col-md-7">
                    <h2 class="padding-top-1x fw-600">{{ $Product->productName }}</h2>
                    <x-product-manufacture-image :product="$Product" />

                    <section>
                        @if (!$isMasterProduct($Product))
                            <input type="hidden" id="product_code" value="{{ $Product->productCode }}" />
                            <input type="hidden" id="product_warehouse_{{ $product_code_id }}"
                                   value="{{ $Product?->ERP?->WarehouseID ?? '' }}" />

                            @if (isset($Product?->ERP))
                                <div>
                                    <span id="product_price_{{ $product_code_id }}"
                                          class="h2 text-warning fw-700">
                                        {{ price_format(customer_check()? $Product->ERP?->Price : ($Product->ERP?->ListPrice ?? $Product->ERP?->Price)) }}
                                        <small class="d-inline">/{{ $Product->ERP?->PricingUnitOfMeasure }}</small>
                                    </span>

                                    @if ($displayDiscountBadge())
                                        <x-product.discount-badge class="ml-2 h-100 badge-primary py-1"
                                                                  :list-price="$Product->ERP?->ListPrice ?: null"
                                                                  :price="$Product->ERP?->Price ?: null"
                                                                  :display-list-price="true" />
                                    @endif
                                </div>

                                @if($qtyConfig)
                                    <div class="d-flex mt-2 gap-2 text-bold">
                                        <span>Min. Order Qty:</span>
                                        <span class="text-danger">{{ $Product->min_order_qty ?: 1}}</span>
                                    </div>

                                    <div class="d-flex mb-2 gap-2 text-bold">
                                        <span>Qty. Interval:</span>
                                        <span class="text-danger">{{ $Product->qty_interval ?: 1}}</span>
                                    </div>
                                @endif

                                @if ($isShowMultipleWarehouse($Product))
                                    <a href="#" title="Warehouse Selection" data-toggle="modal"
                                       data-target="#warehouse-selection"
                                       onclick="getWarehouseSelection('{{ $Product->productCode }}'); setPositionOffCanvas(false)"
                                    >Select Warehouse</a>
                                @endif
                            @else
                                {{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}
                            @endif


                            <div class="d-flex align-items-center gap-2">
                                <x-product.favourite-manage-button style="display: inline-flex; height: 44px"
                                                                   :already-exists="$Product->exists_in_favorite ?? false"
                                                                   :favourite-list-id="$Product->favorite_list_id ?? ''"
                                                                   :product-id="$Product->Product_Id" />

                                <input type="number" class="form-control" id="single_product_qty" style="width: 80px"
                                       min="{{ $qtyConfig ? $Product->min_order_qty ?: 1 : 1}}"
                                       value="{{ $qtyConfig ? $Product->min_order_qty ?: 1 : 1}}"
                                       step="{{ $qtyConfig ? $Product->qty_interval ?: 1 : 1}}"
                                       oninput="validateQuantity(this)"
                                />

                                <a href="#" onclick="addSingleProductToOrder()"
                                   class="btn btn-warning text-capitalize">
                                    Add To Cart
                                </a>
                            </div>
                        @else
                            @if (property_exists($Product, 'ERP'))
                                <span class="h2 d-block fw-600">
                                    From - <span class="text-warning fw-700">
                                        {{ price_format($Product->ERP?->Price ?: $Product->ERP?->StandardPrice) }}
                                    </span>
                                </span>
                            @endif
                        @endif

                        <x-product-social-media-link :product="$Product" />
                    </section>

                    <div class="mb-3">
                        {!! $Product->short_description !!}
                    </div>

                    @if ($Product?->Sku_List_Attributes && $Product->Sku_Count > 1)
                        <div class="row margin-top-1x">
                            @foreach ($Product->Sku_List_Attributes as $attribute)
                                @if (!in_array($attribute->name, $Product->ignoredAttributes ?? []))
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>{{ $attribute->name }}</label>
                                            <select class="form-control product-attribute"
                                                    data-attribute-name="{{ $attribute->name }}">
                                                <option value="">Choose</option>
                                                @foreach ($attribute->attributeValueList ?? [] as $attrVal)
                                                    @if ($attrVal?->attributeValue)
                                                        <option value="{{ $attrVal->attributeValue }}">
                                                            {{ $attrVal->attributeValue }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {!! $middle ?? '' !!}

        <div class="col-12 mt-4" id="productDetailTabs">
            @include('widget::client.demo.product.product-tabs')
        </div>

        {!! $after ?? '' !!}
    </div>
</div>

@include('widget::inc.modal.warehouse-selection')

<script>
    let tagName = document.getElementById('tab-Name');
    let tabContent = document.querySelectorAll('#tab-content a');
    tabContent.forEach(ele => {
        ele.addEventListener('click', (e) => {
            console.log(e.target);
            tagName.innerText = e.target.innerText;
        });
    });

    function validateQuantity(input) {
        const minOrderQty = {{ $qtyConfig ? $Product->min_order_qty ?: 1 : 1}};
        const qtyInterval = {{ $qtyConfig ? $Product->qty_interval ?: 1 : 1}};
        let value = parseInt(input.value);

        if (value < minOrderQty) {
            input.value = minOrderQty;
        } else {
            // Calculate the remainder when (value - minOrderQty) is divided by qtyInterval
            const remainder = (value - minOrderQty) % qtyInterval;

            // If remainder is not zero, it means the quantity does not match the interval
            if (remainder !== 0) {
                // Adjust the value to the nearest valid quantity
                input.value = minOrderQty + Math.round((value - minOrderQty) / qtyInterval) * qtyInterval;
            }
        }
    }
</script>

@php

    push_js('
        $(".product-attribute").on("change", function() {
            let sku_nodes = $(".sku-item");
            sku_nodes.addClass("d-none");
            sku_nodes.removeClass("d-flex");

            let activeFilters = [];

            $(".product-attribute").each(function() {
                let attribute_name = $(this).data("attributeName");
                let attribute_value = $(this).val();

                if (attribute_name && attribute_value) {
                    activeFilters.push({ name: attribute_name, value: attribute_value });
                }
            });

            sku_nodes.each(function() {
                let sku_item = $(this);
                let match = true;

                for (let filter of activeFilters) {
                    if (!sku_item.find(`[filter-attribute="${filter.name}-${filter.value}"]`).length) {
                        match = false;
                        break;
                    }
                }

                if (match) {
                    sku_item.addClass("d-flex");
                    sku_item.removeClass("d-none");
                }
            });

            if (activeFilters.length === 0) {
                sku_nodes.addClass("d-flex");
                sku_nodes.removeClass("d-none");
            }
        });
    ', 'footer-script');

@endphp

<style>
    .iframe-container > * {
        height: 600px !important;
    }
</style>

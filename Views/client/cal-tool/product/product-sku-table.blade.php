<div {!! $htmlAttributes !!}>
    @if (count($sku_products) > 0)
        <!-- Product Table-->
        <div class="row mb-3">
            <div class="col-12 my-4">
                <h2 class="fw-700 mb-0">Products</h2>
            </div>
            <div class="col-12 d-flex flex-column flex-md-row justify-content-md-between align-items-md-center mb-2">
                <div class="dropdown mb-0">
                    <button class="btn btn-light border" data-toggle="dropdown" type="button" aria-expanded="false">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 10H15M2.5 5H17.5M7.5 15H12.5" stroke="#4D5761" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if ($product?->Sku_List_Attributes)
                        <div class="dropdown-menu w-250 p-3" onclick="event.stopPropagation()">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h4 class="mb-0">Filter</h4>
                                <button class="btn btn-sm btn-outline-secondary m-0" type="button"
                                        onclick="resetFilterAttribute()">Reset Filter
                                </button>
                            </div>
                            <div>
                                @foreach ($product->Sku_List_Attributes as $attribute)
                                    @if (!in_array($attribute->name, $product->ignoredAttributes ?? []))
                                        <div class="form-group">
                                            <label class="fw-500 pl-0">{{ $attribute->name }}</label>
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
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="d-flex product-btn-group">
                    <button class="flex-grow-1 btn btn-outline-danger clear-attributes-filter"
                            onclick="resetFilterAttribute()">Show all
                    </button>

                    @if (customer_check())
                        <button class="flex-grow-1 btn btn-danger" style="background: #FDE6E9;color: #E31E27"
                                onclick="resetQuantity()">Reset Qty.
                        </button>

                        <button class="flex-grow-1 btn btn-danger" id="add_to_order_btn_"
                                onclick="addMultipleProductToOrder()">Add to Cart
                        </button>
                    @endif
                </div>
            </div>

            <x-product-favorite-list/>

            <div class="col-12">
                <div class="products-table rounded border" id="sku_details_table_body">
                    @foreach ($sku_products as $product)
                        <div
                            class="sku-item border-bottom d-flex flex-md-nowrap justify-content-between flex-wrap gap-3 p-3"
                            data-product-code="{{ $product->product_code }}" data-product-id="{{ $product->id }}"
                            data-qty="" data-warehouse="">
                            <div class="d-flex gap-3">
                                <div class="position-relative">
                                    <img class="w-120" src="{{ $product->productImage->main }}" alt="product">
                                </div>
                                <div>
                                    <p class="text-uppercase">
                                        <span
                                            class="d-block d-md-inline font-weight-bold mr-md-2 mb-md-0 mb-2">{{ $product->local_product_name }}</span>
                                    </p>
                                    <div class="d-flex gap-col-3 flex-wrap">
                                        @foreach ($product->attributes as $attribute)
                                            @php $attrVal = json_decode($attribute->pivot->attribute_value, true)['en'] ?? ""; @endphp
                                            <p class="d-none mb-1"
                                               filter-attribute="{{ $attribute->local_name . '-' . $attrVal }}">
                                                <b>{{ $attribute->local_name }}:</b> {{ $attrVal }}
                                            </p>
                                        @endforeach
                                        <p><b>Size:</b></p>
                                        <p><b>Part#:</b></p>
                                        <p><b>Manufacturer:</b></p>
                                        <p><b>MPN:</b></p>
                                        <p><b>Color:</b></p>
                                    </div>

                                    @if ($product?->ERP?->Warehouses && ($warehouse = current($product->ERP?->Warehouses)))
                                        <p>
                                            <span class="text-success">{{ $warehouse['StockMessage'] ?? '' }}</span>
                                            / {{ $product?->ERP?->PricingUnitOfMeasure }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex w-sm-100 gap-3">
                                @if ($product?->ERP)
                                    <div class="w-sm-100 flex-shrink-0">
                                        <div class="w-260 ml-auto w-80">
                                            @for ($i = 1; $i <= 6; $i++)
                                                @if ($product?->ERP["QtyPrice_$i"])
                                                    <p class="d-flex justify-content-between mb-2">
                                                        <span>{{ $product?->ERP["QtyBreak_$i"] }}+</span>
                                                        <span>{{ price_format($product?->ERP["QtyPrice_$i"] ?? 0) }}</span>
                                                    </p>
                                                @endif
                                            @endfor

                                            <p class="d-flex justify-content-between mb-2">
                                                <span><b>Your Price</b>/
                                                    {{ $product->ERP->PricingUnitOfMeasure }}</span>
                                                <span>
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
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-2">
                                            @if ($allowFavourites())
                                                <x-product.favourite-manage-button
                                                    :already-exists="$product->exists_in_favorite"
                                                    :favourite-list-id="$product->favorite_list_id"
                                                    :product-id="$product->id"/>
                                            @endif
                                            <div class="count align-items-center d-flex gap-2">
                                                <div>Quantity:</div>
                                                <span
                                                    class="qty-minus d-flex align-items-center justify-content-center text-dark bg-secondary">
                                                    <i class="icon-minus fw-600"></i>
                                                </span>
                                                <p class="fw-600 qty-field mx-2 mb-0">0</p>
                                                <span
                                                    class="qty-plus d-flex align-items-center justify-content-center text-white">
                                                    <i class="icon-plus fw-600"></i>
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
    push_js(
        "
        $('.qty-plus').on('click', function (e) {
            let target = $(e.currentTarget);
            let qty;
            if(qty = parseInt(target.prev().html())+1) {
                target.prev().html(qty);
                target.closest('.sku-item').data('qty', qty);
            }
        });

        $('.qty-minus').on('click', function (e) {
            let target = $(e.currentTarget);
            let qty;
            if((qty = parseInt(target.next().html())-1) >= 0) {
                target.next().html(qty);
                target.closest('.sku-item').data('qty', qty);
            }
        });
    ",
        'footer-script',
    );
@endphp

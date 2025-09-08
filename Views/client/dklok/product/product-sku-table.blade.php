<div {!! $htmlAttributes !!}>
    @if (count($sku_products) > 0)
        <!-- Product Table-->
        <div id="sku_details_table_body" class="w-100">
            @foreach ($sku_products as $key => $product)
                <div
                    class="sku-item rounded border d-flex flex-md-nowrap justify-content-between flex-wrap gap-3 p-3 sku-item-bg m-3"
                    data-product-code="{{ $product->product_code }}" data-product-id="{{ $product->id }}"
                    data-qty="" data-warehouse="">
                    <div class="row w-100">
                        <a
                            href="{{ frontendSingleProductURL($product, $seoPath) }}"
                            class="w-100 d-flex flex-column justify-content-center align-items-center col-md-4 col-xl-2 text-center mb-2 mb-xl-0"
                        >
                            <p class="text-uppercase font-weight-bold text-nowrap">
                                {{ $product->product_code }}
                            </p>
                            <img class="w-100 w-max-250" src="{{ $product->productImage?->main }}" alt="product">
                        </a>
                        <div class="px-xl-0 col-md-8 col-xl-6">
                            <div class="row">
                                @foreach ($product->filtered_attributes as $attribute)
                                    @php
                                        $attrVal = json_decode($attribute->pivot->attribute_value, true)['en'] ?? "";
                                    @endphp
                                    <p class="mb-1 col-4"
                                       filter-attribute="{{ $attribute->local_name . '-' . $attrVal }}">
                                        <b>{{ $attribute->local_name }}:</b> {{ $attrVal }}
                                    </p>
                                @endforeach
                            </div>

                            @if ($product?->ERP?->Warehouses && ($warehouse = current($product->ERP?->Warehouses)))
                                <p>
                                    <span class="text-success">{{ $warehouse['StockMessage'] ?? '' }}</span>
                                    / {{ $product?->ERP?->PricingUnitOfMeasure }}
                                </p>
                            @endif
                        </div>
                        <div class="px-xl-0 col-sm-6 col-xl-2 d-flex align-items-center">
                            <x-product-shopping-list :product-id="$product->Product_Id"/>
                        </div>
                        <div class="px-xl-0 pr-xl-2 col-sm-6 col-xl-2 d-flex align-items-center w-sm-100 gap-3">
                            @if ($product?->ERP)
                                <div class="w-sm-100 flex-shrink-0">
                                    <div class="w-200 ml-auto w-80">
                                        <p class="d-flex gap-1 mb-2">
                                            <span>
                                                <b>Customer Price: </b>
                                                <span class="text-danger text-bold">{{ price_format($product->ERP->Price) }}/</span>
                                            </span>
                                            <span  class="text-danger text-bold">
                                                {{ $product->ERP->UnitOfMeasure }}
                                            </span>
                                        </p>
                                        <p class="d-flex gap-1 mb-2">
                                            <span>
                                                <b>List Price: </b>
                                                <span class="text-primary text-bold">{{ price_format($product->ERP->ListPrice) }}/</span>
                                            </span>
                                            <span class="text-primary text-bold">
                                                {{ $product->ERP->UnitOfMeasure }}
                                            </span>
                                        </p>
                                        <p class="d-flex gap-1 mb-2">
                                            <span>
                                                <b>Quantity Available: </b>
                                                <span class="text-bold">{{ $product->ERP->QuantityAvailable ?? '' }} {{ $product->ERP->UnitOfMeasure }} </span>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex flex-row row-gap-2 w-100">
                                        <span class="d-none" id="customer_back_order_code" data-status="{{ optional(customer())->allow_backorder ? 'Y' : 'N' }}"></span>
                                        <input id="product_code_{{ $key }}" name="product_code[]" type="hidden" value="{{ $product->product_code ?? '' }}" />
                                        <input id="product_id_{{ $key }}" name="product_id[]" type="hidden" value="{{ $product->id ?? '' }}" />
                                        <input id="{{ 'product_warehouse_' . $key }}" type="hidden"
                                               value="{{ optional(optional(customer(true))->warehouse)->code }}" />
                                        <p class="fw-600 mr-2">Quantity:</p>
                                        <div class="product-count d-flex gap-2 align-items-center mb-4">
                                            <span
                                                class="text-dark d-flex flex-1 align-items-center justify-content-center fw-600 rounded border product-qty-btn"
                                                onclick="productQuantity({{ $key }},'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                                            >
                                                <i class="icon-minus fw-700"></i>
                                            </span>

                                            <div
                                                class="h-100 fw-600 d-flex align-items-center justify-content-center">
                                                @include('widget::client.dklok.product.inc.partial', [
                                                    'product' => $product,
                                                    'key' => $key,
                                                ])
                                            </div>
                                            <span
                                                class="d-flex flex-1 flex-wrap align-items-center justify-content-center fw-600 bg-primary rounded border text-white product-qty-btn"
                                                onclick="productQuantity({{ $key }},'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                                            >
                                                <i class="icon-plus fw-700"></i>
                                            </span>
                                        </div>
                                        <button
                                            class="btn btn-primary btn-sm btn-block fw-600 m-0"
                                            id="add_to_order_btn_{{ $key }}" data-toast-position="topRight"
                                            onclick="addSingleProductToOrder('{{ $key }}')"
                                        >
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @if(! customer_check())
                                <a class="btn btn-primary btn-sm btn-block text-capitalize"
                                   href="{{ frontendSingleProductURL($product, $seoPath) }}">
                                    View Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="max-w-95 alert rounded border border-danger alert-danger mx-auto">No Items Found!</div>
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

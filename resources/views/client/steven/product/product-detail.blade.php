<div {!! $htmlAttributes !!}>
    <x-product-favorite-list />
    <div class="row">
        <div class="col-md-4">
            <x-product.product-gallery :image="$product?->product_image" />
        </div>

        <div class="col-md-8">
            <div class="product-dtl">
                <div class="product-info">
                    <h1 class="text-dark my-0">{{ $product->Product_Code ?? '' }}</h1>
                    <p class="text-dark product-name">{!! $product->Product_Name ?? '' !!}</p>
                </div>
                <div class="row product-description mt-4 mr-0 ml-0">
                    <div class="col-md-5 bg-white rounded">
                        <table class="table table-fixed">
                            <tr class="mb-2">
                                <th scope="col" width="55%"><strong>Manufacturer Item :</strong></th>
                                <td>N/A</td>
                            </tr>
                            <tr class="mb-2">
                                <th scope="col" width="55%"><strong>Quantity Available :</strong></th>
                                <td>{{ $product?->ERP?->QuantityAvailable ?? '' }}</td>
                            </tr>
                            <tr class="mb-2">
                                <th scope="col" width="55%"><strong>Your Price :</strong></th>
                                <td>{{ currency_format($product->ERP?->Price ?? null, null, true) }}</td>
                            </tr>
                            <tr class="mb-2">
                                <th scope="col" width="55%"><strong>Unit of Measurement :</strong></th>
                                <td>{{ $product?->ERP?->UnitOfMeasure ?? '' }}</td>
                            </tr>
                            <tr class="mb-2">
                                <th scope="col" width="55%"><strong>Next Quantity Break :</strong></th>
                                <td>{{ $product?->ERP?->QtyBreak_1 ?? '' }}</td>
                            </tr>
                            <tr class="mb-2">
                                <th scope="col" width="55%"><strong>Next Quantity Break Price :</strong></th>
                                <td>{{ $product?->ERP?->QtyPrice_1 ? currency_format($product?->ERP->QtyPrice_1 ?? null, null, true) : 'Call for Pricing' }}
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" width="55%"><strong>Estimated Lead Time:</strong></th>
                                <td>{{ $product->ERP?->AverageLeadTime ?? '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">{!!  $product->ship_restriction ?? null !!}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-7 bg-white rounded border border-primary pt-3">
                        <table class="table table-fixed">
                            <thead>
                            <tr>
                                <th colspan="3" class="inventory-heading">Inventory by Warehouse :</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($product?->warehouses as $key => $warehouse)
                                <tr
                                        class="@if ($key % 2 == 0) product_detail_table_bg_color @endif ">
                                    <th scope="row">{{ $warehouse['name'] }}</th>
                                    <td class="text-right">
                                        {{ $warehouse['quantity_available'] ?? 0}}
                                    </td>
                                    <td width="50" @class(["text-center", 'd-none' => $warehouse['quantity_available'] ==0])>
                                        {{ $product?->ERP?->UnitOfMeasure ?? '' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- // END Table -->
                <br>
                <!-- // datasheet  -->
                <x-product.default-document-link :document="$product->default_document"
                                                 class="list_shop_datasheet_product" />
                <!-- // start quantity -->
                <div class="row">
                    <div class="col-md-5 d-grid border-right">

                        <div class="w-100">
                            <div class="align-items-center d-flex product-count mt-2 gap-2 justify-content-between">
                                <div class="fw-500 align-self-center">Quantity:</div>
                                <span
                                        class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                                        onclick="productQuantity(1,'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                                    <i class="icon-minus fw-700"></i>
                                </span>

                                <input type="text" class="form-control form-control-sm text-center"
                                       style="height: 30px; border-radius: 0 !important; border: 1px solid #999999;"
                                       id="product_qty_1"
                                       name="qty[]" value="{{ $product?->min_order_qty ?? 1 }}"
                                       min="{{ $product?->min_order_qty ?? 1 }}" step="{{ $product?->qty_interval }}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                <input type="hidden" id="product_code_1" value="{{ $product->Product_Code }}" />
                                <input id="product_warehouse_1" type="hidden"
                                       value="{{ optional(optional(customer(true))->warehouse)->code }}" />
                                <input type="hidden" id="product_warehouse_{{ $product->Product_Code }}"
                                       value="{{ $product?->ERP?->WarehouseID ?? '' }}" />

                                <span class="text-white bg-dark d-flex align-items-center justify-content-center
                                    fw-600 flex-shrink-0 rounded border"
                                      onclick="productQuantity(1,'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                                    <i class="icon-plus fw-700"></i>
                                </span>
                            </div>
                        </div>

                        <div class="flex-row justify-content-start content_custom">
                            <button id="add_to_order_btn_1" class="add_to_cart_custom"
                                    onclick="addSingleProductToOrder('1')">Add to Cart
                            </button>
                        </div>
                    </div>
                    <!-- // start colops -->
                    <div class="col-md-7">
                        <!--suppress BladeUnknownComponentInspection -->
                        <x-product-customer-part-number
                                class="d-flex justify-content-between gap-2 align-items-center"
                                :product-id="$product->Product_Id"
                        />
                        <div class="row">
                            <div class="col-md-6">
                                @if (customer_check())
                                    <button @class([
                                        'btn btn-sm btn-block',
                                        'btn-outline-danger' => $product?->exists_in_favorite,
                                        'btn-outline-primary' => !$product?->exists_in_favorite,
                                    ])
                                            onclick="@if ($product?->exists_in_favorite) removeItemFromCustomerList({{ $product?->favorite_list_id }}, {{ $product?->Product_Id }}); @else initCustomerListItemModal(this, '{{ $product?->Product_Id }}'); @endif">
                                        @if ($product?->exists_in_favorite)
                                            Remove from Favorites
                                        @else
                                            Add to Favorites
                                        @endif
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-block btn-outline-primary"
                                            onclick="alert('You need to be logged in to access this feature.')">
                                        Add to Favorites
                                    </button>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <x-product-shopping-list :product-id="$product->Product_Id" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-product.information-tabs
            :product="$product"
            feature-specs-view="table"
            :skip="[\Amplify\System\Backend\Models\Product::TAB_DESCRIPTION, \Amplify\System\Backend\Models\Product::TAB_DOCUMENT]"
    >
        <x-slot:before>
            <svg width="0" height="0">
                <defs>
                    <clipPath id="tabClip" clipPathUnits="objectBoundingBox">
                        <path
                                d="
                            M-0.01,1
                            C0.08,0.9 0.08,0 0.17,0
                            H0.83
                            C0.92,0 0.92,0.9 1.01,1
                        "
                        />
                    </clipPath>
                    <clipPath id="tabClip2" clipPathUnits="objectBoundingBox">
                        <path
                                d="
                            M0,1
                            C0.08,0.9 0.08,0 0.17,0
                            H0.83
                            C0.92,0 0.92,0.9 1,1
            			"
                        />
                    </clipPath>
                </defs>
            </svg>
        </x-slot:before>
        <x-product.extra-tab title="SKUs" :should-render="true">
            <x-product-sku-table :product="$product" />
        </x-product.extra-tab>
    </x-product.information-tabs>
</div>

<script>
    function productQuantity(id, type, interval, min) {
        let item = document.getElementById(`product_qty_${id}`);
        let val = parseInt(item.value);
        switch (type) {
            case 'plus':
                item.value = val + interval;
                break;
            case 'minus':
                if (val > min) {
                    item.value = val - interval;
                }
                break;
        }
    }
</script>

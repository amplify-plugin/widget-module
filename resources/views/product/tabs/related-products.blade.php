<div class="product-sku-table">
    {{-- <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
            <label class="form-check-label fs-16 text-black" for="inlineRadio1">Accessories</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
            <label class="form-check-label fs-16 text-black" for="inlineRadio2">Alternative</label>
        </div>
    </div> --}}
    @if (!empty($relatedProducts) && $relatedProducts->isNotEmpty())
        @foreach ($relatedProducts as $rp)
            @php $idx = $loop->index; @endphp
            <div class="product-sku-item text-black">
                <div class="w-100 d-flex justify-content-between align-items-sm-center gap-4 flex-column flex-sm-row">
                    <div class="product-img" style="height: 105px; width: 120px; border-radius: 8px; padding: 8px;">
                        <img src="{{ $rp?->productImage?->main ?? '' }}" alt="Product"
                            style="height: 100%; width: 100%; object-fit: contain;">
                    </div>
                    <div>
                        <div class="text-uppercase fs-16">{{ $rp->product_code ?? '' }}</div>
                        <div class="mb-2 font-roboto">{{ $rp->product_name ?? '' }}</div>
                        @php
                            $specs = collect($rp->specifications ?? []);
                            $first = $specs->slice(0, 2);
                            $second = $specs->slice(2, 2);
                        @endphp
                        <div class="specs-container d-flex gap-3">
                            {{-- first spec column (always present to reserve space) --}}
                            <div class="spec-column" style="min-width:180px;">
                                @foreach($first as $s)
                                    <div class="text-nowrap">{{ $s->name ?? ($s['name'] ?? '') }} :</div>
                                    <div class="font-roboto text-nowrap">{{ $s->value ?? ($s['value'] ?? '') }}</div>
                                @endforeach
                            </div>

                            {{-- second spec column (always present to reserve space) --}}
                            <div class="spec-column" style="min-width:180px;">
                                @foreach($second as $s)
                                    <div class="text-nowrap">{{ $s->name ?? ($s['name'] ?? '') }} :</div>
                                    <div class="font-roboto text-nowrap">{{ $s->value ?? ($s['value'] ?? '') }}</div>
                                @endforeach
                            </div>

                            {{-- availability / price column (always rendered in third position) --}}
                            <div class="spec-column" style="min-width:200px;">
                                <div class="text-nowrap">Available Qyt :</div>
                                <div class="font-roboto text-nowrap">
                                    @if ($rp->assembled)
                                        Assembled Item
                                    @else
                                        <x-product.availability :product="$rp" :value="$rp->total_quantity_available" />
                                    @endif
                                </div>

                                <div class="text-nowrap">Price :</div>

                                <x-product.price element="div" :product="$rp" class="font-roboto text-nowrap"
                                    :value="$rp->ERP?->Price" :uom="$rp->ERP?->UnitOfMeasure ?? 'EA'" />
                            </div>
                        </div>

                    </div>
                    <div class="d-flex gap-3 align-self-sm-end flex-wrap">
                        <div class="d-flex gap-2 flex-column m-0">
                            <x-wishlist-button :product="$rp"
                                class="flex-center gap-2 btn btn-block btn-outline-primary btn-sm m-0">
                                <x-slot:add-label>
                                    Add To Wishlist
                                </x-slot>
                                <x-slot:remove-label>
                                    Remove from Wishlist
                                </x-slot>
                            </x-wishlist-button>

                            <x-product-shopping-list :product-id="$rp->id" />
                        </div>
                        <div class="d-flex flex-column align-self-end gap-2">
                            <div class="w-100">
                                <div class="fw-500 align-self-center">Quantity:</div>
                                <div class="align-items-center d-flex product-count mt-2 gap-2 justify-content-between">
                                    <span
                                        class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                                        onclick="productQuantity({{ $idx }}, 'minus', {{ $rp?->qty_interval ?? 1 }}, {{ $rp?->min_order_qty ?? 1 }})">
                                        <i class="icon-minus fw-700"></i>
                                    </span>

                                    <input type="text" class="form-control form-control-sm text-center"
                                        style="height: 30px; border-radius: 0 !important; border: 1px solid #999999;"
                                        id="product_qty_{{ $idx }}" name="qty[]"
                                        value="{{ $rp?->min_order_qty ?? 1 }}" min="{{ $rp?->min_order_qty ?? 1 }}"
                                        step="{{ $rp?->qty_interval }}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                    <input type="hidden" id="product_code_{{ $idx }}"
                                        value="{{ $rp->product_code ?? $rp->Product_Code }}" />
                                    <input id="product_warehouse_{{ $idx }}" type="hidden"
                                        value="{{ $rp?->ERP?->WarehouseID ?? (optional(optional(customer(true))->warehouse)->code ?? '') }}" />

                                    <span
                                        class="text-white bg-dark d-flex align-items-center justify-content-center
                        fw-600 flex-shrink-0 rounded border"
                                        onclick="productQuantity({{ $idx }}, 'plus', {{ $rp?->qty_interval ?? 1 }}, {{ $rp?->min_order_qty ?? 1 }})">
                                        <i class="icon-plus fw-700"></i>
                                    </span>
                                </div>
                            </div>
                            <button id="add_to_order_btn_{{ $idx }}" class="add_to_cart_custom"
                                onclick="addSingleProductToOrder({{ $idx }})">Add to cart</button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between gap-4 w-100 flex-wrap">
                    <div>
                        {!! $rp->ship_restriction ?? null !!}
                    </div>

                    <x-product.ncnr-item-flag :product="$rp" :show-full-form="true" />
                    <div class="d-flex gap-2">
                        <x-product.default-document-link :document="$rp->default_document" class="list_shop_datasheet_product" />
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

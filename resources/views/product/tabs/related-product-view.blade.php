<div class="product-sku-table" id="related-products-content"
     style="max-height: 500px; overflow-y: auto; padding-right: 10px;">
    Relation type selector
    @if (!empty($relationTypes) && $relationTypes->isNotEmpty())
        <div class="d-flex justify-content-start align-items-center gap-2 mb-3 relation-type-group">
            @foreach ($relationTypes as $rt)
                @php
                    $active = (int) ($selectedRelationType ?? $relationTypes->first()->id) === (int) $rt->id;
                @endphp
                <div class="form-check form-check-inline">
                    <input
                            class="form-check-input relation-type-radio"
                            type="radio"
                            name="relation_type"
                            id="relation_type_{{ $rt->id }}"
                            value="{{ $rt->id }}"
                            data-url="{{ route('frontend.shop.relatedProducts', ['product' => $product->id, 'relation_type'=> $rt->id]) }}"
                            {{ $active ? 'checked' : '' }}
                    >
                    <label class="form-check-label fs-16 text-black" for="relation_type_{{ $rt->id }}">
                        {{ $rt->name }}
                    </label>
                </div>
            @endforeach
        </div>
    @endif

    @foreach ($relatedProducts as $rp)
        @php $idx = $loop->index + 2; @endphp
        <div class="product-sku-item text-black">
            <div class="w-100 d-flex  align-items-sm-center gap-4 flex-column flex-sm-row">
                <div class="product-img"
                     style="height: 105px; width: 120px; border-radius: 8px; padding: 8px;">
                    <img src="{{ $rp?->productImage?->main ?? asset(config('amplify.frontend.fallback_image_path')) }}"
                         alt="Product"
                         style="height: 100%; width: 100%; object-fit: contain;">
                </div>
                <div>
                    <div class="text-uppercase fs-16"><a
                                href="{{ frontendSingleProductURL($rp ?? '#') }}"
                                target="_blank" style="text-decoration: none"
                                class="text-black">{{ $rp->product_code ?? '' }}</a>
                    </div>
                    <div class="mb-2 font-roboto" style="max-width: 700px;">
                        <a href="{{ frontendSingleProductURL($rp ?? '#') }}" target="_blank"
                           style="text-decoration: none" class="text-black">
                            {{ $rp->product_name ?? '' }}
                        </a>
                    </div>

                    @php
                        $specs = collect($rp->specifications ?? []);
                        $first = $specs->slice(0, 2);
                        $second = $specs->slice(2, 2);
                    @endphp
                    <div class="specs-container d-flex gap-3">
                        {{--                            first spec column (always present to reserve space)--}}
                        <div class="spec-column" style="min-width:180px;">
                            @foreach($first as $s)
                                <div class="text-nowrap">{{ $s->name ?? ($s['name'] ?? '') }} :</div>
                                <div class="font-roboto text-nowrap">{{ $s->value ?? ($s['value'] ?? '') }}</div>
                            @endforeach
                        </div>

                        {{--                            second spec column (always present to reserve space)--}}
                        <div class="spec-column" style="min-width:180px;">
                            @foreach($second as $s)
                                <div class="text-nowrap">{{ $s->name ?? ($s['name'] ?? '') }} :</div>
                                <div class="font-roboto text-nowrap">{{ $s->value ?? ($s['value'] ?? '') }}</div>
                            @endforeach
                        </div>

                        {{--                            availability / price column (always rendered in third position)--}}
                        <div class="spec-column" style="min-width:200px;">
                            <div class="text-nowrap">Available Qyt :</div>
                            <div class="font-roboto text-nowrap">
                                @if ($rp->assembled)
                                    Assembled Item
                                @else
                                    <x-product.availability :product="$rp"
                                                            :value="$rp->total_quantity_available"/>
                                @endif
                            </div>

                            <div class="text-nowrap">Price :</div>

                            <x-product.price element="div" :product="$rp"
                                             class="font-roboto text-nowrap"
                                             :value="$rp->ERP?->Price"
                                             :uom="$rp->ERP?->UnitOfMeasure ?? 'EA'"/>
                        </div>
                    </div>

                </div>
                <div class="d-flex gap-3 align-self-sm-end flex-wrap ml-auto">

                    <div class="d-flex flex-column align-self-end gap-2">
                        <x-product-shopping-list :product-id="$rp->id" :index="$idx"
                                                 class="w-100 mb-0"/>
                    </div>
                    <div class="d-flex flex-column align-self-end gap-2">
                        <div class="w-100">
                            <p class="fw-500 align-self-center">Quantity:</p>
                            <x-cart.quantity-update
                                    name="products[{{ $idx }}][qty]"
                                    :product="$rp"
                                    :index="$idx"
                            />
                        </div>
                        <button class="btn btn-primary btn-sm  btn-block m-0"
                                data-product-code="{{$rp->product_code}}"
                                data-warehouse="{{ $rp->ERP->WarehouseID ?? \ErpApi::getCustomerDetail()->DefaultWarehouse }}"
                                data-options="{{ json_encode(['code' => $rp->product_code]) }}"
                                onclick="Amplify.addSingleItemToCart(this, '#cart-item-{{ $idx }}')"
                                id="add_to_order_btn_{{ $idx }}">
                            {{ __('Add To Cart') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between gap-4 w-100 flex-wrap">
                <div>
                    {!! $rp->ship_restriction ?? null !!}
                </div>

                <div>
                    <x-product.ncnr-item-flag :product="$rp" :show-full-form="true"/>
                </div>

                <div class="d-flex gap-2">
                    <x-product.default-document-link :document="$rp->default_document"
                                                     class="list_shop_datasheet_product"/>
                </div>
            </div>
        </div>
    @endforeach

    @if ($relatedProducts->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $relatedProducts->withQueryString()->links() }}
        </div>
    @endif
</div>
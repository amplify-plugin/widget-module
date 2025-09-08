<div class="d-grid grid-responsive-1 grid-responsive-2 grid-responsive-3 grid-responsive-4 mb-4 gap-2">
    @foreach ($productsData as $key => $product)
        <div class="product-card product-grid">
            <div class="product-thumb">
                <a href="{{ frontendSingleProductURL($product, $seoPath) }}">
                    <img class="rounded-top aspect-square object-contain" src="{{ $product->Product_Image ?? '' }}"
                        alt="Product">
                </a>
            </div>
            <div class="product-info product-info-custom">
                <span class="text-left">
                    <a class="fw-700 text-capitalize truncate-text"
                    data-toggle="tooltip" data-placement="top" title="{!! str_replace(['-'], ' ', $product->Product_Name ?? '') !!}"
                    href="{{ frontendSingleProductURL($product, $seoPath) }}">
                        {{ str_replace(['-'], ' ', $product->Product_Name ?? '') }}
                    </a>
                </span>
                <p class="fw-700 text-center product-price">
                    @if ($product->ERP?->Price)
                        {{ currency_format($product->ERP?->Price ?? null, null, true) }}/{{ $product->ERP?->UnitOfMeasure ?? 'EA' }}
                    @else
                        Upcoming...
                    @endif
                </p>
                <span class="">Item Number:
                    <span class="span-color-weight-custom">{{ $product->productCode ?? '' }}</span>
                </span>
                <span class="d-block">Est. Lead Time: {{ $product->ERP?->AverageLeadTime ?? '' }}</span>
                <div class="flex-custom-grid-shop">
                    <span>
                        Avail. Qty:
                        <span class="span-color-weight-custom">{{ $product->ERP?->QuantityAvailable ?? '-' }}</span>
                    </span>

                    <x-product.default-document-link :document="$product->default_document" class="list_shop_datasheet_product"/>
                </div>

                <div class="flex-custom-grid-shop">
                    {!!  $product->ship_restriction ?? null !!}
                    <x-product.ncnr-item-flag :product="$product" :show-full-form="false"/>
                </div>
                <br>
                <div class="w-100">
                    <div class="align-items-center d-flex product-count gap-2 justify-content-between">
                        <div class="fw-500 align-self-center">Quantity:</div>
                        <span
                            class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                            onclick="productQuantity({{ $key }},'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                            <i class="icon-minus fw-700"></i>
                        </span>
                        <div class="align-items-center d-flex fw-600 justify-content-center">

                            @include('widget::client.steven.product.inc.partial', [
                            'product' => $product,
                            'key' => $key,
                            ])

                        </div>
                        <span
                            class="text-white bg-dark d-flex align-items-center justify-content-center
                            fw-600 flex-shrink-0 rounded border"
                            onclick="productQuantity({{ $key }},'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                            <i class="icon-plus fw-700"></i>
                        </span>
                    </div>
                    {{-- <span>
                        Qty:
                    </span>
                    <div class="quantity_count_custom">
                        <button class="button_minus"  onclick="productQuantity({{ $key }},'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 18 17"
                                    fill="none">
                                    <rect x="1.61602" y="7.4915" width="14.7667" height="2.01667" rx="1.00833"
                                        fill="#121212" stroke="#121212" stroke-width="0.6"/>
                                </svg>
                        </button>

                        @include('widget::client.steven.product.inc.partial', [
                            'product' => $product,
                            'key' => $key,
                        ])
                        <button class="button_plus"  onclick="productQuantity({{ $key }},'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 19 18"
                                fill="none">
                                <mask id="path-1-outside-1_468_6171" maskUnits="userSpaceOnUse" x="1" y="0.5"
                                    width="17" height="17" fill="black">
                                    <rect fill="white" x="1" y="0.5" width="17" height="17" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.75 15.75C8.75 16.1642 9.08579 16.5 9.5 16.5C9.91421 16.5 10.25 16.1642 10.25 15.75V9.75H16.25C16.6642 9.75 17 9.41421 17 9C17 8.58579 16.6642 8.25 16.25 8.25H10.25V2.25C10.25 1.83579 9.91421 1.5 9.5 1.5C9.08579 1.5 8.75 1.83579 8.75 2.25V8.25H2.75C2.33579 8.25 2 8.58579 2 9C2 9.41421 2.33579 9.75 2.75 9.75H8.75V15.75Z" />
                                </mask>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.75 15.75C8.75 16.1642 9.08579 16.5 9.5 16.5C9.91421 16.5 10.25 16.1642 10.25 15.75V9.75H16.25C16.6642 9.75 17 9.41421 17 9C17 8.58579 16.6642 8.25 16.25 8.25H10.25V2.25C10.25 1.83579 9.91421 1.5 9.5 1.5C9.08579 1.5 8.75 1.83579 8.75 2.25V8.25H2.75C2.33579 8.25 2 8.58579 2 9C2 9.41421 2.33579 9.75 2.75 9.75H8.75V15.75Z"
                                    fill="white" />
                                <path
                                    d="M10.25 9.75V9.15H9.65V9.75H10.25ZM10.25 8.25H9.65V8.85H10.25V8.25ZM8.75 8.25V8.85H9.35V8.25H8.75ZM8.75 9.75H9.35V9.15H8.75V9.75ZM9.5 15.9C9.41716 15.9 9.35 15.8328 9.35 15.75H8.15C8.15 16.4956 8.75442 17.1 9.5 17.1V15.9ZM9.65 15.75C9.65 15.8328 9.58284 15.9 9.5 15.9V17.1C10.2456 17.1 10.85 16.4956 10.85 15.75H9.65ZM9.65 9.75V15.75H10.85V9.75H9.65ZM16.25 9.15H10.25V10.35H16.25V9.15ZM16.4 9C16.4 9.08284 16.3328 9.15 16.25 9.15V10.35C16.9956 10.35 17.6 9.74558 17.6 9H16.4ZM16.25 8.85C16.3328 8.85 16.4 8.91716 16.4 9H17.6C17.6 8.25442 16.9956 7.65 16.25 7.65V8.85ZM10.25 8.85H16.25V7.65H10.25V8.85ZM9.65 2.25V8.25H10.85V2.25H9.65ZM9.5 2.1C9.58284 2.1 9.65 2.16716 9.65 2.25H10.85C10.85 1.50442 10.2456 0.9 9.5 0.9V2.1ZM9.35 2.25C9.35 2.16716 9.41716 2.1 9.5 2.1V0.9C8.75442 0.9 8.15 1.50442 8.15 2.25H9.35ZM9.35 8.25V2.25H8.15V8.25H9.35ZM2.75 8.85H8.75V7.65H2.75V8.85ZM2.6 9C2.6 8.91716 2.66716 8.85 2.75 8.85V7.65C2.00442 7.65 1.4 8.25442 1.4 9H2.6ZM2.75 9.15C2.66716 9.15 2.6 9.08284 2.6 9H1.4C1.4 9.74558 2.00442 10.35 2.75 10.35V9.15ZM8.75 9.15H2.75V10.35H8.75V9.15ZM9.35 15.75V9.75H8.15V15.75H9.35Z"
                                    fill="white" mask="url(#path-1-outside-1_468_6171)" />
                            </svg>
                        </button>
                    </div> --}}
                </div>
                <br>
                <div class="content_custom">
                    <button class="add_to_cart_custom" id="add_to_order_btn_{{ $key }}"
                        data-toast-icon="icon-circle-check" onclick="addSingleProductToOrder('{{ $key }}')">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

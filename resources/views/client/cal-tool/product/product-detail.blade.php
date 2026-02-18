@php
    $product_code_id = str_replace(' ', '-', $product->Product_Code);
@endphp
<div {!! $htmlAttributes !!}>

    {!! $before ?? '' !!}

    <div class="padding-bottom-2x single-product-details mb-1">
        <div class="row">
            <!-- Product Gallery-->
            <div class="col-md-5">
                <x-product.product-gallery :image="$product?->product_image" />

                @if ($product?->modelCodes->count())
                    <div class="accordion mt-4" id="modelcodes">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block my-0 text-left" data-toggle="collapse"
                                            data-target="#collapseOne" type="button" aria-expanded="true"
                                            aria-controls="collapseOne" style="padding-left: 0 !important;">
                                        Model Codes
                                    </button>
                                </h2>
                            </div>

                            <div class="collapse" id="collapseOne" data-parent="#modelcodes"
                                 aria-labelledby="headingOne">
                                <div class="card-body user-select-none">
                                    <div id="show_limited">
                                        @foreach ($product?->modelCodes->take(5) as $modelCode)
                                            <span>{{ $modelCode->code }}</span>@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="d-none" id="show_all">
                                        @foreach ($product?->modelCodes as $modelCode)
                                            <span>{{ $modelCode->code }}</span>@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                    @if ($product?->modelCodes->count() > 5)
                                        <a class="show_more_less_btn btn btn-sm p-0" type="button"
                                           onclick="toggleShowMoreLess(this);">
                                            SHOW MORE...
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Product Info-->
            <div class="col-md-7">
                <h3 class="fw-600 product-title">{{ $product?->Product_Name ?? 'Product Name Not Available' }}</h3>
                {{-- <x-product-manufacture-image :product="$product"/> --}}

                @if ($isMasterProduct($product))
                    @if (isset($product?->ERP))
                        <h3 class="fw-700">Starting From:
                            <span id="price"
                                  class="text-danger">{{ price_format($product?->ERP->Price) ?: price_format($product?->ERP->Price) }}</span>
                        </h3>
                    @else
                        <p class="text-danger">
                            {{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}
                        </p>
                    @endif
                @endif

                <div class="d-flex flex-column fw-600 mb-4 gap-2">
                    <div class="d-flex gap-2">
                        <span>Manufacturer:</span>
                        <span class="text-danger">{{ $product?->manufacturer?->name ?? '' }}</span>
                    </div>

                    <div class="d-flex gap-2">
                        <span>Brand Name:</span>
                        <span class="text-danger">{{ $product?->brand_name }}</span>
                    </div>

                    @if (!$isMasterProduct($product))
                        <div class="d-flex gap-2">
                            <span>Part#:</span>
                            <span class="text-danger">{{ $product?->Product_Code ?? '' }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <span>MPN:</span>
                            <span class="text-danger">{{ $product?->mpn ?? '' }}</span>
                        </div>
                        @if($qtyConfig)
                            <div class="d-flex gap-2">
                                <span>Min. Order Qty:</span>
                                <span class="text-danger">{{ $product?->min_order_qty }}</span>
                            </div>

                            <div class="d-flex gap-2">
                                <span>Qty. Interval:</span>
                                <span class="text-danger">{{ $product?->qty_interval }}</span>
                            </div>
                        @endif

                        @if (isset($product?->ERP))
                            <div class="d-flex gap-2">
                                <span>Inventory:</span>
                                <span id="inventory" class="text-danger">{{ $product?->ERP->QuantityAvailable }}</span>
                            </div>
                        @endif

                        @if (!$isMasterProduct($product))
                            <input type="hidden" id="product_code" value="{{ $product->Product_Code }}" />
                            <input type="hidden" id="product_warehouse_{{ $product_code_id }}"
                                   value="{{ $product?->ERP?->WarehouseID ?? '' }}" />

                            @if (isset($product?->ERP))
                                <h3 class="fw-700 mt-2">Price:
                                    <span id="price" class="text-danger">
                                        {{ price_format($product?->ERP->Price ?? $product?->ERP?->ListPrice) }}@isset($product?->ERP->PricingUnitOfMeasure)
                                            /{{ strtoupper($product->ERP->PricingUnitOfMeasure) }}
                                        @endisset
                                    </span>
                                </h3>
                            @else
                                <p class="text-danger mb-0">
                                    {{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}
                                </p>
                            @endif
                        @endif

                        @if (isset($product?->ERP))
                            <div class="w-260 fw-400">
                                @for ($i = 1; $i <= 6; $i++)
                                    @if ($product?->ERP["QtyPrice_$i"])
                                        <p class="d-flex justify-content-between mb-2">
                                            <span>{{ $product?->ERP["QtyBreak_$i"] }}+</span>
                                            <span>{{ price_format($product?->ERP["QtyPrice_$i"] ?? 0) }}</span>
                                        </p>
                                    @endif
                                @endfor

                                <p class="d-flex justify-content-between mb-2">
                                    <span><b>Your Price</b>/{{ $product?->ERP->PricingUnitOfMeasure }}</span>
                                    <span class="fw-500">
                                        {{-- @if ($product?->campaignProduct)
                                                <del>
                                                    {{ price_format($product?->ERP?->Price) }}
                                                </del>
                                                <b>{{ price_format($product?->campaignProduct->discount) }}</b>
                                            @else --}}
                                        {{ price_format($product?->ERP->Price) }}
                                        {{-- @endif --}}
                                    </span>
                                </p>
                            </div>
                        @endif

                        <div class="d-flex align-items-center flex-wrap flex-md-nowrap gap-2">
                            <div class="align-items-center d-flex product-count gap-2 w-50">
                                <div class="fw-500 align-self-center mr-2">Quantity:</div>
                                <span
                                    class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                                    onclick="productQuantity(1,'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"><i
                                        class="icon-minus fw-700"></i></span>
                                <div class="align-items-center d-flex fw-600 justify-content-center">
                                    @include('widget::client.cal-tool.product.inc.partial', [
                                        'product' => $product,
                                        'key' => '1',
                                    ])
                                </div>
                                <span
                                    class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                                    onclick="productQuantity(1,'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"><i
                                        class="icon-plus fw-700"></i></span>
                            </div>
                            <div class="w-100">
                                <select onchange="changeWarehouse(this)" class="form-control form-control-sm"
                                        id="select-warehouse">
                                    <option value="" disabled selected>Select Warehouse</option>
                                    @foreach($product->warehouses as $warehouse)
                                        <option value="{{ $warehouse['code'] }}"
                                                data-qty="{{ $warehouse['quantity_available'] }}"
                                                data-price="{{ $warehouse['price'] }}" @selected($product?->ERP?->WarehouseID == $warehouse['code'])>{{ $warehouse['name'] }}
                                            ({{ $warehouse['quantity_available'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex sp-buttons mb-2 mt-2">
                            <x-product.favourite-manage-button
                                class="btn-wishlist p-3 ml-0 top-0"
                                :already-exists="$product?->exists_in_favorite ?? ''"
                                :favourite-list-id="$product?->favorite_list_id ?? ''"
                                :product-id="$product?->Product_Id" />
                            {{-- <button class="btn btn-outline-danger" data-toggle="modal" data-target="#branchModal" --}}
                            {{--         type="button">Branch Availability --}}
                            {{-- </button> --}}
                            <button class="btn btn-danger" id="add_to_order_btn_1"
                                    onclick="addSingleProductToOrder('1')" aria-label="Add to Cart">
                                Add to Cart
                            </button>
                        </div>

                        @if (config('amplify.prop65.prop65_status'))
                            <div class="mb-3">
                                <span data-toggle="modal" data-target="#warningModal" style="cursor: pointer">
                                    <img class="warning-img" src="{{ config('amplify.prop65.prop65_icon') }}"
                                         alt="warning" width="30px">
                                    {{ config('amplify.prop65.prop65_title') }}
                                </span>
                            </div>
                        @endif
                    @endif
                </div>

                @if ($isMasterProduct($product))
                    <div>
                        <button class="btn btn-outline-danger" data-toggle="modal" data-target="#branchModal"
                                type="button">Branch Availability
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {!! $middle ?? '' !!}

        @if ($isMasterProduct($product))
            <x-product-sku-table :product="$product" is-fav-button-show="true" />
        @endif

        <!-- Product Tabs-->
        <div class="row padding-top-3x mb-3">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" role="tablist">
                    @if ($isMasterProduct($product))
                        <li class="nav-item">
                            <a class="nav-link active" href="#products" data-toggle="tab" role="tab">Products</a>
                        </li>
                    @endif
                    {{-- @if(!empty($product->description))--}}
                    <li class="nav-item">
                        <a class="nav-link @if(!$isMasterProduct($product)) active @endif" href="#description"
                           data-toggle="tab" role="tab">Description</a>
                    </li>
                    {{-- @endif--}}
                    @if(!empty($product->features))
                        <li class="nav-item">
                            <a class="nav-link" href="#features" data-toggle="tab" role="tab">Features</a>
                        </li>
                    @endif
                    @if(!empty($product->specifications))
                        <li class="nav-item">
                            <a class="nav-link" href="#specifications" data-toggle="tab" role="tab">Specifications</a>
                        </li>
                    @endif
                    @foreach ($productInfoTabs ?? [] as $tabKey => $tabs)
                        <li class="nav-item">
                            <a class="nav-link" href="#erp-tab-{{ $tabKey }}" data-toggle="tab"
                               role="tab">{{ $erpMediaType[$tabKey] ?? "Unknown" }}</a>
                        </li>
                    @endforeach
                    @foreach ($product->documents ?? [] as $document)
                        <li class="nav-item">
                            <a class="nav-link" href="#doc-tab-{{ $document->id }}" data-toggle="tab"
                               role="tab">{{ $document->documentType?->name ?? "" }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @if ($isMasterProduct($product))
                        <div class="tab-pane fade show active" id="products" role="tabpanel">
                            <x-product-sku-table
                                :product="$product"
                                is-fav-button-show="true"
                            />
                        </div>
                    @endif

                    {{-- @if(!empty($product->description))--}}
                    <div class="tab-pane fade @if (!$isMasterProduct($product)) show active @endif" id="description"
                    >
                        @if (!empty($product->short_description))
                            {!! $product->short_description !!}<br><br>
                        @endif
                        {!! $product->description !!}
                    </div>
                    {{-- @endif--}}

                    @if(!empty($product->features))
                        <div class="tab-pane fade" id="features" role="features">
                            <div class="grid-container features-tab">
                                @foreach($product->features as $feature)
                                    <div>
                                        @isset($feature->group_name)
                                            <span
                                                class="d-block font-weight-bold mb-2">{{ $feature->group_name }}</span>
                                        @endisset
                                        <ul>
                                            @foreach($feature->group_items as $item)
                                                <li>{{ $item->value }} {{--- Weight: {{ $item->weight }}--}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($product->specifications))
                        <div class="tab-pane fade" id="specifications" role="specifications">
                            <div class="grid-container features-tab">
                                @foreach($product->specifications as $specification)
                                    <div>
                                        @isset($specification->group_name)
                                            <span
                                                class="d-block font-weight-bold mb-2">{{ $specification->group_name }}</span>
                                        @endisset
                                        <ul>
                                            @foreach($specification->group_items as $item)
                                                <li>{{ $item->name }}
                                                    : {{ $item->value }} {{--<div>Weight: {{ $item->weight }}</div>--}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @foreach ($productInfoTabs ?? [] as $tabKey => $tabs)
                        <div class="tab-pane fade" id="erp-tab-{{ $tabKey }}" role="tabpanel"
                             role="erp-tab-{{ $tabKey }}">
                            @foreach ($tabs ?? [] as $tabItem)

                                @if ($tabKey === 'M')
                                    <div class="p-3">
                                        @if ($tabItem['extension'] === "pdf")
                                            <object class="w-100 iframe-style"
                                                    data="{{ 'https://spisafety.com/'.$tabItem['value'] }}"
                                                    type="application/pdf" style="height: 60vh;">
                                                <embed src="{{ 'https://spisafety.com/'.$tabItem['value'] }}"
                                                       type="application/pdf" style="width: 100% !important;" />
                                            </object>
                                        @else
                                            <div class="iframe-container">
                                                {!! $tabItem['value'] !!}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if ($tabKey === 'L')
                                    <div class="p-3">
                                        <a href="{{ $tabItem['value'] }}" target="_blank">
                                            {{ $tabItem['label'] }} <i class="pe-7s-exapnd2"></i>
                                        </a>
                                    </div>
                                @endif

                            @endforeach
                        </div>
                    @endforeach

                    @foreach ($product->documents ?? [] as $document)
                        <div class="tab-pane fade" id="doc-tab-{{ $document->id }}" role="tabpanel"
                             role="doc-tab-{{ $document->id }}">
                            @if ($document->documentType->media_type === 'image')
                                <div class="text-center">
                                    <img class="img-style" src="{{ assets_image($document->file_path) }}"
                                         alt="{{ $document->documentType->name }}">
                                </div>
                            @endif

                            @if ($document->documentType->media_type === 'video')
                                <div class="text-center">
                                    <video width="320" height="240" controls>
                                        <source src="{{ asset($document->file_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif

                            @if ($document->documentType->media_type === 'pdf')
                                <object class="iframe-style" data="{{ external_asset($document->file_path) }}"
                                        type="application/pdf">
                                    <embed src="{{ external_asset($document->file_path) }}" type="application/pdf"
                                           style="width: 100% !important;" />
                                </object>
                            @endif

                            @if ($document->documentType->media_type === 'google_doc' || $document->documentType->media_type === 'google_sheet')
                                <div class="mb-2">
                                    <a href="{{ external_asset($document->file_path) }}" target="_blank">
                                        View on Google Docs <i class="pe-7s-exapnd2"></i>
                                    </a>
                                </div>
                            @endif

                            @if ($document->documentType->media_type === 'doc' || $document->documentType->media_type === 'xls')
                                <a href="{{ external_asset($document->file_path) }}" class="btn btn-primary"
                                   download="{{ $document->documentType->name }}">Download</a>
                            @endif

                            @if ($document->documentType->media_type === 'octet-stream')
                                <a href="{{ external_asset($document->file_path) }}" class="btn btn-primary"
                                   download="{{ $document->documentType->name }}">Download</a>
                            @endif

                            @if ($document->documentType->media_type === 'embedded')
                                @if($document->documentType->name === '360 Image')
                                    @foreach(json_decode($document->content, true) as $index => $viewer)
                                        <style>
                                            .viewer-container {
                                                margin-bottom: 20px;
                                                padding: 10px;
                                            }

                                            .viewer-container h3 {
                                                margin-top: 0;
                                                text-align: center;
                                            }

                                            .viewer {
                                                max-width: 600px;
                                                margin: auto;
                                                overflow: hidden;
                                                cursor: grab;
                                            }

                                            .viewer img {
                                                width: 100%;
                                                user-drag: none;
                                                user-select: none;
                                                pointer-events: none;
                                                border: 1px solid #eee;
                                                border-radius: 10px;
                                            }
                                        </style>
                                        <div class="viewer-container">
                                            <h3>{{ $viewer['display_name'] }}</h3>
                                            <div id="viewer-{{ $index }}" class="viewer"></div>
                                        </div>
                                        <script>
                                            function image360Viewer(data, containerId) {
                                                const cols = parseInt(data.cols);
                                                const rows = parseInt(data.rows);
                                                const initialImage = data.initial_image;
                                                const filenameTemplate = data.initial_image.substring(0, data.initial_image.lastIndexOf('/') + 1) + data.filename;

                                                const viewer = document.getElementById(containerId);
                                                let currentImageIndex = 0;

                                                // Load initial image
                                                const initialImg = document.createElement('img');
                                                initialImg.src = initialImage;
                                                initialImg.style.userSelect = 'none';
                                                viewer.appendChild(initialImg);

                                                // Create an array of image URLs
                                                const images = [];
                                                for (let row = 1; row <= rows; row++) {
                                                    for (let col = 1; col <= cols; col++) {
                                                        const url = filenameTemplate.replace('{row}', row.toString().padStart(2, '0')).replace('{col}', col.toString().padStart(2, '0'));
                                                        images.push(url);
                                                    }
                                                }

                                                // Add mouse event listeners
                                                let isDragging = false;
                                                let startX;

                                                viewer.addEventListener('mousedown', (e) => {
                                                    isDragging = true;
                                                    startX = e.clientX;
                                                    viewer.style.cursor = 'grabbing';
                                                });

                                                viewer.addEventListener('mousemove', (e) => {
                                                    if (isDragging) {
                                                        const diff = e.clientX - startX;
                                                        if (Math.abs(diff) > 10) {
                                                            startX = e.clientX;
                                                            currentImageIndex = (currentImageIndex + (diff > 0 ? 1 : -1) + images.length) % images.length;
                                                            initialImg.src = images[currentImageIndex];
                                                        }
                                                    }
                                                });

                                                viewer.addEventListener('mouseup', () => {
                                                    isDragging = false;
                                                    viewer.style.cursor = 'grab';
                                                });

                                                viewer.addEventListener('mouseleave', () => {
                                                    isDragging = false;
                                                    viewer.style.cursor = 'grab';
                                                });

                                                viewer.addEventListener('dragstart', (e) => {
                                                    e.preventDefault();
                                                });
                                            }

                                            image360Viewer(@json($viewer), 'viewer-{{ $index }}');
                                        </script>
                                    @endforeach
                                @else
                                    <div class="p-3">
                                        {!! $document->content !!}
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {!! $after ?? '' !!}

</div>

@include('widget::inc.modal.warehouse-selection')

{{--@php--}}
{{--    $html = '';--}}

{{--    if (!count($product->warehouses)) {--}}
{{--        $html .= '<tr><td colspan="4" class="py-3 text-center align-middle">Warehouse not available.</td></tr>';--}}
{{--    } else {--}}
{{--        foreach ($product->warehouses as $warehouse) {--}}
{{--            $html .= '<tr>';--}}
{{--            $html .= '<td scope="row" class="align-middle">' . htmlspecialchars($warehouse["name"]) . '</td>';--}}
{{--            $html .= '<td class="align-middle">' . number_format($warehouse["quantity_available"]) . '</td>';--}}
{{--            $html .= '</tr>';--}}
{{--        }--}}
{{--    }--}}
{{--@endphp--}}

@php
    $prop65_message = $product?->prop65_message ?? config('amplify.prop65.prop65_message');

    $modalContent =
        '<div class="modal fade" id="warningModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Note</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">' .
        $prop65_message .
        '</div>
            </div>
        </div>
    </div>';

    push_html(fn() => $modalContent);
@endphp

{{--@php--}}
{{--    push_html(function () use ($html) {--}}
{{--        return <<<HTML--}}
{{--            <div class="modal fade" id="branchModal" aria-labelledby="branchModalLabel" aria-hidden="true" tabindex="-1">--}}
{{--                <div class="modal-dialog">--}}
{{--                    <div class="modal-content">--}}
{{--                        <div class="modal-header align-items-center">--}}
{{--                            <h5 class="modal-title" id="branchModalLabel">Branch Availability</h5>--}}
{{--                            <button class="close" data-dismiss="modal" type="button" aria-label="Close">--}}
{{--                                <span aria-hidden="true">&times;</span>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        <div class="modal-body">--}}
{{--                            <div>--}}
{{--                                <table class="table">--}}
{{--                                    <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th scope="col">Branch Name</th>--}}
{{--                                            <th scope="col">Available</th>--}}
{{--                                        </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                        {$html}--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        HTML;--}}
{{--    });--}}
{{--@endphp--}}

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

    function changeWarehouse(event) {
        let option = event.options[event.selectedIndex];
        let inventory = option.dataset.qty;
        let price = option.dataset.price;

        document.getElementById('product_warehouse_{{ $product_code_id }}').value = option.value;
        document.getElementById('inventory').textContent = inventory;
        document.getElementById('price').textContent = moneyFormat(price);

        // console.log(inventory);
        // console.log(moneyFormat(price));
        // console.log(option.value);
    }

    function moneyFormat(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(parseFloat(price) ?? 0);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Get all quantity input fields
        const quantityInputs = document.querySelectorAll('input[name="qty[]"]');

        quantityInputs.forEach(input => {
            // Prevent invalid values on manual input
            input.addEventListener('input', function(event) {
                validateAndCorrectValue(input);
            });

            // Prevent users from typing manually in the input
            input.addEventListener('keydown', function(event) {
                // Allow navigation keys: backspace, delete, arrow keys, etc.
                const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Delete', 'Tab'];
                if (!allowedKeys.includes(event.key)) {
                    event.preventDefault(); // Prevent all other keys
                }
            });

            // Validate and correct the value
            function validateAndCorrectValue(input) {
                const min = parseFloat(input.min);
                const step = parseFloat(input.step);
                const value = parseFloat(input.value);

                if (isNaN(value) || value < min || (value - min) % step !== 0) {
                    input.value = min; // Reset to minimum value if invalid
                }
            }
        });
    });
</script>

@php
    push_html(function () {
        return <<<HTML
            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="pswp__bg"></div>
                <div class="pswp__scroll-wrap">
                    <div class="pswp__container">
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                    </div>
                    <div class="pswp__ui pswp__ui--hidden">
                        <div class="pswp__top-bar">
                            <div class="pswp__counter"></div>
                            <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                            <button class="pswp__button pswp__button--share" title="Share"></button>
                            <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                            <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                            <div class="pswp__preloader">
                                <div class="pswp__preloader__icn">
                                    <div class="pswp__preloader__cut">
                                        <div class="pswp__preloader__donut"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                            <div class="pswp__share-tooltip"></div>
                        </div>
                        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                        <div class="pswp__caption">
                            <div class="pswp__caption__center"></div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    });
@endphp

@php
    push_js(
        '
        $(".product-attribute").on("change", function() {
            let sku_nodes = $(".sku-item");
            sku_nodes.addClass("d-none");
            sku_nodes.removeClass("d-flex");
            let selector = "";

            $(".product-attribute").each(function() {
                let target = $(this);

                let [attribute_name, attribute_value] = [
                    target.data("attributeName"),
                    target.val().trim()
                ];

                if(attribute_name && attribute_value) {
                    selector += `*[filter-attribute="${attribute_name}-${attribute_value}"]`;
                }
            });

            let items = selector? $(selector).parents(".sku-item") : $(".sku-item");
            items.addClass("d-flex");
            items.removeClass("d-none");
        });
    ',
        'footer-script',
    );
@endphp

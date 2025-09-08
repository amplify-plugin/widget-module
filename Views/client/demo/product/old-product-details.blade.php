{{--@php--}}
{{--    use Amplify\System\Backend\Models\Product;$product_attributes = $Product->Sku_List_Attributes;--}}
{{--    $showCustomerList = !empty($attributes['show_customer_list']) && $attributes['show_customer_list'] == 'true';--}}
{{--    $product_attributes_json = json_encode(json_decode(json_encode($product_attributes), true));--}}
{{--    $userActiveWarehouseCode = optional(optional($auth)->warehouse)->code;--}}
{{--    $featureDetails = optional(Product::find($Product->Product_Id))->features;--}}
{{--    $orderList = $orderList ?? [];--}}
{{--    $customerDetails = $auth ? erp()->getCustomerDetail() : null;--}}
{{--    $seoPath = request('seopath')--}}
{{--@endphp--}}
{{--<div {!! $htmlAttributes !!}>--}}
{{--    <!-- Page Content-->--}}
{{--    @if ($authenticated = customer_check())--}}
{{--        <x-product-favorite-list :list="$orderList"/>--}}
{{--    @endif--}}


{{--    <div class="container padding-bottom-3x">--}}
{{--        <div class="row">--}}
{{--            @if ($isSkuProduct)--}}
{{--                <div class="col-12 d-flex justify-content-end">--}}
{{--                    <a href="{{ $Product->masterProductUrl }}{{ "&seopath={$seoPath}" }}"--}}
{{--                       class="ml-3 breadcrumb-link text-bold text-decoration-none py-1" data-policeweb="verified">« Back--}}
{{--                        to Master Product</a>--}}
{{--                </div>--}}
{{--        @endif--}}
{{--        <!-- Product Gallery-->--}}
{{--            <div class="col-md-4">--}}
{{--                <div class="product-gallery" id="product-gallery-image">--}}
{{--                    <div class="product-carousel owl-carousel gallery-wrapper">--}}
{{--                        <div class="gallery-item" data-hash="one">--}}
{{--                            <a href="{{ $isSkuProduct ? assets_image($Product->Sku_ProductImage ?? '') : assets_image($Product->Product_Image ?? '') }}"--}}
{{--                               data-size="1000x667">--}}
{{--                                <img--}}
{{--                                    src="{{ $isSkuProduct ? assets_image($Product->Sku_ProductImage ?? '') : assets_image($Product->Product_Image ?? '') }}"--}}
{{--                                    alt="Product">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        @if (!empty($productImage->additional))--}}
{{--                            @foreach ($productImage->additional as $key => $image)--}}
{{--                                <div class="gallery-item" data-hash='{{ 'item-' . $key }}'>--}}
{{--                                    @if (!Str::endsWith($image, ['mp4', 'avi', 'mkv']))--}}
{{--                                        <a href="{{ assets_image($image ?? '') }}" data-size="1000x667">--}}
{{--                                            <img src="{{ assets_image($image ?? '') }}" alt="Product">--}}
{{--                                        </a>--}}
{{--                                    @else--}}
{{--                                        <a href="{{ assets_image($image ?? '') }}" data-type="video"--}}
{{--                                           data-video="<video width='100%' height='100%' src='{{ assets_image($image ?? '') }}' alt='Product' controls></video>">--}}
{{--                                            <video width='100%' height='100%' src='{{ assets_image($image ?? '') }}'--}}
{{--                                                   controls data-type="video"></video>--}}
{{--                                        </a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}

{{--                    </div>--}}
{{--                    <ul class="owl-carousel product-thumbnails">--}}
{{--                        <li class="active"><a href="#one">--}}
{{--                                <img--}}
{{--                                    src="{{ $isSkuProduct ? assets_image($Product->Sku_ProductImage ?? '') : assets_image($Product->Product_Image ?? '') }}"--}}
{{--                                    alt="Product"></a>--}}
{{--                        </li>--}}
{{--                        @if (!empty($productImage->additional))--}}
{{--                            @foreach ($productImage->additional as $key => $image)--}}
{{--                                <li>--}}
{{--                                    <a href='{{ '#item-' . $key }}' class="position-relative">--}}
{{--                                        @if (!Str::endsWith($image, ['mp4', 'avi', 'mkv']))--}}
{{--                                            <img src="{{ assets_image($image ?? '') }}" alt="Product">--}}
{{--                                        @else--}}
{{--                                            <i class="fa-solid fa-circle-play position-absolute">--}}
{{--                                            </i>--}}
{{--                                            <video class="opacity-50" src="{{ assets_image($image ?? '') }}"></video>--}}
{{--                                        @endif--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}

{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Product Info-->--}}
{{--            <div class="col-md-8">--}}
{{--                <div class="padding-top-2x mt-2 hidden-md-up"></div>--}}
{{--                <h2 class="text-normal">{{ $isSkuProduct ? $Product->Sku_Name : $Product->Product_Name }}</h2>--}}
{{--                @if ($auth)--}}
{{--                    <span class="h2 d-block product-price mt-4" style="height:36px"> {{ $Product->Msrp ?? '' }}</span>--}}
{{--                @endif--}}
{{--                <p class="more">{!! $Product->short_description ?? '' !!}</p>--}}
{{--                <div class="row margin-top-1x">--}}
{{--                    @if (!empty($product_attributes))--}}
{{--                        @foreach ($product_attributes as $key => $attr_values)--}}
{{--                            --}}{{-- @reference single product trait --}}
{{--                            @if (!in_array($attr_values->name, $Product->ignoredAttributes ?? []))--}}
{{--                                <div class="col-lg-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label--}}
{{--                                            for="{{ str_slug($attr_values->name, '-') }}">{{ $attr_values->name ?? $key }}</label>--}}
{{--                                        <select class="form-control" id="{{ str_slug($attr_values->name, '-') }}"--}}
{{--                                                onchange="onAttributeChange('{{ $attr_values->name }}', this.value)">--}}
{{--                                            <option value="">Choose</option>--}}
{{--                                            @foreach ($attr_values->attributeValueList ?? $attr_values as $value)--}}
{{--                                                @if (($value->attributeValue ?? $value) != 'null')--}}
{{--                                                    <option value="{{ $value->attributeValue ?? $value }}">--}}
{{--                                                        {{ $value->attributeValue ?? $value }}--}}
{{--                                                    </option>--}}
{{--                                                @endif--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    @endif--}}

{{--                    @if ($total_items > 1)--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="color">SKU</label>--}}
{{--                                <select class="form-control" id="sku_dropdown" onchange="changeProduct()">--}}
{{--                                    <option value="">{{ __('Choose') }}</option>--}}
{{--                                    @foreach ($Product->Sku_List_Details as $value)--}}
{{--                                        <option value="{{ $value->product_slug ?? '' }}">--}}
{{--                                            {{ $value->product_name ?? '' }}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    @if ($auth && $total_items <= 1)--}}
{{--                        <div class="col-lg-8">--}}
{{--                            <div class="form-group @defaultwarehouse--}}
{{--                                text-center--}}
{{--@enddefaultwarehouse">--}}
{{--                                @defaultwarehouse--}}
{{--                                <p class="text-bold text-center mb-3">--}}
{{--                                    {{ $Product->warehouses->firstWhere('WarehouseNumber', $userActiveWarehouseCode)->WarehouseName ?? '' }}--}}
{{--                                </p>--}}
{{--                                @enddefaultwarehouse--}}
{{--                                <div class="@defaultwarehouse--}}
{{--                                    d-none--}}
{{--@enddefaultwarehouse">--}}
{{--                                    <label class="pl-0" for="product_warehouse">Warehousess</label>--}}
{{--                                    <select name="warehouse[]" class="form-control" id="product_warehouse"--}}
{{--                                            onchange="changeWarehouse(this.value); setMaxQty('product_warehouse_{{ $key ?? 0 }}', 'product_qty_{{ $key ?? 0 }}');">--}}
{{--                                        <option value="">Select Warehouse</option>--}}
{{--                                        @forelse(($Product->warehouses ?? [] ) as $warehouse)--}}
{{--                                            <option value="{{ $warehouse->WarehouseNumber }}"--}}
{{--                                                    data-quantity="{{ $Product->ERP->firstWhere('WarehouseID', $warehouse->WarehouseNumber)->QuantityAvailable ?? 0 }}"--}}
{{--                                                {{ $warehouse->WarehouseNumber === $userActiveWarehouseCode ? 'selected' : '' }}>--}}
{{--                                                {{ $warehouse->WarehouseName }}</option>--}}
{{--                                        @empty--}}
{{--                                            <option>No Warehouse</option>--}}
{{--                                        @endforelse--}}
{{--                                    </select>--}}
{{--                                </div>--}}

{{--                                @forelse($Product->ERP ?? [] as $warehouse)--}}
{{--                                    <small id="warehouse-{{ $warehouse['WarehouseID'] }}"--}}
{{--                                           data-price="{{ $warehouse['Price'] }}"--}}
{{--                                           data-quantity="{{ $warehouse['QuantityAvailable'] }}">--}}
{{--                                        {{ $Product->warehouses->firstWhere('WarehouseNumber', $warehouse['WarehouseID'])->WarehouseName ?? '' }}--}}
{{--                                        -{{ $warehouse['QuantityAvailable'] }}--}}
{{--                                        @if ($warehouse != end($Product->ERP))--}}
{{--                                            ,--}}
{{--                                        @endif &nbsp;--}}
{{--                                    </small>--}}
{{--                                @empty--}}
{{--                                    No warehouses--}}
{{--                                @endforelse--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-lg-4">--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="pl-0">Quantity</label>--}}
{{--                                <input class="form-control product-quantity" id="single_product_qty" type="number"--}}
{{--                                       step="1"--}}
{{--                                       oninput="this.value = (parseInt(this.value) > 0) ? parseInt(this.value) : 0"/>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    <x-product-hidden-fields :product="$Product"/>--}}

{{--                    <div class="col-lg-12 text-right d-none" id="reset_filter">--}}
{{--                        <a href="{{ url()->current() . '?has_sku=1' }}" style="text-decoration: none">Reset filter</a>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                @if ($auth && $isSkuProduct)--}}
{{--                    <div class="pt-1 mb-2">--}}
{{--                        <span class="text-medium">Inventory:</span>--}}
{{--                        <span class="product-inventory">-</span>--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                <div class="pt-1 mb-2">--}}
{{--                    <span class="text-medium">Brand:--}}
{{--                        <a--}}
{{--                            href="{{ route('frontend.shop.index', ['search' => 1, 'q' => "manufacturer='{" . ($Product->Manufacturer ?? '') . "}'"]) }}">--}}
{{--                            {{ $Product->Manufacturer ?? '' }}--}}
{{--                        </a>--}}
{{--                    </span>--}}
{{--                </div>--}}

{{--                @if ($isSkuProduct)--}}
{{--                    <div class="pt-1 mb-2"><span class="text-medium">SKU ID:</span> #{{ $Product->Sku_Id ?? '' }}--}}
{{--                    </div>--}}
{{--                    <div class="pt-1 mb-2"><span class="text-medium">SKU Product Code:</span>--}}
{{--                        {{ $Product->Sku_ProductCode ?? '' }}--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    <div class="pt-1 mb-2"><span class="text-medium">Product Code:</span>--}}
{{--                        {{ $Product->Product_Code ?? '' }}--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                @if (count($Product->categories) > 0)--}}
{{--                    <div class="">--}}
{{--                        <span class="text-medium">Categories:&nbsp;</span>--}}
{{--                        @foreach ($Product->categories as $category)--}}
{{--                            {{ $category->label }}--}}
{{--                            @if ($category != end($Product->categories))--}}
{{--                                ,--}}
{{--                            @endif &nbsp;--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                @if ($showSkuTable == 'true')--}}
{{--                    --}}{{-- @TODO Convert TO widget --}}
{{--                    <x-product-sku-table :product="$Product" auth="$auth"--}}
{{--                                                             :userActiveWarehouseCode="$userActiveWarehouseCode"/>--}}
{{--                @endif--}}

{{--                <hr class="mb-3">--}}
{{--                <div class="d-flex flex-wrap justify-content-between">--}}
{{--                    <div class="entry-share mt-2 mb-2"><span class="text-muted">Share:</span>--}}
{{--                        <div class="share-links"><a class="social-button shape-circle sb-facebook"--}}
{{--                                                    href="https://www.facebook.com/sharer.php?u={{ url()->current() }}"--}}
{{--                                                    target="_blank"--}}
{{--                                                    data-toggle="tooltip" data-placement="top" title="Facebook"><i--}}
{{--                                    class="socicon-facebook"></i></a><a class="social-button shape-circle sb-twitter"--}}
{{--                                                                        href="https://twitter.com/intent/tweet?url={{ url()->current() }}"--}}
{{--                                                                        target="_blank"--}}
{{--                                                                        data-toggle="tooltip" data-placement="top"--}}
{{--                                                                        title="Twitter"><i--}}
{{--                                    class="socicon-twitter"></i></a><a class="social-button shape-circle sb-instagram"--}}
{{--                                                                       href="https://www.instagram.com/?url=https://www.drdrop.co/"--}}
{{--                                                                       target="_blank"--}}
{{--                                                                       rel="noopener" data-toggle="tooltip"--}}
{{--                                                                       data-placement="top" title="Instagram"><i--}}
{{--                                    class="socicon-instagram"></i></a><a--}}
{{--                                class="social-button shape-circle sb-google-plus"--}}
{{--                                href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}"--}}
{{--                                target="_blank" data-toggle="tooltip" data-placement="top" title="Linkedin"><i--}}
{{--                                    class="fa-brands fa-linkedin"></i></a></div>--}}
{{--                    </div>--}}
{{--                    <div class="sp-buttons mt-2 mb-2">--}}
{{--                        @if ($showCustomerList)--}}
{{--                            <button class="btn btn-outline-secondary" data-toggle="modal" type="button"--}}
{{--                                    data-target="#list-addons" onclick="setPositionOffCanvas(false)"--}}
{{--                                    data-id="{{ $Product->Product_Id }}" data-key="no-key" id="add-to-list"--}}
{{--                                    title="Add to list"><i class="pe-7s-like"></i></button>--}}
{{--                        @endif--}}
{{--                        @if ($auth)--}}
{{--                            @if (isset($Product->Full_Sku_Count) && $total_items === (int) $Product->Full_Sku_Count)--}}
{{--                                <button class="btn btn-primary" id="add_to_order_btn" type="button"--}}
{{--                                        onclick="addMultipleProductToOrder()">--}}
{{--                                    <i class="pe-7s-cart"></i>--}}
{{--                                    Add to Order--}}
{{--                                </button>--}}
{{--                            @else--}}
{{--                                <button class="btn btn-primary" id="add_to_order_btn"--}}
{{--                                        @if ($customerDetails->BackorderCode == 'N' || !$Product->allowBackOrder) disabled--}}
{{--                                        @endif--}}
{{--                                        onclick="addSingleProductToOrder()">--}}
{{--                                    <i class="pe-7s-cart"></i>--}}
{{--                                    Add to Order--}}
{{--                                </button>--}}
{{--                            @endif--}}
{{--                        @else--}}
{{--                            <a class="btn btn-primary" href="{{ route('frontend.login') }}">--}}
{{--                                <i class="icon-bag"></i>--}}
{{--                                Add to Order--}}
{{--                            </a>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                @if (isset($free_shipping_message))--}}
{{--                    <hr class="mb-3">--}}
{{--                    {!! $free_shipping_message ?? '' !!}--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="row padding-top-3x mb-3">--}}
{{--            <div class="col-lg-12">--}}
{{--                <ul class="nav nav-tabs" role="tablist">--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link active" href="#description" data-toggle="tab"--}}
{{--                           role="tab">Description</a>--}}
{{--                    </li>--}}
{{--                    @if (!empty($featureDetails))--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="#features" data-toggle="tab" role="tab">Features</a>--}}
{{--                        </li>--}}
{{--                    @endif--}}
{{--                    @if (count($Product->documents) > 0)--}}
{{--                        @foreach ($Product->documents as $document)--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" href="#document_{{ $document->id }}" data-toggle="tab"--}}
{{--                                   role="tab">{{ $document->documentType->name }}</a>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                </ul>--}}
{{--                <div class="tab-content">--}}
{{--                    <div class="tab-pane fade show active" id="description" role="tabpanel">--}}
{{--                        @if (isset($Product->Product_Description) && !empty($Product->Product_Description))--}}
{{--                            {!! $Product->Product_Description !!}--}}
{{--                        @else--}}
{{--                            <p>No Description Found</p>--}}
{{--                        @endif--}}
{{--                    </div>--}}

{{--                    <div class="tab-pane fade" id="features" role="tabpanel">--}}

{{--                        @if (!empty($featureDetails))--}}
{{--                            <div class="grid-container">--}}
{{--                                @forelse(json_decode($featureDetails) as $feature)--}}
{{--                                    <div class="grid-item border-bottom">--}}
{{--                                        <span--}}
{{--                                            class="d-block font-weight-bold mb-2">{{ $feature->FeatureGroup->Name->Value }}</span>--}}
{{--                                        @forelse($feature->Features as $details)--}}
{{--                                            <span>{{ $details->Feature->Name->Value }}:--}}
{{--                                                @if ($details->Type === 'y_n')--}}
{{--                                                    {!! $details->Value === 'Y'--}}
{{--                                                        ? "<i class='icon-check text-success' title='Done Without Any Error'></i>"--}}
{{--                                                        : "<i class='icon-cross text-danger' title='Done Without Any Error'></i>" !!}--}}
{{--                                                @else--}}
{{--                                                    {{ $details->PresentationValue }}--}}
{{--                                                @endif--}}
{{--                                            </span><br>--}}
{{--                                        @empty--}}
{{--                                        @endforelse--}}

{{--                                    </div>--}}
{{--                                @empty--}}
{{--                                @endforelse--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                    </div>--}}

{{--                    @if (count($Product->documents) > 0)--}}
{{--                        @foreach ($Product->documents as $document)--}}
{{--                            <div class="tab-pane fade show" id="document_{{ $document->id }}" role="tabpanel">--}}
{{--                                <p>--}}
{{--                                    <Strong>{{ $document->documentType->name }}</Strong>--}}
{{--                                </p>--}}
{{--                                <p>{{ $document->documentType->description }}</p>--}}

{{--                                @if ($document->documentType->media_type === 'image')--}}
{{--                                    <div class="text-center">--}}
{{--                                        <img class="img-style" src="{{ assets_image($document->file_path) }}"--}}
{{--                                             alt="{{ $document->documentType->name }}">--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                @if ($document->documentType->media_type === 'video')--}}
{{--                                    <div class="text-center">--}}
{{--                                        <video width="320" height="240" controls>--}}
{{--                                            <source src="{{ asset($document->file_path) }}" type="video/mp4">--}}
{{--                                            Your browser does not support the video tag.--}}
{{--                                        </video>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                @if ($document->documentType->media_type === 'pdf')--}}
{{--                                    <object class="iframe-style" data="{{ $document->file_path }}"--}}
{{--                                            type="application/pdf">--}}
{{--                                        <embed src="{{ $document->file_path }}" type="application/pdf"--}}
{{--                                               style="width: 100% !important;"/>--}}
{{--                                    </object>--}}
{{--                                @endif--}}

{{--                                @if ($document->documentType->media_type === 'google_doc' || $document->documentType->media_type === 'google_sheet')--}}
{{--                                    <div class="mb-2">--}}
{{--                                        <a href="{{ $document->file_path }}" target="_blank">--}}
{{--                                            View on Google Docs <i class="pe-7s-exapnd2"></i>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                @if ($document->documentType->media_type === 'doc' || $document->documentType->media_type === 'xls')--}}
{{--                                    <a href="{{ $document->file_path }}" class="btn btn-primary"--}}
{{--                                       download="{{ $document->documentType->name }}">Download</a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}

{{--                    <span id="product_back_order" data-status="{{ $Product->allowBackOrder ? 'Y' : 'N' }}"></span>--}}
{{--                    @if ($auth)--}}
{{--                        <span id="customer_back_order_code"--}}
{{--                              data-status="{{ $customerDetails->BackorderCode }}"></span>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Photoswipe container-->--}}
{{--    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">--}}
{{--        <div class="pswp__bg" style="opacity: 0.7 !important;"></div>--}}
{{--        <div class="pswp__scroll-wrap">--}}
{{--            <div class="pswp__container">--}}
{{--                <div class="pswp__item"></div>--}}
{{--                <div class="pswp__item" id="pswd_removed_image"></div>--}}
{{--                <div class="pswp__item"></div>--}}
{{--            </div>--}}
{{--            <div class="pswp__ui pswp__ui--hidden">--}}
{{--                <div class="pswp__top-bar">--}}
{{--                    <div class="pswp__counter"></div>--}}
{{--                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>--}}
{{--                    <button class="pswp__button pswp__button--share" title="Share"></button>--}}
{{--                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>--}}
{{--                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>--}}
{{--                    <div class="pswp__preloader">--}}
{{--                        <div class="pswp__preloader__icn">--}}
{{--                            <div class="pswp__preloader__cut">--}}
{{--                                <div class="pswp__preloader__donut"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">--}}
{{--                    <div class="pswp__share-tooltip"></div>--}}
{{--                </div>--}}
{{--                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">--}}
{{--                    <img src="{{ assets_image('frontend/layout-3/images/arrow-left.svg') }}"--}}
{{--                         alt="arrow"/></button>--}}
{{--                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">--}}
{{--                    <img src="{{ assets_image('frontend/layout-3/images/arrow-right.svg') }}"--}}
{{--                         alt="arrow"/></button>--}}
{{--                <div class="pswp__caption">--}}
{{--                    <div class="pswp__caption__center"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<script>--}}
{{--    const customerDetails = @json($customerDetails ?? []);--}}
{{--    const customerBackOrderCode = customerDetails.BackorderCode ?? null;--}}

{{--    function setMaxQty(selectElement, inputField) {--}}
{{--        $("#" + inputField).attr("max", $("#" + selectElement).find(":selected").data("quantity"));--}}
{{--    }--}}

{{--    function changeWarehouse(warehouse_code) {--}}
{{--        if (!warehouse_code) {--}}
{{--            document.getElementById('add_to_order_btn').setAttribute('disabled', true)--}}
{{--        }--}}
{{--        const ProductInfo = document.getElementById(`warehouse-${warehouse_code}`).dataset;--}}

{{--        const ProductQty = document.querySelector(`.product-quantity`);--}}
{{--        const ProductInventory = document.querySelector(`.product-inventory`);--}}
{{--        const ProductPrice = document.querySelector(`.product-price`);--}}

{{--        if ((!ProductInfo.allowBackOrder || customerBackOrderCode === 'N') && parseInt(ProductInfo.quantity) === 0) {--}}
{{--            document.getElementById('single_product_qty').value = 0;--}}
{{--            document.getElementById('add_to_order_btn').setAttribute('disabled', true)--}}
{{--            ProductQty.setAttribute('min', 0);--}}
{{--            ProductQty.value = 0;--}}
{{--        } else {--}}
{{--            document.getElementById('add_to_order_btn').removeAttribute('disabled');--}}
{{--            document.getElementById('single_product_qty').value = 1;--}}
{{--        }--}}


{{--        ProductQty ? ProductQty.setAttribute('max', ProductInfo.quantity) : null;--}}
{{--        ProductInventory ? ProductInventory.innerHTML = ProductInfo.quantity : null;--}}
{{--        ProductPrice ? ProductPrice.innerHTML = '$' + ProductInfo.price : null;--}}
{{--    }--}}

{{--    function changeWarehouseSelectBox(warehouse_code) {--}}
{{--        const WarehouseSelectbox = document.querySelector(`#product_warehouse`).value = warehouse_code;--}}
{{--    }--}}

{{--    document.addEventListener("DOMContentLoaded", function () {--}}

{{--        const product = @json($Product);--}}
{{--        const userActiveWarehouseCode = "{{ $userActiveWarehouseCode }}";--}}
{{--        const isMultiWarehouse =--}}
{{--            @if (erp()->allowMultiWarehouse() == true)--}}
{{--                true--}}
{{--        @else--}}
{{--            false--}}
{{--        @endif ;--}}

{{--        if (!product) return false;--}}

{{--        let selectedWarehouse = null;--}}
{{--        let isWarehouseSelected = false;--}}

{{--        if (typeof product.ERP != 'undefined') {--}}
{{--            for (const warehouse of product.ERP) {--}}
{{--                if (!isMultiWarehouse) {--}}
{{--                    if (warehouse.WarehouseID === userActiveWarehouseCode) {--}}
{{--                        selectedWarehouse = warehouse;--}}
{{--                        break;--}}
{{--                    }--}}
{{--                    continue;--}}
{{--                }--}}

{{--                if (!(selectedWarehouse && parseInt(selectedWarehouse.QuantityAvailable) > 0) && parseInt(--}}
{{--                    warehouse--}}
{{--                        .QuantityAvailable) > 0) {--}}
{{--                    if (isWarehouseSelected) {--}}
{{--                        selectedWarehouse = warehouse;--}}
{{--                        break;--}}
{{--                    }--}}
{{--                    selectedWarehouse = warehouse;--}}
{{--                }--}}

{{--                if (warehouse.WarehouseID == userActiveWarehouseCode) {--}}
{{--                    if (parseInt(warehouse.QuantityAvailable) > 0) {--}}
{{--                        selectedWarehouse = warehouse;--}}
{{--                        break;--}}
{{--                    }--}}

{{--                    if (selectedWarehouse) break;--}}

{{--                    selectedWarehouse = warehouse;--}}
{{--                    isWarehouseSelected = true;--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}
{{--        if (selectedWarehouse) {--}}
{{--            changeWarehouse(selectedWarehouse.WarehouseID);--}}
{{--            changeWarehouseSelectBox(selectedWarehouse.WarehouseID);--}}
{{--        }--}}

{{--        //click event for gallery image show--}}
{{--        document.querySelector('#product-gallery-image').addEventListener('click', function () {--}}
{{--            document.querySelector('body').classList.add('image-showed');--}}
{{--        })--}}
{{--        document.querySelector('.pswp__button.pswp__button--close').addEventListener('click', function () {--}}
{{--            document.querySelector('body').classList.remove('image-showed');--}}
{{--        })--}}
{{--        document.querySelector('#pswd_removed_image').addEventListener('click', function () {--}}
{{--            document.querySelector('body').classList.remove('image-showed');--}}
{{--        })--}}
{{--    });--}}

{{--    function changeProduct() {--}}
{{--        let SkuID = document.querySelector(`#sku_dropdown`).value;--}}
{{--        if (SkuID === '') {--}}
{{--            return;--}}
{{--        }--}}

{{--        var target_url = {{ getIsDynamicSiteFromCache() }} ?--}}
{{--            window.location.origin + '/' + "{{ getDynamicSiteSlugFromCache() }}" + '/shop/' + SkuID :--}}
{{--            window.location.origin + '/shop/' + SkuID;--}}

{{--        const urlParams = new URLSearchParams(window.location.search);--}}
{{--        const seopath = urlParams.get('seopath') ?? '';--}}

{{--        window.location = target_url + '?seopath=' + seopath;--}}
{{--    }--}}

{{--    // Share product to google analytics.--}}
{{--    if (typeof gdata !== 'undefined') {--}}
{{--        gdata.Item = @json($Product);--}}
{{--    }--}}

{{--    if (typeof $.fn.owlCarousel === 'function') {--}}
{{--        $('.owl-carousel.product-thumbnails').owlCarousel({--}}
{{--            center: true,--}}
{{--            margin: 24,--}}
{{--            dots: false,--}}
{{--            nav: false,--}}
{{--            autoplay: true,--}}
{{--            items: 4--}}
{{--        });--}}
{{--    }--}}
{{--</script>--}}

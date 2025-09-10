<!-- Product Tabs-->
<div {!! $htmlAttributes !!}>
    <div class="row mb-4">
        <div class="col-lg-12">
            <ul class="nav nav-tabs d-none d-md-flex" role="tablist">
                <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab" role="tab">Details</a>
                </li>

                @if ($Product->description)
                    <li class="nav-item"><a class="nav-link" href="#description" data-toggle="tab" role="tab">Description</a>
                    </li>
                @endif

                @foreach ($productInfoTabs ?? [] as $tabKey => $tabs)
                    <li class="nav-item"><a class="nav-link" href="#erp-tab-{{ $tabKey }}" data-toggle="tab"
                                            role="tab">{{ $erpMediaType[$tabKey] ?? "Unknown" }}</a></li>
                @endforeach

                @foreach ($Product->documents ?? [] as $document)
                    <li class="nav-item"><a class="nav-link" href="#doc-tab-{{ $document->id }}" data-toggle="tab"
                                            role="tab">{{ $document->documentType?->name ?? "" }}</a></li>
                @endforeach
            </ul>

            <div class="dropdown d-md-none mb-3">
                <button class="w-100 btn border text-left" type="button" data-toggle="dropdown" id="tab-Name">
                    Details
                </button>
                <div class="dropdown-menu">
                    <div class="nav" id="tab-content">
                        <a class="dropdown-item" href="#details" data-toggle="tab" role="tab">Details</a>

                        @if ($Product->description)
                            <a class="dropdown-item" href="#description" data-toggle="tab" role="tab">Description</a>
                        @endif

                        @foreach ($productInfoTabs ?? [] as $tabKey => $tabs)
                            <a class="dropdown-item" href="#erp-tab-{{ $tabKey }}" data-toggle="tab"
                               role="tab">{{ $erpMediaType[$tabKey] ?? "Unknown" }}</a>
                        @endforeach

                        @foreach ($Product->documents ?? [] as $document)
                            <a class="dropdown-item" href="#doc-tab-{{ $document->id }}" data-toggle="tab"
                               role="tab">{{ $document->documentType?->name ?? "" }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="row">
                        <!-- Product Gallery-->
                        <div class="col-md-5">
                            <x-product.product-gallery :image="$Product->product_image"
                                                       :erp-additional-images="$erpAdditionalImages"/>
                        </div>

                        <!-- Product Info-->
                        <div class="col-md-7">
                            <h2 class="padding-top-1x fw-600">{{ $Product->Product_Name }}</h2>
                            <x-product-manufacture-image :product="$Product"/>
                            @if (property_exists($Product, "erpProductList"))
                                <span class="h2 d-block fw-600">
                                From - <span class="text-warning fw-700">{{ price_format($Product->erpProductList->min('Price')) }}</span>
                            </span>
                            @endif

                            <p>{{ $Product->short_description }}</p>

                            @if ($Product?->Sku_List_Attributes)
                                <div class="row margin-top-1x">
                                    @foreach ($Product->Sku_List_Attributes as $attribute)
                                        @if (!in_array($attribute->name, $Product->ignoredAttributes ?? []))
                                            <div class="col-sm d-flex align-items-stretch">
                                                <div class="form-group w-100">
                                                    <label>{{ $attribute->name }}</label>
                                                    <select class="form-control product-attribute"
                                                            data-attribute-name="{{ $attribute->name }}">
                                                        <option value="">Choose</option>
                                                        @foreach ($attribute->attributeValueList ?? [] as $attrVal)
                                                            @if($attrVal?->attributeValue)
                                                                <option
                                                                    value="{{ $attrVal->attributeValue }}">{{ $attrVal->attributeValue }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="entry-share mt-2 mb-2"><span class="text-muted">Share:</span>
                                    <div class="share-links">
                                        <a class="social-button shape-circle sb-facebook"
                                           href="#" data-toggle="tooltip" data-placement="top"
                                           title="Facebook"><i
                                                class="socicon-facebook"></i></a>
                                        <a class="social-button shape-circle sb-twitter" href="#"
                                           data-toggle="tooltip" data-placement="top" title="Twitter"><i
                                                class="socicon-twitter"></i></a>
                                        <a class="social-button shape-circle sb-instagram" href="#"
                                           data-toggle="tooltip" data-placement="top" title="Instagram"><i
                                                class="socicon-instagram"></i></a>
                                        <a class="social-button shape-circle sb-google-plus" href="#"
                                           data-toggle="tooltip" data-placement="top" title="Google +"><i
                                                class="socicon-googleplus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($Product->description)
                    <div class="tab-pane fade" id="description" role="description">
                        {{ $Product->description }}
                    </div>
                @endif

                @foreach ($productInfoTabs ?? [] as $tabKey => $tabs)
                    <div class="tab-pane fade" id="erp-tab-{{ $tabKey }}" role="tabpanel" role="erp-tab-{{ $tabKey }}">
                        @foreach ($tabs ?? [] as $tabItem)

                            @if ($tabKey === 'M')
                                <div class="p-3">
                                    @if ($tabItem['extension'] === "pdf")
                                        <object class="w-100 iframe-style"
                                                data="{{ 'https://spisafety.com/'.$tabItem['value'] }}"
                                                type="application/pdf" style="height: 60vh;">
                                            <embed src="{{ 'https://spisafety.com/'.$tabItem['value'] }}"
                                                   type="application/pdf" style="width: 100% !important;"/>
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

                @foreach ($Product->documents ?? [] as $document)
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
                            <object class="iframe-style" data="{{ external_asset($document->file_path) }}" type="application/pdf">
                                <embed src="{{ external_asset($document->file_path) }}" type="application/pdf" style="width: 100% !important;"/>
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

                        @if ($document->documentType->media_type === 'embedded')
                            <div class="p-3">
                                {!! $document->content !!}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@if ($isMasterProduct($Product))
        <x-product-sku-table
        :product="$Product"
        is-fav-button-show="true"
    />
@endif

<script>
    let tagName = document.getElementById('tab-Name')
    let tabContent = document.querySelectorAll('#tab-content a')
    tabContent.forEach(ele => {
        ele.addEventListener('click', (e) => {
            console.log(e.target)
            tagName.innerText = e.target.innerText
        })
    });
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

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        line-height: 1.2;
        width: 100px;
    }

    .form-group select {
        margin-top: auto;
    }
</style>

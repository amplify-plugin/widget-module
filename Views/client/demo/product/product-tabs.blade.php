<ul class="nav nav-tabs d-none d-md-flex" role="tablist" id="productTablist">

    @if ($isMasterProduct($Product))
        <li class="nav-item">
            <a class="nav-link active" href="#products" data-toggle="tab" role="tab">Products</a>
        </li>
    @endif

    @if(!empty($Product->description))
        <li class="nav-item">
            <a class="nav-link @if(!$isMasterProduct($Product)) active @endif" href="#description" data-toggle="tab" role="tab">Description</a>
        </li>
    @endif

    @if(!empty($Product->features))
        <li class="nav-item">
            <a class="nav-link" href="#features" data-toggle="tab" role="tab">Features</a>
        </li>
    @endif

    @foreach ($productInfoTabs ?? [] as $tabKey => $tabs)
        <li class="nav-item">
            <a class="nav-link" href="#erp-tab-{{ $tabKey }}" data-toggle="tab" role="tab">{{ $erpMediaType[$tabKey] ?? "Unknown" }}</a>
        </li>
    @endforeach

    @foreach ($Product->documents ?? [] as $document)
        <li class="nav-item">
            <a class="nav-link" href="#doc-tab-{{ $document->id }}" data-toggle="tab" role="tab">{{ $document->documentType?->name ?? "" }}</a>
        </li>
    @endforeach
</ul>

<div class="dropdown d-md-none mb-3">
    <button class="w-100 btn border text-left" type="button" data-toggle="dropdown" id="tab-Name">
        {{ $isMasterProduct($Product)? "Products" : "Description" }}
    </button>

    <div class="dropdown-menu">
        <div class="nav" id="tab-content">
            @if ($isMasterProduct($Product))
                <a class="dropdown-item" href="#products" data-toggle="tab" role="tab">Products</a>
            @endif

            @if(!empty($Product->description))
                <a class="dropdown-item" href="#description" data-toggle="tab" role="tab">Description</a>
            @endif

            @if(!empty($Product->features))
                <a class="dropdown-item" href="#features" data-toggle="tab" role="tab">Features</a>
            @endif

            @foreach ($productInfoTabs ?? [] as $tabKey => $tabs)
                <a class="dropdown-item" href="#erp-tab-{{ $tabKey }}" data-toggle="tab" role="tab">{{ $erpMediaType[$tabKey] ?? "Unknown" }}</a>
            @endforeach

            @foreach ($Product->documents ?? [] as $document)
                <a class="dropdown-item" href="#doc-tab-{{ $document->id }}" data-toggle="tab" role="tab">{{ $document->documentType?->name ?? "" }}</a>
            @endforeach
        </div>
    </div>
</div>

<div class="tab-content">
    @if ($isMasterProduct($Product))
        <div class="tab-pane fade show active" id="products" role="tabpanel">
            <x-product-sku-table
                :product="$Product"
                :qtyConfig="$qtyConfig"
                is-fav-button-show="true"
            />
        </div>
    @endif

    @if(!empty($Product->description))
        <div class="tab-pane fade @if (!$isMasterProduct($Product)) show active @endif" id="description" role="description">
            {!! $Product->description !!}
        </div>
    @endif

    @if(!empty($Product->features))
        <div class="tab-pane fade" id="features" role="features">
            <div class="grid-container features-tab">
                @foreach($Product->features as $feature)
                    <div class="grid-item border-bottom">
                        <span class="d-block font-weight-bold mb-2">
                            {{ $feature->FeatureGroup->Name->Value }}
                        </span>
                        @forelse($feature->Features as $details)
                            <span>{{ $details->Feature->Name->Value }}:
                                @if ($details->Type === 'y_n')
                                    {!! $details->Value === 'Y'
                                        ? "<i class='icon-check text-success' title='Done Without Any Error'></i>"
                                        : "<i class='icon-cross text-danger' title='Done Without Any Error'></i>" !!}
                                @else
                                    {{ $details->PresentationValue }}
                                @endif
                            </span><br>
                        @empty
                        @endforelse

                    </div>
                @endforeach
            </div>
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

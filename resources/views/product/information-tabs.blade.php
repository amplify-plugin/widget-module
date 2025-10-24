<div {!! $htmlAttributes !!}>
    {!!  $before  ?? '' !!}
    <ul class="nav nav-tabs {{ $headerClass }}" id="myTab" role="tablist">
        @if($displayTab(\Amplify\System\Backend\Models\Product::TAB_DESCRIPTION))
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#description"
                   role="tab">{{__('Description')}}</a>
            </li>
        @endif

        @if (!empty($product->features) && $displayTab(\Amplify\System\Backend\Models\Product::TAB_FEATURE))
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#features" role="tab">Features</a>
            </li>
        @endif

        @if (!empty($product->specifications) && $displayTab(\Amplify\System\Backend\Models\Product::TAB_SPECIFICATION))
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#specifications"
                   role="tab">Specifications</a>
            </li>
        @endif

        @if($displayTab(\Amplify\System\Backend\Models\Product::TAB_DOCUMENT))
            @foreach ($product->documents ?? [] as $document)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#doc-tab-{{ $document->id }}"
                       role="tab">{{ $document->documentType?->name ?? '' }}</a>
                </li>
            @endforeach
        @endif

        @if ($product->related_product && $displayTab(\Amplify\System\Backend\Models\Product::TAB_RELATED_ITEM))
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#related-products" role="tab">Related Products</a>
            </li>
        @endif


        @stack('product-information-tab')
    </ul>

    <div class="tab-content">
        @if($displayTab(\Amplify\System\Backend\Models\Product::TAB_DESCRIPTION))
            <div class="tab-pane fade show" id="description" role="tabpanel"
                 aria-labelledby="description-tab">
                {!! $product->description !!}
            </div>
        @endif

        @if (!empty($product->features) && $displayTab(\Amplify\System\Backend\Models\Product::TAB_FEATURE))
            <div class="tab-pane fade" id="features" role="features">
                <div class="row">
                    @each("widget::product.tabs.features.{$featureSpecsView}", $product->features ?? [], 'group')
                </div>
            </div>
        @endif

        @if (!empty($product->specifications) && $displayTab(\Amplify\System\Backend\Models\Product::TAB_SPECIFICATION))
            <div class="tab-pane fade" id="specifications" role="specifications">
                <div class="row">
                    @each("widget::product.tabs.features.{$featureSpecsView}", $product->specifications ?? [], 'group')
                </div>
            </div>
        @endif

        @if($displayTab(\Amplify\System\Backend\Models\Product::TAB_DOCUMENT))
            @foreach ($product->documents ?? [] as $document)
                <div class="tab-pane fade" id="doc-tab-{{ $document->id }}" role="tabpanel"
                     role="doc-tab-{{ $document->id }}">
                    @include("widget::product.tabs.document", ['document' => $document])
                </div>
            @endforeach
        @endif

        @if ($product->related_product && $displayTab(\Amplify\System\Backend\Models\Product::TAB_RELATED_ITEM))
            @php
                $relatedUrl = '';
                try {
                    $dbProduct = store()->productModel ?? null;
                    if ($dbProduct) {
                        $relatedUrl = route('frontend.shop.relatedProducts', $dbProduct);
                    }
                } catch (\Exception $e) {
                    $relatedUrl = '';
                }
            @endphp
            <div class="tab-pane fade" id="related-products" role="tabpanel">
                <div id="related-products-content"
                    data-url="{{ $relatedUrl }}">
                    <div class="text-center w-100 py-4">
                        <button class="btn btn-outline-secondary" disabled>
                            Click the tab to load related products
                        </button>
                    </div>
                </div>
            </div>
        @endif
        {!! $slot ?? '' !!}
    </div>

    {!!  $after  ?? '' !!}
</div>
<script>
    document.addEventListener("DOMContentLoaded", (event) => {
        let tab = document.querySelector('.x-product-information-tabs .nav-link:first-of-type');
        if (tab) {
            tab.dispatchEvent(new MouseEvent("click", {
                bubbles: true,
                cancelable: true,
                view: window
            }));
        }
    });
    // document.addEventListener("DOMContentLoaded", (event) => {
    //     let tabs = document.querySelectorAll('.x-product-information-tabs .nav-link');
    //     tabs.forEach(tab => console.log({width: tab.width, offsetWidth: tab.offsetWidth}));
    // });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    $('a[data-toggle="tab"][href="#related-products"]').on('shown.bs.tab', function () {
        const content = document.getElementById('related-products-content');
        
        if (!content || content.dataset.loaded === '1') return;

        const url = content.dataset.url;
        if (!url) return;

        // Show loader
        content.innerHTML = `
            <div class="text-center w-100 py-4">
                <div class="spinner-border text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Loading related products...</p>
            </div>
        `;

        // Fetch and load content
        fetch(url, { credentials: 'same-origin' })
            .then(r => r.ok ? r.text() : Promise.reject('Network error'))
            .then(html => {
                content.innerHTML = html;
                content.dataset.loaded = '1';
            })
            .catch(err => {
                console.error('Error loading related products:', err);
                content.innerHTML = `
                    <div class="text-center text-danger py-4">
                        Unable to load related products.
                    </div>
                `;
            });
    });
});
</script>


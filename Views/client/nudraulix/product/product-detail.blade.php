@php
    use Illuminate\Support\Collection;
    $product_code_id = str_replace(' ', '-', $product->Product_Code);
    $erp = !empty($product->ERP) ? $product->ERP : null;
@endphp

<div {!! $htmlAttributes !!}>
    <x-product-favorite-list />

    {{-- Page Content --}}
    <div class="container single-product-details mb-1">
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
                                            <span>{{ $modelCode->code }}</span>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="d-none" id="show_all">
                                        @foreach ($product?->modelCodes as $modelCode)
                                            <span>{{ $modelCode->code }}</span>
                                            @if (!$loop->last)
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
                <h2 class="product-id text-uppercase">Product ID: {{ $product->Product_Code }}</h2>
                <h4 class="product-title">{{ $product->Product_Name }}</h4>
                @if($isMasterProduct($product))
                    @include('widget::client.nudraulix.product.inc.sku-filters')
                @else
                    @include('widget::client.nudraulix.product.inc.price-qty')
                @endif
            </div>

        </div>

        @if(!empty($tabs))
            <!-- Product Tabs-->
            <div class="row mt-4">
                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-justified product-details-tab" id="productTabs" role="tablist">
                        @foreach($tabs as $index => $tab)
                            <li class="nav-item">
                                <a class="nav-link {{ $tab['active'] ? 'active' : '' }}" id="{{ $tab['id'] }}-tab" data-toggle="tab" href="#{{ $tab['id'] }}" role="tab"
                                   aria-controls="{{ $tab['id'] }}" aria-selected="{{ $tab['active'] ? 'true' : 'false' }}">{{ $tab['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content p-3 bg-white" id="productTabsContent">
                        @foreach($tabs as $tab)
                            <div class="tab-pane fade {{ $tab['active'] ? 'show active' : '' }} {{ $tab['id'] == 'skus' ? 'products-table' : '' }}" id="{{ $tab['id'] }}" role="tabpanel" aria-labelledby="{{ $tab['id'] }}-tab">
                                @if($tab['id'] == 'skus')
                                    <x-product-sku-table
                                        :product="$product"
                                        :qty-config="false"
                                        :is-fav-button-show="false"
                                        :display-product-code="false"
                                        :seo-path="$seoPath"
                                    />
                                @elseif($tab['id'] == 'details')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="mb-3">Specifications</h5>
                                            <table class="table table-bordered">
                                                <tbody>
                                                @foreach ($product->filtered_attributes as $attribute)
                                                    @php
                                                        $attrVal = json_decode($attribute->pivot->attribute_value, true)['en'] ?? "";
                                                    @endphp
                                                    <tr>
                                                        <th>{{ $attribute->local_name }}</th>
                                                        <td>{{ $attrVal }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @elseif($tab['id'] == 'catalogs')
                                    <div class="row">
                                        @foreach($product->documents as $document)
                                            <div class="col-md-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body d-flex align-items-center">
                                                        <i class="fa fa-file-pdf-o text-primary" style="font-size: 24px; margin-right: 15px;"></i>
                                                        <div>
                                                            <h5 class="mb-1">{{ $document->content }}</h5>
                                                            <a target="_blank" href="{{ $document->file_path }}" class="btn btn-sm btn-outline-primary">Download</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($tab['id'] == 'view')
                                    @include('widget::client.nudraulix.product.inc.traceparts-cad-view')
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="bg-primary call-to-action p-5 rounded text-white d-flex justify-content-between">
                    <div>
                        <h2 class="text-white">Unlock Insider Deals!</h2>
                        <p>Join our newsletter for Offers, Latest News and receive a 10% discount on your first order.
                        </p>
                    </div>
                    <div>
                        <a href="#" class="btn btn-sm bg-white text-black">Learn More</a>
                        <a href="#" class="btn btn-dark btn-sm text-white bg-dark">Get started</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('widget::inc.modal.warehouse-selection')
@pushonce('footer-script')
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

                /*// Prevent users from typing manually in the input
                input.addEventListener('keydown', function(event) {
                    // Allow navigation keys: backspace, delete, arrow keys, etc.
                    const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Delete', 'Tab'];
                    if (!allowedKeys.includes(event.key)) {
                        event.preventDefault(); // Prevent all other keys
                    }
                });*/

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
@endpushonce

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

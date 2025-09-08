@php
    $product_code_id = str_replace(' ', '-', $product->Product_Code);
@endphp
<div {!! $htmlAttributes !!}>
    <x-product-favorite-list />

    {{-- Page Content --}}
    <div class="container single-product-details mb-1 pt-5">
        <div class="row">
            {{-- <div class="col-md-12">
                <x-breadcrumb class='d-none d-lg-block' title='' error='false' hide-title='true'>
                </x-breadcrumb>
            </div> --}}
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
                <h2 class="product-title">{{ $product->Product_Name }}</h2>
                <table class="table table-borderless table-sm w-300 mt-2">
                    <tr>
                        <td>Item Number: </td>
                        <td>{{ $product->Product_Code ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Manufacturer Item: </td>
                        <td>{{ $product->mpn ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Quantity Available: </td>
                        <td>{{ customer_check() ? $product?->ERP?->QuantityAvailable ?? 0 : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Price: </td>
                        <td>{{ customer_check() ? price_format($product?->ERP?->Price ?? 0) : '-' }}</td>
                </table>

                {{-- action container --}}
                <div class="row">
                    <div class="col-md-7 mt-2">
                        <input type="hidden" id="product_code_1" value="{{ $product->Product_Code }}" />
                        <input id="product_warehouse_1" type="hidden"
                            value="{{ optional(optional(customer(true))->warehouse)->code }}" />
                        <input type="hidden" id="product_warehouse_{{ $product->Product_Code }}"
                            value="{{ $product?->ERP?->WarehouseID ?? '' }}" />
                        <div class="align-items-center d-flex gap-2 justify-content-around qty-section">
                            <span>Quantity: </span>
                            <button type="button"
                                onclick="productQuantity(1,'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                                class="operator">-</button>
                            <input type="text" class="qty-input" id="product_qty_1" value="1" />
                            <button type="button"
                                onclick="productQuantity(1,'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                                class="operator operator-dark">+</button>
                        </div>
                        <button class="btn btn-primary btn-block btn-sm mt-4 fw-600"
                            onclick="addSingleProductToOrder('1')" id="add_to_order_btn_1">
                            Add to Cart
                        </button>
                    </div>

                    <div class="col-md-5 separate-line">
                        <div class="">
                            @if (customer_check())
                                <button @class([
                                    'btn btn-sm btn-block fw-600',
                                    'btn-outline-danger' => $product?->exists_in_favorite,
                                    'btn-outline-primary' => !$product?->exists_in_favorite,
                                ]) type="button"
                                    onclick="@if ($product?->exists_in_favorite) removeItemFromCustomerList({{ $product?->favorite_list_id }}, {{ $product?->Product_Id }}); @else initCustomerListItemModal(this, '{{ $product?->Product_Id }}'); @endif">

                                    @if ($product?->exists_in_favorite)
                                        Remove from Wishlist
                                    @else
                                        Add to Wishlist
                                    @endif
                                </button>
                            @else
                                <button class="btn btn-outline-primary btn-sm btn-block fw-600" type="button"
                                    onclick="alert('You need to be logged in to access this feature.')">
                                    Add to Wishlist
                                </button>
                            @endif
                            <x-product-shopping-list :product-id="$product->Product_Id" />
                            {{-- <button class="btn btn-outline-primary btn-sm btn-block fw-600" type="button">
                                Add to Shopping List
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-chevron-down ms-1" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </button> --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @if ($isMasterProduct($product))
            <x-product-sku-table :product="$product" is-fav-button-show="true" />
        @endif

        <!-- Product Tabs-->
        <div class="row mt-4">
            <div class="col-md-12">
                <ul class="nav nav-tabs nav-justified border-primary pt-2 bg-gray" id="productTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="additional-tab" data-toggle="tab" href="#additional"
                            role="tab" aria-controls="additional" aria-selected="true">Additional Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="companion-tab" data-toggle="tab" href="#companion" role="tab"
                            aria-controls="companion" aria-selected="false">Companion Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="substitute-tab" data-toggle="tab" href="#substitute" role="tab"
                            aria-controls="substitute" aria-selected="false">Substitute Items </a>
                    </li>
                </ul>

                <div class="tab-content p-3 bg-white border-primary" id="productTabsContent">
                    <!-- Product additional information Tab -->
                    <div class="tab-pane fade show active" id="additional" role="tabpanel"
                        aria-labelledby="additional-tab">
                        <div class="row">
                            <div class="col-md-12 p-5">
                                <p>{!! $product->Product_Description !!}</p>

                            </div>
                        </div>
                    </div>

                    <!-- companion Tab -->
                    <div class="tab-pane fade" id="companion" role="tabpanel" aria-labelledby="companion-tab">
                        <div class="row">
                            <div class="col-md-12 p-5">
                                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Explicabo doloremque iste
                                    velit ad, eaque doloribus? Velit assumenda quos voluptatibus libero commodi harum,
                                    explicabo esse laudantium dolorum sequi quibusdam consequuntur officia!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Substitute Tab -->
                    <div class="tab-pane fade" id="substitute" role="tabpanel" aria-labelledby="substitute-tab">
                        <div class="row">
                            <div class="col-md-12 p-5">
                                <div class="alt-item d-flex justify-content-between align-items-center mb-3">
                                    <div class="item-details d-flex gap-3">
                                        <img src="https://placehold.co/120" alt="Product Image" class="img-fluid">
                                        <div>
                                            <h5 class="mb-1">BSPP Plug 60/captive seal</h5>
                                            <p class="mb-1">Item#: <span class="text-danger ml-4">OPB-08-WD</span>
                                            </p>
                                            <p class="mb-1">Available Quantity: 100</p>
                                            <p>Manufacturer: HYDRA-MAX</p>
                                            <p class="text-danger"><strong>$5.93 </strong><span class="small">/
                                                    EA</span></p>
                                        </div>
                                    </div>

                                    <div class="item-actions">
                                        <div class="qty-section">
                                            Quantity:
                                            <button type="button" class="operator">-</button>
                                            <input type="text" class="qty-input" value="1" />
                                            <button type="button" class="operator operator-dark">+</button>
                                        </div>
                                        <button class="btn btn-sm btn-danger btn-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-cart-plus me-2" viewBox="0 0 16 16">
                                                <path
                                                    d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9V5.5z" />
                                                <path
                                                    d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                            </svg>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('widget::inc.modal.warehouse-selection')

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

@pushonce('footer-script')
	<script src="{{ asset('rhs/rhs_script.js') }}"></script>
@endpushonce
<x-product-favorite-list />
<div {!! $htmlAttributes !!}>

    {!! $before ?? '' !!}

    <div class="padding-bottom-3x single-product-details container mb-1">
        <div class="row">
            <!-- Product Gallery-->
            <div class="col-md-6">
                <x-product.product-gallery :image="$product->product_image" />

                @if ($product->modelCodes->count())
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

                            <div class="collapse show" id="collapseOne" data-parent="#modelcodes"
                                 aria-labelledby="headingOne">
                                <div class="card-body user-select-none">
                                    <div id="show_limited">
                                        @foreach ($product->modelCodes->take(5) as $modelCode)
                                            {{-- blade-formatter-disable-next-line --}}
                                            <span>{{ $modelCode->code }}</span>@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="d-none" id="show_all">
                                        @foreach ($product->modelCodes as $modelCode)
                                            {{-- blade-formatter-disable-next-line --}}
                                            <span>{{ $modelCode->code }}</span>@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                    @if ($product->modelCodes->count() > 5)
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
            <div class="col-md-6 padding-top-1x">
                @if(!empty($product->manufacturer->name))
                    <p class="mb-0">
                        <a
                            class="manufacturer-name text-decoration-none"
                            href="{{ frontendShopURL('-Manufacturer:'.$product->manufacturer->name) }}"
                        >
                            {{ $product->manufacturer->name }}
                        </a>
                        {!! $product->short_description !!}
                    </p>
                @endif
                <p class="mb-0 single-Product-view">{{ $product->Product_Name ?? '' }}</p>

                @if (request()->has('has_sku') && request('has_sku') == 1)
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="p-0" for="inputScrew">Screw Gauge</label>
                            <select class="form-control" id="inputScrew" name="">
                                <option value="">Choose</option>
                                <option value="">Choose 1</option>
                                <option value="">Choose 1</option>
                                <option value="">Choose 1</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="p-0" for="inputScrew2">Screw Gauge</label>
                            <select class="form-control" id="inputScrew2" name="">
                                <option value="">Choose</option>
                                <option value="">Choose 1</option>
                                <option value="">Choose 1</option>
                                <option value="">Choose 1</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="p-0" for="sku">Sku</label>
                        <select class="form-control" id="sku" name="">
                            <option value="">Choose</option>
                            <option value="">Choose 1</option>
                            <option value="">Choose 1</option>
                            <option value="">Choose 1</option>
                        </select>
                    </div>

                    <div class="d-flex flex-column fw-600 mb-4 gap-2">
                        <div class="d-flex gap-2">
                            <span class="w-110">Product Code:</span>
                            <span class="fw-400">Phil-BR-Screw</span>
                        </div>

                        <div class="d-flex gap-2">
                            <span class="w-110">Categories:</span>
                            <span class="fw-400">Screws</span>
                        </div>

                        <div class="d-flex fw-400 gap-3">
                            <span class="">MFR Part #: 051890</span>
                            <span>RHS Part #: 06-042</span>
                        </div>

                    </div>

                    <div class="product-table border-top-0 table-responsive pos border">
                        <table class="table">
                            <thead>
                            <tr class="bg-primary position-sticky top-0 text-white">
                                <th>Product Code</th>
                                <th>Screw Length</th>
                                <th>Price</th>
                                <th>In Stock</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            @for ($i = 1; $i < 10; $i++)
                                <tr>
                                    <td>MFR 051890</td>
                                    <td align="center">1/2</td>
                                    <td>$455458</td>
                                    <td class="rounded p-0" style="width:110px">
                                        <div style="background: #F5F5F5; padding: 6px">
                                            <div class="d-flex justify-content-between"><span>CA</span><span>Yes</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>CO</span><span>6856540</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-count d-flex">
                                            <span
                                                class="text-dark d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"><i
                                                    class="icon-minus fw-700"></i></span>
                                            <div
                                                class="fw-600 d-flex align-items-center justify-content-center mx-2 rounded border px-2">
                                                105462
                                            </div>
                                            <span
                                                class="text-dark d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"><i
                                                    class="icon-plus fw-700"></i></span>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </table>
                    </div>
                @else
                    @if (isset($product?->ERP))
                        <h6 class="fw-700">Your Price: <span class="text-danger">$
                                {{ $product->ERP?->Price ? number_format($product->ERP->Price, 2, '.', '') : '' }}</span>
                        </h6>
                        <h6 class="fw-500 mb-3">List Price:
                            $ {{ $product->ERP?->ListPrice ?: $product->ERP?->ListPrice }}</h6>
                    @else
                        <span class="text-danger">
                            {{ customer_check() ? 'Upcoming...' : 'Please login to see the price and availability.' }}</span>
                    @endif

                    <div class="d-flex flex-column fw-600 mb-4 gap-2">
                        <div class="d-flex gap-2">
                            <span class="w-70">MFR Part:</span>
                            <span class="text-danger">{{ $product->MPN ?? '' }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="w-70">RHS Part:</span>
                            <span class="text-danger">
                                {{ $product->Product_Code ?? '' }}
                            </span>
                        </div>
                        @if($product->wareHouse->count() > 0)
                            <div class="d-flex gap-2">
                                <span class="w-70">In Stock:</span>
                                @foreach($product->wareHouse as $warehouse)
                                    <span class="text-danger d-flex flex-column">
                                        <span>
                                            {{
                                                ! empty($warehouse->WarehouseName) ?
                                                    strtoupper(substr($warehouse->WarehouseName, 0, 2)) :
                                                    ''
                                            }}
                                            @if(customer_check())
                                                {{ ! empty($warehouse->warehouseQty) ? $warehouse->warehouseQty : 0 }}
                                            @else
                                                {{ ! empty($warehouse->warehouseQty) ? 'YES' : 'NO' }}
                                            @endif
                                        </span>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- <div class="d-flex gap-2"><span class="w-70">Models:</span><span
                                class="text-danger">{{ $product->Model_Name ?? '' }}</span></div> --}}
                        <div class="product-count d-flex my-4">
                            <div class="fw-500 align-self-center mr-2">Quantity:</div>
                            <span
                                class="text-dark d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                                onclick="productQuentity(1,'minus')"><i class="icon-minus fw-700"></i></span>
                            <div class="fw-600 d-flex align-items-center justify-content-center mx-2 px-2">
                                @include('widget::client.rhsparts.product.inc.partial', [
                                    'product' => $product,
                                    'key' => '1',
                                ])
                            </div>
                            <span
                                class="text-dark d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                                onclick="productQuentity(1,'plus')"><i class="icon-plus fw-700"></i></span>
                        </div>

                        @if (config('amplify.prop65.prop65_status'))
                            <div class="mb-3">
                            <span data-toggle="modal" data-target="#warningModal" style="cursor: pointer">
                                <img src="{{ config('amplify.prop65.prop65_icon') }}" alt="warning" width="30px">
                                {{ config('amplify.prop65.prop65_title') }}
                            </span>
                            </div>
                        @endif

                        <div class="d-flex sp-buttons mb-2 mt-2">
                            <x-product.favourite-manage-button
                                class="position-relative btn-sm btn-wishlist m-0"
                                :already-exists="isset($product->exists_in_favorite) ? $product->exists_in_favorite : ''"
                                :favourite-list-id="isset($product->favorite_list_id) ? $product->favorite_list_id : ''"
                                :product-id="$product->Product_Id"
                            />
                            <button class="btn btn-primary" id="add_to_order_btn_1"
                                    onclick="addSingleProductToOrder('1')">
                                <i class="icon-bag"></i> Add to Cart
                            </button>
                        </div>
                        @endif
                    </div>
            </div>
        </div>
        {!! $middle ?? '' !!}
        <!-- Product Tabs-->
        <div class="row padding-top-3x mb-3">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        @if($product->description !='NULL')
                            <p>{!! $product->description ?? 'No Description Found' !!}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! $after ?? '' !!}
</div>

<script>
    function productQuentity(id, type) {
        let item = document.getElementById(`product_qty_${id}`);
        let val = parseInt(item.value);
        switch (type) {
            case 'plus':
                item.value = val + 1;
                break;
            case 'minus':
                if (val > 1) {
                    item.value = val - 1;
                }
                break;
        }
    }

    function toggleShowMoreLess(element) {
        var less = $('#show_limited');

        var more = $('#show_all');

        if (less.hasClass('d-none')) {
            less.addClass('d-block');
            less.removeClass('d-none');
            more.addClass('d-none');
            more.removeClass('d-block');
            $(element).html('SHOW MORE...');
        } else {
            less.addClass('d-none');
            less.removeClass('d-block');
            more.addClass('d-block');
            more.removeClass('d-none');
            $(element).text('SHOW LESS...');
        }
    }
</script>

@php
    $prop65_message = $product->prop65_message;
    if ($prop65_message === null || trim($prop65_message) === '') {
        $prop65_message = config('amplify.prop65.prop65_message');
    }

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

    push_html(function () use ($modalContent) {
        return $modalContent;
    });
@endphp

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

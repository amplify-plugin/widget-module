<div {!! $htmlAttributes !!}>
    <div class="row">
        <div class="col-md-4 col-10 mx-auto">
            <x-product.product-gallery :image="$product?->product_image"/>
        </div>
        <div class="col-md-8">
            <h2 class="product-title text-normal">{{ $product->Product_Name ?? '' }}</h2>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta voluptatibus quos ea dolore rem,
                molestias
                laudantium et explicabo assumenda fugiat deserunt in, facilis laborum excepturi aliquid nobis ipsam
                deleniti
                aut? Aliquid sit hic id velit qui fuga nemo suscipit obcaecati. Officia nisi quaerat minus nulla saepe
                aperiam sint possimus magni veniam provident.</p>
            <div class="row margin-top-1x">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="size">Men's size</label>
                        <select class="form-control" id="size">
                            <option>Chooze size</option>
                            <option>11.5</option>
                            <option>11</option>
                            <option>10.5</option>
                            <option>10</option>
                            <option>9.5</option>
                            <option>9</option>
                            <option>8.5</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="color">Choose color</label>
                        <select class="form-control" id="color">
                            <option>White/Red/Blue</option>
                            <option>Black/Orange/Green</option>
                            <option>Gray/Purple/White</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <select class="form-control" id="quantity">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="pt-1 mb-2"><span class="text-medium">SKU:</span> #21457832</div>
            <div class="padding-bottom-1x mb-2"><span class="text-medium">Categories:&nbsp;</span><a class="navi-link"
                                                                                                     href="#">Men’s
                    shoes,</a><a class="navi-link" href="#"> Snickers,</a><a class="navi-link" href="#"> Sport shoes</a>
            </div>
            <hr class="mb-3">
            <div class="d-flex flex-wrap justify-content-between">
                <div class="entry-share mt-2 mb-2"><span class="text-muted">Share:</span>
                    <div class="share-links"><a class="social-button shape-circle sb-facebook" href="#"
                                                data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="Facebook"><i class="socicon-facebook"></i></a><a
                                class="social-button shape-circle sb-twitter" href="#" data-toggle="tooltip"
                                data-placement="top" title="" data-original-title="Twitter"><i
                                    class="socicon-twitter"></i></a><a
                                class="social-button shape-circle sb-instagram" href="#" data-toggle="tooltip"
                                data-placement="top" title="" data-original-title="Instagram"><i
                                    class="socicon-instagram"></i></a><a
                                class="social-button shape-circle sb-google-plus"
                                href="#" data-toggle="tooltip" data-placement="top"
                                title="" data-original-title="Google +"><i
                                    class="socicon-googleplus"></i></a></div>
                </div>
                <div class="sp-buttons mt-2 mb-2">
                    <button class="btn btn-outline-secondary btn-sm btn-wishlist" data-toggle="tooltip" title=""
                            data-original-title="Whishlist"><i class="icon-heart"></i></button>
                    <button class="btn btn-primary" data-toast="" data-toast-type="success"
                            data-toast-position="topRight"
                            data-toast-icon="icon-circle-check" data-toast-title="Product"
                            data-toast-message="successfuly added to cart!"><i class="icon-bag"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{--            <div class="product-dtl">--}}
            {{--                <div class="product-info mb-4">--}}
            {{--                    <h1 class="text-dark my-0">{{ $product->Product_Code ?? '' }}</h1>--}}
            {{--                    <p class="text-black product-name fw-700">{{ $product->Product_Name ?? '' }}</p>--}}
            {{--                    <div class="text-black product-name">{{ $product->Product_Description ?? '' }}</div>--}}
            {{--                </div>--}}
            {{--                <div class="row product-description">--}}
            {{--                    <div class="col-md-6 product-specifications">--}}
            {{--                        <div class="row">--}}
            {{--                            <div class="col"><p class=""><strong>Manufacturer Item :</strong></p></div>--}}
            {{--                            <div class="col"><p>{{ $product->mpn ?? 'N/A' }}</p></div>--}}
            {{--                        </div>--}}
            {{--                        <div class="row">--}}
            {{--                            <div class="col"><p class=""><strong>Quantity Available :</strong></p></div>--}}
            {{--                            <div class="col">--}}
            {{--                                <p>--}}
            {{--                                    @if($product->assembled)--}}
            {{--                                        Assembled Item--}}
            {{--                                    @else--}}
            {{--                                        <x-product.availability--}}
            {{--                                                :product="$product"--}}
            {{--                                                :value="$product->total_quantity_available"/>--}}
            {{--                                    @endif--}}
            {{--                                </p>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                        <div class="row">--}}
            {{--                            <div class="col"><p class=""><strong>Your Price :</strong></p></div>--}}
            {{--                            <div class="col">--}}
            {{--                                <x-product.price--}}
            {{--                                        element="p"--}}
            {{--                                        class="d-flex justify-content-start"--}}
            {{--                                        :product="$product"--}}
            {{--                                        :value="$product->ERP?->Price"--}}
            {{--                                        :uom="$product->ERP?->UnitOfMeasure ?? 'EA'"/>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                        <div class="row">--}}
            {{--                            <div class="col">--}}
            {{--                                <p>{!! $product->ship_restriction ?? null  !!}</p>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                        <div class="d-flex w-100 gap-3">--}}

            {{--                            <x-product.default-document-link :document="$product->default_document"--}}
            {{--                                                             class="list_shop_datasheet_product"/>--}}

            {{--                            <x-product.ncnr-item-flag class="text-warning font-weight-bold" :product="$product"--}}
            {{--                                                      :show-full-form="true"/>--}}
            {{--                        </div>--}}
            {{--                        @if ($product->ERP?->QtyBreak_1 > 0)--}}
            {{--                            <table class="table table-bordered table-sm mt-3">--}}
            {{--                                <thead class="text-center">--}}
            {{--                                <tr>--}}
            {{--                                    <th scope="col">Quantity</th>--}}
            {{--                                    <th scope="col">Price</th>--}}
            {{--                                </tr>--}}
            {{--                                </thead>--}}
            {{--                                <tbody>--}}
            {{--                                @for ($i = 1; $i <= 9; $i++)--}}
            {{--                                    @if($product->ERP['QtyPrice_' . $i] != null)--}}
            {{--                                        <tr>--}}
            {{--                                            <td>{{ number_format($product->ERP['QtyBreak_' . $i]) }}</td>--}}
            {{--                                            <td>{{ $product->ERP['QtyPrice_' . $i] > 0 ? currency_format($product->ERP['QtyPrice_' . $i], null, true) : product_not_avail_message() }}--}}
            {{--                                            </td>--}}
            {{--                                        </tr>--}}
            {{--                                    @endif--}}
            {{--                                @endfor--}}
            {{--                                </tbody>--}}
            {{--                            </table>--}}
            {{--                        @endif--}}
            {{--                    </div>--}}
            {{--                    <div class="col-md-6">--}}
            {{--                        <div class="table-responsive p-2 rounded border border-primary">--}}
            {{--                            <span class="inventory-heading">Inventory by Warehouse :</span>--}}
            {{--                            <table class="table table-sm mb-0 inventory-table">--}}
            {{--                                <thead>--}}
            {{--                                <tr>--}}
            {{--                                    <th>Warehouse</th>--}}
            {{--                                    <th class="text-center">Quantity</th>--}}
            {{--                                </tr>--}}
            {{--                                </thead>--}}
            {{--                                @foreach ($product?->warehouses as $key => $warehouse)--}}
            {{--                                    <tr @if($warehouse['code'] == $customer->DefaultWarehouse)--}}
            {{--                                            class="default-warehouse"--}}
            {{--                                        data-toggle="tooltip"--}}
            {{--                                        data-title="Default Warehouse"--}}
            {{--                                            @endif--}}
            {{--                                    >--}}
            {{--                                        <td scope="row">{{ $warehouse['name'] }}</td>--}}
            {{--                                        <td class="text-center">--}}
            {{--                                            {{ number_format($warehouse['quantity_available'] ?? 0) }}--}}
            {{--                                        </td>--}}
            {{--                                    </tr>--}}
            {{--                                @endforeach--}}
            {{--                            </table>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--                <div class="row">--}}
            {{--                    <div class="col-md-5 d-grid border-right">--}}
            {{--                        <div class="w-100">--}}
            {{--                            <div class="align-items-center d-flex product-count mt-2 gap-2 justify-content-between">--}}
            {{--                                <div class="text-black fw-500 align-self-center">Quantity:</div>--}}
            {{--                                <button--}}
            {{--                                        type="button"--}}
            {{--                                        style="height: 32px; width: 32px"--}}
            {{--                                        class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 border-card-qty"--}}
            {{--                                        onclick="Amplify.handleQuantityChange('#product_qty_1', 'decrement');">--}}
            {{--                                    <i class="icon-minus fw-700"></i>--}}
            {{--                                </button>--}}

            {{--                                <input type="text" class="text-black form-control form-control-sm text-center"--}}
            {{--                                       style="height: 30px; border-radius: 4px !important; border: 1px solid #999999;"--}}
            {{--                                       id="product_qty_1" name="qty[]"--}}
            {{--                                       data-product-code="{{ $product->Product_Code ?? '' }}"--}}
            {{--                                       data-min-order-qty="{{ $product?->min_order_qty ?? 1 }}"--}}
            {{--                                       data-qty-interval="{{ $product?->qty_interval ?? 1 }}"--}}
            {{--                                       onclick="Amplify.handleQuantityChange('#product_qty_1', 'input');"--}}
            {{--                                       value="{{ $product?->min_order_qty ?? 1 }}"--}}
            {{--                                       min="{{ $product?->min_order_qty ?? 1 }}"--}}
            {{--                                       step="{{ $product?->qty_interval ?? 'any' }}"--}}
            {{--                                       @if(!$product->allow_back_order) max="{{$product->total_quantity_available}}" @endif>--}}

            {{--                                <input type="hidden" id="product_code_1" value="{{ $product->Product_Code }}"/>--}}
            {{--                                <input id="product_warehouse_1" type="hidden"--}}
            {{--                                       value="{{ optional(optional(customer(true))->warehouse)->code }}"/>--}}
            {{--                                <input type="hidden" id="product_warehouse_{{ $product->Product_Code }}"--}}
            {{--                                       value="{{ $product?->ERP?->WarehouseID ?? \ErpApi::getCustomerDetail()->DefaultWarehouse }}"/>--}}

            {{--                                <button--}}
            {{--                                        type="button"--}}
            {{--                                        style="height: 32px; width: 32px"--}}
            {{--                                        class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 border-card-qty bg-black text-white"--}}
            {{--                                        onclick="Amplify.handleQuantityChange('#product_qty_1', 'increment');">--}}
            {{--                                    <i class="icon-plus fw-700"></i>--}}
            {{--                                </button>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                        <div class="flex-row justify-content-start content_custom">--}}
            {{--                            <button id="add_to_order_btn_1"--}}
            {{--                                    class="btn btn-sm btn-primary btn-block"--}}
            {{--                                    data-warehouse="{{ $product->ERP->WarehouseID ?? \ErpApi::getCustomerDetail()->DefaultWarehouse }}"--}}
            {{--                                    data-options="{{ json_encode(['code' => $product->Product_Code]) }}"--}}
            {{--                                    onclick="Amplify.addSingleItemToCart(this, '#product_qty_1')"--}}
            {{--                                    onclick="addSingleProductToOrder('1')">--}}
            {{--                                {{ $addToCartBtnLabel() }}--}}
            {{--                            </button>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <!-- // start colops -->--}}
            {{--                    <div class="customer-Part-Number col-md-7 ">--}}
            {{--                        <!--suppress BladeUnknownComponentInspection -->--}}
            {{--                        <x-product-customer-part-number--}}
            {{--                                class="text-black d-flex justify-content-between gap-2 align-items-center"--}}
            {{--                                :product-id="$product->Amplify_Id"--}}
            {{--                                :uom="$product->ERP?->UnitOfMeasure ?? 'EA'"--}}
            {{--                        />--}}
            {{--                        <div class="row">--}}
            {{--                            <div class="col-md-6">--}}
            {{--                                <x-wishlist-button :product="$product"--}}
            {{--                                                   class="btn-block btn-sm btn-outline-primary">--}}
            {{--                                    <x-slot:add-label>--}}
            {{--                                        Add To Wishlist--}}
            {{--                                    </x-slot>--}}
            {{--                                    <x-slot:remove-label>--}}
            {{--                                        Remove from Wishlist--}}
            {{--                                    </x-slot>--}}
            {{--                                </x-wishlist-button>--}}
            {{--                            </div>--}}
            {{--                            <div class="col-md-6">--}}
            {{--                                <x-product-shopping-list :product-id="$product->Amplify_Id" class="w-100 px-0"--}}
            {{--                                                         style="margin-top: 12px"/>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
        </div>
    </div>

    <x-product.information-tabs
            :product="$product"
            :header-class="''"
            :tabs="[
                'description',
                'feature' => ['label' => 'Features', 'style' => 'list'],
                'specification' => ['label' => 'Specifications', 'style' => 'list'],
                'related-products' => ['label' => 'Related'],
                'reviews' => ['label' => 'Reviews'],
            ]">
        <x-slot:before>
            <svg width="0" height="0">
                <defs>
                    <clipPath id="tabClip" clipPathUnits="objectBoundingBox">
                        <path d="
                            M-0.01,1
                            C0.08,0.9 0.08,0 0.17,0
                            H0.83
                            C0.92,0 0.92,0.9 1.01,1
                        "/>
                    </clipPath>
                    <clipPath id="tabClip2" clipPathUnits="objectBoundingBox">
                        <path d="
                            M0,1
                            C0.08,0.9 0.08,0 0.17,0
                            H0.83
                            C0.92,0 0.92,0.9 1,1
            "/>

                    </clipPath>
                </defs>
            </svg>
        </x-slot:before>
    </x-product.information-tabs>
</div>

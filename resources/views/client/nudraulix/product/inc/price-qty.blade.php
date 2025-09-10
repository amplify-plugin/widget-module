@if(!empty($erp))
    <p class="customer-price py-1">
        Customer Price: {{ (price_format($erp->Price) . "/$erp->UnitOfMeasure") }}
    </p>
@endif

<div class="manufacture-info mb-2">
    @if($product->manufacturer)
        <div class="mb-1 d-flex">
            <div class="label">Manufacturer:</div>
            <div class="value">{{ $product->manufacturer->name }}</div>
        </div>
    @endif
    @if(! empty($product->manufacturer))
        <div class="mb-1 d-flex">
            <span class="label">Manufacturer Item: </span>
            <span class="value">{{$product->manufacturer}}</span>
        </div>
    @endif
    @if(!empty($erp))
        <div class="mb-1 d-flex">
            <span class="label">Quantity Available: </span>
            <span class="value">
                {{ "$erp->QuantityAvailable $erp->UnitOfMeasure" }}
            </span>
        </div>
        <div class="mb-1 d-flex">
            <span class="label">List Price: </span>
            <span class="value">{{ price_format($erp->ListPrice) }}</span>
        </div>
    @endif
</div>

{{-- action container --}}
@if( config('amplify.basic.enable_guest_pricing') || customer_check())
    <div class="row">
        <div class="col-md-6 mt-2">
            <div class="align-items-center d-flex gap-2 justify-content-around qty-section">
                <span>Quantity:</span>
                <div class="d-flex gap-1 align-items-center">
                    <button
                        type="button"
                        class="operator"
                        onclick="productQuantity(1,'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                    >
                        <i class="icon-minus fw-700"></i>
                    </button>
                    <div class="align-items-center d-flex fw-600 justify-content-center">
                        @include('widget::client.nudraulix.product.inc.partial', [
                            'product' => $product,
                            'key' => '1',
                        ])
                    </div>
                    <button
                        class="operator bg-primary text-white"
                        onclick="productQuantity(1,'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})"
                    >
                        <i class="icon-plus fw-700"></i>
                    </button>
                </div>
            </div>
            <button
                class="btn btn-primary btn-sm btn-block fw-600 m-0 mt-4"
                id="add_to_order_btn_1" data-toast-position="topRight"
                onclick="addSingleProductToOrder(1)"
            >
                Add to Cart
            </button>
        </div>

        <div class="col-md-6 separate-line">
            {{-- <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-outline-primary btn-sm" type="button" data-toggle="modal"
                        data-target="#warehouseModal">
                    Add to Wishlist
                </button>
                <button class="btn btn-outline-primary btn-sm" type="button">
                    Add to Shopping List
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         fill="currentColor" class="bi bi-chevron-down ms-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                    </svg>
                </button>
            </div>
            <button class="btn btn-primary btn-block btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                     class="bi bi-journal-text me-2" viewBox="0 0 16 16">
                    <path
                        d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z" />
                    <path
                        d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-12a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3z" />
                </svg>
                Open Order Inquiry
            </button> --}}
            <x-product-shopping-list :product-id="$product->Product_Id"/>
        </div>
    </div>
@endif

{{--@if( !empty($product->warehouses ) )
    <div class="row mt-3">
        <div class="col-md-12 accordion p-0">
            <details>
                <summary>Warehouse Info</summary>
                <table class="table mt-4">
                    <thead class="bg-secondary">
                    <tr>
                        <th>Warehouse</th>
                        <th>Description</th>
                        <th>Quantity Available</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach( $product->warehouses as $warehouse )
                        <tr>
                            <td>{{  $warehouse['code'] }}</td>
                            <td>{{  $warehouse['name'] }}</td>
                            <td>
                                {{ $warehouse['quantity_available'] }}/{{ !empty($erp) ?  $erp->UnitOfMeasure : 'EA'}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </details>
        </div>
    </div>
@endif--}}

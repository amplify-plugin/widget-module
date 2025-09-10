<div class="product-btn-group float-right">
    <button
        style="background: #F3702110;color: #2B2B2B"
        class="flex-grow-1 btn fw-600 btn-warning"
        onclick="resetQuantity()"
    >Reset Qty.
    </button>
    @if(customer(true)->can('order.add-to-cart'))
        <button class="flex-grow-1 btn fw-600 btn-warning"
                onclick="addMultipleProductToOrder()"
                id="add_to_order_btn_"
        >Add to Cart
        </button>
    @endif
</div>

<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="order-table">
                <x-slot name="rightside">
                    <form method="get" action="{{ url()->current() }}" id="order-search-form">
                        <div class="form-group">
                            <select class="form-control" name="contact_code"
                                    onchange="$('#order-search-form').submit()">
                                <option value="">All Contacts</option>
                                @foreach ($contacts as $contact)
                                    <option
                                            value="{{ $contact->contact_code }}" {{ $contact_code == $contact->contact_code? "selected" : "" }} >{{ $contact->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="created_start_date"
                               value="{{ request('created_start_date', now(config('app.timezone'))->subMonths(3)->format('Y-m-d')) }}"
                               id="created_start_date">
                        <input type="hidden" name="created_end_date"
                               value="{{ request('created_end_date', now(config('app.timezone'))->format('Y-m-d')) }}"
                               id="created_end_date">

                        <div class="d-flex justify-content-center justify-content-md-end">
                            <label>
                                <div id="created_date_range" class="border form-control form-control-sm py-2 d-flex">
                                    <i class="mr-2 pe-7s-date"
                                       style="font-weight: bold; font-size: 1.25rem;"></i><span></span>
                                </div>
                            </label>
                        </div>
                    </form>
                </x-slot>

                <table class="products-table table table-bordered table-hover" id="order-table">
                    <thead>
                    <tr>
                        <th>Items</th>
                    </tr>
                    </thead>
                    <tbody class="accordion" id="sku_details_table_body">
                    @foreach($products as $index => $product)
                        <tr class="sku-item" data-product-code="{{ $product->product_code }}"
                            data-product-id="{{ $product->id }}" data-qty="" data-warehouse="">
                            <td data-sort="{{ $product->local_product_name }}">
                                <div
                                        class="border-bottom gap-3 p-3 d-flex flex-wrap flex-md-nowrap justify-content-between">
                                    <div class="d-flex gap-3">
                                        <a href="{{ frontendSingleProductURL($product) . "?has_sku={$product->hasSku}" }}"><img
                                                    class="w-120" src="{{ $product->productImage->main }}"
                                                    alt="product"></a>
                                        <div>
                                            <p class="text-uppercase mb-0">
                                                <a class="text-decoration-none"
                                                   href="{{ frontendSingleProductURL($product) . "?has_sku={$product->hasSku}" }}"><span
                                                            class="d-block d-md-inline font-weight-bold mr-md-2 mb-2 mb-md-0">{{ $product->local_product_name }}</span></a>
                                                {!! $product->local_short_description !!}
                                            </p>
                                            <p><b>Product Code:</b> {{ $product->product_code }}</p>

                                            <div class="d-flex flex-wrap gap-col-3">
                                                @foreach ($product->attributes as $attribute)
                                                    @php $attrVal = json_decode($attribute->pivot->attribute_value, true)['en'] ?? ""; @endphp
                                                    <p class="mb-1"
                                                       filter-attribute="{{ $attribute->local_name.'-'.$attrVal }}">
                                                        <b>{{ $attribute->local_name }}:</b> {{ $attrVal }}
                                                    </p>
                                                @endforeach
                                            </div>

                                            @if ($product?->ERP && $warehouse = current($product->ERP->Warehouses))
                                                <p>
                                                    <span class="text-success">{{ $warehouse['StockMessage'] }}</span>
                                                    / {{ $product?->ERP?->PricingUnitOfMeasure }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3">
                                        @if ($product?->ERP)
                                            <div class="w-260 flex-shrink-0">
                                                @for ($i = 1; $i <= 6; $i++)
                                                    @if ($product?->ERP["QtyPrice_{$i}"])
                                                        <p class="d-flex justify-content-between mb-2">
                                                            <span>{{ $product?->ERP["QtyBreak_{$i}"] }}+</span>
                                                            <span>{{ price_format($product?->ERP["QtyPrice_{$i}"] ?? 0) }}</span>
                                                        </p>
                                                    @endif
                                                @endfor

                                                <p class="d-flex justify-content-between mb-2">
                                                    <span><b>Your Price</b>/ {{ $product->ERP->PricingUnitOfMeasure }}</span>
                                                    <span>
                                                    @if ($product?->campaignProduct)
                                                            <del>
                                                        {{ price_format($product->ERP->Price) }}
                                                    </del>
                                                            <b>{{ price_format($product->campaignProduct->discount) }}</b>
                                                        @else
                                                            {{ price_format($product->ERP->Price) }}
                                                        @endif
                                                </span>
                                                </p>

                                                <div class="d-flex align-items-center justify-content-between cs-w-420">
                                                    <div><b>Quantity/{{ $product->ERP->PricingUnitOfMeasure }}</b></div>
                                                    <div class="gap-3 count align-items-center p-2 border rounded d-flex">
                                                    <span
                                                            class="qty-minus bg-secondary text-dark d-flex align-items-center justify-content-center fw-600">
                                                        <i class="icon-minus"></i>
                                                    </span>
                                                        <p class="mb-0 mx-2 fw-600 qty-field">0</p>
                                                        <span
                                                                class="qty-plus bg-warning text-white d-flex align-items-center justify-content-center fw-600">
                                                        <i class="icon-plus"></i>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <div class="card">
                                    <div class="py-0 bg-transaprent" id="heading{{$index}}">
                                        <button class="btn-sm btn my-0 btn-block button-outline-primary collapsed" type="button"
                                                data-toggle="collapse" data-target="#collapse{{$index}}"
                                                aria-expanded="true" aria-controls="collapse{{$index}}">
                                            View History
                                        </button>
                                    </div>

                                    <div id="collapse{{$index}}" class="collapse" aria-labelledby="heading{{$index}}"
                                         data-parent="#sku_details_table_body">
                                        <div class="card-body">
                                            <table class="table table-bordered view-histroy">
                                                <thead>
                                                <tr>
                                                    <th>Order date</th>
                                                    <th>Order Ref Num.</th>
                                                    <th width="30">UOM</th>
                                                    <th>Qty</th>
                                                    <th>Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($product['orderInfo']['items'] as $index => $orderItems)
                                                    <tr>
                                                        <td data-order="{{$orderItems['order']['EntryDate']}}">{{carbon_date($orderItems['order']['EntryDate'])}}</td>
                                                        <td data-order="{{$product['orderInfo']['items'][$index]['order']['CustomerPurchaseOrdernumber']}}">{{$product['orderInfo']['items'][$index]['order']['CustomerPurchaseOrdernumber']}}</td>
                                                        <td>{{$orderItems['item']['UnitOfMeasure']}}</td>
                                                        <td>{{$orderItems['item']['QuantityOrdered']}}</td>
                                                        <td>{{price_format($orderItems['item']['ActualSellPrice'])}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-site.data-table-wrapper>
        </div>
    </div>
</div>
@php
    push_js(function () {
        return <<<HTML
        function resetQuantity() {
            \$('.sku-item').data('qty', 0);
            \$('.qty-field').html(0);
        }
        \$('.qty-plus').on('click', function (e) {
            let target = \$(e.currentTarget);
            let qty;
            if(qty = parseInt(target.prev().html())+1) {
                target.prev().html(qty);
                target.closest('.sku-item').data('qty', qty);
            }
        });

        $('.qty-minus').on('click', function (e) {
            let target = \$(e.currentTarget);
            let qty;
            if((qty = parseInt(target.next().html())-1) >= 0) {
                target.next().html(qty);
                target.closest('.sku-item').data('qty', qty);
            }
        });
                const ORDER_DATE_RANGER = '#created_date_range';

        $(document).ready(function () {
            var startDate = $("#created_start_date").val();
            var endDate = $("#created_end_date").val();
            var table =  $(".view-histroy").DataTable();
            table.order([0, 'desc']).draw();

            initOrderCreatedDateRangePicker(startDate, endDate);
        });
HTML;}, 'footer-script');
@endphp

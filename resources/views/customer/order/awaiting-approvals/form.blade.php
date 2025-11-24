<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            @if($order)
                <div class="row mb-4">
                    <div class="col-4">
                        <span><b>{{__('Ordered By :')}}</b> {{ $order->contact->name ?? 'N/A' }}</span>
                    </div>

                    <div class="col-4">
                        <span><b>{{__('Date Ordered :')}}</b> {{ carbon_date($order->created_at) }}</span>
                    </div>

                    <div class="col-4">
                        <span><b>{{__('Last Changed :')}}</b> {{ "" }}</span>
                    </div>

                    <div class="col-4">
                        <span><b>{{__('Approved By :')}}</b> {{ "" }}</span>
                    </div>

                    <div class="col-4">
                        <span><b>{{__('Order Reference :')}}</b> {{ $order->customer_order_number }}</span>
                    </div>

                    <div class="col-4">
                        <span><b>{{__('PO Number :')}} </b> {{ $order->customer_order_number }}</span>
                    </div>

                    <div class="col-4">
                        <span><b>{{__('Shipping Method :')}} </b> {{ $order->shipping_method }}</span>
                    </div>
                </div>

                <x-site.data-table-wrapper id="order-item-table">
                    @if(customer(true)->can('order.add-to-cart'))
                        <x-slot name="rightside">
                            <div class="d-flex justify-content-center justify-content-md-end">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary btn-right my-2 ml-2 mr-0 text-capitalize"
                                    onclick="addToCart()">
                                    {{__('add all items to the cart')}}
                                </button>
                            </div>
                        </x-slot>
                    @endif

                    <table class="table table-bordered table-striped table-hover" id="product-item-list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Qty')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 0; @endphp
                            @foreach($order->orderLines  ?? [] as $item)
                                <tr>
                                    <input type="hidden" id="{{ 'product_code_' . $counter }}"
                                        value="{{ $item->product_code }}"/>
                                    <input type="hidden" id="{{ 'product_qty_' . $counter }}" value="{{ $item->qty }}"/>

                                    <th scope="row">{{ ++$counter }}</th>
                                    <td>
                                        <a class="product-thumb"
                                        href="{{frontendSingleProductURL( $item['product'] ?? '#')}}">
                                            <img title="View Product"
                                                src="{{ assets_image(optional($item['product'])->productImage->main ?? '')}}"
                                                alt="{{optional($item['product'])->product_name}}"
                                                style="width: 130px">
                                        </a>
                                    </td>
                                    <td>{{ str()->limit(optional($item['product'])->product_name, 40, '...') }}</td>

                                    <td>${{ $item->customer_price }}</td>

                                    <td>
                                        <span class="text-medium">{{ $item->qty }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-site.data-table-wrapper>
            @else
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger">
                            <strong>{{__('No Data Found!')}}</strong>
                        </div>
                    </div>
                </div>
            @endif

            @if ($order->orderRule?->notes)
                <div class="mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold mb-3">{{__('Notes: ')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <textarea class="form-control" rows="4" id="note" readonly> {{ $order->orderRule?->notes ?? "" }}</textarea>
                </div>
            @endif

            @if ($order->order_status === "Pending")
                <div class="form-group text-right">
                    <a class="btn btn-success" href="{{ route('frontend.order.checkout', $order->id) }}" type="submit">Complete Order</a>
                </div>
            @endif
        </div>
    </div>
</div>

@pushonce("footer-script")
    <script src="{{ mix("js/backend.js", "vendor/backend") }}"></script>
@endpushonce
@php
    push_css("
        .product-thumb > img {
            width: auto;
            max-width: 45px;
            max-height: 50px;
            margin: 5px auto;
        }
        .note-wrapper .remove {
            position: absolute;
            top: 2px;
            right: 10px;
        }
    ", "internal-style");

    push_js("
        var timeout;
        var delay = 500;

        function debounceSearch() {
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(function () {
                searchOnOrderItems();
            }, delay);
        }

        function resetSearch() {
            let uri = window.location.href;
            let order_id = '". (request()->has('order_id') ? request()->order_id : 0) ."';
            window.location = uri.split('?')[0] + '?order_id=' + order_id;
        }

        function searchOnOrderItems(e) {
            let search = $('#search_string').val();
            let uri = window.location.href;
            let new_param = updateQueryStringParameter(uri, 'search', search);
            window.location = new_param;
        }

        function onPerPage(e) {
            let uri = window.location.href;
            let new_param = updateQueryStringParameter(uri, 'per_page', e.target.value);
            window.location = new_param;
        }

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
            var separator = uri.indexOf('?') !== -1 ? '&' : '?';
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + '=' + value + '$2');
            } else {
                return uri + separator + key + '=' + value;
            }
        }

    ", "internal-script");
@endphp

<div {!! $htmlAttributes !!}>
    <div class="row">
        <div class="col-12">
            <div class="padding-top-2x mt-2 hidden-lg-up"></div>

            @if($order)
                <div class="row mb-4">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('frontend.drafts.index') }}">Back to Save cart</a>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-4"><span> <b>{{__('Created By:')}}</b> {{ $customer->name ?? 'N/A' }}</span></div>
                    <div class="col-4">
                        <span> <b>{{__('Created Date:')}}</b> {{ carbon_datetime($order->created_at) }}</span></div>
                    <div class="col-4"><span> <b>{{__('Last Changed:')}}</b> {{ "" }}</span></div>
                    <div class="col-4"><span> <b>{{__('Approved By:')}}</b> {{ $order->approver->name ?? "" }}</span>
                    </div>
                    <div class="col-4">
                        <span> <b>{{__('Order Reference:')}}</b> {{ $order->customer_order_number }}</span>
                    </div>
                    <div class="col-4"><span> <b>{{__('Web Order Number:')}} </b> {{ $order->web_order_number }}</span>
                    </div>
                </div>

                <x-site.data-table-wrapper id="order-item-table">
                    <x-slot name="rightside">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            @if(customer(true)->can('order.add-to-cart'))
                                <button type="button" class="btn btn-sm btn-primary btn-right my-2 ml-2 mr-0"
                                        onclick="addToCart()">
                                    {{__('add all items to the cart')}}
                                </button>
                            @endif
                        </div>
                    </x-slot>

                    <table class="table table-bordered table-striped table-hover" id="product-item-list">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('Product Code')}}</th>
                            <th>{{__('Image')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Price')}}</th>
                            <th>{{__('Qty')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($order->orderLines as $key => $item)
                            <input type="hidden" id="{{ 'product_code_' . $key }}" value="{{ $item->product_code }}"/>
                            <input type="hidden" id="{{ 'product_qty_' . $key }}" value="{{ $item->qty }}"/>

                            <tr>
                                <th scope="row">{{ $key+1 }}</th>
                                <td>{{ $item->product_code }}</td>
                                <td>
                                    <a class="product-thumb"
                                       href="{{frontendSingleProductURL( $item->product ?? '#')}}"
                                    >
                                        <img title="View Product"
                                            src="{{ assets_image($item?->product->productImage->main ?? '')}}"
                                            alt="{{ $item?->product->product_name }}"
                                        />
                                    </a>
                                </td>
                                <td>{{ str()->limit($item?->product->product_name, 40, '...') }}</td>
                                <td>{{ price_format($item->customer_price ?? 0) }}</td>
                                <td>
                                    <span class="text-medium">{{ $item->qty }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="alert alert-danger">
                                        <strong>{{__('No Items Found!')}}</strong>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </x-site.data-table-wrapper>
            @else
                <div class="row">
                    <div class="col-md-12 text-right mb-3">
                        <a href="{{ route('frontend.drafts.index') }}">{{__('Back to Orders')}}</a>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger">
                            <strong>{{__('No Data Found!')}}</strong>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

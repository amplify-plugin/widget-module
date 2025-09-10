<table class="table">
    <thead class="thead-light">
        <tr>
            <th width="5%">{{ __('Product Code') }}</th>
            <th width="30%">{{ __('Name') }}</th>
            <th class="text-center">{{ __('Qty') }}</th>
            <th class="text-center">{{ __('Unit Price') }}</th>
            <th class="text-center" width="140">{{ __('Total') }}</th>
            <th width="50" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody id="order_tbody">
        @forelse ($cart->cartItems ?? [] as $key => $product)
            <tr id="{{ 'order_product_' . $product->id }}">
                <td>
                    <span class="product_back_order_code" data-status="{{ $product->product_back_order }}"></span>
                    <div class="product-item">
                        <div class="product-info">
                            <h5 class="product-title">{{ $product->product_code }}
                            </h5>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="product-item">
                        <div class="product-info">
                            <h5 class="product-title"><a href="{{ $product->url }}">{{ $product->product_name }}</a></h5>
                            @if ($product->source_type)
                                <span>
                                    @if ($product->expiry_date < date("Y-m-d"))
                                        <i class="text-danger">Your {{ $product->source_type }} is expired.</i>
                                    @else
                                        <em>Source:</em> {{ $product->source_type }}  {{ $product->source }},
                                        <em>Expires:</em> {{ carbon_date($product->expiry_date) }},
                                        <em>Min. Qty:</em> {{ $product->additional_info['minimum_quantity'] ?? 1 }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <input class="form-control form-control-sm text-center qty"
                        name="qty[]" value="{{ $product->quantity }}" min="1"
                        oninput="checkQtyRule(this, {{ $product->additional_info['minimum_quantity'] ?? 1 }})"
                    />
                </td>
                <td class="text-right text-lg text-medium product-price">
                    {{ price_format($product->unitprice) }}</td>
                <td class="text-right text-lg text-medium product-total-price">
                    {{ price_format(getTotalPriceOfItem($product->unitprice, $product->quantity)) }}
                </td>
                <td>
                    <div class="btn-group gap-2 mr-0 pr-0">
                        <button type="button" class="btn btn-secondary btn-sm rounded" data-toggle="tooltip"
                            title="Update quantity" onclick="updateSingleProduct({{ $product->id }})">
                            <i class="nav-icon pe-7s-refresh-2"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger rounded" data-toggle="tooltip"
                            title="Remove" onclick="removeProductFromOrder({{ $product->id }})">
                            <i class="pe-7s-trash nav-icon"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td @erp colspan="6" @else colspan="5" @enderp
                    class="text-center">
                    <h5>No items in cart</h5>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td @erp colspan="4" @else colspan="3" @enderp class="text-right">
                {{ __('Subtotal') }}
            </td>
            <td class="text-right text-lg text-medium font-weight-bold">
                {{ price_format($order_summary?->TotalOrderValue) }}
            </td>
            <td></td>
        </tr>
        <tr>
            <td @erp colspan="4" @else colspan="3" @enderp class="text-right">
                {{ __('Sales Tax') }}
            </td>
            <td class="text-right text-lg text-medium font-weight-bold">
                {{ price_format($order_summary?->SalesTaxAmount) }}
            </td>
            <td></td>
        </tr>
        <tr>
            <td @erp colspan="4" @else colspan="3" @enderp class="text-right">
                {{ __('Shipping Cost') }}
            </td>
            <td class="text-right text-lg text-medium font-weight-bold">
                {{
                    $order_summary?->FreightAmount <= $order_summary?->TotalOrderValue?
                        config('amplify.marketing.checkout_threshold_replace') : price_format($order_summary->FreightAmount)
                }}
            </td>
            <td></td>
        </tr>
        <tr>
            <td @erp colspan="4" @else colspan="3" @enderp class="text-right">
                {{ __('Total') }}
            </td>
            <td class="text-right text-lg text-medium font-weight-bold">
                {{ price_format($order_summary?->TotalOrderValue + $order_summary?->SalesTaxAmount + $order_summary?->FreightAmount) }}
            </td>
            <td></td>
        </tr>
    </tfoot>
</table>

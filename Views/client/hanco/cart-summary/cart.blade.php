<div {!! $htmlAttributes !!}>
    {{-- Single item template --}}
    <textarea style="display: none;" id="cart-single-item-template">
        <tr class="cart-summary-item">
            <td>
                <div class="product-item">
                    <a class="product-thumb" href="{product_url}">
                        <img src="{product_image}" class="img-thumbnail" alt="Product">
                    </a>

                    <div class="product-info">
                        <p class="mb-0 manufacturer-name">
                            {manufacturer_name}
                        </p>
                        <span>{short_description}</span>
                        <p class="product-title fw-normal">
                            <a href="{product_url}">
                                {product_name}
                            </a>
                        </p>
                        <span><em>Product Code:</em>{product_code}</span>
                        <span>{source_message}</span>
                    </div>
                </div>
            </td>
            <td class="text-center" width="200">
                <div class="d-flex gap-3 justify-content-center">
                    <div class="item-count align-items-center d-flex">
                        <button type="button" onclick="decreaseCartQuantity({cart_item_id});"
                            class="text-dark item-decrease">
                            <i class="icon-minus fw-700"></i>
                        </button>

                        <span class="item-quantity cart-item-{cart_item_id} font-weight-bold mx-2 p-2 text-center border rounded">{quantity}</span>
                        <input type="hidden" value="{quantity}" name="cart-item-qty[{cart_item_id}]" data-min-qty="{minimum_qty}"/>

                        <button type="button" onclick="increaseCartQuantity({cart_item_id});"
                            class="item-increase text-white bg-warning rounded">
                            <i class="icon-plus fw-700"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td class="text-center text-lg text-medium text-right">{unit_price}</td>
            <td class="text-center text-lg text-medium text-right sub_total">{sub_total}</td>
            <td class="text-center">
                <a class="update-from-cart"
                    href="#"
                    onclick="updateSingleProductFromCartSummary({cart_item_id});"
                    data-toggle="tooltip"
                    title="Update item"
                ><i style="font-size: 1.2rem; font-weight: bolder;" class="icon-repeat"></i></a>
                <a class="remove-from-cart ml-3"
                    href="#"
                    onclick="removeProductFromCartSummary({cart_item_id});"
                    data-toggle="tooltip"
                    title="Remove item"
                ><i style="font-size: 1.2rem; font-weight: bolder;" class="icon-cross"></i></a>
            </td>
        </tr>
    </textarea>


    {{-- custom item start from here --}}
    <textarea style="display: none;" id="cart-single-item-custom-item-template">
        <tr class="cart-summary-item">
            <td>
                <div class="product-item">
                    <a class="product-thumb" href="{product_url}">
                        <img src="{product_image}" class="img-thumbnail" alt="Product">
                    </a>

                    <div class="product-info">
                        <p class="badge bg-info text-uppercase m-0">Custom Item</p>
                        <p class="mb-0 manufacturer-name">
                            {manufacturer_name}
                        </p>
                        <span>{short_description}</span>
                        <p class="product-title fw-normal">
                            <a href="{product_url}">
                                {product_name}
                            </a>
                        </p>
                        <span><em>Product Code:</em>{product_code}</span>
                        <span class="text-success text-bold">{source_message}</span>
                    </div>
                </div>
            </td>
            <td class="text-center" width="200">
                <div class="d-flex gap-3 justify-content-center">
                    <div class="item-count align-items-center d-flex">
                        <div class="d-flex justify-content-center flex-column">
                        <span class="item-quantity cart-item-{cart_item_id} font-weight-bold mx-2 p-2 text-center border rounded">{quantity}</span>
                            <input type="hidden" value="{quantity}" name="cart-item-qty[{cart_item_id}]" data-min-qty="{minimum_qty}"/>
                        <p class="text-danger text-bold pt-1">{uom}</p>
                        </div>
                    </div>
                </div>
            </td>
            <td class="text-center text-lg text-medium text-right">{unit_price}</td>
            <td class="text-center text-lg text-medium text-right sub_total">{sub_total}</td>
            <td class="text-center">
                <a class="remove-from-cart ml-3"
                    href="#"
                    onclick="removeProductFromCartSummary({cart_item_id});"
                    data-toggle="tooltip"
                    title="Remove item"
                ><i style="font-size: 1.2rem; font-weight: bolder;" class="icon-cross"></i></a>
            </td>
        </tr>
    </textarea>

    <div class="mb-1">
        <div id="cart-summary">
            <div class="table-responsive shopping-cart">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="py-1 text-center">Product</th>
                        <th class="py-1 text-center" width="200">Quantity</th>
                        <th class="py-1 text-center">Price</th>
                        <th class="py-1 text-center" width="100">Subtotal</th>
                        <th class="py-1 text-center" width="110">
                            <a href="#" class="btn btn-sm btn-outline-danger"
                               data-toggle="modal" data-target="#clear-modal">
                                Clear Cart
                            </a>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="cart-item-summary"></tbody>
                </table>
            </div>
            <div class="shopping-cart-footer">
                <div class="column text-lg">
                    Subtotal: <span class="text-medium" id="order-subtotal">$0.00</span>
                </div>
            </div>
        </div>
    </div>

    <div class="shopping-cart-footer">
        <div class="column">
            <a class="btn btn-outline-secondary" href="{{ route('frontend.shop.index') }}">
                <i class="icon-arrow-left"></i>&nbsp;Back to Shopping
            </a>
        </div>
        <div class="column">
            <a class="btn btn-success" href="{{ route('frontend.checkout') }}">Checkout</a>
        </div>
    </div>
</div>
@pushonce('footer-script')
    <script src="{{ asset('frontend/hanco/js/cart-summary.js') }}"></script>
@endpushonce

@php
    push_html(function() {
    return <<<HTML
    <div class="modal fade" id="clear-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cart Clear Confirmation</h5>
                    <button class="close" type="button" role="button" data-dismiss="modal" aria-label="Close"
                        onclick="setPositionOffCanvas()"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="text-danger d-block text-center mb-3">
                        <i class="fa fa-exclamation-triangle fa-4x"></i>
                    </span>
                    <h3 class="mt-3 text-center font-weight-bold ">
                        Are you sure?
                    </h3>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button class="btn btn-outline-secondary btn-sm" id="list-modal-close" type="button"
                        data-dismiss="modal" onclick="setPositionOffCanvas()">Close
                    </button>
                    <button type="button" id="clear_button" class="btn btn-danger btn-sm" data-dismiss="modal"
                        onclick="deleteCartsOrder()">Delete</button>
                </div>
            </div>
        </div>
    </div>
    HTML;
    });
@endphp

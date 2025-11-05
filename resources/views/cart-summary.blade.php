<div {!! $htmlAttributes !!}>
    <template id="cart-single-item-template">
        {!! $itemRow ?? '' !!}
    </template>

    <div class="mb-1">
        <div id="cart-summary">
            <div class="table-responsive shopping-cart mb-1">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="py-1 text-center">Product</th>
                        <th class="py-1 text-center" width="200">Quantity</th>
                        <th class="py-1 text-center">Price</th>
                        <th class="py-1 text-center" width="100">Subtotal</th>
                        <th class="py-1 text-center" width="110">
                            <a href="#" class="btn btn-sm btn-outline-danger" style="height: 34px; line-height: 34px"
                               data-toggle="modal" data-target="#clear-modal">
                                Clear Cart
                            </a>
                        </th>
                    </tr>
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
            <a class="btn btn-outline-secondary btn-sm" href="{{ $backToShoppingUrl() }}">
                <i class="icon-arrow-left"></i>&nbsp;Back to Shopping
            </a>
        </div>
        <div class="column">
            <a class="btn btn-success btn-sm" href="{{ route('frontend.checkout') }}">Checkout</a>
        </div>
    </div>
</div>
@push('html-default')
    <div class="modal fade" id="clear-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cart Clear Confirmation</h5>
                    <button class="close" type="button" role="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
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
                            data-dismiss="modal">Close
                    </button>
                    <button type="button" id="clear_button" class="btn btn-danger btn-sm" data-dismiss="modal"
                            onclick="deleteCartsOrder()">Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@pushonce('footer-script')
    <script src="{{ asset('assets/js/widget/cart-summary.js') }}"></script>
@endpushonce

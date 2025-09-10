<style>
    @media screen and (max-width: 600px) {
        table#checkout-cart {
            border: 0;
        }

        table#checkout-cart thead {
            border: none;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        table#checkout-cart tr {
            border-top: 1px solid #ddd;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 3px solid #ddd;
            border-radius: 0.3rem;
            display: block;
            margin-bottom: .625em;
        }

        table#checkout-cart td {
            border-bottom: 1px solid #ddd;
            display: block;
            font-size: .8em;
            text-align: right;
            padding: 8px;
        }

        table#checkout-cart td::before {
            /*
            * aria-label has no advantage, it won't be read inside a table
            content: attr(aria-label);
            */
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }

        table#checkout-cart td:last-child {
            border-bottom: 0;
        }
    }
</style>
<section class="checkout checkout-step checkout-{{ $id }} @if($isActive) active @endif" data-parent="#{{ $id }}">
    <h4>Review Your Order</h4>
    <hr class="padding-bottom-1x">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="order_reference">PO Number @if($customer->PoRequired == 'Y')
                        <span class="text-danger font-weight-bold">*</span>
                    @endif
                </label>
                <input class="form-control" type="text" id="order_reference" name="order_reference"
                       @if($customer->PoRequired == 'Y') required @endif>
                @if($customer->PoRequired == 'Y')
                    <span class="text-sm text-muted">A purchase order number is required to complete the order.</span>
                @endif
                <span class="invalid-feedback">@error('order_reference') $message @enderror</span>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="order_reference">Note</label>
                <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                <span class="invalid-feedback">@error('note') $message @enderror</span>
            </div>
        </div>
    </div>
    <h4>Cart Items</h4>
    <hr class="padding-bottom-1x">
    <div class="shopping-cart table-responsive mb-0">
        <table class="table table-hover table-sm" id="checkout-cart">
            <thead>
            <tr>
                <th class="align-content-around" scope="col">Product</th>
                <th class="align-content-around text-center" scope="col">Qty</th>
                <th class="align-content-around text-center" scope="col">Subtotal</th>
                <th class="align-content-around" scope="col"></th>
            </tr>
            </thead>
            <tbody id="checkout-cart-item">
            </tbody>
        </table>
    </div>
    <div class="shopping-cart-footer">
        <div class="column text-lg">Total: <span class="text-medium" id="checkout-item-count">0</span> item(s)</div>
        <div class="column text-lg">Subtotal: <span class="text-medium" id="checkout-subtotal">$0.00</span></div>
    </div>
    @include('widget::checkout.inc.footer')
</section>

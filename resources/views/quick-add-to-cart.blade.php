<div {!! $htmlAttributes !!}>
    <div class="card" style="padding: 10px; background-color: #F2F8FD">
        <div class="card-header d-flex justify-content-between gap-2">
            <h5 class="card-title">{{ $heading }}</h5>
            <i class="icon-arrow-down" type="button" data-toggle="collapse" data-target="#quick-add-to-cart-collapse"
               aria-expanded="true" aria-controls="quick-add-to-cart-collapse"></i>
        </div>
        <div class="card-body p-0  collapse show" id="quick-add-to-cart-collapse">
            <form class="mt-3" id="quick-add-to-cart-form">
                <div class="form-group">
                    <label
                        for="part-number"
                        style="font-size: 1rem; line-height: 26px">
                        Item/Part Number
                    </label>
                    <input class="form-control" id="part-number" name="product[]">
                    <small class="d-block text-danger font-weight-bolder" id="part-number-error"></small>
                </div>
                <div class="form-group row">
                    <label
                        for="quantity"
                        class="col-sm-3 col-form-label" style="font-size: 1rem; line-height: 26px">
                        Quantity
                    </label>
                    <div class="col-sm-9 align-items-center d-flex">
                        <button type="button"
                                class="text-dark item-decrease">
                            <i class="icon-minus fw-700"></i>
                        </button>
                        <input
                            type="number"
                            class="item-quantity cart-item-{cart_item_id} font-weight-bold mx-2 p-2 text-center border rounded"
                            value=""
                            id=""
                            name="product[]"
                            min="0.01"
                            step="0.01"
                        />

                        <button type="button" onclick="increaseCartQuantity({cart_item_id});"
                                class="item-increase text-white bg-warning rounded">
                            <i class="icon-plus fw-700"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <button type="submit"
                            class="btn btn-primary"
                            form="quick-add-to-cart-form">Add To Cart</button>
                </div>
            </form>
        </div>
    </div>
</div>

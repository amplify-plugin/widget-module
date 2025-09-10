<section class="checkout checkout-step checkout-{{ $id }} @if($isActive) active @endif" data-parent="#{{ $id }}">
    <h4><i class="icon-paper"></i> Payment Method</h4>
    <hr class="padding-bottom-1x">
    <div class="row">
        <div class="col-12">
            <p>We accept following credit cards:&nbsp;<img class="d-inline-block align-middle"
                                                           src="{{ assets_image('images/credit-cards.png') }}"
                                                           style="width: 120px;" alt="Credit Cards"></p>
            <div class="card-wrapper"></div>
            <div class="interactive-credit-card">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <input class="form-control billing-info" id="billing_credit_card" type="text" name="number"
                               placeholder="Card Number*" required>
                        <span class="invalid-feedback">@error('shipping_country') $message @enderror</span>
                    </div>
                    <div class="form-group col-sm-6">
                        <input class="form-control billing-info" type="text" name="name" placeholder="Full Name*" required>
                        <span class="invalid-feedback">@error('shipping_country') $message @enderror</span>
                    </div>
                    <div class="form-group col-sm-3">
                        <input class="form-control" type="text" name="expiry" placeholder="MM/YY*" required>
                        <span class="invalid-feedback">@error('shipping_country') $message @enderror</span>
                    </div>
                    <div class="form-group col-sm-3">
                        <input class="form-control" type="text" name="cvc" placeholder="CVC*" required>
                        <span class="invalid-feedback">@error('shipping_country') $message @enderror</span>
                    </div>
                    <div class="form-group col-sm-6">
                        <input class="form-control" type="text" name="phone" placeholder="Phone Number" required>
                        <span class="invalid-feedback">@error('shipping_country') $message @enderror</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h4 class="margin-top-1x"><i class="icon-map"></i> Billing Address</h4>
    <hr class="padding-bottom-1x">
    <div class="form-group">
        <label for="billing-checkout-address">Address</label>
        <select class="form-control"
                name="shipping_address" required
                id="billing-checkout-address" required>
            @foreach($addresses as $address)
                <option
                    value="{{ $address->ShipToNumber ?? '' }}" @selected($address->ShipToNumber == $customer->DefaultShipTo ?? '')>
                    {!! $address->toJson() !!}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="bill-to-name">Name <span
                class="text-danger font-weight-bold">*</span></label>
        <input class="form-control billing-info" name="billing-name" type="text" id="bill-to-name"
               required>
    </div>
    <div class="form-group">
        <label for="bill-to-address1">Address <span
                class="text-danger font-weight-bold">*</span></label>
        <input class="form-control my-1 billing-info" placeholder="Address Line 1" type="text"
               id="bill-to-address1"
               required>
        <input class="form-control my-1 billing-info" placeholder="Address Line 2" type="text"
               id="bill-to-address2">
        <input class="form-control my-1 billing-info" placeholder="Address Line 3" type="text"
               id="bill-to-address3">
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="bill-to-city">City <span
                        class="text-danger font-weight-bold">*</span></label>
                <input class="form-control billing-info" name="zip-code" type="text" required id="bill-to-city">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="bill-to-state">State <span
                        class="text-danger font-weight-bold">*</span></label>
                <select class="form-control billing-info select2-dropdown" name="zip-code" type="text" required
                        id="bill-to-state">
                    <option value="">Select an State</option>
                    @foreach($states as $stateCode => $stateName)
                        <option value="{{ $stateCode }}">
                            {{ $stateCode }} - {{ $stateName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="bill-to-zip-code">ZIP Code <span
                        class="text-danger font-weight-bold">*</span></label>
                <input class="form-control billing-info" name="zip-code" type="text" readonly
                       id="bill-to-zip-code">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="bill-to-country">Country</label>
                <select class="form-control billing-info select2-dropdown" name="shipping_country" id="bill-to-country">
                    @foreach($countries as $countryCode => $countryName)
                        <option
                            value="{{ $countryCode }}" @selected($countryCode == $customer->CustomerCountry)>
                            {{ $countryCode }} - {{ $countryName }}
                        </option>
                    @endforeach
                </select>
                <span class="invalid-feedback">@error('shipping_country') $message @enderror</span>
            </div>
        </div>
    </div>
    @include('widget::checkout.inc.footer')
</section>

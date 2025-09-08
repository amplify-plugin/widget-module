<section class="checkout checkout-step checkout-{{ $id }} @if($isActive) active @endif" data-parent="#{{ $id }}">
    <h4><i class="icon-bag"></i> Shipping Method</h4>
    <hr class="padding-bottom-1x">
    <div class="form-group">
        <select class="form-control select2-dropdown"
                name="shipping_option" required
                id="shipping-checkout-option" required>
            <option value="">Select an Option</option>
            @foreach($shipOptions as $option)
                <option
                    value="{{ $option->CarrierCode ?? '' }}" @selected($option->CarrierCode == $customer->CarrierCode ?? '')>
                    {!! $option->CarrierDescription ?? '' !!}
                </option>
            @endforeach
        </select>
    </div>
    <h4 class="margin-top-1x"><i class="icon-map"></i> Shipping Address</h4>
    <hr class="padding-bottom-1x">
    <div class="form-group">
        <label for="shipping-checkout-address">Address</label>
        <select class="form-control"
                name="shipping_number" required
                id="shipping-checkout-address" required>
            @foreach($addresses as $address)
                <option
                    value="{{ $address->ShipToNumber ?? '' }}" @selected($address->ShipToNumber == $customer->DefaultShipTo ?? '')>
                    {!! $address->toJson() !!}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="ship-to-name">Name <span
                class="text-danger font-weight-bold">*</span></label>
        <input class="form-control shipping-info" name="shipping-name" type="text" id="ship-to-name"
               required>
    </div>
    <div class="form-group">
        <label for="ship-to-address1">Address <span
                class="text-danger font-weight-bold">*</span></label>
        <input class="form-control my-1 shipping-info" placeholder="Address Line 1" type="text"
               id="ship-to-address1"
               required>
        <input class="form-control my-1 shipping-info" placeholder="Address Line 2" type="text"
               id="ship-to-address2">
        <input class="form-control my-1 shipping-info" placeholder="Address Line 3" type="text"
               id="ship-to-address3">
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="ship-to-city">City <span
                        class="text-danger font-weight-bold">*</span></label>
                <input class="form-control shipping-info" name="zip-code" type="text" required id="ship-to-city">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="ship-to-state">State <span
                        class="text-danger font-weight-bold">*</span></label>
                <select class="form-control shipping-info select2-dropdown" name="zip-code" type="text" required
                        id="ship-to-state">
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
                <label for="ship-to-zip-code">ZIP Code <span
                        class="text-danger font-weight-bold">*</span></label>
                <input class="form-control shipping-info" name="zip-code" type="text" readonly
                       id="ship-to-zip-code">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="ship-to-country">Country</label>
                <select class="form-control shipping-info select2-dropdown" name="shipping_country" id="ship-to-country">
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

<section class="checkout checkout-step checkout-{{ $id }} @if($isActive) active @endif" data-parent="#{{ $id }}">
    <div class="accordion" id="address-accordion" role="tablist">
        <div class="card">
            <div class="card-header" role="tab">
                <h6><a href="#billing-address" data-toggle="collapse"><i class="icon-head"></i>Billing Address</a></h6>
            </div>
            <div class="collapse" id="billing-address" data-parent="#address-accordion" role="tabpanel">
                <div class="card-body">
                    <div class="form-group">
                        <label for="billing-checkout-address">Select or Add Temporary Address</label>
                        <select class="form-control" name="billing_address"
                                required
                                id="billing-checkout-address" required>
                            @foreach($addresses as $address)
                                <option
                                    value="{{ $address->ShipToNumber ?? '' }}" @selected($address->ShipToNumber == $customer->DefaultShipTo ?? '')>
                                    {!! $address->toJson() !!}
                                </option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback">@error('billing_address') $message @enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="bill-to-name">Name <span
                                class="text-danger font-weight-bold">*</span></label>
                        <input class="form-control" name="billing_name" type="text" id="bill-to-name"
                               required>
                        <span class="invalid-feedback">@error('billing_name') $message @enderror</span>
                    </div>
                    <div class="form-group">
                        <label for="bill-to-address1">Address <span
                                class="text-danger font-weight-bold">*</span></label>
                        <input class="form-control my-1" placeholder="Address Line 1" type="text"
                               name="billing_address1" id="bill-to-address1"
                               required>
                        <input class="form-control my-1" placeholder="Address Line 2" type="text"
                               name="billing_address2" id="bill-to-address2">
                        <input class="form-control my-1" placeholder="Address Line 3" type="text"
                               name="billing_address3" id="bill-to-address3">
                        <span class="invalid-feedback">@error('billing_address1') $message @enderror</span>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="bill-to-city">City <span
                                        class="text-danger font-weight-bold">*</span></label>
                                <input class="form-control" name="billing_city" type="text" required
                                       id="bill-to-city">
                                <span class="invalid-feedback">@error('billing_city') $message @enderror</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="bill-to-state">State <span
                                        class="text-danger font-weight-bold">*</span></label>
                                <select class="form-control" name="billing_state" type="text" required
                                        id="bill-to-state">
                                    <option value="">Select an State</option>
                                    @foreach($states as $stateCode => $stateName)
                                        <option value="{{ $stateCode }}">
                                            {{ $stateCode }} - {{ $stateName }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">@error('billing_state') $message @enderror</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="bill-to-zip-code">ZIP Code</label>
                                <input class="form-control" name="billing_zipcode" type="text" readonly
                                       id="bill-to-zip-code">
                                <span class="invalid-feedback">@error('billing_zipcode') $message @enderror</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="bill-to-country">Country</label>
                                <select class="form-control" name="billing_country" id="bill-to-country">
                                    @foreach($countries as $countryCode => $countryName)
                                        <option
                                            value="{{ $countryCode }}" @selected($countryCode == $customer->CustomerCountry)>
                                            {{ $countryCode }} - {{ $countryName }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">@error('customer_country') $message @enderror</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab">
                <h6><a href="#shipping-address" data-toggle="collapse"><i class="icon-map"></i>Shipping Address</a>
                </h6>
            </div>
            <div class="collapse show" id="shipping-address" data-parent="#address-accordion" role="tabpanel">
                <div class="card-body">
                    <div class="form-group">
                        <label for="shipping-checkout-address">Address</label>
                        <select class="form-control"
                                name="shipping_address" required
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
                        <input class="form-control" name="shipping-name" type="text" id="ship-to-name"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="ship-to-address1">Address <span
                                class="text-danger font-weight-bold">*</span></label>
                        <input class="form-control my-1" placeholder="Address Line 1" type="text"
                               id="ship-to-address1"
                               required>
                        <input class="form-control my-1" placeholder="Address Line 2" type="text"
                               id="ship-to-address2">
                        <input class="form-control my-1" placeholder="Address Line 3" type="text"
                               id="ship-to-address3">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="ship-to-city">City <span
                                        class="text-danger font-weight-bold">*</span></label>
                                <input class="form-control" name="zip-code" type="text" required id="ship-to-city">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="ship-to-state">State <span
                                        class="text-danger font-weight-bold">*</span></label>
                                <select class="form-control" name="zip-code" type="text" required
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
                                <input class="form-control" name="zip-code" type="text" readonly
                                       id="ship-to-zip-code">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="ship-to-country">Country</label>
                                <select class="form-control" name="shipping_country" id="ship-to-country">
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
                </div>
            </div>
        </div>
    </div>
    @include('widget::checkout.inc.footer')
</section>

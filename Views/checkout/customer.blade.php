<section class="checkout checkout-step checkout-{{ $id }} @if($isActive) active @endif" data-parent="#{{ $id }}">
    <h4><i class="icon-head"></i> Account Information</h4>
    <hr class="padding-bottom-1x">
    <div class="row">
        <div class="col-sm">
            <div class="form-group">
                <label for="customer_name">Customer Name<span class="text-danger font-weight-bold">*</span></label>
                <input class="form-control" type="text" id="customer_name" name="customer_name"
                       value="{{ $customer->CustomerName ?? null }}"
                       @if($customer->CustomerName != null && customer_check()) readonly @endif
                       required>
                <span class="invalid-feedback">@error('customer_name') $message @enderror</span>
            </div>
        </div>
        @if(!empty($customer->CustomerNumber))
            <div class="col-sm">
                <div class="form-group">
                    <label for="customer_code">Account Code</label>
                    <input class="form-control" type="text" id="customer_code" name="customer_code"
                           value="{{ $customer->CustomerNumber ?? null }}"
                           @if($customer->CustomerNumber != null && customer_check()) readonly @endif
                           required>
                    <span class="invalid-feedback">@error('customer_code') $message @enderror</span>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="customer_email">E-mail Address</label>
                <input class="form-control" type="email" id="customer_email"
                       @if($customer->CustomerEmail != null && customer_check()) readonly @endif
                       value="{{ $customer->CustomerEmail ?? null }}" name="customer_email">
                <span class="invalid-feedback">@error('customer_email') $message @enderror</span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="customer_phone">Phone Number</label>
                <input class="form-control" type="text"
                       @if($customer->CustomerPhone != null && customer_check()) readonly @endif
                       required name="customer_phone" id="customer_phone"
                       value="{{ $customer->CustomerPhone ?? null }}">
                <span class="invalid-feedback">@error('customer_phone') $message @enderror</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="customer_user">User</label>
                <input class="form-control" type="text" id="customer_user" name="customer_user"
                       value="{{ $contact->name ?? 'Guest User' }}" readonly
                       required>
                <span class="invalid-feedback">@error('customer_user') $message @enderror</span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="customer_country">Country</label>
                <select class="form-control select2-dropdown " name="customer_country" id="customer_country"
                        style="width: 100%">
                    @foreach($countries as $countryCode => $countryName)
                        <option value="{{ $countryCode }}" @selected($countryCode == $customer->CustomerCountry)>
                            {{ $countryCode }} - {{ $countryName }}
                        </option>
                    @endforeach
                </select>
                <span class="invalid-feedback">@error('customer_country') $message @enderror</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="customer_address_1">Address<span class="text-danger font-weight-bold">*</span></label>
                <input class="form-control my-1" type="text" name="customer_address_1" id="customer_address_1"
                       required
                       @if($customer->CustomerAddress1 != null && customer_check()) readonly @endif
                       placeholder="Address Line 1"
                       value="{{ $customer->CustomerAddress1 ?? null }}">

                <input class="form-control my-1" type="text" name="customer_address_2" id="customer_address_1"
                       placeholder="Address Line 2"
                       @if($customer->CustomerAddress2 != null && customer_check()) readonly @endif
                       value="{{ $customer->CustomerAddress2 ?? null }}">

                <input class="form-control my-1" type="text" name="customer_address_3" id="customer_address_1"
                       placeholder="Address Line 3"
                       @if($customer->CustomerAddress3 != null && customer_check()) readonly @endif
                       value="{{ $customer->CustomerAddress3 ?? null }}">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="customer_city">City<span class="text-danger font-weight-bold">*</span></label>
                <input class="form-control" name="customer_city" type="text" required id="customer_city"
                       @if($customer->CustomerCity != null && customer_check()) readonly @endif
                       value="{{ $customer->CustomerCity ?? null }}">
                <span class="invalid-feedback">@error('customer_city') $message @enderror</span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="customer_state">State<span class="text-danger font-weight-bold">*</span></label>
                <select class="form-control select2-dropdown" name="customer_state" required id="customer_state"
                        style="width: 100%">
                    <option value="">Select an State</option>
                    @foreach($states as $stateCode => $stateName)
                        <option value="{{ $stateCode }}" @selected($stateCode == $customer->CustomerState)>
                            {{ $stateCode }} - {{ $stateName }}
                        </option>
                    @endforeach
                </select>
                <span class="invalid-feedback">@error('customer_state') $message @enderror</span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="customer_zipcode">Zip Code<span class="text-danger font-weight-bold">*</span></label>
                <input class="form-control" name="customer_zipcode" type="text" required id="customer_zipcode"
                       @if($customer->CustomerZipCode != null && customer_check()) readonly @endif
                       value="{{ $customer->CustomerZipCode ?? null }}">
                <span class="invalid-feedback">@error('customer_zipcode') $message @enderror</span>
            </div>
        </div>
    </div>
    @include('widget::checkout.inc.footer')
</section>

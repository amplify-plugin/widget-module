<div {!! $htmlAttributes !!}>
<div class="card">
    <div class="card-body">
        <div class="form-group">
            <p class="font-weight-bold">Address Name</p>
            <p>{{ $address->address_name ?? '' }}</p>
        </div>
        <div class="form-group">
            <p class="font-weight-bold">Street Address</p>
            <p>{{ $address->address_1 ?? '' }}</p>
            <p>{{ $address->address_2 ?? '' }}</p>
            <p>{{ $address->address_3 ?? '' }}</p>
        </div>
        <div class="form-group">
            <p class="font-weight-bold">City</p>
            <p>{{ $address->city ?? '' }}</p>
        </div>

        <div class="form-group">
            <p class="font-weight-bold">State</p>
            <p>{{ $address->state ?? '' }}</p>
        </div>

        <div class="form-group">
            <p class="font-weight-bold">Zip Code</p>
            <p>{{ $address->zip_code ?? '' }}</p>
        </div>

        <div class="form-group">
            <p class="font-weight-bold">Country</p>
            <p>{{ $address->country_code ?? '' }}</p>
        </div>
    </div>
</div>
</div>

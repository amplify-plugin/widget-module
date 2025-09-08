<div class="table-responsive">
    <table class="table table-hover">
        <thead class="thead-default">
        <tr>
            <th></th>
            <th>Shipping method</th>
            <th>Delivery time</th>
            <th>Handling fee</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="align-middle">
                <div class="custom-control custom-radio mb-0">
                    <input class="custom-control-input" type="radio" id="courier_{{$method}}" name="shipping-method"
                           required>
                    <label class="custom-control-label" for="courier_{{$method}}"></label>
                </div>
            </td>
            <td class="align-middle"><span class="text-medium">Courier</span><br><span class="text-muted text-sm">All Addresses(default zone), United States & Canada</span>
            </td>
            <td class="align-middle">2 - 4 days</td>
            <td class="align-middle">$22.50</td>
        </tr>
        <tr>
            <td class="align-middle">
                <div class="custom-control custom-radio mb-0">
                    <input class="custom-control-input" type="radio" id="local_{{$method}}" required
                           name="shipping-method">
                    <label class="custom-control-label" for="local_{{$method}}"></label>
                </div>
            </td>
            <td class="align-middle"><span class="text-medium">Local Shipping</span><br><span
                    class="text-muted text-sm">All Addresses(default zone), United States & Canada</span></td>
            <td class="align-middle">up to one week</td>
            <td class="align-middle">$10.00</td>
        </tr>
        <tr>
            <td class="align-middle">
                <div class="custom-control custom-radio mb-0">
                    <input class="custom-control-input" type="radio" id="flat_{{$method}}" required
                           name="shipping-method">
                    <label class="custom-control-label" for="flat_{{$method}}"></label>
                </div>
            </td>
            <td class="align-middle"><span class="text-medium">Flat Rate</span><br><span class="text-muted text-sm">All Addresses(default zone)</span>
            </td>
            <td class="align-middle">5 - 7 days</td>
            <td class="align-middle">$33.85</td>
        </tr>
        <tr>
            <td class="align-middle">
                <div class="custom-control custom-radio mb-0">
                    <input class="custom-control-input" type="radio" id="ups_{{$method}}" required
                           name="shipping-method">
                    <label class="custom-control-label" for="ups_{{$method}}"></label>
                </div>
            </td>
            <td class="align-middle"><span class="text-medium">UPS Ground Shipping</span><br><span
                    class="text-muted text-sm">All Addresses(default zone)</span></td>
            <td class="align-middle">4 - 6 days</td>
            <td class="align-middle">$18.00</td>
        </tr>
        <tr>
            <td class="align-middle">
                <div class="custom-control custom-radio mb-0">
                    <input class="custom-control-input" type="radio" id="pickup_{{$method}}" required
                           name="shipping-method">
                    <label class="custom-control-label" for="pickup_{{$method}}"></label>
                </div>
            </td>
            <td class="align-middle"><span class="text-medium">Local Pickup from store</span><br><span
                    class="text-muted text-sm">All Addresses(default zone)</span></td>
            <td class="align-middle">&mdash;</td>
            <td class="align-middle">$0.00</td>
        </tr>
        <tr>
            <td class="align-middle">
                <div class="custom-control custom-radio mb-0">
                    <input class="custom-control-input" type="radio" id="locker_{{$method}}" required
                           name="shipping-method">
                    <label class="custom-control-label" for="locker_{{$method}}"></label>
                </div>
            </td>
            <td class="align-middle"><span class="text-medium">Pick Up at Unishop Locker</span><br><span
                    class="text-muted text-sm">All Addresses(default zone), United States & Canada</span></td>
            <td class="align-middle">&mdash;</td>
            <td class="align-middle">$9.99</td>
        </tr>
        <tr>
            <td class="align-middle">
                <div class="custom-control custom-radio mb-0">
                    <input class="custom-control-input" type="radio" id="global_{{$method}}" required
                           name="shipping-method">
                    <label class="custom-control-label" for="global_{{$method}}"></label>
                </div>
            </td>
            <td class="align-middle"><span class="text-medium">Unishop Global Export</span><br><span
                    class="text-muted text-sm">All Addresses(default zone), outside United States</span></td>
            <td class="align-middle">3 - 4 days;</td>
            <td class="align-middle">$25.00</td>
        </tr>
        </tbody>
    </table>
</div>

<aside class="sidebar">
    <div class="padding-top-2x hidden-lg-up"></div>
    <!-- Order Summary Widget-->
    <section class="widget widget-order-summary">
        <h3 class="widget-title">Order Summary</h3>
        <table class="table">
            <tr>
                <td>Cart Subtotal:</td>
                <td class="text-medium">$ {{ currency_format($cartPricingInfo['order_subtotal']) }}</td>
            </tr>
            <tr>
                <td>Shipping:</td>
                <td class="text-medium">$ {{ currency_format($cartPricingInfo['order_ship']) }}</td>
            </tr>
            <tr>
                <td>Estimated tax:</td>
                <td class="text-medium">$ {{ currency_format($cartPricingInfo['order_tax']) }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-lg text-medium">$ {{ currency_format($cartPricingInfo['order_total']) }}</td>
            </tr>
        </table>
    </section>
</aside>

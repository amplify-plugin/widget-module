<aside class="sidebar">
    <div class="padding-top-2x hidden-lg-up"></div>
    <!-- Order Summary Widget-->
    <section class="widget widget-order-summary">
        <h3 class="widget-title">Order Summary</h3>
        <table class="table">
            <tr>
                <td>Order Subtotal:</td>
                <td class="text-medium">$ {{ currency_format($order->total_net_price) }}</td>
            </tr>
            <tr>
                <td>Shipping:</td>
                <td class="text-medium">$ {{ currency_format($order->total_shipping_cost) }}</td>
            </tr>
            <tr>
                <td>Estimated tax:</td>
                <td class="text-medium">$ {{ currency_format($order->total_tax_amount) }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-lg text-medium">$ {{ currency_format($order->total_amount) }}</td>
            </tr>
        </table>
    </section>
</aside>

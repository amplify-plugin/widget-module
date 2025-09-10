<div {!! $htmlAttributes !!}>
    <div class="row">
        <div class="col-lg-6">
            <x-customer.dashboard.open-order :open-orders-count="$open_orders_count"/>
        </div>
        <div class="col-lg-6">
            <x-customer.dashboard.pending-order :pending_orders_count="$pending_orders_count"/>
        </div>
        <div class="col-lg-6">
            <x-customer.dashboard.monthly-order-amount :monthly-total="$monthly_total"/>
        </div>
    </div>
</div>

<style>
    .dash-count {
        min-height: 6rem;
        .card-title {
            font-size: 3rem;
            text-align: center;
            position: unset;
        }
    }
</style>

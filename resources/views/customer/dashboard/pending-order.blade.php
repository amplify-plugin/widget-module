<div {!! $htmlAttributes !!}>
    <div class="card text-white bg-warning text-center my-3">
        <div class="card-header text-lg">{{__('Pending Orders')}}</div>
        <div class="dash-count card-body">
            <h4 class="card-title">{{ $pendingOrdersCount }}</h4>
        </div>
    </div>
</div>

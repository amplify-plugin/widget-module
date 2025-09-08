<div {!! $htmlAttributes !!}>
    <div class="card text-white bg-primary text-center my-3">
        <div class="card-header text-lg">{{__('Monthly Order Amount')}}</div>
        <div class="dash-count card-body text-center">
            <h2 class="card-title">{{ price_format($monthlyTotal) }}</h2>
        </div>
    </div>
</div>

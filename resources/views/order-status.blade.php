<div {!! $htmlAttributes !!}>
    <div class="card text-center">
        <div class="card-body padding-top-2x">
            <h3 class="card-title">Thank you for your order!</h3>

            @if ($hasOrderRule)
                <p class="card-text">{{ $approvalMessage }}</p>
            @else
                <p class="card-text">{{ $successMessage }}</p>
            @endif

            <p class="card-text">You will be receiving an email shortly with confirmation of your order.</p>
            <div class="padding-top-1x padding-bottom-1x">
                <a class="btn btn-outline-secondary" href="{{ route('frontend.shop.index') }}">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>

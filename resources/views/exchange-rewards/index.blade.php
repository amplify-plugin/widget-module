@if(customer_check())
    @if($getMemberStatus())
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">My EXCHANGE Rewards</h5>
                @if(isset($getMemberBalance))
                    <h6 class="card-subtitle mb-2 text-muted">Balance: <strong>{{$getMemberBalance}}</strong></h6>
                @else
                    <h6 class="card-subtitle mb-2 text-muted">Balance: <strong>0</strong></h6>
                @endif
                <a href="{{$getSsoRequest}}" class="btn btn-primary"  target="_blank">Redeem My Points</a>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center">
                <p class="card-text">Activate your free EXCHANGE Rewards program by signing into your Safety Products Inc online account and accepting Terms and Conditions in EXCHANGE.</p>
                <a id="exRedeemLnk" href="{{$getSsoRequest}}" class="btn btn-primary" target="_blank">Accept</a>
            </div>
        </div>
    @endif
@else
    <div class="card">
        <div class="card-body text-center">
            <p class="card-text">Activate your free EXCHANGE Rewards program by signing into your Safety Products Inc online account and accepting Terms and Conditions in EXCHANGE.</p>
            <a href="{{ route('frontend.login') }}" class="btn btn-primary"  target="_self">Login</a>
        </div>
    </div>
@endif

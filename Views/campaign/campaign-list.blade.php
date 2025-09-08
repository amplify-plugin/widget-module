<div {!! $htmlAttributes !!}>
<div class="row">
    @forelse($campaigns ?? [] as $campaign)
        @php
            $products = $campaign->products;
 $count = $products->count();
 $position = 0;
 switch ($count) {
     case 0:
     case 1:
         $position=0;
         break;
     case 2:
         $position=1;
         break;
     default:
         $position = ceil($count/2);
 }
        @endphp
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card">
                <div class="card-img-tiles">
                    <div class="inner">
                        <div class="main-img">
                            <img src="{{ $products->first()?->productImage?->main ?? '#'}}"/>
                        </div>
                        <div class="thumblist">
                            <img src="{{ $products->get($position)?->productImage?->main ?? '#'}}"/>
                            <img src="{{ $products->last()?->productImage?->main ?? '#'}}"/>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center">
                    <a href="{{ route('frontend.campaigns.show', $campaign->slug) }}" class="text-decoration-none">
                        <h4 class="card-title">
                            {{ $campaign->name ?? "" }}
                        </h4>
                    </a>
                    <p class="text-muted">{{ $campaign->description }}</p>
                    @if($campaign->login_required)
                        @if(customer_check())
                            <a class="btn btn-outline-primary btn-sm"
                               href="{{ route('frontend.campaigns.show', $campaign->slug) }}">
                                View Products
                            </a>
                        @else
                            <a class="btn btn-outline-warning btn-sm"
                               href="{{ route('frontend.login') }}">
                                Login to see Products
                            </a>
                        @endif
                    @else
                        <a class="btn btn-outline-primary btn-sm"
                           href="{{ route('frontend.campaigns.show', $campaign->slug) }}">
                            View Products
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-warning">
            No Campaign Available
        </div>
    @endforelse
</div>
</div>

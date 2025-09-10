<div {!! $htmlAttributes !!}>
    @if($show_title)
        <h3 class="text-center mb-30 pb-2">{{ __($heading) }}</h3>
    @endif
    <div class="owl-carousel" data-owl-carousel="{{ $carouselOptions() }}">
        @foreach(($categoryData ?? []) as $category)
            <div class="card mb-30">
                <div class="card-img-tiles">
                    <div class="inner">
                        <div class="main-img">
                            <a href="https://ezshop.easyaskondemand1.com/store/product/82657"> <img
                                    src="https://www.gardner-white.com/images/product/47186.jpg"
                                    alt="Edison Bonded Leather Sofa - $377.95"
                                    title="Edison Bonded Leather Sofa - $377.95"> </a></div>
                        <div class="thumblist">
                            <a href="https://ezshop.easyaskondemand1.com/store/product/98543"> <img
                                    src="https://www.gardner-white.com/images/product/64371.jpg"
                                    alt="Taffy Microfiber Sofa with Floating Ottoman - $459.95"
                                    title="Taffy Microfiber Sofa with Floating Ottoman - $459.95"></a>
                            <a href="https://ezshop.easyaskondemand1.com/store/product/110958"> <img
                                    src="https://www.gardner-white.com/images/product/112345.jpg"
                                    alt="Lenox Reclining Sofa - $657.94" title="Lenox Reclining Sofa - $657.94"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $category->name ?? 'N/A' }}</h4>
                    @if($showStartingPrice)
                        <p class="text-muted">Starting from $299.77</p>
                    @endif
                    <a class="btn btn-outline-primary btn-sm"
                       href="{{ route('frontend.shop.index') }}?search=1&q={{ $category->code ?? '' }}">
                        View Products
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

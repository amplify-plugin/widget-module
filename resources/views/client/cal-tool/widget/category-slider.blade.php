<div {!! $htmlAttributes !!}>
    @if ($show_title)
        <h3 class="">{{ __($heading) }}</h3>
    @endif
    <div class="owl-carousel" data-owl-carousel="{{ $carouselOptions() }}">
        @foreach ($categoryData ?? [] as $category)
            <div class="card mb-30">
                <div class="card-img-tiles">
                    <a href="{{ route('frontend.shop.index') }}/{{ $category->category_name ?? '' }}">
                        <img src="{{ $category->image }}" alt="{{ $category->category_name }}">
                    </a>
                </div>
                <div class="card-body">
                    <a class="text-decoration-none"
                       href="{{ route('frontend.shop.index') }}/{{ $category->category_name ?? '' }}">
                        <h5 class="truncate" data-toggle="tooltip" title="{{ $category->category_name ?? 'N/A' }}">{{ $category->category_name ?? 'N/A' }}</h5>
                        @if ($showStartingPrice)
                            <p class="text-muted">Starting from $299.77</p>
                        @endif
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

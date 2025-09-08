<div {!! $htmlAttributes !!}>
    @if ($show_title)
        <h3 class="pb-2">{{ __($heading) }}</h3>
    @endif

    <div class="owl-carousel" data-owl-carousel="{{ $carouselOptions() }}">
        @foreach ($categoryData ?? [] as $category)
            <div class="card card-hover shadow-sm" style="cursor: pointer;">
                <img src="{{ $category->image }}" class="card-img" alt="...">
                <a href="{{ $category->productsLink() }}">
                    <div class="card-img-overlay">
                        <h5 class="card-title text-white">{{ $category->label }}</h5>
                    </div>
                </a>
                <div class="card-body p-0 bg-white">
                    <div class="p-3">
                        <h4 class="text-bold text-center border-bottom">{{ $category->label }}</h4>
                        <p class="card-text">
                            {{ $category->description ?? '' }}
                        </p>
                        <a href="{{ $category->productsLink() }}" class="btn btn-primary btn-block">Shop Now</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

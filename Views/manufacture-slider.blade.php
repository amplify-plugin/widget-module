<div {!! $htmlAttributes !!}>
    @if($show_title)
        <h3 class="text-center mb-30 pb-2">{{ __($heading) }}</h3>
    @endif
    <div class="manufacture-slider owl-carousel"
         data-owl-carousel="{{ $carouselOptions() }}">
        @foreach(($manufactureData ?? []) as $manufacturer)
            <a href="{{ route('frontend.shop.index') }}?search=1&q={{ $manufacturer->code ?? '' }}"
               title="{{ __($manufacturer->name ?? '') }}"
               class="text-decoration-none">
                <img class="d-block w-110 opacity-75 m-auto"
                     src="{{ assets_image($manufacturer->image) }}"
                     alt="{{ __($manufacturer->name ?? '') }}"/>
                @if ($showName)
                    <p class="text-center mt-2">{{ __($manufacturer->name ?? '') }}</p>
                @endif
            </a>
        @endforeach
    </div>
</div>

<style>
    .manufacture-slider .owl-item img {
        height: 110px;
        width: 110px;
    }
</style>

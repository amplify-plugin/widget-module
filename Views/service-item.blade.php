<div {!! $htmlAttributes !!}>
    <div class="border p-3 rounded text-justify service-card bg-white">
        @if (strlen($imageUrl) > 0 && $imageUrl != '#')
            <div class="w-90 mb-3 mx-auto" style="height: 90px !important;">
                <img class="w-100 h-100" style="object-fit: contain" src="{{ assets_image($imageUrl) }}"
                    alt="{{ __($alternativeText) }}">
            </div>
        @endif
        <h5 class="text-left text-bold">{{ __($title) }}</h5>
        <p class="text-muted margin-bottom-none" style="color: rgba(51, 51, 51, 1) !important">{{ __($subtitle) }}</p>
        {{ $slot }}
    </div>
</div>

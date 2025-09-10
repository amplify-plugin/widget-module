@php
    $bannerDetails = null;
    if (!(bool) $code && !empty($easyAskData['banners'])) {
        $result = array_filter($easyAskData['banners'], function ($banner) use ($zone) {
            return $banner->getZone() == $zone;
        });
        $bannerDetails = get_banner_from_zone($result);
    }
@endphp
<div {!! $htmlAttributes !!}>
    @if (!empty($bannerDetails))
        @if ($bannerDetails['type'] === 'image')
            <a href="{{ $bannerDetails['image_text_link'] ?? '#' }}" target="_blank">
                <div class="mb-4 alert alert-default alert-dismissible rounded fade show p-0" role="alert"
                     id="promo-banner">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-white" style="font-size: 2rem; font-weight:normal">×</span>
                    </button>
                    <img class="w-100 h-100 rounded banner-image" src="{{ $bannerDetails['image_path'] }}">
                </div>
            </a>
        @elseif($bannerDetails['type'] === 'html')
            {!! $bannerDetails['content'] !!}
        @elseif($bannerDetails['type'] === 'video')
            <iframe width="420" height="315" src="{{ $bannerDetails['video_link'] }}" title="Video player"
                    class="mb-4" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        @endif
    @endif
</div>

@pushonce('custom-style')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/widget/event-list.css') }}">
@endpushonce

@if($webinar->booking_url)
    <div {!! $htmlAttributes !!}>
        <div class="col-md-6 col-lg-12">

            @if ($webinar->booking_label)
                <div class="h5 mt-3 mb-3">{{ $webinar->booking_label }}</div>
            @endif

            <div class="rounded-8 bg-white overflow-hidden">
                {{ QrCode::size($qrcodeSize)->generate($webinar->booking_url) }}
            </div>
        </div>
    </div>
@endif

@pushonce('custom-style')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/widget/event-list.css') }}">
@endpushonce

<div {!! $htmlAttributes !!}>
    @if ($message)
        <div class="h5 mb-3">{{ $message }}</div>
    @endif

    <div class="mb-4 rounded-8 overflow-hidden border">
        <iframe
            src="https://www.google.com/maps/embed/v1/place?key={{config('amplify.google.google_map_api_key')}}&q={{ urlencode($webinar->address_url ?? '') }}"
            width="100%" height="{{ $height }}" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>

@pushonce('custom-style')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/widget/event-list.css') }}">
@endpushonce
<div {!! $htmlAttributes !!}>
<a href="{{ $webinar->booking_url ?? '' }}" class="d-block btn-primary m-0 btn mb-4">
    <i class="icon-link mr-2"></i>{{ $webinar->booking_label ?? "Book URL" }}
</a>
</div>

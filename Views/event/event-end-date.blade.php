@pushonce('custom-style')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/widget/event-list.css') }}">
@endpushonce
<div {!! $htmlAttributes !!}>
<div class="d-flex align-items-center gap-3 mb-3">
    <div class="icon d-flex align-items-center justify-content-center">
        <i class="pe-7s-date fs-32 fw-700 text-primary"></i>
    </div>
    <div class="">
        <div class="fs-18">End Date</div>
        <p class="">{{ $webinar->end_date_time->format('d F Y g:i A') }}</p>
    </div>
</div>
</div>

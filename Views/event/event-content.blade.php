@pushonce('custom-style')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/widget/event-list.css') }}">
@endpushonce

<div {!! $htmlAttributes !!}>
    @if ($showShortDesc)
        {{ $webinar->short_description }}
        <hr>
    @endif

    {!! $webinar->content !!}
</div>

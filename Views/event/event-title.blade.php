@pushonce('custom-style')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/widget/event-list.css') }}">
@endpushonce

<div {!! $htmlAttributes !!}>
    <h1 style="font-size: {{ $textSize }}">{{ $webinar->title }}</h2>
</div>

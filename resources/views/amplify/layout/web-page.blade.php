<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <x-amplify.head />
    <title>{{ $pageTitle }}</title>
</head>
<body {!! $htmlAttributes !!}>
@stack('off-canvas-menu')
{!! $slot !!}
<x-amplify.right-side-panel />
</body>
</html>

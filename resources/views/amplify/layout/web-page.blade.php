<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <x-amplify.head/>
    <title>{{ $pageTitle }}</title>
{{--    @livewireStyles--}}
</head>
<body {!! $htmlAttributes !!}>
    @stack('off-canvas-menu')
    {!! $slot !!}
{{--    @livewireScripts--}}
</body>
</html>

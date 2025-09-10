<div {!! $htmlAttributes !!}>
    <forgot-password />
</div>
@pushOnce("footer-script")
    <script src="{{ mix("js/backend.js", "vendor/backend") }}"></script>
@endPushOnce

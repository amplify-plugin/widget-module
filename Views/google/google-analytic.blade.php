@if (!empty($analytics_url) && !empty($analytics_id))
<script async src='{!! $analytics_url !!}?id={{$analytics_id}}'></script>
<script>
    window.dataLayer = window.dataLayer || [];
    window.gdata = {};

    function gtag(...arguments) {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', '{{$analytics_id}}');
</script>
@endif

@if($tag_manager_id)
<!-- Google Tag Manager -->
<script>
    (function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start':
                new Date().getTime(), event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', '{{ $tag_manager_id }}');
</script>
@endif

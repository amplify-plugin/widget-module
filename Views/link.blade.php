<div {!! $htmlAttributes !!}>
<a href="{{ $link() }}"
   class="{{ ($displayAsButton) ? 'btn btn-outline-primary' : '' }}"
   style="text-decoration: none"
   target="{{ ($openInNewWindow) ? '_blank' : '_self' }}">
    {{ $slot }}
</a>
</div>

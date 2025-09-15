<div {!! $htmlAttributes !!}>
    <div class="lang-currency-switcher-wrap">
        <div class="lang-currency-switcher dropdown-toggle">
                <span class="language">
                    <img alt="{{ $active->name ?? '' }}" src="{{ $active->flag ?? '' }}">
                </span>
        </div>
        <div class="dropdown-menu">
            @foreach ($languages as $language)
                @if ( $active->code == $language->code ?? null )
                    @continue
                @endif
                <a class="dropdown-item" href="{{ url("languages/".$language->code) }}"><img
                        src="{{ $language->flag }}"
                        alt="{{ $language->name ?? null }}">{{ $language->name ?? null }}</a>
            @endforeach
        </div>
    </div>
</div>

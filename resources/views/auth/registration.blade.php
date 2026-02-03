<div {!! $htmlAttributes !!}>
    <div class="mb-5">
        @if(!empty($displayableTitle))
            <h4 class="title">{!! $displayableTitle !!}</h4>
        @endif
        @if(!empty($displayableSubTitle))
            <p class="subtitle">{!! $displayableSubTitle !!}</p>
        @endif
    </div>
    <ul class="nav nav-tabs nav-fill registration-tabs">
        @stack('tab-title')
    </ul>
    <div class="tab-content p-3">
        {!! $slot !!}
    </div>
</div>

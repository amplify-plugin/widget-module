@if(isset($handle) && strlen(trim($handle) > 0))
    {!! $handle !!}
@elseif(strlen($toggleIcon) > 0)
    <a id="{{ $id }}-control" class="offcanvas-toggle default-toggle"
       href="#{{ $id }}"
       data-toggle="offcanvas">
        {!! $toggleIcon !!}
    </a>
@else
    <a id="{{ $id }}-control" class="offcanvas-toggle default-toggle"
       href="#{{ $id }}"
       data-toggle="offcanvas">
        {{ $title }}
    </a>
@endif

@push('off-canvas-menu')
    <style>
        {!! $style ?? '' !!}
    </style>
    <div id="{{ $id }}" {!! $htmlAttributes !!}>
        @if(isset($header) && strlen(trim($header) > 0))
            <div class="offcanvas-header">
                {!! $header !!}
            </div>
        @elseif(strlen($title) > 0)
            <div class="offcanvas-header">
                <h3 class="offcanvas-title font-weight-bolder text-center">
                    {!! $title ?? '' !!}
                </h3>
            </div>
        @endif
        <div class="offcanvas-menu">
            {!! $content ?? '' !!}
        </div>
    </div>
@endpush

@if(!$onlyDrawer)
    {!! $content ?? '' !!}
@endif

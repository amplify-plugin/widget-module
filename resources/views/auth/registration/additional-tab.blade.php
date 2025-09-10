@push('tab-title')
    <li class="nav-item">
        <a class="nav-link @if($active) active @endif"
           href="#"
           id="{{$slugTitle}}-tab"
           data-toggle="tab"
           data-target="#{{$slugTitle}}"
           type="button"
           role="tab"
           aria-controls="{{$slugTitle}}"
           aria-selected="true"
        >
            {{ $title ?? '' }}
        </a>
    </li>
@endpush

<div {!! $htmlAttributes !!}>
    {!! $slot !!}
</div>

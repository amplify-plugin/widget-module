@push('product-information-tab')
    <li class="nav-item">
        <a class="nav-link"
           href="#"
           id="{{$slugTitle}}-tab"
           data-toggle="tab"
           data-target="#{{$slugTitle}}"
           type="button"
           role="tab"
           aria-controls="{{$slugTitle}}"
           aria-selected="true"
        >
            {{ $displayableTitle() }}
        </a>
    </li>
@endpush

<div {!! $htmlAttributes !!}>
    {!! $slot !!}
</div>

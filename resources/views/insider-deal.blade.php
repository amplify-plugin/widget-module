@props(['showOnlyVisitor'])

@php
    $show = $showOnlyVisitor == 'false' ? true : auth('customer')->guest();
@endphp

@if ($show)
    <div {!! $htmlAttributes !!}>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
            <div>
                <h2 class="fw-600 text-white">{{ $title }}</h2>
                <p class="fw-600 m-0">{{ $description }}</p>
            </div>
            <div class="flex align-items-center">
                <a class="btn btn-secondary" href="{{ $secondaryBtnUrl }}">{{ $secondaryBtnText }}</a>
                <a class="btn btn-primary" href="{{ $primaryBtnUrl }}">{{ $primaryBtnText }}</a>
            </div>
        </div>
    </div>
@endif

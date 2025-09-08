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
            {{ $displayableTitle ?? '' }}
        </a>
    </li>
@endpush

<div {!! $htmlAttributes !!}>
    <div class="d-flex justify-content-between mb-3">
        <h5 class="subtitle font-weight-bold">{{ $displayableSubTitle }}</h5>
        <span>
        <span class="font-weight-bold text-danger">*</span>
            {{ trans('Indicates a Required Field') }}
        </span>
    </div>

    <form method="post" id="registration-form-request-account"
          action="{{ route('frontend.registration.request-account', ['tab' => 'request-account']) }}">
        @csrf
        <x-honeypot />
            @includeWhen($withCustomerVerification, 'widget::auth.registration.request-account.stepped')
            @includeWhen(!$withCustomerVerification, 'widget::auth.registration.request-account.single')
    </form>
</div>

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
    <form method="post" id="registration-form-request-account"
          autocomplete="off"
          action="{{ route('frontend.registration.request-account') }}">
        @csrf
        {!! \Form::hidden('tab', 'request-account') !!}
        <input type="email" name="_email" class="form-control" style="opacity: 0; height: 0 !important;"/>
        <input type="password" name="_password" class="form-control" style="opacity: 0; height: 0 !important;"/>
        <x-honeypot/>
        <div class="d-flex justify-content-between mb-3">
            <h4 class="subtitle">{!! $displayableSubTitle !!}</h4>
            <span>
                <span class="font-weight-bold text-danger">*</span>
                {{ trans('Indicates a Required Field') }}
            </span>
        </div>

        @includeWhen($withCustomerVerification, 'widget::auth.registration.request-account.stepped')
        @includeWhen(!$withCustomerVerification, 'widget::auth.registration.request-account.single')
    </form>
</div>

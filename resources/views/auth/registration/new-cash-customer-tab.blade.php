@push('tab-title')
    <li class="nav-item">
        <a class="nav-link @if ($active) active @endif" href="#" id="{{ $slugTitle }}-tab"
           data-toggle="tab" data-target="#{{ $slugTitle }}" type="button" role="tab"
           aria-controls="{{ $slugTitle }}" aria-selected="true">
            {{ $displayableTitle ?? '' }}
        </a>
    </li>
@endpush

<div {!! $htmlAttributes !!}>
    <form method="post" id="registration-form-cash-customer"
          autocomplete="off"
          action="{{ route('frontend.registration.create-cash-customer') }}">
        @csrf
        {!! \Form::hidden('tab', 'cash-customer') !!}
        <x-honeypot/>
        <div class="d-flex justify-content-between mb-3">
            <h4 class="subtitle">{{ $displayableSubTitle }}</h4>
            <span>
                <span class="font-weight-bold text-danger">*</span>
                {{ trans('Indicates a Required Field') }}
            </span>
        </div>
        <div class="row">
            <div class="@if ($askIndustryClassification) col-md-6 @else col-md-12 @endif">
                {!! \Form::rText('company_name', 'Company', null, true, ['placeholder' => 'Name']) !!}
            </div>
            @if ($askIndustryClassification)
                <div class=" col-md-6">
                    {!! \Form::rSelect('industry_classification_id', 'Industry', $industries, null) !!}
                </div>
            @endif
            <div class="col-md-6">
                {!! \Form::rText('name', 'Name', null, true, ['placeholder' => 'Name']) !!}
            </div>
            <div class=" col-md-6">
                {!! \Form::rSelect('contact_account_title', 'Account Title', $accountTitles, null, false, [
                    'placeholder' => 'Select Account Title/Department',
                ]) !!}
            </div>
            <div class="col-md-6">
                {!! \Form::rEmail('email', 'Email', null, true, ['placeholder' => 'Email Address']) !!}
                <small class="text-muted small">(Your E-Mail Address will serve as your User ID when you Login)</small>
            </div>
            <div class="col-md-6">
                {!! \Form::rPassword('password', 'Password', true, [
                    'placeholder' => 'Enter Password',
                    'title' =>
                        "Minimum 8 character required with at least one Number, one lower case, one upper case letter and special characters(#?!@$%^&amp;*).",
                ]) !!}
            </div>
            @if ($confirmPassword)
                <div class="col-md-6">
                    {!! \Form::rPassword('password_confirmation', 'Retype Password', true, ['placeholder' => 'Enter Password']) !!}
                </div>
            @endif
            <div class="col-md-6">
                {!! \Form::rTel('phone_number', 'Phone Number', null, true, [
    'placeholder' => 'Enter Phone Number', 'pattern' => '^[0-9+\-\(\)\.\s]+$',
    'title' => 'The field may only contain digits and phone symbols (+,-,(,),. & space).'
    ]) !!}
            </div>
        </div>
        <fieldset>
            <legend class="lead">Default/Billing Address</legend>
            <div class="row">
                <div class="col-md-12">
                    {!! \Form::rText('address_name', 'Address Name', null, true) !!}
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! \Form::label('address_1', 'Street Address', true) !!}
                        <input name="address_1" value="{{ old('address.0.address_1') }}" placeholder="Address Line 1"
                               class="form-control my-1" type="text" id="address_1" required="required">
                        <span id="address_1-error" class="d-block invalid-feedback">
                            @error('address_1') {{ $message }} @enderror
                        </span>
                        <input name="address_2" value="{{ old('address.0.address_2') }}" placeholder="Address Line 2"
                               class="form-control my-1" type="text" id="address_2">
                        <span id="address_2-error" class="d-block invalid-feedback">
                            @error('address_2') {{ $message }} @enderror
                        </span>
                        <input name="address_3" value="{{ old('address.0.address_3') }}" placeholder="Address Line 3"
                               class="form-control my-1" type="text" id="address_3">
                        <span id="address_3-error" class="d-block invalid-feedback">
                            @error('address_3') {{ $message }} @enderror
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    {!! \Form::rText('city', 'City', null, true) !!}
                </div>
                <div class="col-md-6">
                    {!! \Form::rSelect('country_code', 'Country', $countries, null, true, [
                        'onchange' => 'updateState(this.value,\'' . old('state', 'null') . '\');',
                        'placeholder' => 'Select a country',
                    ]) !!}
                </div>
                <div class="col-md-6">
                    {!! \Form::rSelect('state', 'State', [], null, true) !!}
                </div>
                <div class="col-md-6">
                    {!! \Form::rText('zip_code', 'Zip/Postal Code', null, true) !!}
                </div>
            </div>
        </fieldset>
        <div class="row">
            {{ $slot }}
        </div>
        <div class="row">
            @if ($newsletterSubscription)
                <div class="col-md">
                    <div class="form-group">
                        <label for="newsletter"></label>
                        <div class="custom-control custom-checkbox">
                            <input class="form-control custom-control-input" id="customer_newsletter_checkbox_yes"
                                   name="newsletter" type="checkbox" @checked(old('newsletter') == 'yes') value="yes">
                            <label for="customer_newsletter_checkbox_yes" class="custom-control-label">
                                {!! $newsletterLabel ?? '' !!}
                            </label>
                        </div>
                        <span id="newsletter-error" class="d-block invalid-feedback">
                            @error('newsletter') {{ $message }} @enderror
                        </span>
                    </div>
                </div>
            @endif
            @if ($acceptTermsConfirmation)
                <div class="col-md">
                    <div class="form-group">
                        <label for="accept_term"></label>
                        <div class="custom-control custom-checkbox">
                            <input type="hidden" name="required[]" value="accept_term"/>
                            <input class="form-control custom-control-input" required
                                   id="customer_accept_term-checkbox-yes"
                                   name="accept_term" type="checkbox" @checked(old('accept_term') == 'yes') value="yes">
                            <label for="customer_accept_term-checkbox-yes" class="custom-control-label">
                                {!! $termsLabel ?? '' !!}
                            </label>
                        </div>
                        <span id="customer_accept_term-error" class="d-block invalid-feedback">
                            @error('accept_term') {{ $message }} @enderror
                        </span>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            @if ($captchaVerification)
                <div class="col-md-12">
                    <x-captcha :display="$captchaVerification" id="captcha-container-register" field-name="captcha"
                               :reload-captcha="$active"/>
                </div>
            @endif
        </div>
        <div class="d-flex justify-content-md-end justify-content-center">
            <button type="submit" class="btn btn-{{ $submitButtonColor }}">
                {{ $submitButtonLabel }}
            </button>
        </div>
    </form>
</div>

@pushonce('plugin-script')
    <script>
        function updateState(countryCode, selectState = 'null') {

            countryCode = countryCode === 'null' ? null : countryCode;

            selectState = selectState === 'null' ? null : selectState;

            const stateDropdown = $('select[name="state"]');

            if (!countryCode) {
                stateDropdown.empty();
                return;
            }

            $.get(`/get-states-by-country-code/${countryCode}`, {}, function (response) {
                stateDropdown.empty();
                stateDropdown.append(`<option value="">Select a State</option>`);
                $.each(response.states, function (index, state) {
                    let selected = (state.iso2 === selectState) ? 'selected' : '';
                    stateDropdown.append(
                        `<option value="${state.iso2}" ${selected}>${state.name}</option>`);
                });
            }).catch((err) => {
                ShowNotification('error', 'Address Form', err.response.data.message ??
                    'The given data is invalid.');
                console.error('Error fetching states:', err);
            });
        }

        document.addEventListener('DOMContentLoaded', () => updateState('{{ old('country_code', 'null') }}',
            '{{ old('state', 'null') }}'));
    </script>
@endpushonce

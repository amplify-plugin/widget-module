@pushonce('plugin-style')
    <link type="text/css" href="{{ asset('vendor/bs-stepper/css/bs-stepper.min.css') }}" rel="stylesheet"/>
@endpushonce
<div class="bs-stepper" id="customer-verification-steps">
    <div class="bs-stepper-header" role="tablist">
        <div class="step" data-target="#verification-part">
            <button type="button" class="step-trigger px-2 pt-0 pb-2" role="tab"
                    aria-controls="verification-part" id="verification-part-trigger">
                1.
                <span class="bs-stepper-label">{{ trans('Customer Verification') }}</span>
            </button>
        </div>
        <div class="line"></div>
        <div class="step" data-target="#information-part">
            <button type="button" class="step-trigger px-2 pt-0 pb-2" role="tab"
                    aria-controls="information-part" id="information-part-trigger">
                2.
                <span class="bs-stepper-label">
                    {{ trans('Contact Information') }}</span>
            </button>
        </div>
    </div>
    <div class="bs-stepper-content pb-0">
        <div id="verification-part" class="content" role="tabpanel"
             aria-labelledby="verification-part-trigger">
            <div class="row">
                <div class="px-2 col-md-6">
                    {!! \Form::rText('customer_street_address', trans('Street Address'), null, false, ['placeholder' => trans('Enter Street Address')]) !!}
                    {!! \Form::rText('customer_postal_code', trans('Postal/Zip Code'), null, false, ['placeholder' => trans('Enter Postal/Zip Code')]) !!}
                    <input type="hidden" name="required[]" value="search_account_number">
                    {!! \Form::rText('search_account_number', trans('Customer Number/Code'), null, false, ['placeholder' => trans('Enter Customer Number/Code'), 'autocomplete' => 'off']) !!}
                    <button class="btn btn-primary"
                            onclick="verifyCustomerInformation(event, this);"
                            type="button">
                        <i class="icon-search"></i> {{ trans('SEARCH') }}
                    </button>
                </div>
                <div class="px-2 col-md-6" id="customer-profile">
                    <div class="card mt-4 mt-sm-0">
                        <div class="card-body b-2">
                            <p class="border-bottom pb-2">{{ trans('Customer Profile') }}</p>
                            <div class="mb-2 row">
                                <div class="col-sm-4 text-sm-right">
                                    <strong>{{ trans('Company Name:') }}</strong>
                                </div>
                                <div class="col-sm-8 text-uppercase">
                                    <span id="CustomerName"></span>
                                    {!!\Form::hidden('customer_name') !!}
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-sm-4 text-sm-right">
                                    <strong>{{ trans('Street Address:') }}</strong>
                                </div>
                                <div class="col-sm-8 text-uppercase">
                                    <span id="CustomerAddress"></span>
                                    {!!\Form::hidden('customer_address') !!}
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-sm-4 text-sm-right">
                                    <strong>{{ trans('City:') }}</strong>
                                </div>
                                <div class="col-sm-8 text-uppercase">
                                    <span id="CustomerCity"></span>
                                    {!!\Form::hidden('customer_city') !!}
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-sm-4 text-sm-right">
                                    <strong>{{ trans('State:') }}</strong>
                                </div>
                                <div class="col-sm-8 text-uppercase">
                                    <span id="CustomerState"></span>
                                    {!!\Form::hidden('customer_state') !!}
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-sm-4 text-sm-right">
                                    <strong>{{ trans('Postal Code:') }}</strong>
                                </div>
                                <div class="col-sm-8 text-uppercase">
                                    <span id="CustomerZipCode"></span>
                                    {!!\Form::hidden('customer_zip_code') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 text-sm-right">
                                    <strong>{{ trans('Country:') }}</strong>
                                </div>
                                <div class="col-sm-8 text-uppercase">
                                    <span id="CustomerCountry"></span>
                                    {!!\Form::hidden('customer_country') !!}
                                    {!!\Form::hidden('customer_account_number') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center justify-content-sm-end mt-3">
                        <button class="btn btn-success mx-0" type="button" onclick="stepper.next();">
                            {{ trans('I Confirm') }} <i class="icon-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="information-part" class="content" role="tabpanel"
             aria-labelledby="information-part-trigger">
            <div class="row">
                <div class="col-md-6">
                    {!! \Form::rText('contact_name', trans('Full Name'), null, true, ['placeholder' => 'Enter Contact Full Name']) !!}
                </div>
                <div class="col-md-6">
                    {!! \Form::rSelect('contact_account_title', trans('Account Title'), $accountTitles, null, true, ['placeholder' => 'Select Account Title/Department']) !!}
                </div>
                <div class="col-md-6">
                    {!! \Form::rTel('contact_phone_number', trans('Phone'), null, true, [
    'placeholder' => 'Enter Contact Phone Number', 'pattern' => '^[0-9+\-\(\)\.\s]+$',
    'title' => 'The field may only contain digits and phone symbols (+,-,(,),. & space).'
    ]) !!}
                </div>
                <div class="col-md-6">
                    {!! \Form::rEmail('contact_email', trans("Email Address"), null, true, ['placeholder' => 'Email Address']) !!}
                    <small class="text-muted small">
                        ({{ trans('Your E-Mail Address will serve as your User ID when you Login') }})
                    </small>
                </div>
                <div class="col-md-6">
                    {!! \Form::rPassword('contact_password', trans('Password'), true, [
                    'placeholder' => trans('Enter Password'),
                     'title'=>"Minimum {$minPasswordLength()} character required with at least one Number, one lower case, one upper case letter and special characters(#?!@$%^&amp;*).",
                    "min" => $minPasswordLength(),
                     "max" => '255',
                    "minlength" => $minPasswordLength(),
                     "maxlength" => '255'
                    ]) !!}
                </div>
                <div class="col-md-6">
                    {!! \Form::rPassword('contact_password_confirmation', trans('Retype Password'), true, [
                    'placeholder' => trans('Enter Password'),
                    'title'=>"Minimum {$minPasswordLength()} character required with at least one Number, one lower case, one upper case letter and special characters(#?!@$%^&amp;*).",
                    "min" => $minPasswordLength(),
                     "max" => '255',
                    "minlength" => $minPasswordLength(),
                     "maxlength" => '255'

                    ]) !!}
                </div>
            </div>
            <div class="row">
                {{ $slot  }}
            </div>
            <div class="row">
                @if($newsletterSubscription)
                    <div class="col-md">
                        <div class="form-group">
                            <label for="newsletter"></label>
                            <div class="custom-control custom-checkbox">
                                <input class="form-control custom-control-input"
                                       id="newsletter-checkbox-yes"
                                       name="newsletter"
                                       type="checkbox"
                                       @checked(old('newsletter') =='yes')
                                       value="yes">
                                <label for="newsletter-checkbox-yes"
                                       class="custom-control-label">
                                    {!! $newsletterLabel ?? '' !!}
                                </label>
                            </div>
                            <span id="newsletter-error" class="d-block invalid-feedback"></span>
                        </div>
                    </div>
                @endif
                @if($acceptTermsConfirmation)
                    <div class="col-md">
                        <div class="form-group">
                            <label for="accept_term"></label>
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="required[]" value="contact_accept_term"/>
                                <input class="form-control custom-control-input"
                                       id="accept_term-checkbox-yes"
                                       name="accept_term"
                                       type="checkbox"
                                       required
                                       @checked(old('accept_term') =='yes')
                                       value="yes">
                                <label for="accept_term-checkbox-yes"
                                       class="custom-control-label">
                                    {!! $termsLabel ?? '' !!}
                                </label>
                            </div>
                            <span id="accept_term-error" class="d-block invalid-feedback"></span>
                        </div>
                    </div>
                @endif
            </div>
            @if($captchaVerification)
                <div class="row">
                    <div class="col-md-12">
                        <x-captcha :display="$captchaVerification" id="captcha-container-account"
                                   field-name="contact_captcha" :reload-captcha="$active"/>
                    </div>
                </div>
            @endif
            <div class="d-flex justify-content-between">
                <button class="btn btn-warning mx-0"
                        type="button"
                        onclick="stepper.previous();">
                    <i class="icon-arrow-left"></i> Previous
                </button>
                <button type="submit" form="registration-form-request-account"
                        class="btn btn-{{ $submitButtonColor }}">
                    {{ $submitButtonLabel }}
                </button>
            </div>
        </div>
    </div>
</div>
@pushonce('plugin-script')
    <script src="{{ asset('vendor/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script>
        function verifyCustomerInformation(event, element) {
            event.preventDefault();

            $('#customer-profile').hide();

            let street_address = $('#customer_street_address').val();
            let zip_code = $('#customer_postal_code').val();
            let customer_number = $('#search_account_number').val();

            let payload = {
                street_address: street_address ?? '',
                zip_code: zip_code ?? '',
                customer_number: customer_number ?? ''
            }

            if (payload.street_address === '' && payload.zip_code === '' && payload.customer_number === '') {
                Amplify.alert('Customer Number, Street address, Postal code any of them is must', 'Registration');
                return false;
            }

            if (typeof validateCustomerSearchPayload === 'function') {
                if (!validateCustomerSearchPayload(payload)) {
                    return;
                }
            } else {
                console.warn("`validateCustomerSearchPayload(payload)` function not defined. using system default");
            }

            if (payload.customer_number === '') {
                delete payload.customer_number;
            }

            swal.fire({
                title: 'Registration',
                icon: 'info',
                backdrop: true,
                showCancelButton: false,
                text: `Searching for valid customer information...`,
                confirmButtonText: 'Okay',
                customClass: {
                    confirmButton: 'btn btn-outline-secondary'
                },
                willOpen: () => document.querySelector('.swal2-actions').style.justifyContent = 'center',
                didOpen: () => {
                    $.ajax('{{ route('frontend.contact-validation') }}', {
                        beforeSend: () => window.swal.showLoading(),
                        method: 'POST',
                        data: payload,
                        success: function (response) {
                            if (!response.status) {
                                Amplify.alert(response.message, 'Registration');
                                return;
                            }

                            window.swal.close();

                            $('#customer-profile').show();

                            for (const [id, value] of Object.entries(response.data)) {
                                if (value != null) {
                                    id.includes('_')
                                        ? $(`input:hidden[name='${id}']`).val(value)
                                        : $(`#${id}`).text(value);
                                }
                            }
                        },
                        error: function (xhr) {
                            window.swal.hideLoading();
                            let response = JSON.parse(xhr.responseText);
                            window.swal.showValidationMessage(response?.message ?? xhr.responseText);
                        }
                    });
                },
                allowOutsideClick: () => !window.swal.isLoading()
            });
        }

        var stepper = new Stepper(document.querySelector('#customer-verification-steps'));

        document.addEventListener('DOMContentLoaded', function () {
            $('#customer-profile').hide();
        });
    </script>
@endpushonce

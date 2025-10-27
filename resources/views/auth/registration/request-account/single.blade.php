<div class="row">
    <div class="col-md-12">
        {!! \Form::rText('contact_name', 'Name', null, true, ['placeholder' => 'Name']) !!}
    </div>
    <div class="col-md-6">
        <input type="hidden" name="required[]" value="customer_account_number">
        {!! \Form::rText('customer_account_number', 'Account Number', null, true, ['placeholder' => 'Account Number']) !!}
    </div>
    <div class="col-md-6">
        {!! \Form::rEmail('contact_email', 'Email', null, true, ['placeholder' => 'Email Address']) !!}
        <small class="text-muted small">
            ({{ trans('Your E-Mail Address will serve as your User ID when you Login') }})
        </small>
    </div>
    <div class="col-md-6">
        {!! \Form::rPassword('contact_password', 'Password', true, ['placeholder' => 'Enter Password', 'title'=>"Minimum 8 character required with at least one Number, one lower case, one upper case letter and special characters(#?!@$%^&amp;*)."]) !!}
    </div>
    <div class="col-md-6">
        {!! \Form::rPassword('contact_password_confirmation', 'Retype Password', true, ['placeholder' => 'Enter Password']) !!}
    </div>
</div>
<div class="row">
    {{ $slot  }}
</div>
<div class="row">
    <div class="col-md-6">
        @if($newsletterSubscription)
            {!! \Form::rCheckbox('contact_newsletter','', ['yes' => config('app.name').' newsletter subscription.']) !!}
        @endif
    </div>
    <div class="col-md-6">
        @if($acceptTermsConfirmation)
            <input type="hidden" name="required[]" value="contact_accept_term"/>
            {!! \Form::rCheckbox('contact_accept_term','', ['yes' => 'I accept the '.config('app.name').' Terms and Conditions.'], [], true) !!}
        @endif
    </div>
    @if($captchaVerification)
        <div class="col-md-12">
            <x-captcha :display="$captchaVerification" id="captcha-container-account"
                       field-name="contact_captcha" :reload-captcha="$active" />
        </div>
    @endif
</div>
<div class="d-flex justify-content-md-end justify-content-center">
    <button type="submit" class="btn btn-{{ $submitButtonColor }}">
        {{ $submitButtonLabel }}
    </button>
</div>

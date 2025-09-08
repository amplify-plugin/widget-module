@pushonce('footer-script')
    <script src="{{ asset("packages/select2/dist/js/select2.min.js") }}"></script>
@endpushonce
@php

    push_js("
        function checkPassword() {
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();
            const submitButton = $('button[type=\"submit\"]');
            const passwordMinLength = $minPassLength

            if (!newPassword && !confirmPassword) {
                // If both fields are empty, remove validation errors and enable submit button
                $('#new_password, #confirm_password').removeClass('is-invalid');
                $('#new_password').siblings('.text-danger').remove();
                $('#confirm_password').siblings('.text-danger').remove();
                submitButton.prop('disabled', false); // Enable submit button
                return;
            }

            if (newPassword.length < passwordMinLength) {
                $('#new_password').addClass('is-invalid');
                if (!$('#new_password').siblings('.text-danger').length) {
                    $('#new_password').after('<p class=\"text-danger my-2\">Password must be at least $minPassLength characters long</p>');
                }
                submitButton.prop('disabled', true); // Disable submit button
            } else {
                $('#new_password').removeClass('is-invalid');
                $('#new_password').siblings('.text-danger').remove();

                if (newPassword !== confirmPassword) {
                    $('#confirm_password').addClass('is-invalid');
                    if (!$('#confirm_password').siblings('.text-danger').length) {
                        $('#confirm_password').after('<p class=\"text-danger my-2\">Passwords do not match</p>');
                    }
                    submitButton.prop('disabled', true); // Disable submit button
                } else {
                    $('#confirm_password').removeClass('is-invalid');
                    $('#confirm_password').siblings('.text-danger').remove();
                    submitButton.prop('disabled', false); // Enable submit button when both conditions are met
                }
            }
        }

        $(document).ready(function() {
            user_profile = $('#profile').validate({
                rules: {
                    confirm_password: {
                        equalTo: '#new_password'
                    }
                }
            });

            $('.roles').select2({
                placeholder: 'Select roles',
                allowClear: true
            });

            // Trigger validation when the user types in either password field
            $('#new_password, #confirm_password').on('keyup change', function() {
                checkPassword();
            });
        });
    ", 'footer-script');
@endphp

<div {!! $htmlAttributes !!}>
<form method="post" autocomplete="off"
      class="@if(isset($errors) && !empty($errors->isEmpty())) was-validated @endif"
      action="{{ route('frontend.profile.update', $account->id) }}"
      id="profile">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="customer" aria-label="customer"> {{ __('Customer') }} </label>
                        <input class="form-control"
                               id="customer"
                               type="text" disabled value="{{ $customerName() }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name" aria-label="name"> {{ __('Name') }} </label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                               name="name" placeholder="Enter name" value="{{ $account->name }}" required>
                        @error('name')
                        <p class="text-danger my-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-email" aria-label="account-email">{{ __('E-mail Address') }}</label>
                        <input class="form-control  @error('email') is-invalid @enderror" type="email"
                               id="account-email" value="{{ $account->email }}"
                               disabled>
                        @error('email')
                        <p class="text-danger my-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone"  aria-label="phone">{{ __('Phone Number') }}</label>
                        <input class="form-control  @error('phone') is-invalid @enderror" type="text" name="phone"
                               id="phone"
                               placeholder="Enter phone" value="{{ $account->phone }}">
                        @error('phone')
                        <p class="text-danger my-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="new_password" aria-label="new_password">{{ __('New Password') }}</label>
                        <input class="form-control  @error('new_password') is-invalid @enderror" name="new_password"
                               type="password"
                               onchange="checkPassword();"
                               id="new_password"
                               minlength="{{ $minPassLength }}" maxlength="255"
                               placeholder="Enter new password">
                        @error('new_password')
                        <p class="text-danger my-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="confirm_password" aria-label="confirm_password">{{ __('Confirm Password') }}</label>
                        <input class="form-control @error('confirm_password') is-invalid @enderror"
                               name="confirm_password" type="password" id="confirm_password"
                               placeholder="Enter confirm password">
                        @error('confirm_password')
                        <p class="text-danger my-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if (customer(true)->can('profile.change-start-page'))
                    <div class="@if(config('amplify.basic.enable_multi_customer_manage', false)) col-md-6 @else col-md-12 @endif">
                        <div class="form-group">
                            <label>Preferred Page After Login</label>
                            <select name="redirect_route" style="width: 100%" class="roles form-control @error('redirect_route') is-invalid @enderror">
                                @foreach($urls as $url => $label)
                                    <option
                                        value="{{ $url }}"
                                        @selected($url == old('redirect_route', $account->redirect_route ?? ''))>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('redirect_route')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="col-md-6">
                    <div class="form-check d-flex align-items-center h-100 p-0 mt-1">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input @error('subscribe') is-invalid @enderror"
                                   type="checkbox"
                                   name="subscribe"
                                   id="subscribe"
                                   name="subscribe"
                                {{ $account->subscribed ? 'checked' : '' }}>
                            <label class="custom-control-label"  aria-label="custom-control-label"
                                   for="subscribe">{{ __('Subscribe me to Newsletter') }}</label>
                            @error('subscribe')
                            <p class="text-danger my-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <button class="btn btn-primary margin-right-none"
                                type="submit">
                            <i class="pe-7s-diskette"></i>
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
<script>
    var user_profile = null;
</script>

<style>
    .select2-container .select2-selection--single .select2-selection__clear {
        float: right;
        margin-right: 1.6rem;
        margin-top: 0.7rem;
        height: 1rem;
        width: 1rem;
        line-height: 1rem;
        padding-left: 0.65%;
        padding-top: 0.1%;
    }

    @media (min-width: 991px) {
        .select2-container .select2-selection--single .select2-selection__clear {
            padding-left: 1.2%;
        }
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        display: unset;
    }
</style>

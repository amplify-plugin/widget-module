{{--<style>--}}
{{--    .select2-container .select2-selection--single .select2-selection__clear {--}}
{{--        float: right;--}}
{{--        margin-right: 1.6rem;--}}
{{--        margin-top: 0.7rem;--}}
{{--        height: 1rem;--}}
{{--        width: 1rem;--}}
{{--        line-height: 1rem;--}}
{{--        padding-left: 0.65%;--}}
{{--        padding-top: 0.1%;--}}
{{--    }--}}

{{--    @media (min-width: 991px) {--}}
{{--        .select2-container .select2-selection--single .select2-selection__clear {--}}
{{--            padding-left: 1.2%;--}}
{{--        }--}}
{{--    }--}}

{{--    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {--}}
{{--        display: unset;--}}
{{--    }--}}
{{--</style>--}}
<div {!! $htmlAttributes !!}>
    <form method="post" autocomplete="off"
          action="{{ route('frontend.profile.update', $account->id) }}"
          id="profile">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! \Form::rText('customer', __('Customer'),  $customerName(), false, ['disabled' => true]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! \Form::rText('default_warehouse',  __('Default Warehouse'),   $defaultWarehouse, false, ['disabled' => true]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! \Form::rText('name',  __('Name'), $account->name, true) !!}
                    </div>

                    <div class="col-md-6">
                        {!! \Form::rSelect('account_title_id',  __('Account Title'), $accountTitles, $account->account_title_id) !!}
                    </div>

                    <div class="col-md-6">
                        {!! \Form::rText('email',  __('E-mail Address'), $account->email, true, ['disabled' => true]) !!}
                    </div>

                    <div class="col-md-6">
                        {!! \Form::rText('phone',  __('Phone Number'), $account->phone, true) !!}
                    </div>

                    <div class="col-md-6">
                        {!! \Form::rPassword('new_password',  __('New Password'), false,
['onchange' => 'checkPassword();', 'minlength' => $minPassLength, 'maxlength' => '255', 'placeholder' => 'Enter new password']) !!}
                    </div>

                    <div class="col-md-6">
                        {!! \Form::rPassword('confirm_password',  __('Confirm Password'), false,
['onchange' => 'checkPassword();', 'minlength' => $minPassLength, 'maxlength' => '255', 'placeholder' => 'Enter confirm password']) !!}
                    </div>

                    @if (customer(true)->can('profile.change-start-page'))
                        <div
                            class="@if(config('amplify.basic.enable_multi_customer_manage', false)) col-md-6 @else col-md-12 @endif">
                            <div class="form-group">
                                <label>Preferred Page After Login</label>
                                <select name="redirect_route" style="width: 100%"
                                        class="roles form-control @error('redirect_route') is-invalid @enderror">
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
                                <label class="custom-control-label" aria-label="custom-control-label"
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
                            <button class="btn btn-success margin-right-none"
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

@pushonce('footer-script')
    <script src="{{ asset("packages/select2/dist/js/select2.min.js") }}"></script>
    <script>
        var user_profile = null;

        function checkPassword() {
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();
            const submitButton = $('button[type="submit"]');
            const passwordMinLength = {{$minPassLength}};

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
                    $('#new_password').after('<p class="text-danger my-2">Password must be at least {{$minPassLength}} characters long</p>');
                }
                submitButton.prop('disabled', true); // Disable submit button
            } else {
                $('#new_password').removeClass('is-invalid');
                $('#new_password').siblings('.text-danger').remove();

                if (newPassword !== confirmPassword) {
                    $('#confirm_password').addClass('is-invalid');
                    if (!$('#confirm_password').siblings('.text-danger').length) {
                        $('#confirm_password').after('<p class="text-danger my-2">Passwords do not match</p>');
                    }
                    submitButton.prop('disabled', true); // Disable submit button
                } else {
                    $('#confirm_password').removeClass('is-invalid');
                    $('#confirm_password').siblings('.text-danger').remove();
                    submitButton.prop('disabled', false); // Enable submit button when both conditions are met
                }
            }
        }

        $(document).ready(function () {
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
            $('#new_password, #confirm_password').on('keyup change', function () {
                checkPassword();
            });
        });
    </script>
@endpushonce

<div {!! $htmlAttributes !!}>
    <form id="force-password-reset-box" class="login-box" method="post"
          action="{{route('frontend.force-reset-password')}}">
        @csrf
        @honeypot
        <h4 class="login-box-title">{{ $displayableTitle() }}</h4>
        {!! $subtitle ?? '' !!}
        <div class="form-group">
            <label for="password" class="sr-only">{{ __('New Password') }}</label>
            <div class="input-group {{$errors->has('password') ? 'is-invalid' : ''}}">
                <input id="password" tabindex="1" class="form-control" type="password" name="password"
                       placeholder="{{ __('Enter New Password') }}"
                       required>
                <span class="input-group-addon">
                    <i class="icon-lock"></i>
                </span>
            </div>
            <span class="invalid-feedback d-block">
                    @error('password') <strong>{{ $message }}</strong> @enderror
            </span>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="sr-only">{{ __('Retype New Password') }}</label>
            <div class="input-group {{$errors->has('password_confirmation') ? 'is-invalid' : ''}}">
                <input id="password_confirmation" tabindex="2" class="form-control" type="password" name="password_confirmation"
                       placeholder="{{ __('Enter Retype New Password') }}" required>
                <span class="input-group-addon">
                            <i class="icon-lock"></i>
                        </span>
            </div>
            <span class="invalid-feedback d-block">
                    @error('password_confirmation') <strong>{{ $message }}</strong> @enderror
            </span>
        </div>

        <div class="text-center text-sm-right">
            <button class="btn btn-primary margin-bottom-none" type="submit">
                <i class="pe-7s-unlock"></i>{{ __('Update Password') }}
            </button>
        </div>
    </form>
    {!!  $slot ?? '' !!}
</div>
@pushonce('footer-script')
    <script>
        $(function () {
            $('#force-password-reset-box').validate({
                rules: {
                    password_confirmation: {
                        required: true,
                        equalTo: '#password',
                        minlength: {{ $minPassLength }},
                    },
                    password: {
                        required: true,
                        minlength: {{ $minPassLength }},
                    },
                },
                messages: {
                    password: {
                        required: 'The new password field is required.',
                        minlength: 'The new password must be at least {{ $minPassLength }} characters.'
                    },
                    password_confirmation: {
                        required: 'The retype new password field is required.',
                        minlength: 'The retype new password must be at least {{ $minPassLength }} characters.'
                    }
                }
            });
        });
    </script>
@endpushonce


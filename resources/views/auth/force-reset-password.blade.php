<div {!! $htmlAttributes !!}>
    <form id="force-password-reset-box" class="login-box" method="post"
          action="{{route('frontend.force-reset-password-attempt')}}">
        @csrf
        @honeypot
        <h4 class="margin-bottom-1x">{{ $displayableTitle() }}</h4>
        <div class="form-group">
            <div class="input-group {{$errors->has('password') ? 'is-invalid' : ''}}">
                <input id="password" class="form-control" type="password" name="password" placeholder="{{ __('Password') }}"
                       required>
                <span class="input-group-addon">
                    <i class="icon-lock"></i>
                </span>
            </div>
            @if ($errors->has('password'))
                <span class="invalid-feedback d-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <div class="input-group {{$errors->has('password_confirmation') ? 'is-invalid' : ''}}">
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation"
                       placeholder="{{ __('Confirm Password') }}" required>
                <span class="input-group-addon">
                            <i class="icon-lock"></i>
                        </span>
            </div>
            @if ($errors->has('password_confirmation'))
                <span class="invalid-feedback d-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>

        <div class="text-center text-sm-right">
            <button class="btn btn-primary margin-bottom-none" type="submit">
                <i class="pe-7s-unlock"></i>{{ __('Update Password') }}
            </button>
        </div>
    </form>
</div>
@pushOnce('footer-script')
    <script>
        $(function() {
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
            });
        });
    </script>
@endPushOnce


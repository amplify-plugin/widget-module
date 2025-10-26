<div {!! $htmlAttributes !!}>
    <form method="post" action="{{route('frontend.login')}}" id="login-box">
        <input type="hidden" name="previous_url"
               value="{{ !$referrer || strpos($referrer, config('app.url')) !== 0 ? '' : url()->previous() }}">
        @csrf

        @if($honeyPotProtection)
            <x-honeypot/>
        @endif

        <h4 class="padding-bottom-1x login-box-title">{{ $displayableTitle() }}</h4>

        {!! $subtitle ?? '' !!}

        <x-auth.login.input-login/>

        <x-auth.login.input-password toggle-password="{{ $togglePassword }}"/>

        <x-auth.login.input-remember-forgot-password/>

        <div class="text-center text-sm-right">
            <button class="btn btn-primary margin-bottom-none" type="submit" id="login-submit-btn">
                <i class="icon-unlock font-weight-bold"></i> {{ $loginButtonTitle() }}
            </button>
        </div>

        @if($displayRegisterLink())
            <p class="mt-3 text-sm-center text-left border-top pt-3">
                <a href="{{ route('frontend.registration') }}" class="register-link-text">
                    {!!  $registerTagLabel() !!}
                </a>
            </p>
        @endif
    </form>
    {!! $slot ?? '' !!}
</div>
@pushOnce('footer-script')
    <script>
        $(function () {
            var $form = $('#login-box');
            var $btn = $('#login-submit-btn');
            var originalHtml = $btn.html();

            // Eye toggle
            $form.on('click', '.toggle-password', function () {
                var $password = $('#login-password');
                var $icon = $(this).find('i');
                if ($password.attr('type') === 'password') {
                    $password.attr('type', 'text');
                    $icon.removeClass('pe-7s-look').addClass('pe-7s-close');
                } else {
                    $password.attr('type', 'password');
                    $icon.removeClass('pe-7s-close').addClass('pe-7s-look');
                }
            });

            $form.on('input', 'input', function () {
                $(this).closest('.form-group').find('.invalid-feedback').text('');
            });

            // Loader
            function disableBtn() {
                $btn.prop('disabled', true)
                    .addClass('opacity-75 cursor-not-allowed')
                    .data('original-html', originalHtml)
                    .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __("Signing in…") }}');
            }

            function enableBtn() {
                $btn.prop('disabled', false)
                    .removeClass('opacity-75 cursor-not-allowed')
                    .html($btn.data('original-html') || originalHtml);
            }

            // Validate
            $form.validate({
                rules: {
                    email: {required: true, email: true},
                    password: {required: true, minlength: {{ $minPassLen }}}
                },
                messages: {
                    email: {required: "{{ __('Email is required') }}", email: "{{ __('Enter a valid email') }}"},
                    password: {
                        required: "{{ __('Password is required') }}",
                        minlength: "{{ __('Password must be at least :n characters.', ['n' => $minPassLen]) }}"
                    }
                },
                highlight: function (el) {
                    $(el).removeClass('is-valid').addClass('is-invalid');
                },
                unhighlight: function (el) {
                    $(el).removeClass('is-invalid').addClass('is-valid');
                },
                errorPlacement: function (error, element) {
                    // Put error message in the <span.invalid-feedback>
                    element.closest('.form-group').find('.invalid-feedback').html(error);
                },
                submitHandler: function (form) {
                    disableBtn();
                    form.submit();
                }
            });

            // Enable/disable submit button
            $form.on('keyup change', 'input', function () {
                $btn.prop('disabled', !$form.valid());
            }).trigger('keyup');

            $(document).ajaxError(function () {
                enableBtn();
            });
        });
    </script>
@endPushOnce




<div {!! $htmlAttributes !!}>
    <form id="forgot-password-form" class="login-box">
        <h4 class="padding-bottom-1x login-box-title">{{ $displayableTitle() }}</h4>
        {!! $subtitle ?? '' !!}
        <div class="form-group">
            <div class="input-group">
                <input class="form-control" type="email" id="email" placeholder="Email" required />
                <span class="input-group-addon">
                <i class="icon-mail"></i>
            </span>
            </div>
            <span class="invalid-feedback d-block" id="email-error"></span>
        </div>
        <div class="d-flex justify-content-center justify-content-sm-end">
            <button class="btn btn-primary margin-bottom-none" id="submit-btn" type="submit">
                <span id="submit-text"><i class="icon-location font-weight-bold mr-2"></i>{{ $submitButtonTitle() }}</span>
            </button>
        </div>
    </form>
    {!!  $slot ?? '' !!}
</div>
@pushonce("html-default")
    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Password Reset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="otp-form" class="login-box">
                        <h4>Enter The OTP</h4>
                        <p class="margin-bottom-1x">We have sent the OTP to your e-mail.</p>
                        <div style="margin-top: 0.5rem">
                            <div class="input-group">
                                <input class="form-control" type="text" id="otp" placeholder="OTP" required />
                                <span class="input-group-addon">
                                <i class="icon-lock"></i>
                            </span>
                            </div>
                            <span class="invalid-feedback d-block" id="otp-error"></span>
                        </div>

                        <span class="invalid-feedback d-block" id="otp-resend" style="padding-left: 0; color: green"></span>
                        <span id="resend-link" class="resend-otp" style="font-size: 80%">Resend OTP ?</span>

                        <div class="text-center text-sm-right">
                            <button class="btn btn-primary margin-bottom-none" id="otp-submit-btn" type="submit">
                                <span id="otp-submit-text"><i class="icon-location font-weight-bold mr-2"></i>{{ $submitButtonTitle() }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Password Reset</h5>
                </div>
                <div class="modal-body">
                    <form id="password-form" class="login-box">
                        <h4 class="margin-bottom-1x">Enter Your New Password</h4>

                        <div style="margin-bottom: 0.5rem">
                            <div class="input-group">
                                <input
                                        class="form-control"
                                        type="password"
                                        id="password"
                                        placeholder="Password"
                                        minlength="6"
                                        required
                                />
                                <span class="input-group-addon">
                                <i class="icon-lock"></i>
                            </span>
                            </div>
                            <span class="invalid-feedback d-block" id="password-error"></span>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input
                                        class="form-control"
                                        type="password"
                                        id="confirmPassword"
                                        placeholder="Confirm Password"
                                        minlength="6"
                                        required
                                />
                                <span class="input-group-addon">
                                <i class="icon-lock"></i>
                            </span>
                            </div>
                            <span class="invalid-feedback d-block" id="confirm-password-error"></span>
                        </div>

                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <button class="btn btn-primary margin-bottom-none" id="password-submit-btn" type="submit">
                                <span id="password-submit-text"><i class="icon-location font-weight-bold mr-2"></i>{{ $submitButtonTitle() }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpushonce

@pushOnce("footer-script")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forgotForm = document.getElementById('forgot-password-form');
            const otpForm = document.getElementById('otp-form');
            const passwordForm = document.getElementById('password-form');
            const emailInput = document.getElementById('email');
            const otpInput = document.getElementById('otp');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const otpSubmitBtn = document.getElementById('otp-submit-btn');
            const otpSubmitText = document.getElementById('otp-submit-text');
            const passwordSubmitBtn = document.getElementById('password-submit-btn');
            const passwordSubmitText = document.getElementById('password-submit-text');
            const emailError = document.getElementById('email-error');
            const otpError = document.getElementById('otp-error');
            const passwordError = document.getElementById('password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');
            const otpResend = document.getElementById('otp-resend');
            const resendLink = document.getElementById('resend-link');

            let email = '';
            let otp = '';
            let codeSend = false;
            let resendTimer = null;

            function setLoading(button, textElement, loading) {
                button.disabled = loading;
                textElement.innerHTML = loading ? "<i class='icon-loader font-weight-bold'></i> Loading..." : "<i class='icon-location font-weight-bold mr-2'></i> {{ $submitButtonTitle() }}";
            }

            function clearErrors() {
                emailError.innerHTML = '';
                otpError.innerHTML = '';
                passwordError.innerHTML = '';
                confirmPasswordError.innerHTML = '';
            }

            function closeModal() {
                otpInput.value = '';
                passwordInput.value = '';
                confirmPasswordInput.value = '';
                codeSend = false;
                otpResend.innerHTML = '';
                resendLink.style.display = 'inline';
                clearErrors();

                if (resendTimer) {
                    clearInterval(resendTimer);
                    resendTimer = null;
                }
            }

            forgotForm.addEventListener('submit', function(e) {
                e.preventDefault();
                email = emailInput.value;
                clearErrors();
                setLoading(submitBtn, submitText, true);
                fetch('/password-reset-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({ email: email })
                })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({})); // safely parse JSON
                        setLoading(submitBtn, submitText, false);

                        // support both real HTTP status and a custom JSON status
                        const statusCode = response.status || data.status;

                        if (statusCode === 210) {
                            emailError.innerHTML = data.message || 'Please check your input.';
                            return; // stop here, don't show modal
                        }

                        // show modal only if OK and not 210
                        $('#otpModal').modal('show');
                    })
                    .catch(() => {
                        setLoading(submitBtn, submitText, false);
                        emailError.innerHTML = 'Network error. Please try again.';
                    });
            });

            // ⏳ Resend OTP with 2-minute timer
            resendLink.addEventListener('click', function() {
                fetch('/password-reset-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({ email: email })
                })
                    .then(response => response.json())
                    .then(() => {
                        codeSend = true;
                        otpResend.innerHTML = 'OTP Resent To Your Email';

                        // Hide resend link and start timer
                        resendLink.style.display = 'none';
                        let timeLeft = 120; // 2 minutes in seconds

                        resendTimer = setInterval(() => {
                            timeLeft--;
                            const minutes = Math.floor(timeLeft / 60);
                            const seconds = timeLeft % 60;
                            otpResend.innerHTML = `You can resend OTP in ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                            if (timeLeft <= 0) {
                                clearInterval(resendTimer);
                                resendTimer = null;
                                otpResend.innerHTML = '';
                                resendLink.style.display = 'inline';
                            }
                        }, 1000);
                    })
                    .catch(() => {
                        emailError.innerHTML = 'Something Went Wrong';
                    });
            });

            otpForm.addEventListener('submit', function(e) {
                e.preventDefault();
                otp = otpInput.value;
                setLoading(otpSubmitBtn, otpSubmitText, true);
                fetch('/otp-check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({ email: email, otp: otp })
                })
                    .then(response => response.json())
                    .then(() => {
                        setLoading(otpSubmitBtn, otpSubmitText, false);
                        $('#otpModal').modal('hide');
                        $('#passwordModal').modal('show');
                    })
                    .catch(() => {
                        setLoading(otpSubmitBtn, otpSubmitText, false);
                        otpError.innerHTML = 'Something Went Wrong';
                    });
            });

            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                setLoading(passwordSubmitBtn, passwordSubmitText, true);

                if (password.length < 6) {
                    passwordError.innerHTML = 'Password Minimum 6 Character';
                    setLoading(passwordSubmitBtn, passwordSubmitText, false);
                    return;
                } else {
                    passwordError.innerHTML = '';
                }

                if (password !== confirmPassword) {
                    confirmPasswordError.innerHTML = 'Confirm Password Not Matched';
                    setLoading(passwordSubmitBtn, passwordSubmitText, false);
                    return;
                } else {
                    confirmPasswordError.innerHTML = '';
                }

                fetch('/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({ email: email, password: password, otp: otp })
                })
                    .then(response => response.json())
                    .then(data => {
                        setLoading(passwordSubmitBtn, passwordSubmitText, false);
                        if (typeof ShowNotification !== 'undefined') {
                            ShowNotification('success', 'Authentication', data.message);
                        } else {
                            alert(data.message);
                        }
                        if (data.success) {
                            window.location.href = '/login';
                        } else {
                            confirmPasswordError.innerHTML = data.message;
                        }
                    })
                    .catch(() => {
                        setLoading(passwordSubmitBtn, passwordSubmitText, false);
                        otpError.innerHTML = 'Something Went Wrong';
                    });
            });

            $('#otpModal').on('hidden.bs.modal', closeModal);
        });
    </script>
@endPushOnce

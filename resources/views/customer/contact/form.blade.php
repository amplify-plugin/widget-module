@pushonce('plugin-script')
    <script src="{{ asset("packages/select2/dist/js/select2.min.js") }}"></script>
@endpushonce

<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ $action_route }}" autocomplete="off" novalidate>
                @csrf
                @method($action_method)
                <input type="hidden" name="customer_id" value="{{ customer()->id }}" />
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $contact->name ?? '') }}"
                                   class="form-control @error('name') is-invalid @enderror" autocomplete="off"
                                   title="Letters required; allowed: letters, spaces, hyphens (-), apostrophes (’), and dots (.)"
                                   data-allow="person_name">
                            @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{old('email', $contact->email ?? '')}}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   autocomplete="new-email"
                                   autocapitalize="off" autocorrect="off" spellcheck="false"
                                   inputmode="email" data-form-type="other">
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone<span class="text-danger">*</span></label>
                            <input type="text" name="phone" value="{{old('phone', $contact->phone ?? '')}}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   inputmode="tel"
                                   maxlength="20"
                                   title="7–20 characters; digits, spaces, + ( ) - only"
                                   data-allow="phone">
                            @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Address</label>
                            <select name="customer_address_id" class="form-control custom-select">
                                @foreach($addresses as $address)
                                    <option
                                        value="{{$address->id}}"
                                        @selected($address->id == old('customer_address_id', $contact->customer_address_id ?? ''))>
                                        {{$address->address_name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_address_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password
                                @if(!$editable)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <div class="position-relative">
                                <input type="password" name="password" autocomplete="new-password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       data-form-type="other" id="password-field" style="padding-right: 40px;">
                                <button type="button" class="btn btn-link position-absolute"
                                        style="right: 0; top: 30%; transform: translateY(-50%); border: none; background: none; padding: 0; z-index: 10; outline: none; box-shadow: none;"
                                        id="toggle-password">
                                    <i class="fa fa-eye" id="password-eye" style="color: #6c757d;"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Confirm Password
                                @if(!$editable)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <div class="position-relative">
                                <input type="password" name="password_confirmation" autocomplete="new-password"
                                       class="form-control" data-form-type="other" id="password-confirmation-field" style="padding-right: 40px;">
                                <button type="button" class="btn btn-link position-absolute"
                                        style="right: 0; top: 30%; transform: translateY(-50%); border: none; background: none; padding: 0; z-index: 10; outline: none; box-shadow: none;"
                                        id="toggle-password-confirmation">
                                    <i class="fa fa-eye" id="password-confirmation-eye" style="color: #6c757d;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Order Limit<span class="text-danger">*</span></label>
                            <input type="number" name="order_limit"
                                   value="{{old('order_limit', $contact->order_limit ?? '')}}"
                                   step="any" min="0"
                                   class="form-control @error('order_limit') is-invalid @enderror">
                            @error('order_limit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Daily Budget Limit<span class="text-danger">*</span></label>
                            <input type="number" name="daily_budget_limit"
                                   value="{{old('daily_budget_limit', $contact->daily_budget_limit ?? '')}}" step="any"
                                   min="0"
                                   class="form-control @error('daily_budget_limit') is-invalid @enderror">
                            @error('daily_budget_limit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Monthly Budget Limit<span class="text-danger">*</span></label>
                            <input type="number" name="monthly_budget_limit"
                                   value="{{old('monthly_budget_limit', $contact->monthly_budget_limit ?? '')}}"
                                   step="any"
                                   min="0"
                                   class="form-control @error('monthly_budget_limit') is-invalid @enderror">
                            @error('monthly_budget_limit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Roles</label>
                            <select name="roles[]" style="width: 100%" class="roles form-control"
                                    multiple="multiple">
                                @foreach($roles as $role)
                                    <option
                                        value="{{$role->id}}"
                                        @selected(in_array($role->id, old('roles', $contact_roles)))>
                                        {{$role->name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <!-- <div class="form-check d-flex align-items-center h-100 p-0 mt-1"> -->
                            <label>Preferred Page After Login</label>
                            <select name="redirect_route" style="width: 100%"
                                    class="roles form-control @error('redirect_route') is-invalid @enderror">
                                @foreach($urls as $url => $label)
                                    <option
                                        value="{{ $url }}"
                                        @selected($url == old('redirect_route', $contact->redirect_route ?? ''))>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('redirect_route')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0 !important;">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="pe-7s-diskette"></i>
                            <span>Save</span>
                        </button>
                    </div>
                    <a href="{{ route('frontend.contacts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /*.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {*/
    /*    color: #606975;*/
    /*}*/

    /*.select2-container .select2-selection--single .select2-selection__clear {*/
    /*    float: right;*/
    /*    margin-right: 1.6rem;*/
    /*    margin-top: 0.7rem;*/
    /*    height: 1rem;*/
    /*    width: 1rem;*/
    /*    line-height: 1rem;*/
    /*    padding-left: 0.25rem;*/
    /*    padding-top: 0.05%;*/
    /*}*/

    /*.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {*/
    /*    display: unset;*/
    /*}*/

    /* Remove focus and hover effects from password toggle buttons */
    #toggle-password, #toggle-password-confirmation {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
    }

    #toggle-password:focus, #toggle-password-confirmation:focus,
    #toggle-password:hover, #toggle-password-confirmation:hover,
    #toggle-password:active, #toggle-password-confirmation:active {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
        background: none !important;
    }
</style>

@pushonce('footer-script')
    <script>
        $(document).ready(function() {
            $('.roles').select2({
                placeholder: 'Select roles',
                allowClear: true
            });
        });

                 (function () {
             // live sanitizer by whitelist type
             var filters = {
                 // Name: letters + space + dot + apostrophe + hyphen
                 person_name: /[^A-Za-z .'-]/g,
                 // Phone: digits + space + + ( ) -
                 phone:       /[^0-9+\-() ]/g
             };

             document.addEventListener('input', function (e) {
                 var el = e.target;
                 var allow = el.getAttribute && el.getAttribute('data-allow');
                 if (!allow || !filters[allow]) return;
                 var before = el.value;
                 var after  = before.replace(filters[allow], '');
                 if (after !== before) {
                     var s = el.selectionStart, d = before.length - after.length;
                     el.value = after;
                     // try keep caret position sensible
                     if (s != null) el.setSelectionRange(Math.max(0, s - d), Math.max(0, s - d));
                 }
             }, true);

             // Password visibility toggle functionality
             document.addEventListener('DOMContentLoaded', function() {
                 // Toggle for main password field
                 var passwordField = document.getElementById('password-field');
                 var togglePassword = document.getElementById('toggle-password');
                 var passwordEye = document.getElementById('password-eye');

                 if (togglePassword && passwordField) {
                     togglePassword.addEventListener('click', function() {
                         if (passwordField.type === 'password') {
                             passwordField.type = 'text';
                             passwordEye.classList.remove('fa-eye');
                             passwordEye.classList.add('fa-eye-slash');
                         } else {
                             passwordField.type = 'password';
                             passwordEye.classList.remove('fa-eye-slash');
                             passwordEye.classList.add('fa-eye');
                         }
                     });
                 }

                 // Toggle for confirm password field
                 var passwordConfirmationField = document.getElementById('password-confirmation-field');
                 var togglePasswordConfirmation = document.getElementById('toggle-password-confirmation');
                 var passwordConfirmationEye = document.getElementById('password-confirmation-eye');

                 if (togglePasswordConfirmation && passwordConfirmationField) {
                     togglePasswordConfirmation.addEventListener('click', function() {
                         if (passwordConfirmationField.type === 'password') {
                             passwordConfirmationField.type = 'text';
                             passwordConfirmationEye.classList.remove('fa-eye');
                             passwordConfirmationEye.classList.add('fa-eye-slash');
                         } else {
                             passwordConfirmationField.type = 'password';
                             passwordConfirmationEye.classList.remove('fa-eye-slash');
                             passwordConfirmationEye.classList.add('fa-eye');
                         }
                     });
                 }
             });
         })();
    </script>
@endpushonce

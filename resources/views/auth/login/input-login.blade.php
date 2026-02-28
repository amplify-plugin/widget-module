<div class="form-group">
    <div class="input-group">
        <label class="sr-only" for="login-email">Email</label>
        <span class="input-group-addon">
            <i class="icon-mail"></i>
        </span>
        <input id="login-email"
               type="email"
               name="email"
               class="form-control @error('email') is-invalid @enderror"
               placeholder="{{ __('Email') }}"
               autocomplete="username"
               tabindex="1"
               required>
    </div>
    <span class="invalid-feedback d-block">
        @error('email') {{ $message }} @enderror
    </span>
</div>

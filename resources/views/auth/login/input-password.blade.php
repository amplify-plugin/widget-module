<div class="form-group">
    <div class="input-group @error('password') is-invalid @enderror">
        <label for="password" class="sr-only">password</label>
        {{-- <input class="form-control" type="password" name="password" placeholder="Password" required> --}}
        <span class="input-group-addon">
            <i class="icon-lock"></i>
        </span>
        <input 
            id="login-password"
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror pr-7"
            placeholder="{{ __('Password') }}"
            autocomplete="current-password"
            required
        />
        @if ($togglePassword)
            <span
                class="toggle-password"
                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer;"
            >
                <i class="pe-7s-look"></i>
            </span>
        @endif
    </div>
    <span class="invalid-feedback d-block">
        @error('password') {{ $message }} @enderror
    </span>
</div>

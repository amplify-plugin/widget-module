<div class="d-flex flex-wrap justify-content-between padding-bottom-1x">
    <div class="custom-control custom-checkbox">
        <input class="custom-control-input" type="checkbox" id="remember_me" tabindex="3" name="remember_me">
        <label class="custom-control-label" for="remember_me">{{ trans('Remember me')}}</label>
        <span class="invalid-feedback d-block">
            @error('remember_me') {{ $message }} @enderror
        </span>
    </div>
    <a class="navi-link"
       href="{{ route('frontend.password.request')}}">{{__('Forgot password?')}}</a>
</div>

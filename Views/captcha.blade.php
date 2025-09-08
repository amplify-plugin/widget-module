<style>
    {{ '#'.$id }}.captcha-container {
        height: 60px !important;
        width: 147px !important;
    }

    {{ '#'.$id }}.captcha-container > img {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain;
    }
</style>
<div {!! $htmlAttributes !!}>
    {!! \Form::label($id, 'Captcha Verification', true) !!}
    <div class="bg-secondary border d-flex rounded px-3 gap-3">
        <div class="d-flex gap-3 justify-content-between align-items-baseline">
            <div class="captcha-container" id="{{ $id }}"></div>
            <button type="button" class="btn btn-outline-secondary px-3 m-0 btn-sm"
                    onclick="reloadCaptcha('{{'#'.$id }}');">
                <i class="icon-reload"></i>
            </button>
        </div>
        <input type="text" name="{{$fieldName}}" class="form-control mt-2 @error($fieldName) is-invalid @enderror"
               placeholder="Enter Captcha Character" />
        <input type="hidden" name="captcha_name" value="{{$fieldName}}">
    </div>
        {!! \Form::error($fieldName) !!}
</div>

<script>
    if (typeof reloadCaptcha === 'undefined') {
        function reloadCaptcha(target) {
            $.get('reload-captcha', {}, function(response) {
                $(`${target}`).empty();
                $(`${target}`).html(response.captcha);
            }).catch((err) => {
                ShowNotification('error', 'Alert!', err.response.data.message ?? 'The given data is invalid.');
                console.error(err);
            });
        }
    }

    @if($reloadCaptcha)
        if (typeof reloadCaptcha === 'function') {
            document.addEventListener('DOMContentLoaded', () => reloadCaptcha('{{'#'.$id }}'));
        }
    @endif
</script>

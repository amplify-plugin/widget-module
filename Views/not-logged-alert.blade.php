<div {!! $htmlAttributes !!}>
    <div
        style="height: {{ $height }} !important; width: {{ $width }} !important; background-color: {{ $backgroundColor }} !important;"
        class="card shadow shadow-sm">
        <div class="card-body">
            <h1
                style="font-size: {{ $frontSize}} !important; color: {{ $textColor }} !important;"
                class="text-center d-block mb-4">
                {{ $message }}
            </h1>
            <div class="d-flex justify-content-center">
                <a
                    class="btn btn-link {{ $buttonClass ?? '' }} {{empty($buttonColorClass) ? 'btn-outline-warning' : $buttonColorClass}}"
                    href="{{ route('frontend.login') }}"
                >
                    {{ $buttonLabel }}
                </a>
            </div>
        </div>
    </div>
</div>

<div {!! $htmlAttributes !!}>
    <div class="flip-card-inner">
        <div class="flip-card-front">
            <img class="w-100 p-3" src="{{ $image }}" alt="Avatar">
        </div>
        <div class="flip-card-back px-3 pb-4 pt-3">
            <!-- <p class="flip-content text-center text-white"> -->
            {!! $sliceContent !!}
            <!-- </p> -->
            @if (isset($link))
                <a class="read-more-btn" href="{{ $link }}">
                    {{ !empty($buttonLabel) ? $buttonLabel : 'Read More' }}
                </a>
            @endif
        </div>
    </div>
    <!--    <div class="flip-card-wrapper">
        <div class="flip-card-inner-section">
            <div class="flip-card-image">
                <img class="img-fluid" src="{{ $image }}" alt="Avatar">
                <h1 class="card-title"> {{ $contentItem->name }}</h1>
            </div>
        </div>
    </div>-->
</div>

@pushOnce('footer-scripts')
    <script>
        function cancel() {
            let url = window.location.href;
            const parts = url.split('#');
            let baseUrl = parts[0];
            location.href = baseUrl;
        }
    </script>
@endPushOnce
    <div {!! $htmlAttributes !!}>
    <div>
        <div class="gallery-wrapper">
            <div class="gallery-item position-relative video-btn text-center mb-0">
                <a class="position-absolute top-0 left-0 w-100 h-100" href="#" data-toggle="tooltip"
                    data-type="video" data-video="{{ $videoPlayerConfiguration() }}">
                </a>
                <div class="video-image overflow-hidden rounded" style="height: {{$playerHeight}}px">
                    <img class="w-100 h-100" src="{{ assets_image($thumbnailImage) }}" alt="Cover">
                </div>
            </div>
        </div>
        {!! $slot ?? '' !!}
    </div>
    </div>


    @php
    push_html(function () {
    return <<<HTML
        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" onclick="cancel()" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
        </div>
        HTML;
        })
        @endphp
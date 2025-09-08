<div {!! $htmlAttributes !!}>
    <div class="product-gallery">
        {{-- <div class="gallery-wrapper"> --}}
        {{--     <div class="gallery-item video-btn text-center"> --}}
        {{--         <a href="#" --}}
        {{--            data-toggle="tooltip" --}}
        {{--            data-type="video" --}}
        {{--            data-video="&lt;div class=&quot;wrapper&quot;&gt;&lt;div class=&quot;video-wrapper&quot;&gt;&lt;iframe class=&quot;pswp__video&quot; width=&quot;960&quot; height=&quot;640&quot; src=&quot;//www.youtube.com/embed/B81qd2v6alw?rel=0&quot; frameborder=&quot;0&quot; allowfullscreen&gt;&lt;/iframe&gt;&lt;/div&gt;&lt;/div&gt;" --}}
        {{--            title="Watch video"></a> --}}
        {{--     </div> --}}
        {{-- </div> --}}
        <div class="product-carousel owl-carousel gallery-wrapper">
            <div class="gallery-item" data-hash="item-one">
                <a data-size="1000x667" href="{{ $productImage->main ?? ' ' }}">
                    <img src="{{ $productImage->main ?? ' ' }}" alt="Product">
                </a>
            </div>

            @if (!empty($productImage->additional))
                @foreach ($productImage->additional as $key => $image)
                    @if (str_contains($image, 'youtube.com') !== false)
                        @php
                            preg_match('/\/embed\/([a-zA-Z0-9_-]+)/', $image, $matches);
                            $videoId = $matches[1];
                        @endphp
                        <div class="gallery-item video-btn text-center" data-hash="{{ 'item-' . $key }}">
                            <a data-toggle="tooltip" data-type="video" data-size="1920x1080"
                               data-video="&lt;div class=&quot;wrapper&quot;&gt;&lt;div class=&quot;video-wrapper&quot;&gt;&lt;iframe class=&quot;pswp__video&quot; width=&quot;960&quot; height=&quot;640&quot; src=&quot;{{ $image }}&quot; frameborder=&quot;0&quot; autoplay&gt; allowfullscreen&gt;&lt;/iframe&gt;&lt;/div&gt;&lt;/div&gt;"
                               href="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg">
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg" alt="Product">
                            </a>
                        </div>
                    @else
                        <div class="gallery-item" data-hash="{{ 'item-' . $key }}">
                            <a data-size="1000x667" href="{{ assets_image($image ?? '') }}">
                                <img src="{{ assets_image($image ?? '') }}" alt="Product">
                            </a>
                        </div>
                    @endif
                @endforeach
            @endif

            @if (!empty($erpAdditionalImages))
                @foreach ($erpAdditionalImages as $key => $additionalImage)
                    <div class="gallery-item" data-hash="{{ 'erp-item-' . $key }}">
                        <a data-size="1000x667"
                           href="{{ 'https://www.spisafety.com/images/products/' . $additionalImage['value'] }}">
                            <img src="{{ 'https://www.spisafety.com/images/products/' . $additionalImage['value'] }}"
                                 alt="Product">
                        </a>
                    </div>
                @endforeach
            @endif
        </div>

        <ul class="product-thumbnails thumbnails-carousel owl-carousel">
            <li class="active overflow-hidden item">
                <a class="product-thumbnail w-100" href="#item-one">
                    <img src="{{ $productImage->main ?? ' ' }}" alt="Product" class="img-fluid" />
                </a>
            </li>

            @if (!empty($productImage->additional))
                @foreach ($productImage->additional as $key => $image)
                    @if (str_contains($image, 'youtube.com') !== false)
                        @php
                            preg_match('/\/embed\/([a-zA-Z0-9_-]+)/', $image, $matches);
                            $videoId = $matches[1];
                        @endphp
                        <li class="item">
                            <a class="product-thumbnail video-thumbnail" href="#{{ 'item-' . $key }}">
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg" alt="Product">
                            </a>
                        </li>
                    @else
                        <li class="item">
                            <a class="product-thumbnail" href="#{{ 'item-' . $key }}">
                                <img src="{{ assets_image($image ?? '') }}" alt="Product" />
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif

            @if (!empty($erpAdditionalImages))
                @foreach ($erpAdditionalImages as $key => $additionalImage)
                    <li class="item">
                        <a class="product-thumbnail" href="#{{ 'erp-item-' . $key }}">
                            <img
                                src="{{ 'https://www.spisafety.com/images/products/thumb/' . $additionalImage['value'] }}"
                                alt="Product" />
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

@push('html-default')
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
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
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
@endpush

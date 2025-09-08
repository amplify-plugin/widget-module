<div {!! $htmlAttributes !!}>
    <div class="entry-share mt-2 mb-2"><span class="text-muted">Share:</span>
        <div class="share-links">
            @foreach($links as $media => $link)
                <a
                    class="social-button shape-circle sb-{{$media}}"
                    href="{{ $link }}" data-toggle="tooltip" data-placement="top"
                    title="{{ ucfirst($media) }}"
                    target="_blank"
                ><i class="socicon-{{$media}}"></i></a>
            @endforeach
        </div>
    </div>
</div>

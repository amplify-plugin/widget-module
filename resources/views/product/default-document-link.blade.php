<div {!! $htmlAttributes !!}>
    <a href="{{ $document->file_path }}" target="_blank" class="d-flex gap-2 text-decoration-none">
        <img class="product-default-document-image"
             src="{{ asset('images/pdf-file.png') }}"
             alt="{{$document->name ?? 'Document'}} Icon">
        <span class="Datasheet_custom">{{$document->name ?? 'Document'}}</span>
    </a>
</div>

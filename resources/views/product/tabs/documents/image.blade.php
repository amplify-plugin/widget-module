@push('title')
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#{{ $id }}" role="tab">
            {{ __($document->documentType->name ?? 'Document') }}
        </a>
    </li>
@endpush

@push('content')
    <div class="tab-pane fade" id="{{ $id }}" role="tabpanel"
         aria-labelledby="{{ $id }}">
        <div class="text-center w-100">
            <img class="img-style" src="{{ assets_image($document->file_path) }}"
                 alt="{{ $document->documentType->name }}">
        </div>
    </div>
@endpush
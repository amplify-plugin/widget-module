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
        <a href="{{ external_asset($document->file_path) }}" class="btn btn-primary"
           download="{{ $document->documentType->name }}">Download</a>
    </div>
@endpush
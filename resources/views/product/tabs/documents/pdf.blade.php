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
        <object class="iframe-style" data="{{ external_asset($document->file_path) }}"
                type="application/pdf">
            <embed src="{{ external_asset($document->file_path) }}" type="application/pdf"
                   style="width: 100% !important;"/>
        </object>
    </div>
@endpush
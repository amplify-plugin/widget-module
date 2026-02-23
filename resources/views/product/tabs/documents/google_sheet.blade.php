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
        <div class="mb-2">
            <a href="{{ external_asset($document->file_path) }}" target="_blank">
                View on Google Docs <i class="pe-7s-exapnd2"></i>
            </a>
        </div>
    </div>
@endpush
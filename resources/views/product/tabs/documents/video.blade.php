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
        <div class="text-center">
            <video width="320" height="240" controls>
                <source src="{{ asset($document->file_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
@endpush
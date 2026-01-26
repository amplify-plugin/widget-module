@if ($document->documentType->media_type === 'image')
    <div class="text-center">
        <img class="img-style" src="{{ assets_image($document->file_path) }}"
             alt="{{ $document->documentType->name }}">
    </div>
@endif

@if ($document->documentType->media_type === 'video')
    <div class="text-center">
        <video width="320" height="240" controls>
            <source src="{{ asset($document->file_path) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
@endif

@if ($document->documentType->media_type === 'pdf')
    <object class="iframe-style" data="{{ external_asset($document->file_path) }}"
            type="application/pdf">
        <embed src="{{ external_asset($document->file_path) }}" type="application/pdf"
               style="width: 100% !important;" />
    </object>
@endif

@if ($document->documentType->media_type === 'google_doc' || $document->documentType->media_type === 'google_sheet')
    <div class="mb-2">
        <a href="{{ external_asset($document->file_path) }}" target="_blank">
            View on Google Docs <i class="pe-7s-exapnd2"></i>
        </a>
    </div>
@endif

@if ($document->documentType->media_type === 'doc' || $document->documentType->media_type === 'xls')
    <a href="{{ external_asset($document->file_path) }}" class="btn btn-primary"
       download="{{ $document->documentType->name }}">Download</a>
@endif

@if ($document->documentType->media_type === 'octet-stream')
    <a href="{{ external_asset($document->file_path) }}" class="btn btn-primary"
       download="{{ $document->documentType->name }}">Download</a>
@endif

@if ($document->documentType->media_type === 'embedded')
    @if ($document->documentType->name === '360 Image')
        @foreach (json_decode($document->content, true) as $index => $viewer)
            <style>
                .viewer-container {
                    margin-bottom: 20px;
                    padding: 10px;
                }

                .viewer-container h3 {
                    margin-top: 0;
                    text-align: center;
                }

                .viewer {
                    max-width: 600px;
                    margin: auto;
                    overflow: hidden;
                    cursor: grab;
                }

                .viewer img {
                    width: 100%;
                    user-drag: none;
                    user-select: none;
                    pointer-events: none;
                    border: 1px solid #eee;
                    border-radius: 10px;
                }
            </style>
            <div class="viewer-container">
                <h3>{{ $viewer['display_name'] }}</h3>
                <div id="viewer-{{ $index }}" class="viewer"></div>
            </div>
            <script>
                function image360Viewer(data, containerId) {
                    const cols = parseInt(data.cols);
                    const rows = parseInt(data.rows);
                    const initialImage = data.initial_image;
                    const filenameTemplate = data.initial_image.substring(0, data.initial_image.lastIndexOf('/') + 1) + data
                        .filename;

                    const viewer = document.getElementById(containerId);
                    let currentImageIndex = 0;

                    // Load initial image
                    const initialImg = document.createElement('img');
                    initialImg.src = initialImage;
                    initialImg.style.userSelect = 'none';
                    viewer.appendChild(initialImg);

                    // Create an array of image URLs
                    const images = [];
                    for (let row = 1; row <= rows; row++) {
                        for (let col = 1; col <= cols; col++) {
                            const url = filenameTemplate.replace('{row}', row.toString().padStart(2, '0')).replace('{col}', col
                                .toString().padStart(2, '0'));
                            images.push(url);
                        }
                    }

                    // Add mouse event listeners
                    let isDragging = false;
                    let startX;

                    viewer.addEventListener('mousedown', (e) => {
                        isDragging = true;
                        startX = e.clientX;
                        viewer.style.cursor = 'grabbing';
                    });

                    viewer.addEventListener('mousemove', (e) => {
                        if (isDragging) {
                            const diff = e.clientX - startX;
                            if (Math.abs(diff) > 10) {
                                startX = e.clientX;
                                currentImageIndex = (currentImageIndex + (diff > 0 ? 1 : -1) + images.length) % images
                                    .length;
                                initialImg.src = images[currentImageIndex];
                            }
                        }
                    });

                    viewer.addEventListener('mouseup', () => {
                        isDragging = false;
                        viewer.style.cursor = 'grab';
                    });

                    viewer.addEventListener('mouseleave', () => {
                        isDragging = false;
                        viewer.style.cursor = 'grab';
                    });

                    viewer.addEventListener('dragstart', (e) => {
                        e.preventDefault();
                    });
                }

                image360Viewer(@json($viewer), 'viewer-{{ $index }}');
            </script>
        @endforeach
    @else
        <div class="p-3">
            {!! $document->content !!}
        </div>
    @endif
@endif

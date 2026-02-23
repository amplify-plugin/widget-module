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
    </div>
@endpush
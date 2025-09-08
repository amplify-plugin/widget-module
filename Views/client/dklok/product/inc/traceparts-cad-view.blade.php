<div class="row">
    <div class="col-md-12 mb-3">
        <ul class="nav nav-pills mb-3" id="viewTabs" role="tablist">
            <li class="nav-item">
                <a
                    class="nav-link cad-handle-btn active"
                    id="view-2d-tab"
                    data-toggle="pill"
                    href="#view-2d"
                    role="tab"
                    aria-controls="view-2d"
                    aria-selected="true"
                    onclick="handleView(event, '2d')"
                >
                    2D View
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link cad-handle-btn"
                    id="view-3d-tab"
                    data-toggle="pill"
                    href="#view-3d"
                    role="tab"
                    aria-controls="view-3d"
                    aria-selected="false"
                    onclick="handleView(event, '3d')"
                >
                    3D View
                </a>
            </li>
        </ul>

        <div class="tab-content" id="viewTabsContent">
            <div class="cad-viewer-container">
                @empty($cadView)
                    <div class="alert alert-danger" id="cad-view-not-found">
                        No 2D or 3D view is available for this product
                    </div>
                @else
                    <div id="cad-loading" class="justify-content-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <select id="view-select" class="d-none" disabled>
                        @foreach( $cadView as $cadData )
                            <option description="{{ $cadData['description'] }}" value="{{ $cadData['url'] }}">{{ $cadData['name'] }}</option>
                        @endforeach
                    </select>
                    <iframe id="cad-iframe" allowfullscreen src=""></iframe>
                @endempty
            </div>
        </div>
    </div>
</div>

@pushonce('footer-script')
    <script>
        const cadButtons = document.getElementsByClassName('cad-handle-btn');
        const select = document.getElementById('view-select');
        const iframe = document.getElementById('cad-iframe');
        const loadingOverlay = document.getElementById('cad-loading');

        $(document).ready(function () {
            if(select){
                select.removeAttribute('disabled');
                handle2DView();
            }

        });

        const handleView = (event, value) => {
            event.preventDefault();
            if (value === '2d') {
                handle2DView();
                return;
            }

            handle3DView();
        };

        // Show/hide loading overlay
        const showLoading = () => {
            iframe?.classList.add('d-none');
            loadingOverlay?.classList.remove('d-none');
            loadingOverlay?.classList.add('d-flex');
        };

        const hideLoading = () => {
            iframe?.classList.remove('d-none');
            loadingOverlay?.classList.add('d-none');
            loadingOverlay?.classList.remove('d-flex');
        };

        // Wait for iframe to load once
        const waitForIframeLoad = () =>
            new Promise(resolve => {
                iframe.addEventListener('load', () => resolve(), { once: true });
            });

        window.handle2DView = async function () {
            if (select.disabled) return;

            const twoD = Array.from(select.options)
                .find(o => {
                    console.log(o.getAttribute('description'));
                    return /2D/i.test(o.getAttribute('description')) || /2D/i.test(o.text)
                });

            console.log(twoD);
            const viewUrl = twoD?.value || select.options[0].value;

            // Prevent reload loop: only change src if it's different
            if (iframe.src === viewUrl) return;

            showLoading();
            const loadPromise = waitForIframeLoad();
            iframe.src = viewUrl;
            await loadPromise;
            hideLoading();
        };

        window.handle3DView = async function () {
            if (select.disabled) return;

            const threeD = Array.from(select.options)
                .find(o => /3D/i.test(o.getAttribute('description')) || /3D/i.test(o.text));
            const viewUrl = threeD?.value || select.options[0].value;

            if (iframe.src === viewUrl) return;

            showLoading();
            const loadPromise = waitForIframeLoad();
            iframe.src = viewUrl;
            await loadPromise;
            hideLoading();
        };

    </script>
@endpushonce

<style>
    body { margin:0; padding:0 }
    .cad-viewer-container {
        width:100%; height:100vh; position:relative;
    }
    iframe {
        width:100%; height:100%; border:none;
    }
    #view-select {
        position:absolute;
        top:1rem; right:1rem;
        z-index:10; padding:.5rem;
    }
</style>


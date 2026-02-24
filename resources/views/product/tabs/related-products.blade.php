@if($product->related_product)
    @php
        $relatedUrl = ($product->related_product)
            ? route('frontend.shop.relatedProducts', $product->Amplify_Id)
            : '';
    @endphp

    @push('title')
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#{{ $entry['name'] }}" role="tab">
                {{ __($entry['label']) }}
            </a>
        </li>
    @endpush

    @push('content')
        <div class="tab-pane fade" id="{{ $entry['name'] }}" role="tabpanel"
             aria-labelledby="{{ $entry['name'] }}">
            <div id="related-products-content"
                 data-url="{{ $relatedUrl }}" data-loaded="0">
                <div class="text-center w-100 py-4">
                    <button class="btn btn-outline-secondary" disabled>
                        Click the tab to load related products
                    </button>
                </div>
            </div>
        </div>
    @endpush

    @push('footer-script')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('a[data-toggle="tab"][href="#related-products"]').on('shown.bs.tab', function () {
                    const content = document.getElementById('related-products-content');

                    if (!content || content.dataset.loaded === '1') return;

                    const url = content.dataset.url;
                    if (!url) return;

                    // Show loader
                    content.innerHTML = `
            <div class="text-center w-100 py-4">
                <div class="spinner-border text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Loading related products...</p>
            </div>
        `;

                    // Fetch and load content
                    fetch(url, {
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }).then(r => r.ok ? r.json() : Promise.reject('Network error'))
                        .then((response) => {
                            content.innerHTML = response.html;
                            content.dataset.loaded = '1';
                        })
                        .catch(err => {
                            console.error('Error loading related products:', err);
                            content.innerHTML = `
                    <div class="text-center text-danger py-4">
                        Unable to load related products.
                    </div>
                `;
                        });
                });
            });


            //when change the related proucts type
            (function () {
                document.addEventListener('change', function (e) {
                    const input = e.target.closest('.relation-type-radio');
                    if (!input) return;

                    const url = input.dataset.url;
                    const container = document.getElementById('related-products-content');
                    if (!url || !container) return;

                    container.innerHTML = `
            <div class="text-center w-100 py-4">
                <div class="spinner-border text-secondary" role="status"></div>
                <p class="mt-2">Loading related products...</p>
            </div>
        `;

                    fetch(url, {credentials: 'same-origin'})
                        .then(res => {
                            if (!res.ok) throw new Error('Network error');
                            return res.json();
                        })
                        .then(({html}) => {
                            const temp = document.createElement('div');
                            temp.innerHTML = html.trim();
                            console.log(temp);
                            const newContent = temp.querySelector('#related-products-content');
                            if (newContent) {
                                container.replaceWith(newContent);
                            }
                        })
                        .catch(err => {
                            console.error('Error loading related products:', err);
                        });
                });
            })();
            //pagination handler
            (function () {
                // Delegate pagination clicks inside related-products-content
                document.addEventListener('click', function (e) {
                    const anchor = e.target.closest('#related-products-content .pagination a');
                    if (!anchor) return;

                    e.preventDefault();

                    const container = document.getElementById('related-products-content');
                    if (!container) return;

                    const url = anchor.href;
                    if (!url) return;

                    // show loader
                    container.innerHTML = `
            <div class="text-center w-100 py-4">
                <div class="spinner-border text-secondary" role="status"></div>
                <p class="mt-2">Loading related products...</p>
            </div>
        `;

                    fetch(url, {credentials: 'same-origin'})
                        .then(res => {
                            if (!res.ok) throw new Error('Network error');
                            return res.text();
                        })
                        .then(html => {
                            const temp = document.createElement('div');
                            temp.innerHTML = html.trim();

                            // If server returns the full fragment with #related-products-content, replace whole container
                            const newContent = temp.querySelector('#related-products-content');
                            if (newContent) {
                                container.replaceWith(newContent);
                            } else {
                                // Fallback: server returned inner HTML only
                                container.innerHTML = html;
                            }

                            // mark loaded so other handlers ignore initial-load logic if needed
                            const finalContainer = document.getElementById('related-products-content');
                            if (finalContainer) finalContainer.dataset.loaded = '1';

                            // optional: update browser URL so back/forward keep page state
                            // history.pushState(null, '', url);
                        })
                        .catch(err => {
                            console.error('Error loading related products (pagination):', err);
                            container.innerHTML = `
                    <div class="text-center text-danger py-4">
                        Unable to load related products.
                    </div>
                `;
                        });
                });
            })();
        </script>
    @endpush
@endif





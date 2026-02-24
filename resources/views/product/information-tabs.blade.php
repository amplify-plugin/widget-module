<div {!! $htmlAttributes !!}>

    {!!  $before  ?? '' !!}

    @foreach($entries as $view => $entry)
        @include($view, ['tab' => $entry, 'product' => $product])
    @endforeach

    <ul class="nav nav-tabs {{ $headerClass }}" id="product-information-tabs" role="tablist">
        @stack('title')
    </ul>

    <div class="tab-content">
        @stack('content')
        {!! $slot ?? '' !!}
    </div>
    {!!  $after ?? '' !!}
</div>

@push('footer-script')
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            let tab = document.querySelector('.x-product-information-tabs .nav-link:first-of-type');
            if (tab) {
                tab.dispatchEvent(new MouseEvent("click", {
                    bubbles: true,
                    cancelable: true,
                    view: window
                }));
            }
        });
    </script>
@endpush


@if($product->HasSku)
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
            <x-product-sku-table :product="$product" is-fav-button-show="true" />
        </div>
    @endpush
@endif
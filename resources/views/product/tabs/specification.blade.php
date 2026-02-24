@if(!empty($product->specifications))
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
            <div class="row">
                @each("widget::product.tabs.features.{$entry['style']}", $product->specifications ?? [], 'group')
            </div>
        </div>
    @endpush
@endif
<div {!! $htmlAttributes !!}>
    <a href="{{ frontendShopURL("-Manufacturer:{$product?->manufacturer?->code}") }}"
       title="{{ $product->manufacturer->name }}"
       class="text-decoration-none">
        @if(isset($product->manufacturer->image) && $product->manufacturer->image != null)
            <img src="{{ $product->manufacturer->image }}"
                 class="h-100" style="object-fit: contain"
                 alt="{{ $product->manufacturer->name }}">
        @else
            <p class="font-weight-bold">{{ $product->manufacturer?->name }}</p>
        @endif
    </a>
</div>

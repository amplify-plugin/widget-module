@if(!empty($product->ERP))
    <div class="mb-1 d-flex h4">
        <span class="label">From - </span>
        <span class="value text-primary"> {{ price_format($product->ERP->ListPrice) }} </span>
    </div>
    <div class="manufacture-info mb-2">
        @if($product->manufacturer)
            <div class="mb-1 d-flex">
                <div class="label">Manufacturer:</div>
                <div class="value">{{ $product->manufacturer->name }}</div>
            </div>
        @endif
        @if(! empty($product->manufacturer))
            <div class="mb-1 d-flex">
                <span class="label">Manufacturer Item: </span>
                <span class="value">{{$product->manufacturer}}</span>
            </div>
        @endif
        @if(! empty($product->attribute))
            <div class="mb-1 d-flex">
                <span class="label">Manufacturer Item: </span>
                <span class="value">{{$product->manufacturer}}</span>
            </div>
        @endif
    </div>
@endif

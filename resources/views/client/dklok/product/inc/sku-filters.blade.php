@if(!empty($product->ERP))
    @if(!$isMasterProduct($product))
        <div class="mb-1 d-flex h4">
            <span class="label">From - </span>
            <span class="value text-primary"> {{ price_format($product->ERP->ListPrice) }} </span>
        </div>
    @endif
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
@if(!empty($product->attribute_filters))
    <form method="get" class="row">
        @foreach($product->attribute_filters as $attr)
            <div class="mb-1 col-12 col-md-6 col-xl-4">
                <label for="{{ $attr['slug'] }}" class="label">{{ $attr['name'] }}: </label>
                <select id="{{ $attr['slug'] }}" name="{{ $attr['slug'] }}" class="form-control">
                    <option value="">Select to filter</option>
                    @php
                        $filterValue = request()->get($attr['slug']);
                    @endphp
                    @foreach($attr['values'] as $value)
                        <option
                            value="{{ $value }}"
                            {{ (! empty($filterValue) && $filterValue === $value) ? 'selected' : '' }}
                        >
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endforeach
        <div class="col-12 text-right">
            @if(! empty(request()->all()))
                <a href="{{ url()->current() }}" class="btn btn-sm btn-danger"> Clear All </a>
            @endif
            <button class="btn btn-sm btn-primary" type="submit">
                <i class="fa fa-filter"></i>
                Filter
            </button>
        </div>
    </form>
@endif

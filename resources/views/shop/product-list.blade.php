{{--@php
    $authenticated = customer_check();
    $hasPermission = customer(true)?->canAny(['favorites.manage-global-list', 'favorites.manage-personal-list']) ?? false;
@endphp--}}
<div {!! $htmlAttributes !!}>

    {!! $before ?? '' !!}

    <p @class(["font-weight-bold my-2 error-message", 'd-none' => empty($message)])>{!! $message !!}</p>

    @if($showPaginationOnTop)
        <x-shop-pagination
            prev-label="<i class='icon-arrow-left'></i><span class='pl-1 d-none d-md-inline-block'>Previous</span>"
            next-label="<span class ='d-none d-md-inline-block pr-1'>Next</span><i class='icon-arrow-right'></i>"
            class="border-bottom border-top mt-2 mb-1 py-2"/>
    @endif

    @if($productView == 'list')
        {!! $header ?? '' !!}
    @endif

    @if(!empty($products))
        <div @class(["isotope-grid grid-no-gap mt-2 {$productView}", "cols-{$gridItemsPerLine}" => $productView == 'grid', 'cols-1' => $productView == 'list'])>
            <div class="gutter-sizer"></div>
            <div class="grid-sizer"></div>
            @foreach($products as $product)
                <div class="grid-item">
                    <div @class(['product-card','product-grid' => $productView == 'grid', 'product-list' => $productView == 'list'])>
                        {!! ${$productView}->with(['loop' => $loop, 'product' => $product]) !!}
                    </div>
                </div>
            @endforeach
        </div>

        <x-shop-pagination
            prev-label="<i class='icon-arrow-left'></i><span class='pl-1 d-none d-md-inline-block'>Previous</span>"
            next-label="<span class ='d-none d-md-inline-block pr-1'>Next</span><i class='icon-arrow-right'></i>"
            class="border-bottom border-top mt-2 py-2"/>
    @else
        <x-shop-empty-result/>
    @endif

    {!! $after ?? '' !!}
</div>

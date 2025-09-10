<div {!! $htmlAttributes !!}>
    <div class="d-flex justify-content-center flex-wrap">
        @foreach (range('A', 'Z') as $key)
            <a class="btn btn-sm @if($key == request('key')) {{$btn_class['bg']}} @else {{$btn_class['bg-outline']}} @endif @"
               @if(!$brands->has($key)) disabled @endif
               href='{{ url()->current() . "?key=$key" }}'>
                {{ $key }}
            </a>
        @endforeach
    </div>

    @if(empty($btn_class['hide_number_row']))
        <div class="d-flex justify-content-center flex-wrap">
            @foreach (array_merge(range(0, 9), ['@']) as $key)
                <a class="btn btn-sm  @if($key == request('key')) {{$btn_class['bg']}} @else {{$btn_class['bg-outline']}} @endif"
                   @if(!$brands->has($key)) disabled @endif
                   href='{{ url()->current() . "?key=$key" }}'>
                    {{ $key }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="row justify-content-center mt-3">
        <div class="col-md-6 col-12">
            <form class="input-group d-flex align-items-center" method="get">
                    <span class="input-group-btn">
                        <button type="submit" class="custom-search-button" aria-label="Search">
                            <i class="icon-search"></i>
                        </button>
                    </span>
                <label for="search-input" class="sr-only">Search</label>
                <input class="form-control" type="text" name="search" placeholder="{{$placeholder}}" id="search-input" />
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @foreach (($filtered_brands ?? []) as $key => $brands)
                <div class="d-flex justify-content-between align-items-center mt-4 border-bottom">
                    <h6 class="text-muted text-normal text-uppercase">{{ $key }}</h6>
                    @if (!empty($brands['totalItems']) && $brands['totalItems'] > 6)
                        <a class="badge badge-info" href='{{ url()->current() . "?key=$key" }}'>
                            View All
                        </a>
                    @endif
                </div>

                @php $brands = !empty($brands['brands']) ? $brands['brands'] : $brands @endphp
                <div class="row">
                    @foreach ($brands as $brand)
                        <a class="font-weight-bold my-2 text-decoration-none"
                        data-toggle="tooltip" data-placement="top" title="{{ $brand['title'] }}"
                           href="{{ frontendShopURL('Brands:'.$brand['slug']) }}">
                            <div class="d-flex flex-column justify content-center align-items-center">
                                <div class="card m-2 w-auto">
                                    @if(!$nameOnly)
                                    <div class="brand-img-container">
                                        <img class="brand-img card-img-top m-0"
                                             src="{{ assets_image($brand['image']) }}"
                                             alt="{{ $brand['title'] }}"/>
                                    </div>
                                    @endif
                                    <span class="brand-name d-block text-center text-truncate p-2">{{ $brand['title'] }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

<div {!! $htmlAttributes !!}>
    <div class="d-flex justify-content-center flex-wrap">
        @foreach (range('A', 'Z') as $key)
            <a class="btn btn-sm @if($key == request('key')) {{$btn_class['bg']}} @else {{$btn_class['bg-outline']}} @endif @"
               @if(!$manufacturers->has($key)) disabled @endif
               href='{{ url()->current() . "?key=$key" }}'>
                {{ $key }}
            </a>
        @endforeach
    </div>

    @if(empty($btn_class['hide_number_row']))
        <div class="d-flex justify-content-center flex-wrap">
            @foreach (array_merge(range(0, 9), ['@']) as $key)
                <a class="btn btn-sm  @if($key == request('key')) {{$btn_class['bg']}} @else {{$btn_class['bg-outline']}} @endif"
                   @if(!$manufacturers->has($key)) disabled @endif
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
            @foreach (($filtered_manufacturers ?? []) as $key => $manufacturers)
                <div class="d-flex justify-content-between align-items-center mt-4 border-bottom">
                    <h6 class="text-muted text-normal text-uppercase">{{ $key }}</h6>
                    @if (!empty($manufacturers['totalItems']) && $manufacturers['totalItems'] > 6)
                        <a class="badge badge-info" href='{{ url()->current() . "?key=$key" }}'>
                            View All
                        </a>
                    @endif
                </div>

                @php $manufacturers = !empty($manufacturers['manufacturers']) ? $manufacturers['manufacturers'] : $manufacturers @endphp
                <div class="row">
                    @foreach ($manufacturers as $manufacturer)
                        <a class="font-weight-bold my-2 text-decoration-none"
                        data-toggle="tooltip" data-placement="top" title="{{ $manufacturer['name'] }}"
                           href="{{ frontendShopURL('Brand:'.$manufacturer['code']) }}">
                            <div class="d-flex flex-column justify content-center align-items-center">
                                <div class="card m-2 w-auto">
                                    @if(!$nameOnly)
                                    <div class="manufacturer-img-container">
                                        <img class="manufacturer-img card-img-top m-0"
                                             src="{{ assets_image($manufacturer['image']) }}"
                                             alt="{{ $manufacturer['name'] }}"/>
                                    </div>
                                    @endif
                                    <span class="manufacturer-name d-block text-center text-truncate p-2">{{ $manufacturer['name'] }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

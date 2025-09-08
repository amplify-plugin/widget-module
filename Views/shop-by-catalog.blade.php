<div {!! $htmlAttributes !!}>
    <div class="row">
        <div class="col-12">
            @foreach ($categories as $key => $category)
                <div class="d-flex justify-content-between align-items-center mt-4 border-bottom pb-2">
                    <h6 class="text-muted text-normal text-uppercase">{{ $category['label'] }}</h6>
                    <a class="badge badge-info"
                       href="{{ route('frontend.shop.index', str()->replace(' ', '-', $category['category_code'])) }}">
                        View All
                    </a>
                </div>
                <div class="row">
                    @foreach ($category['children'] as $subCategory)
                        <a class="font-weight-bold my-2 text-decoration-none"
                           href="{{ route('frontend.shop.index', str()->replace(' ', '-', $category['category_code']) . '/' . str()->replace(' ', '-', $subCategory['category_code'])) }}">
                            <div class="d-flex flex-column justify content-center align-items-center">
                                <div class="card m-2 w-auto">
                                    <div class="brand-img-container">
                                        <img class="brand-img card-img-top m-0"
                                             src="{{ assets_image($subCategory['image']) }}"
                                             alt="{{ $subCategory['label'] }}"/>
                                    </div>
                                    <span
                                        class="brand-name d-block text-center text-truncate p-2">{{ $subCategory['label'] }}</span>
                                </div>

                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

<div {!! $htmlAttributes !!}>
    <div class="row">
        @foreach ($categories as $category)
            <div class='col-sm-4'>
                <div class='card mb-30'>
                    <div class='card-body text-center'>
                        <h4 class='card-title'>{{ $eacategory->name ?? '' }}</h4>
                        <p class='text-muted'>Number of Products {{ $eacategory->productCount ?? '' }}</p>
                        <a class='btn btn-outline-primary btn-sm' href='{{ route('frontend.shop') }}'>View Products</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

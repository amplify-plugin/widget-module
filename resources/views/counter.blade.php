<div {!! $htmlAttributes !!}>
    <div class="count-up-section mt-96 py-4 py-md-5">
        <div class="container">
            <div class="row">
            @forelse ($entries as $item)
                @php
                    // Calculate the data increment based on the item number
                    $dataIncrement = ($item->number < 100 || $item->number < 1000 ) ? 1 : (floor($item->number / 1000));
                    // Check if the suffix contains a plus sign
                    if (preg_match('/\+/', $item->suffix)) {
                        $suffix = $item->suffix;
                    } else {
                        $suffix = '<sub class="fw-400">' . e($item->suffix) . '</sub>';
                    }
                @endphp

                <div class="col-md-3 col-sm-6 text-center mb-3 mb-md-0">
                    <h2 class="fw-700 text-warning">
                        <span class="numscroller" data-min="0" data-max="{{ $item->number ?? '' }}" data-delay="2" data-increment="{{$dataIncrement}}">0</span>
                        {!! $suffix !!}
                    </h2>
                    <div class="fs-18 text-warning" style="font-size: 18px;">{{ $item->name ?? '' }}</div>
                </div>
            @empty
                <!-- Handle empty case if necessary -->
            @endforelse
            </div>
        </div>
    </div>
</div>

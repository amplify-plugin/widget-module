<div {!! $htmlAttributes !!}>
    <div class="row">
        @foreach ($entries as $item)
            <div class="col text-center x-address-entry">
                <p>{{ $item->state ?? '' }}</p>
                <p>{{ $item->phone ?? '' }}</p>
                <p>{{ $item->street_address_1 ?? ''}}</p>
                <p>{{ $item->street_address_2 ?? '' }}</p>
            </div>
        @endforeach
    </div>
</div>

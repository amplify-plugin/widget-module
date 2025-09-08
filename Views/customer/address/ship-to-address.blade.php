<div {!! $htmlAttributes !!}>
    <style>
        .select2-container .select2-selection--single .select2-selection__clear {
            float: right;
            margin-right: 1.6rem;
            margin-top: 0.7rem;
            height: 1rem;
            width: 1rem;
            line-height: 1rem;
            padding-left: 0.65%;
            padding-top: 0.1%;
        }

        @media (min-width: 991px) {
            .select2-container .select2-selection--single .select2-selection__clear {
                padding-left: 1.2%;
            }
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            display: unset;
        }
    </style>

    @php
        // build a plain array of only the fields we need
        $shipAddresses = $addresses->map(function($loc) {
            return [
                'ShipToNumber'   => $loc->ShipToNumber,
                'ShipToName'     => $loc->ShipToName,
                'ShipToAddress1' => $loc->ShipToAddress1,
                'ShipToAddress2' => $loc->ShipToAddress2,
                'ShipToCity'     => $loc->ShipToCity,
                'ShipToState'    => $loc->ShipToState,
                'ShipToZipCode'  => $loc->ShipToZipCode,
            ];
        })->all();
    @endphp

    <div class="container py-5">
        @if($selectedShipToAddress)
            <div class="card mb-4 border-info">
                <div class="card-header bg-info text-white">
                    Selected Ship-To Address
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-3 font-weight-bold">Code:</div>
                        <div class="col-sm-9">{{ $selectedShipToAddress['ShipToNumber'] }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 font-weight-bold">Name:</div>
                        <div class="col-sm-9">{{ $selectedShipToAddress['ShipToName'] }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-3 font-weight-bold">Address:</div>
                        <div class="col-sm-9">
                            {{ $selectedShipToAddress['ShipToAddress1'] }}
                            @if(!empty($selectedShipToAddress['ShipToAddress2']))
                                <br>{{ $selectedShipToAddress['ShipToAddress2'] }}
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 font-weight-bold">Location:</div>
                        <div class="col-sm-9">
                            {{ $selectedShipToAddress['ShipToCity'] }},
                            {{ $selectedShipToAddress['ShipToState'] }}
                            {{ $selectedShipToAddress['ShipToZipCode'] }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('frontend.ship-to-address.store') }}">
                @csrf

                <div class="form-group">
                    <label for="ship_to_number">Select Ship-To Address</label>
                    <select
                        id="ship_to_number"
                        name="ship_to_number"
                        class="form-control address-select"
                        required
                    >
                        <option value="">— Search or select —</option>
                        @foreach($addresses as $loc)
                            <option value="{{ $loc->ShipToNumber }}">
                                [{{ $loc->ShipToNumber }}] {{ $loc->ShipToName }} –
                                {{ $loc->ShipToAddress1 }}
                                @if(!empty($loc->ShipToAddress2)), {{ $loc->ShipToAddress2 }}@endif
                                ({{ $loc->ShipToCity }}, {{ $loc->ShipToState }})
                            </option>
                        @endforeach
                    </select>
                    @error('ship_to_number')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {{-- live‐preview of what they picked --}}
                <div id="selected-shipto-card" class="mt-4"></div>

                <button type="submit" class="btn btn-primary">
                    Continue
                </button>
            </form>
        @endif
    </div>

    @pushonce('footer-script')
        <!-- Select2 JS -->
        <script src="{{ asset('packages/select2/dist/js/select2.min.js') }}"></script>
        <script>
            $(function(){
                $('.address-select').select2({
                    placeholder: 'Search by code, name, address, locations…',
                    width: '100%',
                    allowClear: true
                });

                // make just the fields we need available in JS
                const shipAddresses = @json($shipAddresses);

                function renderCard(addr) {
                    return `
<div class="card mb-4 border-info">
  <div class="card-header bg-info text-white">
    Selected Ship-To Address
  </div>
  <div class="card-body">
    <div class="row mb-2">
      <div class="col-sm-3 font-weight-bold">Code:</div>
      <div class="col-sm-9"><span>${addr.ShipToNumber}</span></div>
    </div>
    <div class="row mb-2">
      <div class="col-sm-3 font-weight-bold">Name:</div>
      <div class="col-sm-9">${addr.ShipToName}</div>
    </div>
    <div class="row mb-2">
      <div class="col-sm-3 font-weight-bold">Address:</div>
      <div class="col-sm-9">
        ${addr.ShipToAddress1}${addr.ShipToAddress2 ? '<br>' + addr.ShipToAddress2 : ''}
      </div>
    </div>
    <div class="row">
      <div class="col-sm-3 font-weight-bold">Location:</div>
      <div class="col-sm-9">
        ${addr.ShipToCity}, ${addr.ShipToState} ${addr.ShipToZipCode}
      </div>
    </div>
  </div>
</div>`;
                }

                // if already selected on page load
                const initial = $('.address-select').val();
                if (initial) {
                    const addr = shipAddresses.find(a => a.ShipToNumber === initial);
                    if (addr) $('#selected-shipto-card').html(renderCard(addr));
                }

                // update when user picks a new one
                $('.address-select').on('change', function(){
                    const code = $(this).val();
                    const addr = shipAddresses.find(a => a.ShipToNumber === code);
                    $('#selected-shipto-card').html(addr ? renderCard(addr) : '');
                });
            });
        </script>
    @endpushonce
</div>



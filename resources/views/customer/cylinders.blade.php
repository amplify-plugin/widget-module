<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <x-site.data-table-wrapper id="cylinder-table">
                <table class="table table-bordered table-striped table-hover" id="cylinder-table">
                    <thead>
                    <tr class="text-center">
                        <th>{{ __('Cylinder') }}</th>
                        <th>{{ __('Beginning') }}</th>
                        <th>{{ __('Delivered') }}</th>
                        <th>{{ __('Returned') }}</th>
                        <th>{{ __('Balance') }}</th>
                        <th>{{ __('Last Delivery') }}</th>
                        <th>{{ __('Last Returned') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cylinders as $cylinder)
                        <tr>
                            <td>{{ $cylinder->Cylinder }}</td>
                            <td>{{ $cylinder->Beginning }}</td>
                            <td>{{ $cylinder->Delivered }}</td>
                            <td>{{ $cylinder->Returned }}</td>
                            <td>{{ $cylinder->Balance }}</td>
                            <td>{{ $cylinder->LastDelivery }}</td>
                            <td>{{ $cylinder->LastReturned }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-site.data-table-wrapper>
        </div>
    </div>
</div>

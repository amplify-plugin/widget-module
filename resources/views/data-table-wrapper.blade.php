@pushonce('plugin-style')
    <link type="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.13.1/datatables.min.css" />
@endpushonce

@pushonce('plugin-script')
    <script src="https://cdn.datatables.net/v/bs4/dt-1.13.1/datatables.min.js"></script>
@endpushonce
<div class="row">
    <div class="col-md-4 my-2 mb-md-0">
        <div id="search_filter" class="d-flex justify-content-center justify-content-md-start"></div>
    </div>
    @if(isset($rightside))
        <div class="col-md-8 mb-2 mb-md-0 my-2">
            {{ $rightside ?? '' }}
        </div>
    @endif
    @if(isset($fullsection))
        <div class="col-12">
            {{ $fullsection ?? '' }}
        </div>
    @endif
</div>
<div class="table-responsive pb-4 pb-md-0">
    {{ $slot }}
</div>
@if(isset($id))
    @push('footer-script')
        <script>
            $(document).ready(function() {

                const dataTables = $('#{{ $id }}').DataTable({
                    'dom': `<f><"row"<"col-sm-12"tr>><"row mt-2"<"col-sm-12 col-md-5"l><"col-sm-12 col-md-7"p>>`,
                    'language': {
                        'search': '_INPUT_',
                        'searchPlaceholder': 'Search...',
                    },
                    'lengthMenu': @json(getPaginationLengths()),
                    order: [[0, 'desc']],
                });

                $('#{{ $id }}_filter').appendTo($('#search_filter'));

                //Filter by status
                $('#status_filter').on('change', function () {
                    const value = $(this).val();
                    dataTables.column(2).search(value ? '^' + value + '$' : '', true, false).draw();
                });
            });
        </script>
    @endpush
@else
    <h3>Table id not passed to component</h3>
@endif

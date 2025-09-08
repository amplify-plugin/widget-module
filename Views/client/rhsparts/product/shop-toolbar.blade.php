<div {!! $htmlAttributes !!}>
<div class="shop-toolbar padding-bottom-1x mb-2">
    <div class="column">
        <div class="align-items-center d-flex flex-column flex-sm-row gap-3 shop-sorting">
            @if ($showItemCount)
                <div class="text-nowrap"><span>{{ $itemDescription() }}</span></div>
            @endif
            @if ($showSortingOption)
            <select class="form-control text-dark fw-500" id="sortby" onchange="onSortPage(event)">
                <option value="" disabled>Sort By ---</option>
                @foreach (getPaginationSortBy() as $key => $value)
                    <option value="{{ $key }}" @if (request('sort_by') == $key) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @endif
            @if ($showPerPageOption)
            <select class="form-control text-dark fw-500" id="sorting" onchange="onPerPage(event)">
                <option value="" disabled>Per Page --</option>
                @foreach (getPaginationLengths() as $value)
                    <option value="{{ $value }}" @if ($perPage == $value) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @endif

        </div>
    </div>

@if ($showProductViewChanger)
<div class="column">
    <div class="shop-view">
        <a class="grid-view {{ $productView() === 'grid' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['currentPage' => $currentPage, 'view' => 'grid']) }}"><span></span><span></span><span></span></a>
        <a class="list-view  {{ $productView() === 'list' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['currentPage' => $currentPage, 'view' => 'list']) }}"><span></span><span></span><span></span></a></div>
</div>
@endif

</div>
</div>


@php
    push_js(function () {
        return <<<HTML
            function onSortBy(e) {
                window.location = updateQueryStringParameter('order_by', e.target.value);
            }

            function onPerPage(e) {
                window.location = updateQueryStringParameter('resultsPerPage', e.target.value);
            }

            function onSortPage(e) {
                window.location = updateQueryStringParameter('sort_by', e.target.value);
            }

            function updateQueryStringParameter(key, value) {

                let uri = new URL(window.location.href);
                let queries = {};

                uri.searchParams.forEach((value, query) => queries[query] = value);
                queries[key] = value;

                if (queries.hasOwnProperty('sort_by') || queries.hasOwnProperty('resultsPerPage')) {
                    if (queries.hasOwnProperty('currentPage')) {
                        delete queries.currentPage;
                    }
                }

                return (uri.origin + uri.pathname + '?') + (new URLSearchParams(queries)).toString();

            }
        HTML;
    }, 'footer-script');
@endphp

<div {!! $htmlAttributes !!}>
    <form id="campaign-filter-form" class="row pb-3" method="get" action="{{ url()->current() }}">
        <input type="hidden" name="view" value="{{ $productView }}">
        <div class="col-md-5">
            <p class="text-muted font-weight-bold text-primary text-center text-md-left my-2 mx-0">
                Showing: {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }}
                items
            </p>
        </div>
        <div class="col-md-2 mt-4 mt-md-0">
            <select
                class="form-control" id="sorting"
                data-toggle="tooltip" data-placement="top"
                data-original-title="Items Per Page"
                name="per_page" onchange="$('#campaign-filter-form').submit();"
            >
                <option value="" disabled="">Per Page --</option>
                @foreach (getPaginationLengths() as $perPageOption)
                    <option
                        value="{{ $perPageOption }}"
                        {{ $perPage == $perPageOption? 'selected' : '' }}
                    >{{ $perPageOption }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mt-4 mt-md-0">
            <select
                class="form-control" id="sortby"
                data-toggle="tooltip" data-placement="top"
                data-original-title="Sort By" name="short_by"
                onchange="$('#campaign-filter-form').submit();"
            >
                <option value="" disabled="">Sort By ---</option>
                {{--                <option value="latest" {{ $sortBy == 'latest'? 'selected' : '' }}>Latest</option>--}}
                <option value="product_code--ASC" {{ $sortBy == 'product_code--ASC'? 'selected' : '' }}>Product
                    Code
                    A-Z
                </option>
                <option value="product_code--DESC" {{ $sortBy == 'product_code--DESC'? 'selected' : '' }}>Product
                    Code
                    Z-A
                </option>
                <option value="product_name--ASC" {{ $sortBy == 'product_name--ASC'? 'selected' : '' }}>Product
                    Name
                    A-Z
                </option>
                <option value="product_name--DESC" {{ $sortBy == 'product_name--DESC'? 'selected' : '' }}>Product
                    Name
                    Z-A
                </option>
            </select>
        </div>
        <div class="col-md-2 mt-4 mt-md-0">
            <div class="d-flex justify-content-md-end justify-content-center shop-view">
                <a class="grid-view {{ $productView == 'grid'? 'active' : '' }}" href="?view=grid"
                   data-toggle="tooltip" data-placement="top" data-original-title="Grid view">
                    <span></span><span></span><span></span>
                </a>
                <a class="list-view {{ $productView == 'list'? 'active' : '' }}" href="?view=list"
                   data-toggle="tooltip"
                   data-placement="top" data-original-title="List view">
                    <span></span><span></span><span></span>
                </a>
            </div>
        </div>
    </form>
</div>

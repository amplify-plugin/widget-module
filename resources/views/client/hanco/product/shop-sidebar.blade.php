<div {!! $htmlAttributes !!}>
    <div class="sidebar-toggle position-left">
        <i class="icon-filter pe-7s-filter"></i>
    </div>
    <aside class="sidebar sidebar-offcanvas position-left">
        <span class="sidebar-close hidden-lg-up">
            <i class="icon-x pe-7s-close"></i>
        </span>
        @if (count($filters) > 0)
            <!-- current filters and sort -->
            <section class="widget widget-categories mb-1">
                <div class="d-flex justify-content-between border-bottom">
                    <h3 class="widget-title">Filters</h3>
                    <a class="d-inline-flex align-items-center text-danger text-decoration-none rounded"
                       data-toggle="tooltip" data-placement="top" href="{{ route('frontend.shop.index') }}"
                       title="Remove All" aria-current="page">
                        {{-- <i class="filter-btn-icon pe-7s-close-circle text-danger font-weight-bold"></i> --}}
                        <span class="fw-500">Reset Filter</span>
                    </a>
                </div>
                <ul class="d-block list-unstyled fw-normal small mt-3 pb-1">
                    @foreach ($filters as $key => $filter)
                        @php
                            $label =
                                $filter->getType() == 2
                                    ? $filter->getName() . ': ' . $filter->getValue()
                                    : $filter->getValue();
                        @endphp
                        <li class="active">
                            <a class="d-inline-flex align-items-center active rounded" data-toggle="tooltip"
                               data-placement="top"
                               href="{{ route('frontend.shop.index', [$filter->getSEOPath(), ...$extraQuery]) }}"
                               title="Remove {{ ucwords($label) }}" aria-current="page">
                                <i class="pe-7s-close-circle close-icon"></i>
                                {{ ucwords($label) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
        {{-- @if (
            (isset($categories->categoryList) && count($categories->categoryList) > 0) ||
                (isset($eaattributes) && count($eaattributes) > 0))
            <section class="widget widget-categories mb-1">
                <a class="d-flex justify-content-between text-muted text-decoration-none widget-title my-3"
                    data-current-state="expanded" onclick="toggleAllFilters(event, this);">
                    COLLAPSE ALL <i class="filter-btn-icon pe-7s-angle-down pr-0"></i>
                </a>
            </section>
        @endif --}}

        @if (isset($categories->categoryList) && count($categories->categoryList) > 0)
            <section class="widget widget-categories border-bottom mb-1">
                <div class="d-flex justify-content-between"
                     onclick="toggleSection(this, 'category{{ $categories->suggestedCategoryID }}')">
                    <h3 class="widget-title" data-toggle="tooltip" data-placement="top"
                        title="{{ $categories->suggestedCategoryTitle ?? 'Categories' }}">
                        {{ $categories->suggestedCategoryTitle ?? 'Categories' }}
                    </h3>
                    <button class="btn btn-sm toggle-btn m-0 p-0" data-toggle="tooltip" data-placement="top"
                            type="button" title="Expand or Collapse">
                        <i class="filter-btn-icon pe-7s-angle-up"></i>
                    </button>
                </div>
                <div class="filter-section d-block" id="category{{ $categories->suggestedCategoryID }}">
                    @if ($categories->isInitDispLimited)
                        <ul class="d-block list-unstyled fw-normal small mt-3 pb-1"
                            id="show_limited_{{ $categories->suggestedCategoryID }}">
                            @foreach ($categories->initialCategoryList ?? [] as $initialCatkey => $category)
                                <li class="shop-sidebar-checkbox">
                                    <input class="mr-2" type="checkbox" value="{{ $category->seoPath }}"
                                           onchange="changedFilter(this)">
                                    <a href="{{ route('frontend.shop.index', [$category->seoPath, ...$extraQuery]) }}">
                                        {{ $category->name }}
                                        <span class="ml-1">({{ $category->productCount }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <ul class="list-unstyled fw-normal small @if ($categories->isInitDispLimited) d-none @else d-block @endif mt-3 pb-1"
                        id="show_all_{{ $categories->suggestedCategoryID }}">
                        @foreach ($categories->categoryList as $catKey => $category)
                            <li class="shop-sidebar-checkbox">
                                <input class="mr-2" type="checkbox" value="{{ $category->seoPath }}"
                                       onchange="changedFilter(this)">
                                <a href="{{ route('frontend.shop.index', [$category->seoPath, ...$extraQuery]) }}">
                                    {{ $category->name }}
                                    <span class="ml-1">({{ $category->productCount }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @if ($categories->isInitDispLimited)
                        <button class="show_more_less_btn d-block ml-auto" type="button"
                                onclick="toggleShowMoreLess(this, '{{ $categories->suggestedCategoryID }}');">
                            SHOW MORE...
                        </button>
                    @endif
                </div>
            </section>
        @endif

        @if (isset($eaattributes) && count($eaattributes) > 0)
            @foreach ($eaattributes as $key => $attr)
                @php
                    //NULL Attribute Value are escaped from sidebar
                    if (strtolower($attr->name) !== 'price') {
                        if (isset($attr->attributeValueList) && count($attr->attributeValueList) == 1) {
                            $firstAttrValue = current($attr->attributeValueList);
                            if ($firstAttrValue->attributeValue == 'null') {
                                continue;
                            }
                        }
                    }
                @endphp
                <section class="widget widget-categories mb-1">
                    <div class="d-flex justify-content-between"
                         onclick="toggleSection(this, 'attribute_{{ $key }}')">
                        <h3 class="widget-title" data-toggle="tooltip" data-placement="top"
                            title="{{ $attr->name ?? '' }}">
                            {{ $attr->name ?? '' }}
                        </h3>
                        <button class="btn btn-sm m-0 p-0" data-toggle="tooltip" data-placement="top" type="button"
                                title="Expand or Collapse">
                            <i class="pe-7s-angle-up" style="font-size: 26px;color: #000"></i>
                        </button>
                    </div>
                    <div class="filter-section @if ($key > 7) d-none @else d-block @endif"
                         id="attribute_{{ $key }}">
                        @if (strtolower($attr->name) !== 'price')
                            @if ($attr->isInitDispLimited)
                                <ul class="d-block list-unstyled fw-normal small mt-3 pb-1"
                                    id="show_limited_attribute_{{ $key }}">
                                    @foreach ($attr->initialAttributeValueList ?? [] as $initialAttrKey => $colorAttr)
                                        @if ($colorAttr->attributeValue != 'null')
                                            <li
                                                @if (isset($colorAttr->selected) && $colorAttr->selected) class="active shop-sidebar-checkbox"
                                                @else class="shop-sidebar-checkbox" @endif>
                                                <input class="mr-2" type="checkbox" value="{{ $colorAttr->seoPath }}"
                                                       onchange="changedFilter(this)"
                                                       @if (isset($colorAttr->selected) && $colorAttr->selected) checked @endif />
                                                <a
                                                    href="{{ route('frontend.shop.index', [$colorAttr->seoPath, ...$extraQuery]) }}">
                                                    {{ $colorAttr->attributeValue }}<span
                                                        class="ml-1">({{ $colorAttr->productCount }})</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                            <ul class="list-unstyled fw-normal small @if ($attr->isInitDispLimited) d-none @else d-block @endif mt-3 pb-1"
                                id="show_all_attribute_{{ $key }}">
                                @foreach ($attr->attributeValueList ?? [] as $attributeKey => $colorAttr)
                                    @if ($colorAttr->attributeValue != 'null')
                                        <li
                                            @if (isset($colorAttr->selected) && $colorAttr->selected) class="active shop-sidebar-checkbox"
                                            @else class="shop-sidebar-checkbox" @endif>
                                            <input class="mr-2" type="checkbox" value="{{ $colorAttr->seoPath }}"
                                                   onchange="changedFilter(this)"
                                                   @if (isset($colorAttr->selected) && $colorAttr->selected) checked @endif />
                                            <a
                                                href="{{ route('frontend.shop.index', [$colorAttr->seoPath, ...$extraQuery]) }}">
                                                {{ $colorAttr->attributeValue }}<span
                                                    class="ml-1">({{ $colorAttr->productCount }})</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            @if ($attr->isInitDispLimited)
                                <button class="show_more_less_btn d-block ml-auto" type="button"
                                        onclick="toggleShowMoreLess(this, 'attribute_{{ $key }}');">
                                    SHOW MORE...
                                </button>
                            @endif
                        @else
                            @php $priceAttributes =  $attr->attributeValueList ?? [] @endphp
                            <div>
                                <form class="price-range-slider" id="range_slider_224435542"
                                      data-start-min="{{ (float) $priceAttributes[0]->minValue ?? 0 }}"
                                      data-start-max="{{ (float) $priceAttributes[0]->maxValue ?? 0 }}"
                                      data-min="{{ (float) $priceAttributes[0]->minRangeValue ?? 0 }}"
                                      data-max="{{ (float) $priceAttributes[0]->maxRangeValue ?? 0 }}" data-step="1"
                                      method="GET">

                                    <div class="ui-range-slider mx-2 mb-2" id="price_range_slider"></div>

                                    <footer class="ui-range-slider-footer d-flex justify-content-between pt-2">
                                        <div class="ui-range-value-min">$<span></span>
                                            <input name="min" type="hidden">
                                        </div>
                                        <div class="ui-range-value-max">$<span></span>
                                            <input name="max" type="hidden">
                                        </div>
                                    </footer>

                                    {{-- <p class="text-muted mt-3">
                                        Available Product
                                        <span class="product-counter">
                                            ({{ $priceAttributes[0]->productCount }})
                                        </span>
                                    </p> --}}
                                </form>
                            </div>
                        @endif
                    </div>
                </section>
            @endforeach
        @endif
    </aside>
</div>

<script>
    function toggleAllFilters(event, element) {
        event.preventDefault();
        element = $(element);
        if (element.data('current-state') === 'collapsed') {
            $('.filter-section').each(function() {
                var div = $(this);
                div.addClass('d-block');
                if (div.hasClass('d-none')) {
                    div.removeClass('d-none');
                }
            });
            element.data('current-state', 'expanded');
            element.html('COLLAPSE ALL <i class="filter-btn-icon pe-7s-angle-up"></i>');
        } else {
            $('.filter-section').each(function() {
                var div = $(this);
                div.addClass('d-none');
                if (div.hasClass('d-block')) {
                    div.removeClass('d-block');
                }
            });
            element.data('current-state', 'collapsed');
            element.html('EXPAND ALL <i class="filter-btn-icon pe-7s-angle-down"></i>');
        }
    }

    // Debounce function to  make request for data
    let timer;
    const debounce = function(fn, d) {
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(fn, d);
    };

    function toggleSection(element, target_id) {
        var x = $('#' + target_id);
        if (x.hasClass('d-none')) {
            x.addClass('d-block');
            x.removeClass('d-none');
            $(element).find('i:first').removeClass('pe-7s-angle-down');
            $(element).find('i:first').addClass('pe-7s-angle-up');
        } else {
            x.addClass('d-none');
            x.removeClass('d-block');
            $(element).find('i:first').removeClass('pe-7s-angle-up');
            $(element).find('i:first').addClass('pe-7s-angle-down');
        }
    }

    function updateRangeSliderQueryString(key, value) {
        let prev_query_string = '{{ request('ea_server_products') ?? '' }}';
        let search = window.location.search.substring(1);
        if (search && search.length) {
            search = JSON.parse('{"' +
                decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') +
                '"}');
            let ea_server_products = search.ea_server_products;
            if (ea_server_products && ea_server_products.length) {
                ea_server_products = ea_server_products.split('/');
                let filtered_query_string = ea_server_products.filter(ele => {
                    let single_string = ele.toLowerCase();
                    return single_string.startsWith('price') === false;
                });
                prev_query_string = filtered_query_string.join('/');
            }
        }
        let uri = window.location.hash.length > 0 ?
            window.location.origin.concat(window.location.pathname).concat(window.location.hash.substring(1)) :
            window.location.href;
        var re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
        var separator = uri.indexOf('?') !== -1 ? '&' : '?';
        if (uri.match(re)) {
            let new_value = prev_query_string.length > 0 ? prev_query_string.concat('/' + value) : value;
            return uri.replace(re, '$1' + key + '=' + new_value + '$2');
        } else {
            return uri + separator + key + '=' + value;
        }
    }

    function filterMaxMin() {
        //e.preventDefault();
        // let buttonProperty       = document.querySelector("#filter-btn-224523");
        // buttonProperty.innerHTML = 'Please wait....';
        // buttonProperty.disabled  = true;
        let min_range_value =
            {{ !empty($priceAttributes[0]->minRangeValue) ? (float) $priceAttributes[0]->minRangeValue : 0 }};
        let max_range_value =
            {{ !empty($priceAttributes[0]->maxRangeValue) ? (float) $priceAttributes[0]->maxRangeValue : 0 }};
        let current_min_value = parseFloat(document.querySelector('.ui-range-value-min input').value);
        let current_max_value = parseFloat(document.querySelector('.ui-range-value-max input').value);
        let sliderValue = `Price:\${current_min_value}-\${current_max_value}-\${min_range_value}-\${max_range_value}`;
        window.location =
            updateRangeSliderQueryString('ea_server_products', sliderValue);
    }

    function toggleShowMoreLess(element, target_id) {

        var less = $('#show_limited_' + target_id);

        var more = $('#show_all_' + target_id);

        if (less.hasClass('d-none')) {
            less.addClass('d-block');
            less.removeClass('d-none');
            more.addClass('d-none');
            more.removeClass('d-block');
            $(element).html('SHOW MORE...');
        } else {
            less.addClass('d-none');
            less.removeClass('d-block');
            more.addClass('d-block');
            more.removeClass('d-none');
            $(element).text('SHOW LESS...');
        }
    }

    function changedFilter(el) {
        el.nextElementSibling.click();
    }

    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
        var separator = uri.indexOf('?') !== -1 ? '&' : '?';
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + '=' + value + '$2');
        } else {
            return uri + separator + key + '=' + value;
        }
    }

    function priceRangeSliderObserver() {
        var changeCount = 0;

        var priceRangeSlider = document.querySelector('#price_range_slider');

        if (!priceRangeSlider) {
            window.setTimeout(priceRangeSliderObserver, 500);
            return;
        }

        var observer = new MutationObserver(function(event) {
            changeCount++;
            if (changeCount > 1) {
                debounce(filterMaxMin, 1000);
            }
        });

        observer.observe(priceRangeSlider, {
            attributes: true,
            attributeFilter: ['class'],
            childList: false,
            characterData: false,
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            if (window.innerWidth <= 768) {
                return; // Skip height adjustment on mobile view
            }

            let productGridHeight = 0;
            let element = document.getElementsByClassName('x-product-list');
            if (element.length > 0) {
                productGridHeight = element[0].clientHeight;
            }

            element = document.getElementsByClassName('x-shop-toolbar');
            if (element.length > 0) {
                productGridHeight += element[0].clientHeight;
                if (productGridHeight > screen.height) {
                    element = document.getElementsByClassName('x-shop-sidebar');
                    if (element.length > 0) {
                        element[0].style.height = productGridHeight.toString() + 'px';
                        element[0].style.overflowY = 'auto';
                    }
                }
            }
        }, 1000);
    });
</script>

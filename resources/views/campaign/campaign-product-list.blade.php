@if ($showShopToolbar)
    <x-campaign.campaign-product-filter 
    :products="$products"
    :product-view="$productView"
    :per-page="$perPage"
    :sort-by="$sortBy"/>
@endif
<x-product-favorite-list/>
<div {!! $htmlAttributes !!}>
    @if ($productView === 'grid')
        <div class="row">
            @forelse($products ?? [] as $key => $campaign_item)
                <input type="hidden" id="product_code_{{ $key }}" value="{{ $campaign_item->product_code }}"/>
                <input type="hidden" id="product_qty_{{ $key }}" min="0" autocomplete="off"/>

                <div class="col-md-4 col-sm-6 mb-3 px-2">
                    <div class="product-card">
                        @if ($authenticated)
                            <div class="product-badge text-danger">
                                {{ discount_badge_label($campaign_item->pivot->discount, $campaign_item->msrp) }}
                            </div>
                        @endif

                        <a class="product-thumb" href="{{ url('shop/'.$campaign_item->id) }}">
                            <img src="{{ $campaign_item->productImage?->main ?? '' }}" alt="Product">
                        </a>
                        <h3 class="product-title">
                            <a href="{{ url('shop/'.$campaign_item->id) }}">{{ $campaign_item->product_name }}</a></h3>

                        @if ($authenticated)
                            <h4 class="product-price">
                                <del>{{ price_format($campaign_item->msrp) }}</del>
                                {{ price_format($campaign_item->pivot->discount) }}
                                / {{ $campaign_item->uom ?? 'Each' }}
                            </h4>
                            <div class="product-buttons d-flex gap-2 p-3">
                                @if($allowFavorite)
                                    <button
                                        class="btn btn-sm @if($campaign_item->exists_in_favorite) btn-favorite btn-warning @else btn-outline-secondary @endif"
                                        type="button" data-toggle="tooltip"
                                        @if($authenticated)
                                            @if(!$hasPermission)
                                                onclick="@if($campaign_item->exists_in_favorite) removeItemFromCustomerList({{ $campaign_item->favorite_list_id }}, {{ $campaign_item->id }}); @else initCustomerListItemModal(this, '{{ $campaign_item->id }}'); @endif"
                                        @else
                                            data-toast data-toast-type="warning"
                                        data-toast-position="topRight" data-toast-icon="icon-circle-cross"
                                        data-toast-title="Favorites"
                                        data-toast-message="You don't have permission to use this feature"
                                        @endif
                                        @else
                                            data-toast data-toast-type="warning"
                                        data-toast-position="topRight" data-toast-icon="icon-circle-cross"
                                        data-toast-title="Favorites"
                                        data-toast-message="You need to be logged in to use this feature."
                                        @endif
                                        title="@if($campaign_item->exists_in_favorite) Remove from Favorites @else Add to Favorites @endif"
                                    >
                                        <i class="icon-heart"></i>
                                    </button>
                                @endif
                                <div class="align-items-center d-flex" data-toggle="tooltip" title=""
                                     data-original-title="Quantity/{{ $campaign_item->uom ?? 'Each' }}">
                                    <div class="gap-3 count align-items-center p-1 border rounded d-flex">
                                        <span
                                            class="bg-secondary text-dark d-flex align-items-center justify-content-center fw-600 qty-minus"
                                            data-input="#product_qty_{{ $key }}">
                                            <i class="icon-minus"></i>
                                        </span>
                                        <p class="mb-0 mx-2 fw-600">0</p>
                                        <span
                                            class="bg-warning text-white d-flex align-items-center justify-content-center fw-600 qty-plus"
                                            data-input="#product_qty_{{ $key }}">
                                            <i class="icon-plus"></i>
                                        </span>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary btn-block btn-sm"
                                        onclick="addToCartCampaignProduct({{ $key }})">Add to Cart
                                </button>
                            </div>
                        @else
                            <h4 class="product-title">
                                Please login to see the price and availability.
                            </h4>
                        @endif
                    </div>
                </div>
            @empty
                <x-shop-empty-result/>
            @endforelse
        </div>
    @endif

    @if ($productView === 'list')
        <div class="products">
            @forelse($products ?? [] as $key => $campaign_item)
                <input type="hidden" id="product_code_{{ $key }}" value="{{ $campaign_item->product_code }}"/>
                <input type="hidden" id="product_qty_{{ $key }}" min="0" autocomplete="off"/>

                <div class="product-card product-list">
                    <a class="product-thumb" href="{{ url('shop/'.$campaign_item->id) }}">
                        @if ($authenticated)
                            <div class="product-badge text-danger">
                                {{ discount_badge_label($campaign_item->pivot->discount, $campaign_item->msrp) }}
                            </div>
                        @endif

                        <img src="{{ $campaign_item->productImage?->main ?? '' }}" alt="Product">
                    </a>
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="{{ url('shop/'.$campaign_item->id) }}">{{ $campaign_item->product_name }}</a>
                        </h3>

                        @if ($authenticated)
                            <h4 class="product-price">
                                <del>{{ price_format($campaign_item->msrp) }}</del>
                                {{ price_format($campaign_item->pivot->discount) }}
                                / {{ $campaign_item->uom ?? 'Each' }}
                            </h4>
                            <p class="hidden-xs-down">
                                {{ $campaign->description }}
                            </p>
                            <div class="product-buttons d-flex gap-3">
                                <button
                                    class="btn btn-sm @if($campaign_item->exists_in_favorite) btn-favorite btn-warning @else btn-outline-secondary @endif"
                                    type="button" data-toggle="tooltip"
                                    @if($authenticated)
                                        @if(!$hasPermission)
                                            onclick="@if($campaign_item->exists_in_favorite) removeItemFromCustomerList({{ $campaign_item->favorite_list_id }}, {{ $campaign_item->id }}); @else initCustomerListItemModal(this, '{{ $campaign_item->id }}'); @endif"
                                    @else
                                        data-toast data-toast-type="warning"
                                    data-toast-position="topRight" data-toast-icon="icon-circle-cross"
                                    data-toast-title="Favorites"
                                    data-toast-message="You don't have permission to use this feature"
                                    @endif
                                    @else
                                        data-toast data-toast-type="warning"
                                    data-toast-position="topRight" data-toast-icon="icon-circle-cross"
                                    data-toast-title="Favorites"
                                    data-toast-message="You needs to be logged in to use this feature."
                                    @endif
                                    title="@if($campaign_item->exists_in_favorite) Remove from Favorites @else Add to Favorites @endif"
                                >
                                    <i class="icon-heart"></i>
                                </button>
                                <div class="align-items-center cs-w-420 d-flex" data-toggle="tooltip" title=""
                                     data-original-title="Quantity/{{ $campaign_item->uom }}">
                                    <div class="gap-3 count align-items-center p-1 border rounded d-flex">
                                    <span
                                        class="bg-secondary text-dark d-flex align-items-center justify-content-center fw-600 qty-minus"
                                        data-input="#product_qty_{{ $key }}">
                                        <i class="icon-minus"></i>
                                    </span>
                                        <p class="mb-0 mx-2 fw-600">0</p>
                                        <span
                                            class="bg-warning text-white d-flex align-items-center justify-content-center fw-600 qty-plus"
                                            data-input="#product_qty_{{ $key }}">
                                        <i class="icon-plus"></i>
                                    </span>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary btn-sm"
                                        onclick="addToCartCampaignProduct({{ $key }})">
                                    Add to Cart
                                </button>
                            </div>
                        @else
                            <h4 class="product-title">
                                Please login to see the price and availability.
                            </h4>
                        @endif
                    </div>
                </div>
            @empty
                <x-shop-empty-result/>
            @endforelse
        </div>
    @endif
    <div class="d-block">
        {!! $products->links() !!}
    </div>
</div>
@pushOnce('footer-script')
<script>
        $('.qty-plus').on('click', function (e) {
            let target = $(e.currentTarget);
            let qty;
            if(qty = parseInt(target.prev().html())+1) {
                target.prev().html(qty);
                $(target.data('input')).val(qty);
            }
        });

        $('.qty-minus').on('click', function (e) {
            let target = $(e.currentTarget);
            let qty;
            if((qty = parseInt(target.next().html())-1) >= 0) {
                target.next().html(qty);
                $(target.data('input')).val(qty);
            }
        });
</script>
    
@endPushOnce


<script>
    function addToCartCampaignProduct(index) {
        let str_date = "{{ carbon_date($campaign->end_date, 'Y-m-d') }}";

        if (new Date(str_date) < new Date()) {
            ShowNotification('error', 'Cart', "Campaign has been expired.");
            return;
        }

        let attributes = {
            source_type: "Promo",
            source: "{{ $campaign->code }}",
            expiry_date: str_date,
            additional_info: {
                minimum_quantity: 1
            }
        };

        addSingleProductToOrder(index, attributes);
    }
</script>

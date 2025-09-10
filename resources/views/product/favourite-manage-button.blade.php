<button
    {!! $htmlAttributes !!}
    type="button"
    data-toggle="tooltip"
    aria-label="Add to Favorites"
    @if(customer_check())
        @if($hasPermission())
            onclick="@if($alreadyExists) removeItemFromCustomerList({{ $favouriteListId }}, {{ $productId }}); @else initCustomerListItemModal(this, '{{ $productId }}'); @endif"
        @else
            data-toast
            data-toast-type="warning"
            data-toast-position="topRight"
            data-toast-icon="icon-circle-cross"
            data-toast-title="Favorites"
            data-toast-message="You don't have permission to use this feature"
        @endif
    @else
        data-toast
        data-toast-type="warning"
        data-toast-position="topRight"
        data-toast-icon="icon-circle-cross"
        data-toast-title="Favorites"
        data-toast-message="You need to be logged in to access this feature."
    @endif
    title="@if($alreadyExists) Remove from Favorites @else Add to Favorites @endif"
><i class="icon-heart"></i>
</button>

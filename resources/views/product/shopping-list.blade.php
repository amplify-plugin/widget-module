<div {!! $htmlAttributes !!}>
    @php
        $formRoute = route('frontend.favourites.store');
        $types = config('amplify.constant.favorite_list_type', []);
        if (! config('amplify.basic.enable_quick_list', true)) {
            unset($types['quick-list']);
        }
    @endphp
    <button
            class="btn btn-outline-warning btn-sm dropdown-toggle btn-block"
            id="shoppingListDropdown"
            type="button" data-toggle="dropdown" aria-expanded="false"
            onclick="loadOrderListDropdown({{ $productId }}, '{{ $widgetTitle }}');">
        {!! $addLabel !!}
    </button>
    <div class="dropdown-menu"
         style="width: 100%; min-width: 200px !important;"
         aria-labelledby="shoppingListDropdown">
        @if(customer_check() && customer(true)->can('favorites.manage-personal-list'))
            <a href="javascript:void(0);"
               class="dropdown-item align-center"
               onclick="Amplify.addToNewOrderList({{ $productId }}, 'product', 'Shopping List')">
                <i class="icon-plus mr-1"></i> {{ __('Add to new list') }}
            </a>
            <div class="dropdown-divider"></div>
        @endif
        <div class="dropdown-item" id="shopping-list-items-container">
        </div>
    </div>
</div>

@pushonce('footer-script')
    <script>
        function loadOrderListDropdown(productId, title) {

            productId = productId.toString();

            if (!Amplify.authenticated()) {
                Amplify.alert('You need to be logged in to access this feature.', title);
                return;
            }

            $.ajax('{{ route('frontend.favourites.index') }}', {
                beforeSend: () => {
                    $('#shopping-list-items-container').empty();

                    $('#shopping-list-items-container').append(`<p class="dropdown-item-text text-primary text-center mb-2">
                <i class="icon-loader spinner mr-1"></i> Loading...
            </p>`);
                },
                method: 'GET',
                dataType: 'json',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                success: function (response) {
                    $('#shopping-list-items-container').empty();
                    let html = '';

                    $.each(response.data, function (key, value) {
                        html += `<li class="text-primary">
                                    <a href="javascript:void(0);" class="text-decoration-none"
                                    onclick="Amplify.addToExistingOrderList(${value.id}, ${productId}, 'product', '${title}')">
                                    ${value.name} (${value.list_type})
                                    </a>
                                </li>`;
                    });

                    $('#shopping-list-items-container').append(`<ul class="mb-0">${html}</ul>`);
                },
                error: function (xhr) {
                    Amplify.alert(xhr.responseJSON?.message || xhr.statusText, title);
                }
            });
        }
    </script>
@endpushonce

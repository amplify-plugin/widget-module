<div {!! $htmlAttributes !!}>
    @php
        $formRoute = route('frontend.favourites.store');
        $types = config('amplify.constant.favorite_list_type', []);
        if (! config('amplify.basic.enable_quick_list', true)) {
            unset($types['quick-list']);
        }
    @endphp

    <div
        class="dropdown shopping-dropdown"
        data-logged-in="{{ customer_check() ? 'true' : 'false' }}"
        data-product-id="{{ $productId }}">
        <button
            class="btn btn-outline-primary btn-sm dropdown-toggle"
            type="button"
            id="shoppingListDropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            {!! $addLabel !!}
        </button>
        <div class="dropdown-menu" aria-labelledby="shoppingListDropdown">
            <a href="javascript:void(0);"
               class="dropdown-item create-shopping"
               onclick="Amplify.createShippingList({{ $productId }}, 'product', '')">
                {{ __('Add to new list') }}
            </a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">{{ __('Existing Lists') }}:</div>
            <div class="shopping-list-items-container"></div>
        </div>
    </div>

    @pushonce('html-default')
        <div
            class="modal fade" id="shopping-list-modal"
            data-backdrop="static" data-keyboard="false"
            tabindex="-1" role="dialog"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create New Shopping List</h4>
                        <button class="close close-shopping-button" type="button" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="shoppingListForm" action="{{ $formRoute }}" method="POST"
                          onsubmit="addItemToShoppingList(this, event); return false;">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="shopping_product_id" name="product_id">
                            <input type="hidden" id="shopping_product_qty" name="product_qty">
                            <input type="hidden" id="shopping_list_id" name="list_id">
                            <input type="hidden" name="is_shopping_list" value="1">

                            <div id="new-shopping-list">
                                <div class="form-group">
                                    <label for="shopping_list_name">Name<span class="text-danger">*</span></label>
                                    <input name="list_name" class="form-control" type="text" id="shopping_list_name"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="shopping_list_type">List Type<span class="text-danger">*</span></label>
                                    <select class="form-control custom-select" name="list_type" id="shopping_list_type"
                                            required>
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="shopping_list_desc">Description</label>
                                    <textarea name="list_desc" class="form-control" id="shopping_list_desc"
                                              rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="icon-disc"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endpushonce

    @pushonce('footer-script')
        <script>
            $(function () {
                // Notification helper
                function notify(type, title, msg) {
                    ShowNotification(type, title, msg);
                }

                // Fetch & render lists for a specific dropdown
                function fetchLists(dropdown) {
                    let container = dropdown.find('.shopping-list-items-container');
                    $.ajax({
                        url: '/favourites',
                        method: 'GET',
                        dataType: 'json',
                        success(response) {
                            container.empty();
                            const groups = response.data; // { Global: [], Personal: [] }
                            let hasAny = false;

                            $.each(groups, (groupName, items) => {
                                if (!items.length) return;
                                hasAny = true;
                                items.forEach(item => {
                                    $('<a>')
                                        .addClass('dropdown-item list-item')
                                        .attr('href', '#')
                                        .data('listId', item.id)
                                        .data('listType', groupName.toLowerCase())
                                        .html(item.name + ' <small>(' + groupName + ')</small>')
                                        .appendTo(container);
                                });
                            });

                            if (!hasAny) {
                                notify('warning', 'Shopping List', 'No shopping lists available.');
                            }
                        },
                        error(err) {
                            notify('error', 'Shopping List', err.responseJSON?.message || 'Failed to load lists');
                        }
                    });
                }

                // 1) Toggle this dropdown
                $(document).on('click', '.shopping-dropdown .dropdown-toggle', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    let dropdown = $(this).closest('.shopping-dropdown');
                    let menu = dropdown.find('.dropdown-menu');
                    let isLoggedIn = dropdown.data('loggedIn');

                    if (menu.hasClass('show')) {
                        dropdown.removeClass('show');
                        menu.removeClass('show');
                        return;
                    }

                    if (!isLoggedIn) {
                        return notify('warning', 'Shopping List', 'You need to be logged in to access this feature.');
                    }

                    fetchLists(dropdown);
                    dropdown.addClass('show');
                    menu.addClass('show');
                });

                // 2) Close all dropdowns when clicking outside
                $(document).on('click', function (e) {
                    if (!$(e.target).closest('.shopping-dropdown').length) {
                        $('.shopping-dropdown.show')
                            .removeClass('show')
                            .find('.dropdown-menu.show')
                            .removeClass('show');
                    }
                });

                // 3) Click existing list → add item
                $(document).on('click', '.shopping-list-items-container .list-item', function (e) {
                    e.preventDefault();

                    let dropdown = $(this).closest('.shopping-dropdown');
                    let isLoggedIn = dropdown.data('loggedIn');
                    if (!isLoggedIn) {
                        return notify('warning', 'Shopping List', 'You need to be logged in to access this feature.');
                    }

                    let listId = $(this).data('listId');
                    let listType = $(this).data('listType');
                    let productId = dropdown.data('productId');

                    $('#shopping_list_id').val(listId);
                    $('#shopping_list_type').val(listType);
                    $('#shopping_product_id').val(productId);
                    $('#shopping_product_qty').val(1);

                    addItemToShoppingList(document.getElementById('shoppingListForm'), e);
                });

                // 4) “Create new” → open modal
                $(document).on('click', '.shopping-dropdown .create-shopping-list', function (e) {
                    e.preventDefault();

                    let dropdown = $(this).closest('.shopping-dropdown');
                    let isLoggedIn = dropdown.data('loggedIn');
                    if (!isLoggedIn) {
                        return notify('warning', 'Shopping List', 'You need to be logged in to access this feature.');
                    }

                    let productId = dropdown.data('productId');

                    // reset form fields
                    $('#shopping_list_name').val('');
                    $('#shopping_list_type').val('global');
                    $('#shopping_list_desc').val('');
                    $('#shopping_product_id').val(productId);
                    $('#shopping_product_qty').val(1);
                    $('#shopping_list_id').val('');

                    $('#shopping-list-modal').modal();
                    // close any open dropdown
                    $('.shopping-dropdown.show')
                        .removeClass('show')
                        .find('.dropdown-menu.show')
                        .removeClass('show');
                });

                $('#shopping-list-modal').on('shown.bs.modal', function (event) {
                    $("#shopping_list_name").trigger('focus');
                })

                // 5) Global form-submit helper
                window.addItemToShoppingList = function (form, ev) {
                    ev.preventDefault();
                    const data = {};
                    $(form).serializeArray().forEach(f => data[f.name] = f.value);

                    $.ajax({
                        url: $(form).attr('action'),
                        method: 'POST',
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        data,
                        success(res) {
                            const isNew = !data.list_id;
                            const message = isNew
                                ? 'Shopping list created and product added successfully.'
                                : 'Product added to your shopping list successfully.';
                            notify('success', 'Shopping List', message);
                            if (res.status) {
                                $('#shopping-list-modal').modal('hide');
                                // refresh only the relevant dropdown
                                let dropdown = $('.shopping-dropdown[data-product-id="' + data.product_id + '"]');
                                fetchLists(dropdown);
                            }
                        },
                        error(err) {
                            notify('error', 'Shopping List', err.responseJSON?.message || 'Could not save list');
                        }
                    });
                };
            });
        </script>
    @endpushonce
</div>

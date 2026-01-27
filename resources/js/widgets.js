import Swal from 'sweetalert2';

window.swal = Swal.mixin({
    theme: 'bootstrap-4-light',
    showCloseButton: true,
    // buttonsStyling: false,
    customClass: {
        confirmButton: 'btn btn-danger',
        cancelButton: 'btn btn-outline-secondary',
    }
});

window.Amplify = {
    cartUrl: () => '/carts',
    cartItemRemoveUrl: () => '/carts/remove/cart_item_id',
    cartItemUpdateUrl: () => '/carts/update/cart_item_id',
    maxCartItemQuantity: () => 9999999999,
    favouritesCreateUrl: () => '/favourites',
    orderListUrl: () => '/order-lists',

    isHtml(text) {
        return new DOMParser()
            .parseFromString(text, 'text/html')
            .body.children.length > 0;
    },

    /**
     * The function validate if the customer is logged in
     * return bool
     */
    authenticated() {
        const meta = document.querySelector('meta[name="authenticated"]');
        return meta ? meta.content === 'true' : false;
    },

    /**
     * This function fires a popup notification on top right corner
     * @param type
     * @param message
     * @param title
     * @param options
     */
    notify(type = 'info', message = 'Your message here!', title = 'Notification', options = {}) {
        let allowedTypes = ['success', 'error', 'warning', 'info', 'question'];
        if (!allowedTypes.includes(type)) {
            alert(`The ${type} type is not allowed. Allowed types: ${allowedTypes}`);
        }
        const toast = window.swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (t) => {
                t.onmouseenter = Swal.stopTimer;
                t.onmouseleave = Swal.resumeTimer;
            },
            padding: '0.25em 0.75em',
        });

        toast.fire({
            icon: type,
            title: title,
            html: message,
            ...options
        });
    },

    /**
     * The function fires a confirmation popup that ask for confirmation
     * and trigger a action when confirmed.
     * @param question
     * @param title
     * @param confirmBtnText
     * @param options
     * @returns {Promise<SweetAlertResult<Awaited<any>>>}
     */
    confirm(question = 'You won\'t be able to revert this!', title = 'Are you sure?', confirmBtnText = '', options = {}) {
        const confirmAlert = window.swal.mixin({
            icon: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            padding: '1em',
        });

        return confirmAlert.fire({
            title: title,
            html: question,
            confirmButtonText: confirmBtnText,
            ...options
        });
    },

    alert(message = 'This action is not allowed', title = 'Alert', options = {}) {
        return this.confirm(message, title, '', {
            icon: 'warning',
            showConfirmButton: true,
            showLoaderOnConfirm: false,
            showCancelButton: false,
            confirmButtonText: 'Okay',
            customClass: {
                confirmButton: 'btn btn-outline-secondary'
            },
            willOpen: () => document.querySelector('.swal2-actions').style.justifyContent = 'center'
        })
    },

    /**
     * This function handle the custom part number add, update and remove operation.
     *
     * @param element
     * @param inputId
     */
    syncCustomPartNumber(element, inputId) {
        const input = document.getElementById(inputId);
        const button = element;
        const actionLink = button.dataset.route;
        const productId = button.dataset.productId;
        const oldValue = input.dataset.current.toString();
        const uom = input.dataset.uom.toString();
        const newValue = input.value.toString();

        if (!this.authenticated()) {
            this.notify('warning', 'You need to be logged in to access this feature.', 'Customer Part Number');
            return;
        }

        if (!oldValue && !newValue) {
            this.notify('warning', 'This field is required', 'Customer Part Number');
            return;
        }

        //current exist and new empty
        if (oldValue) {
            this.confirm('Are you sure you want to remove this part number?',
                'Customer Part Number', 'Remove', {
                    preConfirm: async () => {
                        return await $.ajax({
                            url: actionLink,
                            type: 'DELETE',
                            data: {
                                product_id: productId,
                                customer_product_uom: uom,
                                customer_product_code: oldValue,
                            },
                            success: function (result) {
                                input.dataset.current = false;
                                return result;
                            },
                            error: function (xhr) {
                                Swal.showValidationMessage(xhr.responseJSON.message ?? xhr.statusText);
                                Swal.hideLoading();
                            },
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
                .then(function (result) {
                    if (result.isConfirmed) {
                        Amplify.notify('warning', result.value.message, 'Customer Part Number');
                        input.value = '';
                        input.dataset.current = '';
                        button.innerHTML = 'Add';
                    }
                }).catch((error) => console.error(error));
        } else {
            $.ajax({
                url: actionLink,
                type: 'POST',
                data: {
                    product_id: productId,
                    customer_product_uom: uom,
                    customer_product_code: newValue,
                },
                success: function (response) {
                    Amplify.notify('success', response.message, 'Customer Part Number');
                    input.dataset.current = newValue;
                    button.innerHTML = 'Delete';
                },
                error: function (xhr) {
                    Amplify.alert(xhr.responseJSON.message ?? xhr.statusText, 'Customer Part Number');
                }
            });
        }
    },

    /**
     * This function handle the clear cart option.
     * @param element
     */
    clearCart(element) {
        const actionLink = element.dataset.actionLink;
        this.confirm('Are you sure to remove all items from shopping cart?',
            'Cart', 'Confirm', {
                preConfirm: async () => {
                    return await $.ajax({
                        url: actionLink,
                        type: 'DELETE',
                        data: {},
                        header: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        success: function (result) {
                            return result;
                        },
                        error: function (xhr) {
                            Swal.showValidationMessage(xhr.responseJSON.message ?? xhr.statusText);
                            Swal.hideLoading();
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, 'Cart');
                    setTimeout(() => {
                        const {origin, pathname} = window.location;
                        window.location.replace(origin + pathname);
                    }, 2000);
                }
            }).catch((error) => console.error(error));
    },

    removeCartItem(cartItemId, redirect = true) {
        const actionLink = this.cartItemRemoveUrl().replace('cart_item_id', cartItemId);
        this.confirm('Are you sure to remove this item from cart?',
            'Cart', 'Remove', {
                preConfirm: async () => {
                    return await $.ajax({
                        url: actionLink,
                        type: 'DELETE',
                        data: {},
                        header: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        success: function (result) {
                            return result;
                        },
                        error: function (xhr) {
                            Swal.showValidationMessage(xhr.responseJSON.message ?? xhr.statusText);
                            Swal.hideLoading();
                        },
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, 'Cart');
                    if (redirect) {
                        setTimeout(() => {
                            const {origin, pathname} = window.location;
                            window.location.replace(origin + pathname);
                        }, 2000);
                        return;
                    }

                    Amplify.loadCartDropdown();
                }
            })
            .catch((error) => console.error(error));
    },

    updateCartItem(e, target, cartItemId) {
        e.preventDefault();
        e.stopPropagation();

        if (!this.handleQuantityChange(target, 'input')) {
            return;
        }

        const targetElement = document.querySelector(target);
        const quantity = targetElement.value;

        const actionLink = this.cartItemUpdateUrl().replace('cart_item_id', cartItemId);
        const warehouseCode = targetElement.dataset.warehouseCode;
        const productCode = targetElement.dataset.productCode;

        swal.fire({
            title: 'Cart',
            icon: 'warning',
            backdrop: true,
            showCancelButton: false,
            text: `Updating ${productCode}'s information in cart...`,
            confirmButtonText: 'Okay',
            customClass: {
                confirmButton: 'btn btn-outline-secondary'
            },
            willOpen: () => document.querySelector('.swal2-actions').style.justifyContent = 'center',
            didOpen: () => {
                return $.ajax({
                    beforeSend: () => Swal.showLoading(),
                    url: actionLink,
                    type: 'PATCH',
                    data: {
                        'quantity': quantity,
                        product_warehouse_code: warehouseCode,
                    },
                    header: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    success: function (result) {
                        if (result.success) {
                            Swal.close();
                            Amplify.notify('success', result.message, 'Cart');
                            setTimeout(() => {
                                const {origin, pathname} = window.location;
                                window.location.replace(origin + pathname);
                            }, 2000);
                        }
                    },
                    error: function (xhr) {
                        Swal.showValidationMessage(xhr.responseJSON.message ?? xhr.statusText);
                        Swal.hideLoading();
                    },
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    },

    /**
     * This function load the current items and display in cart summary
     * table
     */
    async loadCartSummary() {
        await $.ajax({
            beforeSend: function () {
                $('#cart-item-summary').html(
                    `<tr>
                        <td colspan='50' class="text-center padding-top-1x bg-transparent">
                                <img src='/assets/img/preloader.gif' alt="preloader"/>
                         </td>
                  </tr>`,
                );
            },
            url: this.cartUrl(),
            method: 'GET',
            dataType: 'json',
            headers: {
                Accept: 'application/json'
            },
            success: function (response) {
                $('#cart-item-summary').empty();
                if (response.data.products.length > 0) {
                    $(response.data.products).each(function (index, item) {
                        let layout = Amplify.renderCartSummaryItemRow(item, index);
                        $('#cart-item-summary').append(layout);
                    });
                }
                $('#order-subtotal').text(response.data.sub_total);
            },
            error: function (xhr) {
                Amplify.alert(xhr.responseJSON.message ?? xhr.statusText, 'Cart');
            },
        });
    },

    renderCartSummaryItemRow(product, index) {
        let template = $('#cart-single-item-template').html();

        let mapper = {
            '{serial}': index + 1,
            '{cart_item_id}': product.id,
            '{code}': product.product_code,
            '{warehouse}': product.warehouse_name,
            '{warehouse_code}': product.product_warehouse_code,
            '{name}': product.product_name,
            '{description}': product.short_description,
            '{manufacturer}': product.manufacturer_name,
            '{quantity}': product.qty,
            '{unit_price}': product.price,
            '{subtotal}': product.subtotal,
            '{image}': product.product_image,
            '{uom}': product.uom,
            '{actions}': JSON.stringify(product),
            '{url}': product.url,
            '{min_qty}': product?.additional_info?.minimum_quantity ?? 1,
            '{qty_interval}': product?.additional_info?.quantity_interval ?? 1,
            '{note}': product.note,
            '{ncnr_msg}': product.ncnr_msg,
            '{ship_restriction}': product.ship_restriction,
            '{error}': product.error,
        };

        return stringReplaceArray(Object.keys(mapper), Object.values(mapper), template);
    },

    /**
     * This function load the current cart items from backend
     * @returns {Promise<void>}
     */
    async loadCartDropdown() {
        await $.ajax(this.cartUrl(), {
            beforeSend: () => Amplify.renderEmptyCart('/assets/img/preloader.gif'),
            method: 'GET',
            dataType: 'json',
            headers: {
                Accept: 'application/json'
            },
            success: function (res) {
                $('.cart-dropdown').empty();
                if (res.data.products.length > 0) {
                    $('.total_cart_items').text(res.data.item_count);
                    res.data.products.forEach((product, index) => {
                        $('.cart-dropdown').append(`
                        <div class="dropdown-product-item" id="cart_products_${index}">
                        <span class="dropdown-product-remove" onclick="Amplify.removeCartItem(${product.id})">
                            <i class="icon-cross"></i>
                        </span>
                        <a class="dropdown-product-thumb"
                           href="${product.url}">
                            <img src="${product.product_image}"
                                onerror="this.onerror=null; this.src=FALLBACK_IMG_SRC;"
                                alt="Product">
                        </a>
                        <div class="dropdown-product-info">
                            <a class="dropdown-product-title" href="${product.url}">
                                ${product.product_name}
                            </a>
                            <span class="dropdown-product-details">${product.qty} x ${product.price} = ${product.subtotal}</span>
                        </div>
                    </div>`);
                    });
                    $('.total_cart_amount').text(res.data.sub_total);
                } else {
                    Amplify.renderEmptyCart();
                }
            },
            error: function (xhr, status) {
                Amplify.renderEmptyCart();
            }
        });
    },

    renderEmptyCart(imageUrl = '/assets/img/empty_cart.png') {
        $('.total_cart_items').text(0);
        $('.total_cart_amount').text('$0.00');

        $('.cart-dropdown').append(`
        <div id="cart_items" class="text-center" style="min-height: 250px;">
            <img src="${imageUrl}"
                style="max-width: 160px !important;"
                class="img-fluid mt-5" alt="No items in cart"
            />
        </div>
    `);
    },

    /**
     * The function handle quantity update and quantity value validation
     *
     * @param target
     * @param action
     */
    handleQuantityChange(target, action) {

        const targetElement = document.querySelector(target);

        const productCode = targetElement.dataset.productCode;

        if (!['decrement', 'input', 'increment'].includes(action)) {
            this.alert(`Invalid Quantity Change action [${action}].`, 'Cart');
        }

        if (!targetElement) {
            this.alert(`Target input element not found in ${target}`, 'Cart');
            return false;
        }

        targetElement.max = this.maxCartItemQuantity();

        const minOrderQty = parseFloat(targetElement.dataset.minOrderQty);

        if (!minOrderQty) {
            this.alert(`Target input element [${target}] doesn't have "data-min-order-qty" attribute set or is empty.`, 'Cart');
            return false;
        }

        targetElement.min = minOrderQty;

        const qtyInterval = parseFloat(targetElement.dataset.qtyInterval);

        if (!qtyInterval) {
            this.alert(`Target input element [${target}] doesn't have "data-qty-interval" attribute set or is empty.`, 'Cart');
            return false;
        }

        targetElement.step = qtyInterval;

        let quantity = parseFloat(targetElement.value);

        switch (action) {

            case 'decrement' : {
                let newValue = quantity - qtyInterval;
                if (newValue < minOrderQty) {
                    this.alert(
                        `Product ${productCode} requires a minimum order quantity of ${minOrderQty}. You entered ${newValue}.`,
                        'Cart');
                    return false;
                }
                targetElement.value = newValue;
                break;
            }

            case 'increment' : {
                let newValue = quantity + qtyInterval;
                if (newValue > this.maxCartItemQuantity()) {
                    this.alert(
                        `Product ${productCode} requires a maximum order quantity of ${this.maxCartItemQuantity()}. You entered ${newValue}.`,
                        'Cart');
                    return false;
                }
                targetElement.value = newValue;
                break;
            }

            default : {
                if (quantity < minOrderQty) {
                    this.alert(
                        `Product ${productCode} requires a minimum order quantity of ${minOrderQty}. You entered ${quantity}.`,
                        'Cart');
                    targetElement.value = minOrderQty;
                    return false;
                }

                if (quantity > this.maxCartItemQuantity()) {
                    this.alert(
                        `Product ${productCode} requires a maximum order quantity of ${this.maxCartItemQuantity()}. You entered ${quantity}.`,
                        'Cart');
                    targetElement.value = minOrderQty;
                    return false;
                }
                break;
            }
        }

        return true;
    },

    /**
     * The function create a shopping list from product and cart and order and invoice, etc.
     * @param sourceId
     * @param source
     * @param title
     */
    addToNewOrderList(sourceId, source = 'product', title = 'Order List') {

        if (!this.authenticated()) {
            this.alert('You need to be logged in to access this feature.', title);
            return;
        }

        this.confirm('Create a new ' + title.toLowerCase() + ' & add item on it',
            title, 'Save', {
                icon: undefined,
                input: "text",
                inputPlaceholder: 'Enter name',
                inputAttributes: {
                    required: true,
                    max: "255",
                    maxlength: "255",
                    autocorrect: "on"
                },
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-secondary'
                },
                preConfirm: async () => {
                    try {
                        let payload = {
                            type: source,
                            list_id: null,
                            list_name: $('#swal2-input').val(),
                            list_type: $('#swal2-select').val(),
                            list_desc: $('#swal2-textarea').val(),
                            title: title
                        };

                        payload[source + '_id'] = sourceId;

                        return await $.ajax({
                            url: Amplify.orderListUrl(),
                            type: 'POST',
                            data: JSON.stringify(payload),
                            contentType: 'application/json; charset=UTF-8',
                            headers: {
                                Accept: 'application/json'
                            },
                            success: function (result) {
                                return result;
                            },
                            error: function (xhr) {
                                Swal.showValidationMessage(xhr.responseJSON.message ?? xhr.statusText);
                                Swal.hideLoading();
                            },
                        });
                    } catch (error) {
                        return false;
                    }
                },
                allowOutsideClick: () => !Swal.isLoading(),
                willOpen: () => {
                    $.get('/api/list-types', (result) => {
                        $.each(result.data, function (index, item) {
                            $('#swal2-select').append('<option value="' + index + '">' + item + '</option>');
                        });
                    })
                },
                didOpen: function () {
                    $('#swal2-select').css({
                        'display': 'flex',
                    }).addClass('swal2-input');

                    $('#swal2-textarea').css('display', 'flex').attr('placeholder', 'Enter description');
                }
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, title);
                }
            }).catch((error) => console.error(error));

        return true;
    },

    /**
     *
     * The function add a item(product/cart/order/invoice) to an exists order list
     * @param listId
     * @param sourceId
     * @param source
     * @param title
     */
    addToExistingOrderList(listId, sourceId, source = 'product', title = 'Order List') {

        if (!this.authenticated()) {
            this.alert('You need to be logged in to access this feature.', title);
            return;
        }

        this.confirm('Add item to existing ' + title.toLowerCase(),
            title, 'Save', {
                icon: undefined,
                input: "text",
                inputPlaceholder: 'Enter Quantity',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-secondary'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return "The quantity is required";
                    }

                    if (isNaN(value)) {
                        return "The quantity is not a number";
                    }

                    if (Number(value) <= 0) {
                        return "The quantity cannot to be less than 0";
                    }

                    if (Number(value) >= this.maxCartItemQuantity()) {
                        return "The quantity cannot to be greater than " + this.maxCartItemQuantity();
                    }
                },
                preConfirm: async function (value) {
                    try {
                        let payload = {
                            type: source,
                            list_id: listId,
                            is_shopping_list: 1,
                            list_type: null,
                            title: title
                        };

                        payload[source + '_id'] = sourceId;

                        if (source === 'product') {
                            payload['product_qty'] = value;
                        }

                        return await $.ajax({
                            url: Amplify.orderListUrl(),
                            type: 'POST',
                            data: JSON.stringify(payload),
                            contentType: 'application/json; charset=UTF-8',
                            headers: {
                                Accept: 'application/json'
                            },
                            success: function (result) {
                                return result;
                            },
                            error: function (xhr) {
                                Swal.showValidationMessage(xhr.responseJSON.message ?? xhr.statusText);
                                Swal.hideLoading();
                            },
                        });
                    } catch (error) {
                        return false;
                    }
                },
                allowOutsideClick: () => !Swal.isLoading(),
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, 'Shopping List');
                }
            }).catch((error) => console.error(error));

        return true;
    },

    manageOrderList(element, title, id = null) {
        this.confirm(id == null ? 'Create a new ' : 'Update existing ' + title.toLowerCase(),
            title, 'Save', {
                icon: undefined,
                input: "text",
                inputPlaceholder: 'Enter name',
                inputAttributes: {
                    required: true,
                    max: "255",
                    maxlength: "255",
                    autocorrect: "on"
                },
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-secondary'
                },
                preConfirm: async () => {
                    try {
                        return await $.ajax(element.dataset.action, {
                            type: 'PATCH',
                            data: JSON.stringify({
                                name: $('#swal2-input').val(),
                                list_type: $('#swal2-select').val(),
                                description: $('#swal2-textarea').val(),
                                title: title
                            }),
                            contentType: 'application/json; charset=UTF-8',
                            headers: {
                                Accept: 'application/json'
                            },
                            success: (result) => result,
                            error: function (xhr) {
                                Swal.showValidationMessage(xhr.responseJSON?.message ?? xhr.statusText);
                                Swal.hideLoading();
                            },
                        });
                    } catch (err) {
                        return false;
                    }
                },

                allowOutsideClick: () => !Swal.isLoading(),

                willOpen: function () {
                    $.get('/api/list-types', (result) => {
                        $.each(result.data, function (index, item) {
                            $('#swal2-select').append('<option value="' + index + '">' + item + '</option>');
                        });
                    }).then(() => {
                        $.ajax(element.dataset.action, {
                            type: 'GET',
                            contentType: 'application/json; charset=UTF-8',
                            headers: {
                                Accept: 'application/json'
                            },
                            success: function (result) {
                                let orderList = result.data;
                                $('#swal2-textarea').val(orderList.description).trigger('change');
                                $('#swal2-input').val(orderList.name).trigger('change');
                                $('#swal2-select').val(orderList.list_type).trigger('change');
                            }
                        });
                    });
                },

                didOpen: function () {
                    $('#swal2-select').css({
                        'display': 'flex',
                    }).addClass('swal2-input');

                    $('#swal2-textarea').css('display', 'flex').attr('placeholder', 'Enter description');
                }
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, title);
                }
                setTimeout(() => {
                    const {origin, pathname} = window.location;
                    window.location.replace(origin + pathname);
                }, 2000);
            });
    },

    async addSingleItemToCart(cartElement, quantityTarget, extras = {}) {

        let defaultContent = cartElement.innerHTML;
        let quantityElement = document.querySelector(quantityTarget);

        cartElement.disabled = true;
        cartElement.innerHTML = '<i class="icon-loader spinner"></i> Processing...';

        if (this.handleQuantityChange(quantityTarget, 'input')) {

            let warehouse = cartElement.dataset.warehouse;
            let options = JSON.parse(cartElement.dataset.options);

            let cartItem = {
                product_code: options.code,
                product_warehouse_code: warehouse,
                qty: quantityElement.value,
            }

            // if (typeof options.source_type != 'undefined') {
            //     cartItem.source_type = options.source_type;
            // }
            //
            // if (typeof options.source_type != 'undefined') {
            //     cartItem.source_type = options.source_type;
            // }

            await $.ajax(this.cartUrl(), {
                beforeSend: () => Amplify.renderEmptyCart('/assets/img/preloader.gif'),
                method: 'POST',
                dataType: 'json',
                data: {
                    products: [cartItem]
                },
                headers: {
                    Accept: 'application/json',
                    ContentType: 'application/json',

                },
                success: function (res) {
                    Amplify.notify('success', res.message, 'Cart');
                    Amplify.loadCartDropdown();
                },
                error: function (xhr) {
                    Amplify.alert(xhr.responseJSON?.message ?? 'Something Went Wrong. PLease try again later.', 'Cart');
                }
            }).always(function () {
                cartElement.innerHTML = defaultContent;
                cartElement.disabled = false;
            });
        }

        cartElement.innerHTML = defaultContent;
        cartElement.disabled = false;
    },

    addMultipleItemToCart(cartElement, formTarget, extras = {}) {

        let defaultContent = cartElement.innerHTML;

        cartElement.disabled = true;
        cartElement.innerHTML = '<i class="icon-loader spinner"></i> Processing...';

        swal.fire({
            title: 'Cart',
            icon: 'info',
            backdrop: true,
            showCancelButton: false,
            text: `Adding Items to Cart...`,
            confirmButtonText: 'Okay',
            customClass: {
                confirmButton: 'btn btn-outline-secondary'
            },
            willOpen: () => document.querySelector('.swal2-actions').style.justifyContent = 'center',
            didOpen: async () => {
                return await $.ajax(this.cartUrl(), {
                    beforeSend: () => {
                        Swal.showLoading();
                        $('tr span[id^="product-"][id$="-error"]').each(function () {
                            this.empty();
                        });
                    },
                    method: 'POST',
                    data: $(`form${formTarget}`).serialize(),
                    processData: false,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {
                        if (response.success) {
                            Amplify.notify('success', response.message, 'Cart');
                            setTimeout(() => {
                                const {origin, pathname} = window.location;
                                window.location.replace(origin + pathname);
                            }, 2000);
                        }
                    }, error: function (xhr) {
                        let response = xhr.responseJSON ?? {};
                        if (xhr.status === 400) {
                            $.each(response.errors, function (key, messages) {
                                let message = messages.join('<br>');
                                $(`input#product-code-${key}`).addClass('is-invalid');
                                $(`#product-${key}-error`).html(message);
                            })
                        }
                        Swal.showValidationMessage(response.message);
                        Swal.hideLoading();
                    }
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then(function () {
            cartElement.innerHTML = defaultContent;
            cartElement.disabled = false;
        });

        cartElement.innerHTML = defaultContent;
        cartElement.disabled = false;
    },

    /**
     * This confirmation modal only sent request on DELETE method
     * also follow the standard api response.
     *
     * @param target
     * @param title
     * @param payload
     * @param redirect
     * @param question
     */
    deleteConfirmation(target, title, payload = {}, redirect = true, question = 'Are you sure to delete this item?') {
        let actionLink = target.dataset.action;

        if (!actionLink) {
            this.alert('There is no action link in the target element.<br>Please add `data-action` attribute to the target element.');
            return;
        }

        this.confirm(question,
            title, 'Delete', {
                preConfirm: async () => {
                    return await $.ajax({
                        url: actionLink,
                        type: 'DELETE',
                        dataType: 'json',
                        data: payload,
                        header: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        success: function (result) {
                            return result;
                        },
                        error: function (xhr) {
                            Swal.showValidationMessage(xhr.responseJSON.message ?? xhr.statusText);
                            Swal.hideLoading();
                        },
                    });
                },
                allowOutsideClick: () => !window.swal.isLoading()
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, title);
                    if (redirect) {
                        setTimeout(() => {
                            const {origin, pathname} = window.location;
                            window.location.replace(origin + pathname);
                        }, 2000);
                    }
                }
            })
            .catch((error) => console.error(error));
    }
}

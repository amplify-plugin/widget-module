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
            padding: '0.25em 0.75em',
        });

        toast.fire({
            icon: type,
            title: title,
            text: message,
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
            text: question,
            confirmButtonText: confirmBtnText,
            ...options
        });
    },

    alert(message = 'This action is not allowed', title = 'Alert', options = {}) {
        return this.confirm(message, title, '', {
            icon: 'warning',
            showConfirmButton: false,
            showLoaderOnConfirm: false,
            cancelButtonText: 'Okay',
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
                    preConfirm: async function () {
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                url: actionLink,
                                type: 'DELETE',
                                data: {
                                    product_id: productId,
                                    customer_product_uom: uom,
                                    customer_product_code: oldValue,
                                },
                                success: function (result) {
                                    input.dataset.current = false;
                                    resolve(result);
                                },
                                error: function (xhr, status, err) {
                                    let response = JSON.parse(xhr.responseText);
                                    Swal.showValidationMessage(response.message);
                                    reject(false);
                                },
                            });
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
                });
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
                error: function (xhr, status, err) {
                    Amplify.notify('error', xhr.response.message, 'Customer Part Number');
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
                preConfirm: async function () {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: actionLink,
                            type: 'DELETE',
                            data: {},
                            header: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            success: function (result) {
                                resolve(result);
                            },
                            error: function (xhr, status, err) {
                                let response = JSON.parse(xhr.responseText);
                                Swal.showValidationMessage(response.message);
                                reject(false);
                            },
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, 'Cart');
                    setTimeout(() => window.location.reload(), 2500)
                }
            });
    },

    async removeCartItem(cartItemId, redirect = true) {
        const actionLink = this.cartItemRemoveUrl().replace('cart_item_id', cartItemId);
        this.confirm('Are you sure to remove this item from cart?',
            'Cart', 'Remove', {
                preConfirm: async function () {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: actionLink,
                            type: 'DELETE',
                            data: {},
                            header: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            success: function (result) {
                                resolve(result);
                            },
                            error: function (xhr, status, err) {
                                let response = JSON.parse(xhr.responseText);
                                Swal.showValidationMessage(response.message);
                                reject(false);
                            },
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, 'Cart');
                    if (redirect) {
                        setTimeout(() => window.location.reload(), 2500)
                        return;
                    }

                    Amplify.loadCartDropdown();
                }
            });
    },

    async updateCartItem(e, target, cartItemId) {
        e.preventDefault();
        e.stopPropagation();

        const targetElement = $(target);
        const qty = targetElement.val();

        const actionLink = this.cartItemUpdateUrl().replace('cart_item_id', cartItemId);
        const warehouseCode = targetElement.data('warehouse-code');

        $.ajax({
            url: actionLink,
            type: 'PATCH',
            data: {
                quantity: qty,
                product_warehouse_code: warehouseCode,
            },
            header: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            success: function (result) {
                if (result.success) {
                    Amplify.notify('success', result.message, 'Cart');
                    setTimeout(() => window.location.reload(), 2500)
                }
            },
            error: function (xhr, status, err) {
                let response = JSON.parse(xhr.responseText);
                Swal.showValidationMessage(response.message);
            },
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
                    $('.total_cart_amount').text(res.data.total);
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
            this.alert(`Target Element not found in ${target}`, 'Cart');
            return false;
        }

        targetElement.max = this.maxCartItemQuantity();

        const minOrderQty = parseFloat(targetElement.dataset.minOrderQty);

        if (!minOrderQty) {
            this.alert('Target Element doesn\'t have "data-min-order-qty" attribute set or is empty.', 'Cart');
            return false;
        }

        targetElement.min = minOrderQty;

        const qtyInterval = parseFloat(targetElement.dataset.qtyInterval);

        if (!qtyInterval) {
            this.alert(`Target Element doesn't have "data-qty-interval" attribute set or is empty.`, 'Cart');
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
     */
    createShippingList(sourceId, source = 'product') {
        this.confirm('Create a new shopping list?',
            'Shopping List', 'Save', {
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
                preConfirm: async function () {
                    return new Promise((resolve, reject) => {
                        let payload = {
                            type: source,
                            list_id: null,
                            is_shopping_list: 1,
                            list_name: $('#swal2-input').val(),
                            list_type: $('#swal2-select').val(),
                            list_desc: $('#swal2-textarea').val(),
                        };

                        payload[source + '_id'] = sourceId;

                        $.ajax({
                            url: Amplify.favouritesCreateUrl(),
                            type: 'POST',
                            data: JSON.stringify(payload),
                            contentType: 'application/json; charset=UTF-8',
                            headers: {
                                Accept: 'application/json'
                            },
                            success: function (result) {
                                resolve(result);
                            },
                            error: function (xhr, status, err) {
                                let response = JSON.parse(xhr.responseText);
                                Swal.showValidationMessage(response.message);
                                reject(false);
                            },
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading(),
                willOpen: function () {
                    $('#swal2-select').append(
                        $('<option>', {value: '', text: 'Select Type', disabled: true, selected: true}),
                        $('<option>', {value: 'personal', text: 'Personal'}),
                        $('<option>', {value: 'global', text: 'Global'})
                    ).css({
                        'display': 'flex',
                    }).addClass('swal2-input');

                    $('#swal2-textarea').css('display', 'flex').attr('placeholder', 'Enter description');
                }
            })
            .then(function (result) {
                if (result.isConfirmed) {
                    Amplify.notify('success', result.value.message, 'Shopping List');
                }
            });

        return true;
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
                    Amplify.notify('success', res.message);
                    Amplify.loadCartDropdown();
                },
                error: function (xhr, status) {
                    Amplify.alert((JSON.parse(xhr.responseText)?.message || 'Something Went Wrong. PLease try again later.'));
                }
            });
        }

        cartElement.innerHTML = defaultContent;
        cartElement.disabled = false;
    }
}

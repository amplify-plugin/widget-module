import Swal from 'sweetalert2';
import {e_preventDefault} from "codemirror/src/util/event";

window.swal = Swal.mixin({
    theme: 'bootstrap-4-light',
    showCloseButton: true,
    // buttonsStyling: false,
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-secondary'
    }
}); 

window.Amplify = {
    cartUrl: () => '/carts',
    cartItemRemoveUrl: () => '/carts/remove/cart_item_id',
    cartItemUpdateUrl: () => '/carts/update/cart_item_id',
    maxCartItemQuantity: () => 9999999999,

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

        if (!newValue) {
            this.notify('warning', 'This field is required', 'Customer Part Number');
            return;
        }

        //current exist and new empty
        if (oldValue) {
            this.confirm('Are you sure you want to remove this part number?',
                'Customer Part Number', 'Remove', {
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
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
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
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
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
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

                $('#order-subtotal').text(
                    response.data.total_price.toLocaleString('en-US', {
                        style: 'currency',
                        currency: AMPLIFY_BASE_CURRENCY,
                    }),
                );
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
            '{unit_price}': product.price.toLocaleString('en-US', {style: 'currency', currency: AMPLIFY_BASE_CURRENCY}),
            '{subtotal}': parseFloat(product.subtotal).toLocaleString('en-US', {
                style: 'currency',
                currency: AMPLIFY_BASE_CURRENCY,
            }),
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
        await $.ajax({
            beforeSend: () => Amplify.renderEmptyCart('/assets/img/preloader.gif'),
            url: this.cartUrl(),
            method: 'GET',
            dataType: 'json',
            headers: {
                Accept: 'application/json'
            },
            success: function (res) {
                $('.cart-dropdown').empty();

                if (res.data.products.length > 0) {
                    let item_qty = 0;
                    res.data.products.forEach((product, index) => {
                        item_qty += parseInt(product.qty);
                        let item_price = (product.price * 1).toLocaleString('en-US', {
                            style: 'currency',
                            currency: AMPLIFY_BASE_CURRENCY,
                        });
                        let total_amount = (product.qty * product.price).toLocaleString('en-US', {
                            style: 'currency',
                            currency: AMPLIFY_BASE_CURRENCY,
                        });

                        item_qty = item_qty > 0 && item_qty < 100 ? item_qty : '99+';
                        $('.total_cart_items').text(item_qty);

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
                            <span class="dropdown-product-details">${product.qty} x ${item_price} = ${total_amount}</span>
                        </div>
                    </div>`);
                    });
                    $('.total_cart_amount').text(
                        new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: AMPLIFY_BASE_CURRENCY,
                        }).format(parseFloat(res.data.total_price)),
                    );
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

    handleQuantityChange(target, action) {

        const targetElement = document.querySelector(target);

        const productCode = targetElement.dataset.productCode;

        if (!['decrement', 'input', 'increment'].includes(action)) {
            this.notify('warning', `Invalid Quantity Change action [${action}].`, 'Cart');
        }

        if (!targetElement) {
            alert(`Target Element not found in ${target}`);
            return;
        }

        targetElement.max = this.maxCartItemQuantity();

        const minOrderQty = parseFloat(targetElement.dataset.minOrderQty);

        if (!minOrderQty) {
            alert(`Target Element doesn't have "data-min-order-qty" attribute set or is empty.`);
            return;
        }

        targetElement.min = minOrderQty;

        const qtyInterval = parseFloat(targetElement.dataset.qtyInterval);

        if (!qtyInterval) {
            alert(`Target Element doesn't have "data-qty-interval" attribute set or is empty.`);
            return;
        }

        targetElement.step = qtyInterval;

        let quantity = parseFloat(targetElement.value);

        switch (action) {

            case 'decrement' : {
                let newValue = quantity - qtyInterval;
                if (newValue < minOrderQty) {
                    this.confirm(
                        `Product ${productCode} requires a minimum order quantity of ${minOrderQty}. You entered ${newValue}.`,
                        'Cart', 'Confirm', {
                            showConfirmButton: false,
                            showLoaderOnConfirm: false,
                        });
                    return;
                }
                targetElement.value = newValue;
                break;
            }

            case 'increment' : {
                let newValue = quantity + qtyInterval;
                if (newValue > this.maxCartItemQuantity()) {
                    this.confirm(
                        `Product ${productCode} requires a maximum order quantity of ${this.maxCartItemQuantity()}. You entered ${newValue}.`,
                        'Cart', 'Confirm', {
                            showConfirmButton: false,
                            showLoaderOnConfirm: false,
                        });
                    return;
                }
                targetElement.value = newValue;
                break;
            }

            default : {
                if (quantity < minOrderQty) {
                    this.confirm(
                        `Product ${productCode} requires a minimum order quantity of ${minOrderQty}. You entered ${quantity}.`,
                        'Cart', 'Confirm', {
                            showConfirmButton: false,
                            showLoaderOnConfirm: false,
                        });
                    targetElement.value = minOrderQty;
                    return;
                }

                if (quantity > this.maxCartItemQuantity()) {
                    this.confirm(
                        `Product ${productCode} requires a maximum order quantity of ${this.maxCartItemQuantity()}. You entered ${quantity}.`,
                        'Cart', 'Confirm', {
                            showConfirmButton: false,
                            showLoaderOnConfirm: false,
                        });
                    targetElement.value = minOrderQty;
                    return;
                }
                break;
            }
        }
    }
}

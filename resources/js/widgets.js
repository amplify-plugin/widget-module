import Swal from 'sweetalert2';

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
    /**
     * The function validate if the customer is logged in
     * return bool
     */
    authenticated: function () {
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
    notify: function (type = 'info', message = 'Your message here!', title = 'Notification', options = {}) {
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
    confirm: function (question = 'You won\'t be able to revert this!', title = 'Are you sure?', confirmBtnText = '', options = {}) {
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
    syncCustomPartNumber: function (element, inputId) {
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

        if (oldValue === newValue) {
            this.notify('warning', 'This old and new value is same.', 'Customer Part Number');
            return;
        }

        //current exist and new empty
        if (oldValue && !newValue) {
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
                },
                error: function (xhr, status, err) {
                    Amplify.notify('error', response.message, 'Customer Part Number');
                }
            });
        }
    },

}

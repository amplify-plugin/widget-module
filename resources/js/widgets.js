import Swal from 'sweetalert2';

window.swal = Swal.mixin({
    theme: 'bootstrap-4',
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-secondary',
    },
    showCloseButton: true,
    showCancelButton: true,
    focusConfirm: true,
});

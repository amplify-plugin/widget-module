<style>
    .modal_menu {
        overflow-x: hidden !important;
        overflow-y: auto !important;
        z-index: 9200 !important;
        position: fixed;
        top: 110px;
        right: 0;
        z-index: 1050;
        width: 20%;
        height: 100%;
        overflow: hidden;
        outline: 0;
        transform: translateX(100%);
        transition: all 600ms ease-in-out;
        visibility: hidden;
        opacity: 0;
    }

    .modal_menu.show {
        transform: translateX(0) !important;
        visibility: visible;
        opacity: 1;
        padding-right: 0 !important;
    }

    .modal_menu.closing {
        transform: translateX(100%) !important;
        /* Slide out */
        transition: transform 7s ease-in-out, opacity 0.3s ease-in-out;
        visibility: hidden;
        opacity: 0;
    }

    .line_item_add {
        background-color: #f2f8fd;
        color: black;
        padding: 10px;
        border: 1px solid #999999;
        border-radius: 8px;
    }

    .line_item_add_body {
        padding: 16px;
        border: 1px solid #999999;
        border-radius: 4px;
        background-color: white;
    }

    .modal-header {
        background-color: #f2f2f2;
    }

    .modal-body {
        padding: 0.5rem;
        width: 100% !important;
    }

    .accordian_item_style {
        border: 3px solid black;
        border-radius: 12px;
    }

    .btn-dark:hover {
        color: #fff;
        background-color: transparent !important;
        border-color: #1d2124;
    }

    .btn-dark:focus {
        color: #fff;
        background-color: transparent !important;
        border-color: transparent !important;
        box-shadow: none;
    }

    .modal-header .close {
        padding: 0 !important;
        margin: 0 !important;
    }

    .modal.fade .modal-dialog {
        transition: transform 0.5s ease-out, opacity 0.5s ease-out;
    }
</style>

<div {!! $htmlAttributes !!}>
    <div class="modal_menu fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="closeBtn_custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="11" viewBox="0 0 21 11"
                             fill="none">
                            <path
                                d="M20.2708 4.94699L20.2701 4.94625L16.188 0.883755C15.8821 0.579419 15.3875 0.580552 15.0831 0.886411C14.7787 1.19223 14.7799 1.68688 15.0857 1.99125L17.8265 4.71875H1.28125C0.849765 4.71875 0.5 5.06852 0.5 5.5C0.5 5.93149 0.849765 6.28125 1.28125 6.28125H17.8264L15.0857 9.00875C14.7799 9.31312 14.7787 9.80777 15.0831 10.1136C15.3875 10.4195 15.8822 10.4205 16.188 10.1162L20.2702 6.05375L20.2709 6.05301C20.5769 5.74762 20.5759 5.25137 20.2708 4.94699Z"
                                fill="black" />
                        </svg>
                    </button>
                    <button id="pin_button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19"
                             fill="none">
                            <path
                                d="M18.1276 5.98447C18.1593 5.95096 18.159 5.89794 18.1261 5.86472L18.1276 5.98447ZM18.1276 5.98447C18.1105 5.99961 18.0884 6.008 18.0655 6.008C18.042 6.008 18.0194 5.99921 18.0022 5.9834L17.5557 5.53695L17.5556 5.53684C17.4526 5.43393 17.3129 5.37613 17.1673 5.37613C17.0217 5.37613 16.882 5.43393 16.7789 5.53683L16.7788 5.53695L11.3897 10.9261C11.3897 10.9261 11.3897 10.9261 11.3897 10.9261C11.2867 11.0291 11.2288 11.1688 11.2287 11.3145V11.3146L11.2287 14.2354C11.2287 14.2355 11.2287 14.2356 11.2287 14.2357C11.2284 14.3273 11.1924 14.4151 11.1285 14.4807C10.9884 14.6115 10.7707 14.6113 10.6309 14.4802L5.01726 8.86651C4.88128 8.73053 4.88109 8.50994 5.01699 8.3736C5.08284 8.30835 5.17168 8.27156 5.26438 8.27114H8.18531H8.18538C8.33106 8.27111 8.47076 8.21321 8.57376 8.1102L18.1276 5.98447ZM13.9629 2.7211L13.9629 2.72104C14.0139 2.67005 14.0544 2.6095 14.082 2.54285C14.1096 2.47619 14.1239 2.40473 14.1239 2.33257C14.1239 2.26042 14.1096 2.18896 14.082 2.12229C14.0544 2.05565 14.0139 1.9951 13.9629 1.94411C13.9629 1.94409 13.9629 1.94407 13.9629 1.94405L13.514 1.49519C13.514 1.49514 13.5139 1.49509 13.5139 1.49504C13.4813 1.46228 13.4805 1.40947 13.512 1.37565C13.5292 1.36031 13.5514 1.3518 13.5745 1.3518C13.5979 1.3518 13.6205 1.36059 13.6378 1.37641L18.1259 5.86452L13.7861 2.54427L13.9629 2.7211ZM13.9629 2.7211L8.57378 8.11018L13.9629 2.7211ZM12.737 0.596855L12.7369 0.596918C12.2745 1.05953 12.2744 1.80945 12.737 2.27199L12.7976 2.33263L7.95785 7.17239L5.26391 7.17239L5.2632 7.17239C4.87993 7.17347 4.51255 7.32569 4.24085 7.59601L4.24037 7.59649C3.67523 8.16177 3.67523 9.07818 4.24037 9.64347L4.2404 9.64349L6.21086 11.614L2.0829 15.7419L2.08288 15.7419C1.44139 16.3835 0.986322 17.1875 0.766329 18.0677L0.766274 18.0679C0.743377 18.1599 0.744661 18.2562 0.770002 18.3476C0.795344 18.4389 0.84388 18.5221 0.910895 18.5891L0.911008 18.5892C0.978042 18.6562 1.06124 18.7047 1.15254 18.73L1.15255 18.73C1.24384 18.7553 1.34013 18.7566 1.43207 18.7338L1.43238 18.7337C2.31257 18.5136 3.11641 18.0585 3.75801 17.417L3.75803 17.417L7.88596 13.289L9.85657 15.2595C10.4217 15.8246 11.3381 15.8246 11.9034 15.2595L11.9038 15.2591C12.1742 14.9874 12.3265 14.6201 12.3275 14.2368V14.2362V11.542L17.1673 6.70226L17.2279 6.76291L17.228 6.76295C17.6906 7.2254 18.4405 7.22545 18.903 6.76291C19.3656 6.30037 19.3655 5.55046 18.9031 5.08781L18.903 5.08778L14.4121 0.596891C13.9495 0.13435 13.1996 0.134402 12.737 0.596855ZM2.9808 16.6401L2.98077 16.6401C2.78697 16.834 2.57382 17.0064 2.34506 17.1549C2.49354 16.926 2.66596 16.7127 2.85985 16.5188L6.98776 12.3908L7.10905 12.5121L2.9808 16.6401Z"
                                fill="#4A7CA5" stroke="#4A7CA5" stroke-width="0.5" />
                        </svg>
                    </button>
                </div>

                <div class="modal-body">
                   <x-quick-add-to-cart heading='LINE ITEM ADD'/>
                    <x-customer.current-session/>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('pin_button').addEventListener('click', function() {
        let colElement = document.querySelector('.col-lg-9');
        let gridElement = document.querySelector('.grid-md-cols-5');

        // Toggle max-width for .col-lg-9
        if (colElement) {
            colElement.style.maxWidth = colElement.style.maxWidth === '55%' ? '' : '55%';
        } else { // Remove the condition inside else
            colElement.style.maxWidth = colElement.style.maxWidth === '100%' ? '' : '100%';
        }

        // Modify .modal-backdrop.show opacity
        let modalBackdrop = document.querySelector('.modal-backdrop.show');
        if (modalBackdrop) {
            modalBackdrop.style.opacity = '0';
            modalBackdrop.style.transition = 'opacity 0.3s ease'; // Smooth transition effect
        }

        // Toggle grid-template-columns
        // if (gridElement) {
        //     gridElement.style.gridTemplateColumns =
        //         gridElement.style.gridTemplateColumns === "repeat(4, minmax(0, 1fr))"
        //             ? ""
        //             : "repeat(4, minmax(0, 1fr))";
        // }
    });
    document.getElementById('closeBtn_custom').addEventListener('click', function() {
        let colElement = document.querySelector('.col-lg-9');
        let gridElement = document.querySelector('.grid-md-cols-5');
        if (colElement) {
            colElement.style.maxWidth = colElement.style.maxWidth === '100%' ? '' : '100%';
        }
    });
</script>


<div class="modal fade" id="quick-view" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick View</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="quick-view-output">
                <div class='loader-wrapper'>
                    <img src="{{ asset('img/loading.gif') }}" width="30">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const seopath = "-{{ str_replace('/', '@', (request('q') ?? '')) }}"
    async function getQuickDetails(id) {
        try {
            setQuickViewLoader();

            $.ajax({
                url: `/quick-view/${id}/${seopath}`,
                type: 'GET',
                success: function (res) {
                    $('#quick-view-output').html(res.html);
                    $('.clear-attributes-filter').remove();
                    confQuantityManager();
                },
                error: function (err) {
                    removeQuickViewLoader();
                }
            });
        } catch (err) {
            removeQuickViewLoader();
        }
    }

    function setQuickViewLoader () {
        $('#quick-view-output').html(`<div class='loader-wrapper'>
            <img src="{{ asset('img/loading.gif') }}" width="30">
        </div>`);
    }

    function removeQuickViewLoader (message = 'Something went wrong.', status = 'danger') {
        $('#quick-view-output').html(`<div class='loader-wrapper'>
            <p class='text-${status}'>${message}</p>
        </div>`);
    }
</script>

@php
    push_js("
        function confQuantityManager() {
            $('.qty-plus').on('click', function (e) {
                let target = $(e.currentTarget);
                let parentItem = target.closest('.sku-item');
                let qtyField = target.prev();
                let qty = parseInt(qtyField.html());

                let minQty = parseInt(parentItem.find('.sku-min-qty').text()) || 1;
                let interval = parseInt(parentItem.find('.sku-qty-intrvl').text()) || 1;

                if (qty === 0) {
                    qty = minQty;
                } else {
                    qty += interval;
                }

                qtyField.html(qty);
                parentItem.data('qty', qty);
            });

            $('.qty-minus').on('click', function (e) {
                let target = $(e.currentTarget);
                let parentItem = target.closest('.sku-item');
                let qtyField = target.next();
                let qty = parseInt(qtyField.html());

                let minQty = parseInt(parentItem.find('.sku-min-qty').text()) || 1;
                let interval = parseInt(parentItem.find('.sku-qty-intrvl').text()) || 1;

                if ((qty - interval) >= minQty) {
                    qty -= interval;
                } else {
                    qty = 0;
                }

                qtyField.html(qty);
                parentItem.data('qty', qty);
            });
        }
    ", 'footer-script');
@endphp

<style>
    .loader-wrapper {
        text-align: center;
        padding: 60px 0;
    }
    .loader-wrapper img {
        width: 30px;
    }
</style>

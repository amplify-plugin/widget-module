<div class="modal fade" id="warehouse-selection" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warehouse Inventory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="warehouse-selection-output">
                <div class='loader-wrapper'>
                    <img src="{{ asset('img/loading.gif') }}" width="30">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function getWarehouseSelection(product_code) {
        try {
            setWarehouseSelectionLoader();
            let current_warehouse = $(`#product_warehouse_${product_code.replaceAll(' ', '-')}`).val();

            $.ajax({
                url: `/warehouse-selection-view/${product_code}?warehouse=${current_warehouse}`,
                type: 'GET',
                success: function (res) {
                    $('#warehouse-selection-output').html(res.html);
                },
                error: function (err) {
                    removeWarehouseSelectionLoader();
                }
            });
        } catch (err) {
            removeWarehouseSelectionLoader();
        }
    }

    function setWarehouseSelectionLoader () {
        $('#warehouse-selection-output').html(`<div class='loader-wrapper'>
            <img src="{{ asset('img/loading.gif') }}" width="30">
        </div>`);
    }

    function removeWarehouseSelectionLoader (message = 'Something went wrong.', status = 'danger') {
        $('#warehouse-selection-output').html(`<div class='loader-wrapper'>
            <p class='text-${status}'>${message}</p>
        </div>`);
    }
</script>

<style>
    .loader-wrapper {
        text-align: center;
        padding: 60px 0;
    }
    .loader-wrapper img {
        width: 30px;
    }
</style>

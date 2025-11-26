<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div class="form-group mb-0">
                <div class="input-group mb-2">
                    <div class="custom-file">
                        <input class="custom-file-input form-control" type="file" id="quick_order_file" aria-label="file-label"
                               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                               onchange="readQuickOrderFile(this)" name="quick_order_file">
                        <label class="custom-file-label" style="margin-bottom: 0;" for="file-input" id="quick_order_file_label">
                            {{ __('Load from file...') }}
                        </label>
                    </div>
                    <div class="quick-order-upload-btn input-group-prepend">
                        <button type="button" id="upload_btn"
                                class="btn btn-primary btn-sm my-0 btn-block rounded-right"
                                onclick="UploadQuickOrderFile()">
                            {{ __('Upload') }}
                        </button>
                    </div>
                </div>
                <span id="error_div" class="d-block text-danger" role="alert"></span>
                <span class="">
                    Download the sample file from
                    <a href="{{ asset('assets/samples/QUICK-ORDER-SAMPLE.csv') }}" download>
                        Here</a>.
                    <p class="mb-0">
                        Spreadsheet Requirements:
                        <ol class="text-danger">
                        <li>{{ __('Supported file types CSV,XLS,XLSX') }}.</li>
                        <li>{{ __('Headings must be included on row 1 of the spreadsheet. The names of the headings are irrelevant') }}.</li>
                        <li>{{ __('Column A = SKU / Item Number') }}.</li>
                        <li>{{ __('Column B = Quantity Ordered') }}.</li>
                        <li>{{ __('A maximum of 100 line items can be listed.') }}.</li>
                    </ol>
                    </p>
                </span>
            </div>
            <div class="tableFixHead table-responsive pb-4 pb-md-0">
                <table class="table table-bordered" id="quickOrderTable">
                    <thead>
                    <tr>
                        <th scope="col" class="text-center" style="min-width: 150px">{{ __('Product Code') }}</th>
                        <th scope="col" class="text-center" style="min-width: 150px">{{ __('Product Name') }}</th>
                        @erp
                        <th scope="col" rowspan="2" style="width: 440px" class="text-center">{{ __('Warehouse') }}</th>
                        <th scope="col" rowspan="2" style="width: 135px" class="text-center">{{ __('Qty') }}</th>
                        @enderp
                        <th scope="col" rowspan="2">Remove</th>
                    </tr>
                    </thead>
                    <tbody id="quick_order_tbody"></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center pb-md-0">
                <div class="text-success text-bold" id="added_product_count"></div>
                <div class="text-center">
                    <button id="add_to_order_btn" class="btn btn-primary btn-sm"
                            onclick="addToOrder()">
                        {{ __('Add to Order') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    var timeout;
    var delay = 500;
    var limit = 0;
    var from = 0;

    const MULTIPLE_WAREHOUSE_ENABLED = @selectwarehouse true;
    @else false @endselectwarehouse;
    const USER_ACTIVE_WAREHOUSE_CODE = "{{ $userActiveWarehouseCode }}";
    const isMultiWarehouse = "{{ erp()->allowMultiWarehouse() }}";
    var user_active_warehouse_name = null;
    var user_active_warehouse = null;
    const WAREHOUSE_QUANTITY_AVAILABILITY_CHECK = parseInt("{{ $checkWarehouseQtyAvailability }}")

    document.addEventListener('DOMContentLoaded', function(event) {
        addProduct();
    });

    function readQuickOrderFile(file) {
        let data = file.value;
        let file_name = data.split('\\')[2];
        if (data.length != 0) {
            $('#quick_order_file_label').text('Selected file : ' + file_name);
        } else {
            $('#quick_order_file_label').text('Invalid File selection');
        }
    }

    function UploadQuickOrderFile() {
        $('#error_div').empty();
        $('#upload_btn').attr('disabled', 'disabled');
        let html = `<div class="spinner-border spinner-border-sm text-white mr-2" role="status"></div>Uploading`;
        $('#upload_btn').html(html);
        var formData = new FormData();
        var file = $('#quick_order_file')[0].files[0];
        if (file !== undefined) {
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: '{{ route('frontend.order.quick-order-file-upload') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        let products = response.data;
                        // let products = items.filter(i => !i.error);

                        let products_array = Object.keys(products).map(function(key) {
                            return products[key];
                        });
                        if (response.message !== '') {
                            $('#error_div').removeClass('d-none');
                            $('#error_div').html(response.message);
                        } else {
                            $('#error_div').addClass('d-none');
                            $('#error_div').html('');
                        }
                        if (products_array.length > 0) {
                            let html = '';
                            products_array.forEach(function(product, index) {
                                html += `<tr class="added_products" id="added_products_${index}">
                                            <td>
                                                <input type="hidden" id="product_id_${index}" value="${product['product_id']}" name="product_id[]" />
                                                <input type="hidden" id="product_back_order_${index}" value="${product['product_back_order']}" name="product_back_order[]" />
                                                <input type="text" onblur="debounceSearch(this)" id="product_code_${index}" placeholder="Enter product code" name="product_code[]" class="form-control form-control-sm" value="${product['product_code']}">
                                                <small class="text-danger" id="product_code_error_${index}">${product['error']}</small>
                                            </td>
                                             <td>
                                                <span class="text-center" id="product_name_${index}">${product['product_name']}</span>
                                            </td>
                                            <td class="warehouse text-center">
                                                ${createWarehouse(product.ERP, index)}
                                            </td>
                                            <td>
                                                <input type="number"   placeholder="Quantity" name="qty[]" value="${product['qty']}" min="1" max=""  id="qty_${index}" class="form-control form-control-sm">
                                                <!-- <small class="text-danger" id="qty_error_${index}"></small>  -->
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm mt-0" onclick="removeProduct(this)">
                                                   Remove
                                                </button>
                                            </td>
                                        </tr>`;
                            });
                            $('#upload_btn').removeAttr('disabled');
                            $('#upload_btn').html('Upload');
                            $('#quick_order_tbody').html(html);
                            ShowNotification('success', 'Quick Order', 'File uploaded successfully');
                            $('#quick_order_file').val('');
                            $('#quick_order_file_label').text('Load from file...');
                            let added_product_count = $('#quick_order_tbody tr').length - 1;
                            $('#added_product_count').text(added_product_count + ' Products added');
                            from = 0;
                            limit = products_array.length;
                            verifyQuantityByERP(products_array);
                            addProduct();
                        } else {
                            showNoProductsFound();
                        }
                    } else {
                        $('#upload_btn').removeAttr('disabled');
                        $('#upload_btn').html('Upload');
                        ShowNotification('error', 'Quick Order', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status == 422) {
                        $('#error_div').html(response.message);
                    }
                    ShowNotification('error', 'Quick Order', 'Something Went Wrong');
                    $('#upload_btn').removeAttr('disabled');
                    $('#upload_btn').html('Upload');
                },
            });
        } else {
            $('#upload_btn').removeAttr('disabled');
            $('#upload_btn').html('Upload');
            ShowNotification('error', 'Quick Order', 'Please select a file to upload');
        }
    }

    function verifyQuantityByERP(products) {
        if (!products) return false;

        for (const index in products) {
            let selectedWarehouse = null;
            let isWarehouseSelected = false;
            let isPassedDefaultWarehouse = false;

            for (const i in products[index].ERP) {
                const warehouse = products[index].ERP[i];

                if (!isWarehouseSelected && parseInt(warehouse.QuantityAvailable) > 0) {
                    if (selectedWarehouse && parseInt(selectedWarehouse.QuantityAvailable) > parseInt(warehouse
                        .QuantityAvailable)) {
                        continue;
                    }
                    if (parseInt(warehouse.QuantityAvailable) >= parseInt(products[index].qty)) {
                        selectedWarehouse = warehouse;
                        isWarehouseSelected = true;
                        if (isPassedDefaultWarehouse) break;
                        continue;
                    }

                    selectedWarehouse = warehouse;
                }

                if (warehouse.WarehouseID == USER_ACTIVE_WAREHOUSE_CODE) {
                    let product_qty = products[index].qty == null ? 0 : products[index].qty;
                    if (parseInt(warehouse.QuantityAvailable) >= parseInt(product_qty)) {
                        selectedWarehouse = warehouse;
                        break;
                    }
                    if (isWarehouseSelected) break;
                    if (selectedWarehouse && parseInt(selectedWarehouse.QuantityAvailable) > parseInt(warehouse
                        .QuantityAvailable)) continue;

                    selectedWarehouse = warehouse;
                    isPassedDefaultWarehouse = true;
                }
            }

            if (WAREHOUSE_QUANTITY_AVAILABILITY_CHECK && selectedWarehouse) {
                const quantity = parseInt(selectedWarehouse.QuantityAvailable) >= parseInt(products[index].qty) ?
                    parseInt(products[index].qty) : parseInt(selectedWarehouse.QuantityAvailable);
                changeUserInputsERP(selectedWarehouse.WarehouseID, quantity, index);
            }
        }
    }

    function changeUserInputsERP(warehouseID, quantity, index) {
        document.querySelector(`#added_products_${index} select[name="product_warehouse_code[]"]`).value = warehouseID;
        document.querySelector(`#added_products_${index} input[name="qty[]"]`).value = quantity;
    }

    function setMaxQty(selectElement, inputField) {

        $('#' + inputField).attr('max', $('#' + selectElement).find(':selected').data('quantity'));
    }

    function createWarehouse(warehouses, index) {
        let html = '';
        let inventory = [];

        if (!MULTIPLE_WAREHOUSE_ENABLED) {
            html +=
                `<div class="d-block mx-auto py-1 text-center font-weight-bold">{{ $userActiveWarehouseName }}</div>`;
        }

        html += `<select
        class="form-control form-control-sm"
        name="product_warehouse_code[]"
        id="warehouse_${index}"
        onchange="changeWarehouse(this, 'added_products_${index}'); setMaxQty('warehouse_${index}', 'qty_${index}');"
        `;

        html += (!MULTIPLE_WAREHOUSE_ENABLED) ?
            ` style="display:none;">` :
            `>`;

        html += ` <option value="">Select Warehouse</option>`;

        if (warehouses) {
            for (const warehouse of warehouses) {
                if (warehouse.WarehouseID == USER_ACTIVE_WAREHOUSE_CODE) {
                    $('#qty_' + index).attr('max', warehouse.QuantityAvailable);
                }
                const isDisabled = (WAREHOUSE_QUANTITY_AVAILABILITY_CHECK && parseInt(warehouse.QuantityAvailable) <= 0);

                html += `<option value="${warehouse.WarehouseID}" data-quantity="${warehouse.QuantityAvailable}"
                    ${(warehouse.WarehouseID == USER_ACTIVE_WAREHOUSE_CODE) ? 'selected' : ''}
                    ${isDisabled ? 'disabled' : ''} >
                                    ${warehouse.WarehouseName}
                                </option>`;

                inventory.push(`${warehouse.WarehouseName}-${warehouse.QuantityAvailable}`);
            }
        }

        html += `</select>`;

        //show validation error
        html += `<small class="text-danger d-block" id="warehouse_error_${index}"></small>`;
        return (html + '<small>' + inventory.join(', ') + '</small>');
    }

    function changeWarehouse(e, product_div_id) {
        const warehouseInfo = e.selectedOptions[0].dataset;

        $(`#${product_div_id} input[name="qty[]"]`).val(1);
        $(`#${product_div_id} input[name="qty[]"]`).attr('max', warehouseInfo.quantity);
    }

    function addProduct() {
        if ($('#no_product_tr').length > 0) {
            $('#no_product_tr').remove();
        }
        from = limit;
        limit += 1;
        let html = '';
        for (var i = from; i < limit; i++) {
            html += ` <tr class="added_products" id="added_products_${i}">
                        <td width="15%">
                            <input type="hidden" id="product_id_${i}" value="" name="product_id[]" />
                            <input type="hidden" id="product_back_order_${i}" value="" name="product_back_order[]" />
                            <input type="text" aria-label="Product code" onblur="debounceSearch(this)" id="product_code_${i}" placeholder="Enter product code" name="product_code[]" class="form-control form-control-sm" value="">
                            <small class="text-danger" id="product_code_error_${i}"></small>
                        </td>
                        <td>
                            <span class="text-center" id="product_name_${i}">-</span>
                        </td>
                        <td class="warehouse text-center">
                        </td>
                        <td>
                            <input type="number" aria-label="Quantity" placeholder="Quantity" name="qty[]" value="" min="0" max=""
                                    onkeypress="return event.charCode >= 48"
                                    id="qty_${i}"
                                    class="form-control form-control-sm" style="width: 110px;">
                            <!-- <small class="text-danger" id="qty_error_${i}"></small> -->
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm d-none" onclick="removeProduct(this)">
                                Remove
                            </button>
                        </td>
                    </tr>`;
        }

        $('#quick_order_tbody').append(html);
        let added_product_count = $('#quick_order_tbody tr').length - 1;
        $('#added_product_count').text(added_product_count + ' Products added');
    }

    function promptRemoveButton(index) {
        $(`#added_products_${index} td button`).removeClass('d-none');
    }

    function removeProduct(element) {
        $(element).closest('tr').remove();
        ShowNotification('success', 'Quick Order', 'Product removed successfully');
        if ($('#no_product_tr').length > 0) {
            $('#added_product_count').text('');
        } else {
            let added_product_count = $('#quick_order_tbody tr').length - 1;
            $('#added_product_count').text(added_product_count + ' Products added');
        }
        if ($('#quick_order_tbody tr').length === 0) {
            showNoProductsFound();
        }
    }

    function debounceSearch(element) {
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(function() {
            getProductNameByCode(element);
        }, delay);
    }

    function getProductNameByCode(element) {
        let product_code = $(element).val();
        if (product_code.trim() !== '' && product_code.length >= 3) {
            let index = $(element).attr('id').split('_')[2];
            $.ajax({
                url: '{{ route('frontend.order.get-product-name-by-code') }}',
                type: 'POST',
                data: {
                    product_code: product_code,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success == true) {

                        $('#product_id_' + index).val(response.data.product_id);
                        $('#product_back_order_' + index).val(response.data.product_back_order);
                        $('#product_name_' + index).text(response.data.product_name);
                        $('#product_code_error_' + index).text('');

                        user_active_warehouse = response.data.ERP.find(function(element) {
                            return USER_ACTIVE_WAREHOUSE_CODE == element.WarehouseID;
                        });
                        user_active_warehouse_name = user_active_warehouse?.WarehouseName ?? '';
                        if (response.data.ERP.length)
                            $(`#added_products_${index} .warehouse`).html(createWarehouse(response.data.ERP,
                                index));
                        else
                            $(`#added_products_${index} .warehouse`).html('');

                        selectWarehouseForSingleProduct(response.data, index);

                        if ($('#quick_order_tbody tr').last().find('input[type="text"]').val() != '') {
                            addProduct();
                            promptRemoveButton(index);
                        }

                    } else {
                        $('#product_name_' + index).text(response.data.product_name);
                        $('#product_code_error_' + index).text(response.data.error);
                        $(`#added_products_${index} .warehouse`).html('');
                    }
                },
            });
        }
    }

    function selectWarehouseForSingleProduct(product, index) {
        if (!product) return false;

        let selectedWarehouse = null;
        let isWarehouseSelected = false;

        for (const i in product.ERP) {
            const warehouse = product.ERP[i];

            if (!isMultiWarehouse) {
                if (warehouse.WarehouseID == USER_ACTIVE_WAREHOUSE_CODE) {
                    selectedWarehouse = warehouse;
                    break;
                }
                continue;
            }

            if (!(selectedWarehouse && parseInt(selectedWarehouse.QuantityAvailable) > 0) && parseInt(warehouse
                .QuantityAvailable) > 0) {
                if (isWarehouseSelected) {
                    selectedWarehouse = warehouse;
                    break;
                }
                selectedWarehouse = warehouse;
            }

            if (warehouse.WarehouseID == USER_ACTIVE_WAREHOUSE_CODE) {
                if (parseInt(warehouse.QuantityAvailable) > 0) {
                    selectedWarehouse = warehouse;
                    break;
                }

                if (selectedWarehouse) break;

                selectedWarehouse = warehouse;
                isWarehouseSelected = true;
            }
        }

        let availableQuantity = selectedWarehouse.QuantityAvailable > 0 ? 1 : 0;
        if (selectedWarehouse) {
            changeUserInputsERP(selectedWarehouse.WarehouseID, availableQuantity, index);
        }
    }

    function showNoProductsFound() {
        $('#quick_order_tbody').empty();
        addProduct();

    }

    function addToOrder() {
        let products = [];
        let validation_error = false;
        if ($('#quick_order_tbody tr').length > 0 && $('#quick_order_tbody tr#no_product_tr').length === 0) {
            $('#quick_order_tbody tr').each(function(index, element) {
                let product_code = $(element).find('input[name="product_code[]"]').val();
                let product_id = $(element).find('input[name="product_id[]"]').val();
                let product_back_order = $(element).find('input[name="product_back_order[]"]').val();
                let qty = $(element).find('input[name="qty[]"]').val();
                let warehouse = $(element).find('select[name="product_warehouse_code[]"]').val();
                let id = $(element).attr('id').split('_')[2];
                let maxQty = $(element).find('select[name="product_warehouse_code[]"]').find('option:selected')
                    .data('quantity');

                if (product_code.trim() !== '' && qty.trim() !== '' && warehouse !== null && warehouse
                    ?.trim() !== '') {
                    $('#product_code_error_' + id).text('');
                    $('#qty_error_' + id).text('');
                    $('#warehouse_error_' + id).text('');
                    products.push({
                        product_code: product_code,
                        product_id: product_id,
                        product_back_order: product_back_order,
                        qty: qty,
                        product_warehouse_code: warehouse,
                    });
                    if (WAREHOUSE_QUANTITY_AVAILABILITY_CHECK && (qty > maxQty)) {
                        $('#qty_error_' + id).text('Check quantity limit');
                        validation_error = true;
                    }
                } else {
                    if (product_code.trim() !== '') {
                        if (warehouse === null || warehouse?.trim() === '') {
                            $('#warehouse_error_' + id).text(warehouse === null ? 'Warehouse has no quantity.' :
                                'Warehouse is required.');
                        } else {

                            $('#warehouse_error_' + id).text('');
                        }

                        if (qty.trim() === '') {
                            $('#qty_error_' + id).text('Quantity is required');
                        } else {
                            $('#qty_error_' + id).text('');
                        }


                        validation_error = true;
                    } else {
                        $('#product_code_error_' + id).text('');
                    }
                }
            });

            if (!validation_error) {
                $('#error_div').addClass('d-none');
                $('#error_div').html('');
                if (products.length > 0) {
                    $('#add_to_order_btn').attr('disabled', 'disabled');
                    let html =
                        `<div class="spinner-border spinner-border-sm text-primary mr-2" role="status"></div>Adding...`;
                    $('#add_to_order_btn').html(html);
                    $.ajax({
                        url: '{{ route('frontend.order.quick-order-add-to-order') }}',
                        type: 'POST',
                        data: {
                            products: products,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#add_to_order_btn').removeAttr('disabled');
                                $('#add_to_order_btn').html('Add to Order');
                                $('#quick_order_tbody tr').each(function(index, element) {
                                    if ($(element).hasClass('added_protducts')) {
                                        $(element).remove();
                                    }
                                });
                                showNoProductsFound();
                                $('#added_product_count').text('');
                                ShowNotification('success', 'Quick Order', response.message);
                                setTimeout(function() {
                                    Amplify.loadCartDropdown();
                                }, 1000);
                            } else {
                                ShowNotification('error', 'Quick Order', response.message);
                                $('#add_to_order_btn').removeAttr('disabled');
                                $('#add_to_order_btn').html('Add to Order');
                            }
                        },
                        error: function(err) {
                            ShowNotification('error', 'Quick Order', err.responseJSON.message);
                            $('#add_to_order_btn').removeAttr('disabled');
                            $('#add_to_order_btn').html('Add to Order');
                        },

                    });
                } else {
                    ShowNotification('error', 'Quick Order', 'Please add at least one product');
                }
            } else {
                ShowNotification('error', 'Quick Order', 'Please fill valid warehouse and quantity');
            }
        } else {
            ShowNotification('error', 'Quick Order', 'Please add at least one product');
        }
    }
</script>

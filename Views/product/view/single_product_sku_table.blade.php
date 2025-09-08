@php
    push_css(
        '
            table#variantTable td {
                align-items: center;
                vertical-align: middle;
            }
    ',
        'custom-style',
    )
@endphp

@if (count($sku_details) > 0)
    <div class="tableFixHead table-md-responsive mt-4">
        <style>
            table#variantTable td {
                align-items: center;
                vertical-align: middle;
            }
        </style>
        <table id="variantTable" class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Product Code</th>
                @foreach ($Product->sku_default_attributes as $key => $value)
                    @php $warehouseRowCount++ @endphp
                    <th scope="col">{{ $value->local_name }}</th>
                @endforeach
                <th scope="col">{{ $auth ? 'Price' : 'Msrp' }}</th>
                @if ($auth)
                    <th scope="col">Inventory</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Warehouse</th>
                @endif
            </tr>
            </thead>
            <tbody id="sku_details_table_body">
            @foreach ($sku_details as $key => $skuProduct)
                <tr id="sku_details_table_tr-{{ $key }}" data-qty="" data-warehouse=""
                    data-product-code="{{ $skuProduct['product_code'] }}" data-product-id="{{ $skuProduct['id'] }}">
                    <td>{{ $skuProduct['product_code'] }}</td>
                    @foreach ($Product->sku_default_attributes as $defaultAttribute)
                        @if ($skuAttribute = sku_attribute_filter($defaultAttribute->id, $skuProduct['attributes']))
                            @php $attributeValue = $skuAttribute->pivot->attribute_value->en ?? '-' @endphp
                            <td data-filter-attribute="{{ $skuAttribute->name->en }}"
                                data-filter-value="{{ $attributeValue }}">{{ $attributeValue }}</td>
                        @else
                            <td data-filter-attribute="" data-filter-value="">-</td>
                        @endif
                    @endforeach
                    <td>
                        <p class="mb-0 product-price-{{ $skuProduct['product_code'] }}">
                            {{ $auth ? '-' : currency_format($skuProduct['Msrp'] ?? 0) }}</p>
                    </td>
                    @if ($auth)
                        <td class="text-center">
                            <p class="mb-0 product-inventory-{{ $skuProduct['product_code'] }}">-</p>
                        </td>
                        <td>
                            <input class="form-control product-qty-{{ $skuProduct['product_code'] }} qty_field"
                                   type="number" value="0" min="0" max="0"
                                   oninput="document.querySelector('#sku_details_table_tr-{{ $key }}').dataset.qty = this.value = (parseInt(this.value) > 0) ? parseInt(this.value) : 0">
                        </td>
                        <td>
                            @defaultwarehouse
                            <div class="product-info">
                                <p class="text-center">
                                    {{ $warehouses->firstWhere('WarehouseNumber', $userActiveWarehouseCode)->WarehouseName }}
                                </p>
                            </div>
                            @enddefaultwarehouse
                            <select name="warehouse[]"
                                    class="form-control w-110 warehouse-selectbox-{{ $skuProduct['product_code'] }} @defaultwarehouse d-none @enddefaultwarehouse"
                                    onchange="changeSkuWarehouse('{{ $skuProduct['product_code'] }}', this.value); resetSkuData('{{ $key }}')"
                                    oninput="document.querySelector('#sku_details_table_tr-{{ $key }}').dataset.warehouse = this.value">

                                <option value="">Select Warehouse</option>
                                @forelse($warehouses as $warehouse)
                                    <option value="{{ $warehouse->WarehouseNumber }}"
                                        {{ $warehouse->WarehouseNumber === $userActiveWarehouseCode ? 'selected' : '' }}>
                                        {{ $warehouse->WarehouseName }}</option>
                                @empty
                                    <option>No Warehouse</option>
                                @endforelse
                            </select>
                        </td>
                    @endif
                </tr>
                @if (!empty($skuProduct['ERP']))
                    <tr>
                        <td colspan="{{ $warehouseRowCount }}" class="product-info">
                            @forelse($skuProduct['ERP'] as $warehouse)
                                <span
                                    id="warehouse-{{ $warehouse['ItemNumber'] }}-{{ $warehouse['WarehouseID'] }}"
                                    data-price="{{ $warehouse['Price'] }}"
                                    data-quantity="{{ $warehouse['QuantityAvailable'] }}">
                                        {{ $warehouses->firstWhere('WarehouseNumber', $warehouse['WarehouseID'])->WarehouseName ?? '' }}-{{ $warehouse['QuantityAvailable'] }}
                                    @if ($warehouse != end($skuProduct['ERP']))
                                        ,
                                    @endif &nbsp;
                                    </span>
                            @empty
                                No warehouses
                            @endforelse
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@endif
<script>
    function changeSkuWarehouse(product_code, warehouse_code) {
        if (!warehouse_code) return;
        const ProductInfo = document.getElementById(`warehouse-${product_code}-${warehouse_code}`).dataset;
        const ProductQty = document.querySelector(`.product-qty-${product_code}`);
        const ProductInventory = document.querySelector(`.product-inventory-${product_code}`);
        const ProductPrice = document.querySelector(`.product-price-${product_code}`);
        const SkuDetailsRow = document.querySelector(`.warehouse-selectbox-${product_code}`).parentElement
            .parentElement;

        let formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        });
        let price = formatter.format(parseFloat(ProductInfo.price));
        ProductQty ? ProductQty.setAttribute('min', 0) : null;
        ProductQty ? ProductQty.value = 0 : null;
        ProductQty ? ProductQty.setAttribute('max', ProductInfo.quantity) : null;
        ProductInventory ? ProductInventory.innerHTML = ProductInfo.quantity : null;
        ProductPrice ? ProductPrice.innerHTML = price : null;
        SkuDetailsRow ? SkuDetailsRow.dataset.warehouse = warehouse_code : null;
    }

    function changeSkuWarehouseSelectBox(product_code, warehouse_code) {
        const WarehouseSelectbox = document.querySelector(`.warehouse-selectbox-${product_code}`);

        if (WarehouseSelectbox) {
            WarehouseSelectbox.value = warehouse_code
        } else {
            document.getElementById(`product_warehouse_${product_code}-${warehouse_code}`).checked = true;
        }
    }

    function resetSkuData(key) {
        const ProductInfo = document.querySelector(`#sku_details_table_tr-${key}`).dataset;
        ProductInfo.qty = "";
    }

    function onAttributeChange(attributeName, attributeValue) {
        if (attributeName && attributeValue) {
            const filteredAttr = $(`*[data-filter-attribute="${attributeName}"][data-filter-value="${attributeValue}"]`)
                .parent();

            // Clear previous data.
            $(`#sku_details_table_body tr td input[type="number"]`).val(0);
            $(`#sku_details_table_body tr[data-qty]`).attr('data-qty', 0);

            // Toggling filtered data.
            $(`#sku_details_table_body tr`).removeClass('d-block');
            $(`#sku_details_table_body tr`).addClass('d-none');
            filteredAttr.removeClass('d-none');
            filteredAttr.next().removeClass('d-none');
        } else {
            $(`#sku_details_table_body tr`).removeClass('d-none');
        }
    }

    $(document).ready(function (event) {
        if (JSON.parse(localStorage.getItem('filterAttributes')) !== null) {
            localStorage.removeItem('filterAttributes');
        }

        const products = @json($sku_details);
        const userActiveWarehouseCode = "{{ $userActiveWarehouseCode }}";
        if (!products) return false;

        for (let index = 0; index < products.length; index++) {
            let selectedWarehouse = null;
            let isWarehouseSelected = false;

            for (const i in products[index].ERP) {
                const warehouse = products[index].ERP[i];

                if (!(selectedWarehouse && parseInt(selectedWarehouse.QuantityAvailable) > 0) && parseInt(
                    warehouse.QuantityAvailable) > 0) {
                    if (isWarehouseSelected) {
                        selectedWarehouse = warehouse;
                        break;
                    }
                    selectedWarehouse = warehouse;
                }

                if (warehouse.WarehouseID === userActiveWarehouseCode) {
                    if (parseInt(warehouse.QuantityAvailable) > 0) {
                        selectedWarehouse = warehouse;
                        break;
                    }

                    if (selectedWarehouse) break;

                    selectedWarehouse = warehouse;
                    isWarehouseSelected = true;
                }
            }

            if (selectedWarehouse) {
                changeSkuWarehouse(selectedWarehouse.ItemNumber, selectedWarehouse.WarehouseID);
                changeSkuWarehouseSelectBox(selectedWarehouse.ItemNumber, selectedWarehouse.WarehouseID);
            }
        }
    });
</script>

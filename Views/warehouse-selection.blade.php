@php
    /**
     * @var $inventory \Amplify\ErpApi\Collections\ProductPriceAvailabilityCollection
     * @var $customer \Amplify\ErpApi\Wrappers\Customer
     */
@endphp

@use('Amplify\System\Backend\Models\Warehouse')

<div {!! $htmlAttributes !!}>
    <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered">
            <thead>
            <tr>
                <th>Warehouse</th>
                <th>Available</th>
                <th>Unit Price</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($inventory as $stock)
                @php
                    $qty = [];
                    for ($i = 1; $i <= 6; $i++){
                        $qtyBreakVar = "QtyBreak_" . $i;
                        $qtyPriceVar = "QtyPrice_" . $i;
                        $qty[] =[
                            "break" => $stock->$qtyBreakVar,
                            "price" =>  !empty($stock->$qtyPriceVar) ? price_format($stock->$qtyPriceVar) : null,
                        ];
                    }
                @endphp
                <tr class="@if ($isActiveWarehouse($stock->WarehouseID)) table-dark @endif">
                    @if (isset($stock?->Warehouses))
                        <td scope="row" class="align-middle">{{ $stock->Warehouses->WarehouseName }}</td>
                    @else
                        @if(config('amplify.basic.client_code') === 'ACT')
                            @php
                                $warehouse = Warehouse::whereCode($stock->WarehouseID)->first();
                            @endphp
                            <td scope="row" class="align-middle">{{ $warehouse->name }}</td>
                        @else
                            <td scope="row" class="align-middle">{{ $stock->WarehouseID }}</td>
                        @endif
                    @endif

                    <td class="align-middle text-center">{{ number_format($stock->QuantityAvailable) }}</td>
                    <td class="align-middle">{{ price_format($stock->Price) . "/" . ($stock->UnitOfMeasure ?? 'EA') }}</td>
                    <td class="text-center align-middle">
                        <button type="button"
                                class="btn btn-sm btn-info my-0"
                                @disabled($isActiveWarehouse($stock->WarehouseID))
                                onclick="selectWarehouse('{{ $stock->ItemNumber }}', '{{ $stock->WarehouseID }}', '{{ price_format($stock->Price?: $stock->StandardPrice) }}' , '{{ json_encode($qty) }}')"
                        >Select
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="align-middle text-center py-3">Warehouse not available.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function selectWarehouse(product_code, warehouse_code, product_price, qty = null) {
        const quantity = JSON.parse(qty);
        let qtyHtml = '';
        quantity.forEach(item => {
            if (item.price != null) {
                qtyHtml += `<p class="d-flex justify-content-between mb-2">
                        <span>${item.break}+</span>
                        <span>${item.price}</span>
                        </p>`;
            }
        });
        $('#product_warehouse_' + product_code).val(warehouse_code);

        $('#product_price_' + product_code).text(product_price);
        $('#product_sku_qty_break_' + product_code).html(qtyHtml);

        $('#warehouse-selection').modal('hide');
        ShowNotification('success', 'Warehouse', `Warehouse code: ${warehouse_code} selected`);
    }
</script>

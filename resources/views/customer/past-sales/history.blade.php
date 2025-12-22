<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <div>
                <!-- Page Title -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h1 class="text-uppercase font-weight-bold" style="font-size: 2rem; letter-spacing: 0.1em; margin-bottom:0.25rem;">
                            Sales Statistics
                        </h1>
                    </div>
                </div>

                <!-- Product Section -->
                <div class="row mb-5">
                    <div class="col-lg-8">
                        <!-- Sales Statistics Label -->
                        <p class="text-danger font-weight-bold mb-3" style="font-size: 0.95rem;">Sales Statistics</p>

                        <div class="mb-3 product-details">
                            <p class="mb-1"><strong style="font-size:0.95rem;">PART NUMBER:</strong> <span style="font-size:0.95rem;">{{ $prod ?? ($product->product_code ?? '-') }}</span></p>
                            <p class="mb-2" style="font-size:0.92rem; color:#333;">{{ $product->product_name ?? ($product->description ?? '-') }}</p>
                        </div>

                        <!-- Hidden product code and quantity inputs used by cart.js -->
                        <input type="hidden" id="product_code" value="{{ $prod ?? ($product->product_code ?? '') }}" />
                            <div style="max-width:260px;">
                                <label for="single_product_qty" class="font-weight-bold" style="font-size:0.92rem;">QUANTITY:</label>
                                <input type="number" class="form-control mb-2" id="single_product_qty" placeholder="0" min="0" style="max-width:190px; max-height: 38px;">

                                <button id="add_to_order_btn" type="button" class="btn btn-primary btn-sm font-weight-bold"
                                    style="background-color: #336699; border: none; padding:8px 14px; line-height:1; display:inline-block;" onclick="addSingleProductToOrder()">
                                    ADD TO CART
                                </button>
                            </div>
                    </div>
                </div>

                <!-- Sales Table with Navigation Buttons -->
                <div class="table-responsive position-relative">
                    <!-- Navigation Buttons aligned to top-right -->
                    <div class="d-flex justify-content-end align-items-center gap-2 mb-3" style="margin-right: 0;">
                        <a href="{{ request()->fullUrlWithQuery(['year' => ($year ?? date('Y')) - 1]) }}" class="btn btn-primary btn-sm font-weight-bold" style="background-color: #336699; border: none; padding:1px 12px;">PREVIOUS YEAR</a>
                        @if( (int) ($year ?? date('Y')) < (int) date('Y') )
                            <a href="{{ request()->fullUrlWithQuery(['year' => ($year ?? date('Y')) + 1]) }}" class="btn btn-primary btn-sm font-weight-bold" style="background-color: #336699; border: none; padding:1px 12px;">NEXT YEAR</a>
                        @endif
                    </div>
                    <table class="table table-hover" style="background-color: #f8f9fa;">
                        <thead style="background-color: #e8e8e8;">
                            <tr>
                                <th class="text-uppercase font-weight-bold" style="font-size: 0.85rem; color: #333;">Month</th>
                                <th class="text-uppercase font-weight-bold" style="font-size: 0.85rem; color: #333;">Year</th>
                                <th class="text-uppercase font-weight-bold text-center" style="font-size: 0.85rem; color: #333;">Quantity Purchased</th>
                                <th class="text-uppercase font-weight-bold" style="font-size: 0.85rem; color: #333;">Average Purchase Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $months = $sales['months'] ?? null;
                                $fallbackMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                $displayYear = $year ?? date('Y');
                            @endphp

                            @if(!empty($months) && is_array($months))
                                @foreach($months as $m)
                                        <tr>
                                            <td class="align-middle">{{ $m['month'] }}</td>
                                            <td class="align-middle">{{ $m['year'] }}</td>
                                            <td class="text-center align-middle">{{ $m['quantity_purchased'] }}</td>
                                            <td class="align-middle">{{ $m['average_purchase_price_formatted'] }}</td>
                                        </tr>
                                @endforeach
                            @else
                                @foreach($fallbackMonths as $idx => $mon)
                                        <tr>
                                            <td class="align-middle">{{ $mon }}</td>
                                            <td class="align-middle">{{ $displayYear }}</td>
                                            <td class="text-center align-middle">0</td>
                                            <td class="align-middle">$0.00</td>
                                        </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        border-bottom: 2px solid #ddd;
    }

    .table td {
        vertical-align: middle;
        padding: 12px;
    }

    .btn-primary:hover {
        opacity: 0.9;
    }

    .gap-2 {
        gap: 0.5rem !important;
    }
</style>

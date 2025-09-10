<div class="container d-flex flex-wrap gap-3 justify-content-between buy-now p-4" style=" background: #BC2127; border-radius: 5px;">
    <input type="hidden" value="{{$product->ItemNumber ?? ''}}" id="product_code_highlighted"/>
    <input type="hidden" value="{{$product->WarehouseID ?? ''}}" id="product_warehouse_highlighted"/>
    <div style="color: white">
        <h3 class="fw-600" style="color: white;">{{$title ?? ''}}</h3>
        <p class="mb-0">{{$description ?? ''}}</p>
    </div>
    <div>
    <button type="button" id="add_to_order_btn_highlighted" onclick="highlightedProductAddToCart('2')" class="btn" style=" color: Black ; background: white">Learn more
    </button>
    <button type="button" id="add_to_order_btn_highlighted" onclick="highlightedProductAddToCart('1')" class="btn" style=" color: white ; background: {{$buttonColor ?? ''}}">{{$buttonText ?? ''}}
    </button>
    </div>
    
</div>
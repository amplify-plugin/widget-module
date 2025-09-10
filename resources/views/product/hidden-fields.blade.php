{{-- input hidden fields needed retrieve the product information for cart summary for product details views --}}
<input id="product_code{{$input}}"
       name="product_code{{$name}}"
       type="hidden"
       @if(isset($product->Product_Code))
       value="{{$product->Product_Code}}"
       @elseif(isset($product->Sku_ProductCode))
       value="{{$product->Sku_ProductCode}}"
       @else
       value=""
    @endif
/>

<input id="single_product_id{{$input}}"
       name="product_id{{$name}}"
       type="hidden"
       @if(isset($product->Product_Id))
       value="{{$product->Product_Id}}"
       @elseif(isset($product->Product_Id))
       value="{{$product->Product_Id}}"
       @else
       value=""
    @endif
/>

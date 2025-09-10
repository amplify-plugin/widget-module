<div {!! $htmlAttributes !!}>
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
            <label class="form-check-label fs-16 text-black" for="inlineRadio1">Accessories</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
            <label class="form-check-label fs-16 text-black" for="inlineRadio2">Alternative</label>
        </div>
    </div>
   @for($i=0; $i<3; $i++)
       <div class="product-sku-item text-black">
           <div  class="w-100 d-flex justify-content-between align-items-sm-center gap-4 flex-column flex-sm-row">
               <div class="product-img">
                   <img src="" alt="product-img">
               </div>
               <div>
                   <div class="text-uppercase fs-16">GSCC3</div>
                   <div class="mb-2 font-roboto">Mersen 5 x 20mm Glass/Ceramic Time Delay Fuse.</div>
                   <div class="specs-container">
                       <div class="spec-column">
                           <div class="text-nowrap">Voltage (AC) :</div>
                           <div class="font-roboto text-nowrap">250 VAC</div>

                           <div class="text-nowrap">Amperage :</div>
                           <div class="font-roboto text-nowrap">3 A</div>

                           <div class="text-nowrap">Body Type :</div>
                           <div class="font-roboto text-nowrap">Glass</div>
                       </div>

                       <div class="spec-column">
                           <div class="text-nowrap">Fuse Type :</div>
                           <div class="font-roboto text-nowrap">Time Delay</div>

                           <div class="text-nowrap">Size :</div>
                           <div class="font-roboto text-nowrap">5 x 20mm</div>
                       </div>

                       <div class="spec-column">
                           <div class="text-nowrap">Est. Lead Time :</div>
                           <div class="font-roboto text-nowrap">2-3 weeks</div>

                           <div class="text-nowrap">Available Qyt :</div>
                           <div class="font-roboto text-nowrap">80000</div>

                           <div class="text-nowrap">Price :</div>
                           <div class="font-roboto text-nowrap">$1.88/each</div>
                       </div>

                   </div>
               </div>
               <div class="d-flex gap-3 align-self-sm-end flex-wrap">
                   <div class="d-flex gap-2 flex-column m-0">
                       <button class="flex-center gap-2 btn btn-block btn-outline-primary btn-sm m-0">
                           <i class="icon-heart"></i>
                           Add to Wishlist
                       </button>
                       <x-product-shopping-list :product-id="1" />
                   </div>
                   <div class="d-flex flex-column align-self-end gap-2">
                       <div class="w-100">
                           <div class="fw-500 align-self-center">Quantity:</div>
                           <div class="align-items-center d-flex product-count mt-2 gap-2 justify-content-between">
                               {{--<span
                                   class="d-flex align-items-center justify-content-center fw-600 flex-shrink-0 rounded border"
                                   onclick="productQuantity(1,'minus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                                    <i class="icon-minus fw-700"></i>
                                </span>

                               <input type="text" class="form-control form-control-sm text-center"
                                      style="height: 30px; border-radius: 0 !important; border: 1px solid #999999;"
                                      id="product_qty_1"
                                      name="qty[]" value="{{ $product?->min_order_qty ?? 1 }}"
                                      min="{{ $product?->min_order_qty ?? 1 }}" step="{{ $product?->qty_interval }}"
                                      oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                               <input type="hidden" id="product_code_1" value="{{ $product->Product_Code }}" />
                               <input id="product_warehouse_1" type="hidden"
                                      value="{{ optional(optional(customer(true))->warehouse)->code }}" />
                               <input type="hidden" id="product_warehouse_{{ $product->Product_Code }}"
                                      value="{{ $product?->ERP?->WarehouseID ?? '' }}" />

                               <span class="text-white bg-dark d-flex align-items-center justify-content-center
                                    fw-600 flex-shrink-0 rounded border"
                                     onclick="productQuantity(1,'plus', {{ $product?->qty_interval ?? 1 }}, {{ $product?->min_order_qty ?? 1 }})">
                                    <i class="icon-plus fw-700"></i>
                                </span>--}}
                           </div>
                       </div>
                       <button class="add_to_cart_custom">Add to cart</button>
                   </div>
               </div>
           </div>

           <div class="d-flex justify-content-between gap-4 w-100 flex-wrap">
               <div>
                   Shipping Restriction:Billing or shipping address must be OR, WA, ID or MT
{{--                   {!!  $product->ship_restriction ?? null !!}--}}
               </div>


               <div class="text-warning fw-500">
                   It is non-cancelable, non-returnable
               </div>
{{--               <x-product.ncnr-item-flag :product="$product" :show-full-form="true"/>--}}
               <div class="d-flex gap-2">
                   <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                       <g clip-path="url(#clip0_1743_9021)">
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M0.390716 16.0781L10.0782 15.375L14.6095 15.625L19.047 18.0938C19.0938 18.125 19.0157 18.1563 18.8751 18.1719L7.40634 19.9844C7.26572 20 7.09384 20 7.04697 19.9688L0.218841 16.1562C0.156341 16.125 0.234466 16.0938 0.390716 16.0781Z" fill="black" fill-opacity="0.125"/>
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M7.42188 0L16.4531 1.875L19.8125 5.21875V17.8594C19.8125 18 19.7031 18.0938 19.5469 18.1094L7.42188 20C7.28125 20.0156 7.15625 19.8906 7.15625 19.75V0.265625C7.15625 0.125 7.26563 0 7.42188 0Z" fill="url(#paint0_linear_1743_9021)"/>
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M7.53125 0.015625L7.6875 0.046875L7.73437 0.0625V19.9531L7.53125 19.9844V0.015625ZM8.0625 0.125L8.15625 0.15625V19.8906L7.95313 19.9219V0.109375L8.0625 0.125ZM8.5625 19.8125L8.35937 19.8438V0.1875L8.5625 0.234375V19.8125Z" fill="black" fill-opacity="0.125"/>
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M7.17188 9.01563L7.15625 1.10938L14.6719 2.5625C15.0625 2.64062 15.3906 2.89062 15.3906 3.28125V6.35938C15.3906 6.75 15.0781 7.03125 14.6875 7.125L7.17188 9.01563ZM16.2656 1.875H16.4375L19.7969 5.21875V5.39062H16.8281C16.5156 5.39062 16.25 5.14062 16.25 4.8125V1.875H16.2656Z" fill="black" fill-opacity="0.25"/>
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M4.92204 0.687742L14.8439 2.56274C15.1408 2.62524 15.3908 2.81274 15.3908 3.10962V6.34399C15.3908 6.64087 15.1408 6.84399 14.8439 6.89087L4.92204 8.79712C4.62517 8.85962 4.37517 8.54712 4.37517 8.25024V1.23462C4.35954 0.937742 4.62517 0.625242 4.92204 0.687742Z" fill="url(#paint1_linear_1743_9021)"/>
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M19.7969 5.21875H16.8281C16.6094 5.21875 16.4375 5.04688 16.4375 4.82812V1.875L19.7969 5.21875Z" fill="url(#paint2_linear_1743_9021)"/>
                           <path d="M6.90625 6.45312V3.09375H7.65625C8 3.09375 8.25 3.1875 8.40625 3.375C8.57812 3.5625 8.65625 3.84375 8.65625 4.21875C8.65625 4.57813 8.5625 4.84375 8.375 5.04688C8.1875 5.25 7.9375 5.34375 7.60938 5.34375H7.54687V6.4375H6.90625V6.45312ZM7.5625 3.59375V4.82812H7.59375C7.73437 4.82812 7.84375 4.78125 7.90625 4.67188C7.98437 4.5625 8.01562 4.40625 8.01562 4.20312C8.01562 4 7.98437 3.85938 7.90625 3.75C7.82812 3.65625 7.71875 3.59375 7.5625 3.59375ZM9.07812 6.45312V3.09375H9.73437C10.1875 3.09375 10.5 3.21875 10.7031 3.46875C10.9062 3.71875 11 4.125 11 4.6875C11 5.29688 10.9062 5.73438 10.7031 6.01562C10.5156 6.29688 10.2031 6.4375 9.78125 6.4375H9.07812V6.45312ZM9.71875 5.92188H9.73437C9.95312 5.92188 10.0937 5.84375 10.1875 5.67188C10.2812 5.5 10.3125 5.20313 10.3125 4.78125C10.3125 4.34375 10.2656 4.04688 10.1875 3.875C10.0937 3.70312 9.95312 3.625 9.73437 3.625H9.70312V5.92188H9.71875ZM11.4375 6.45312V3.09375H12.8437V3.625H12.0938V4.42188H12.8437V4.9375H12.0938V6.4375H11.4375V6.45312Z" fill="white"/>
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M13.1251 12.7188L13.7032 13.3906C13.797 13.4844 13.9063 13.5781 14.0157 13.6719L14.5313 14.0938C14.7032 14.2344 14.8907 14.3594 15.0782 14.4844C15.1407 14.5156 15.3282 14.6406 15.3907 14.6406C15.6407 14.6406 15.8907 14.625 16.1407 14.6094C16.5001 14.5938 16.8594 14.5781 17.2188 14.5781C17.6407 14.5781 18.1095 14.5938 18.5313 14.6875C18.8438 14.75 19.2657 14.8906 19.4063 15.2188C19.422 15.2656 19.4376 15.3281 19.4532 15.3906C19.4688 15.5625 19.4376 15.7188 19.3438 15.8594C19.1876 16.0781 18.922 16.2031 18.672 16.25C18.3126 16.3125 17.8595 16.25 17.5001 16.1875C17.1407 16.125 16.7657 16.0313 16.4063 15.9063C16.1876 15.8438 15.9845 15.75 15.7813 15.6562C15.6251 15.5938 15.4845 15.5313 15.3282 15.4688C15.2657 15.4375 15.1563 15.3906 15.0782 15.375C14.6563 15.375 14.2032 15.4375 13.7813 15.5156C13.3282 15.6094 12.8751 15.7188 12.4376 15.8438C12.2345 15.9063 11.7813 16 11.672 16.1719C11.3282 16.6563 10.922 17.2969 10.3595 17.5469C9.93758 17.7344 9.4532 17.5938 9.21883 17.1875C9.15633 17.0781 9.12508 16.9531 9.1407 16.8281C9.21883 16.2656 10.1407 15.9375 10.6095 15.7344C10.7501 15.6719 10.8907 15.625 11.0313 15.5781C11.0938 15.5625 11.1563 15.5312 11.2188 15.5C11.2657 15.4688 11.297 15.4063 11.3282 15.3594C11.4845 15.0938 11.6251 14.8281 11.7501 14.5469C11.797 14.4219 11.8438 14.2812 11.8751 14.1562C11.9376 13.9688 11.9845 13.7656 12.0313 13.5625C12.0782 13.3594 12.1876 12.8125 12.1407 12.6094L12.047 12.4688C11.9845 12.3594 11.9063 12.25 11.8438 12.1563C11.6563 11.8594 11.4845 11.5469 11.3282 11.2344C11.1407 10.875 10.922 10.4062 10.8751 10C10.8126 9.48438 10.9845 9.17187 11.4845 9C11.6876 8.9375 11.8907 8.92188 12.0938 9.01563C12.6095 9.26563 12.7657 10.0938 12.8282 10.625C12.8751 11.0312 12.8751 11.4531 12.8751 11.8594V12.25C12.8751 12.2969 12.8751 12.3594 12.8907 12.3906C12.9063 12.4219 12.922 12.4688 12.9532 12.5C13.0157 12.5781 13.0782 12.6406 13.1251 12.7188ZM12.1407 11.1875C12.1095 10.6719 12.047 10.1094 11.8907 9.8125C11.8595 9.75 11.7501 9.67188 11.672 9.6875C11.6251 9.70313 11.6251 9.82812 11.6251 9.85937C11.6251 10.1406 11.797 10.5156 11.922 10.75C12.0001 10.8906 12.0782 11.0469 12.1407 11.1875ZM17.0157 15.3125C17.4063 15.4219 17.8126 15.5156 18.1876 15.5156C18.3282 15.5156 18.4688 15.5156 18.5938 15.4844C18.6095 15.4844 18.6251 15.4688 18.6563 15.4688C18.5938 15.4375 18.5157 15.4219 18.4845 15.4062C18.3126 15.3594 18.1095 15.3438 17.9376 15.3281C17.7032 15.3125 17.4688 15.2969 17.2345 15.2969C17.172 15.3125 17.0938 15.3125 17.0157 15.3125ZM12.2813 15.1563C12.4532 15.1094 12.6095 15.0625 12.7813 15.0156C13.1563 14.9062 13.547 14.8281 13.9376 14.7656C14.0313 14.75 14.1095 14.75 14.2032 14.7344L14.0626 14.625C13.8282 14.4688 13.5782 14.2344 13.3751 14.0313C13.1876 13.8438 13.0001 13.6562 12.8282 13.4531C12.7188 14.0625 12.5313 14.6094 12.2813 15.1563ZM10.4063 16.6562C10.3907 16.6719 10.3595 16.6719 10.3438 16.6875C10.2657 16.7188 10.0626 16.8281 9.96883 16.9062H10.0001C10.1251 16.9219 10.297 16.7656 10.4063 16.6562Z" fill="url(#paint3_linear_1743_9021)"/>
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0471 16.1717C10.8908 16.3904 10.2346 17.4217 9.78144 17.0311C9.54706 16.8279 10.1252 16.5623 10.2658 16.4998C10.5002 16.3904 10.7971 16.2498 11.0471 16.1717ZM11.8908 15.4842C12.2502 14.7498 12.5939 13.9842 12.6564 12.9842L12.9064 13.2498C13.2033 13.5623 13.7971 14.2186 14.1721 14.4529L14.7189 14.8592C14.5783 14.9061 14.1408 14.9373 13.9533 14.9529C13.0939 15.0936 12.6564 15.2654 11.8908 15.4842ZM18.5471 15.2186C19.4533 15.4217 18.8752 16.1561 16.5783 15.3904C16.4689 15.3592 16.3752 15.3123 16.2502 15.2811C16.1252 15.2342 16.0158 15.2186 15.9377 15.1404C16.5627 15.0936 17.9064 15.0779 18.5471 15.2186ZM12.3752 11.9373C12.2346 11.7967 11.8752 11.1092 11.7346 10.8592C10.9533 9.31231 11.8283 9.31231 12.0627 9.71856C12.3596 10.2029 12.3752 11.3592 12.3752 11.9373ZM11.5783 9.20294C11.2346 9.32794 11.0627 9.51544 11.1096 9.98419C11.2189 10.8748 12.3283 12.4529 12.3596 12.5467C12.4846 12.9373 12.1096 14.2811 11.9689 14.6404C11.8439 14.9686 11.6877 15.2186 11.5314 15.4842C11.3596 15.7811 11.2658 15.7342 10.7189 15.9529C10.3127 16.1248 9.10956 16.5467 9.42206 17.1092C9.56269 17.3748 9.92207 17.5467 10.2971 17.3748C10.7971 17.1404 11.2033 16.5154 11.5158 16.0623C11.6877 15.8279 12.1096 15.7498 12.3908 15.6561C13.0939 15.4373 14.3283 15.1561 15.1096 15.1717C15.2346 15.1717 16.1096 15.5779 16.4846 15.7029C17.0314 15.8748 18.0314 16.1404 18.6408 16.0311C18.9689 15.9842 19.2971 15.7654 19.2658 15.3904C19.1721 14.5154 16.1877 14.8279 15.4064 14.8436C15.0627 14.8436 13.7971 13.7811 13.5471 13.5311L12.9689 12.8436C12.6564 12.4061 12.6877 12.5154 12.6877 11.8592C12.6877 11.4686 12.6721 11.0311 12.6408 10.6404C12.5627 10.0154 12.3283 8.93731 11.5783 9.20294Z" fill="white"/>
                       </g>
                       <defs>
                           <linearGradient id="paint0_linear_1743_9021" x1="7.15625" y1="0" x2="19.8125" y2="0" gradientUnits="userSpaceOnUse">
                               <stop stop-color="#D74A4B"/>
                               <stop offset="1" stop-color="#891719"/>
                           </linearGradient>
                           <linearGradient id="paint1_linear_1743_9021" x1="4.37451" y1="0.679688" x2="15.3908" y2="0.679688" gradientUnits="userSpaceOnUse">
                               <stop stop-color="#E15355"/>
                               <stop offset="1" stop-color="#CD1F26"/>
                           </linearGradient>
                           <linearGradient id="paint2_linear_1743_9021" x1="16.4375" y1="1.875" x2="19.7969" y2="1.875" gradientUnits="userSpaceOnUse">
                               <stop stop-color="#E15355"/>
                               <stop offset="1" stop-color="#CD1F26"/>
                           </linearGradient>
                           <linearGradient id="paint3_linear_1743_9021" x1="9.13672" y1="8.94922" x2="19.457" y2="8.94922" gradientUnits="userSpaceOnUse">
                               <stop stop-color="#D74A4B"/>
                               <stop offset="1" stop-color="#891719"/>
                           </linearGradient>
                           <clipPath id="clip0_1743_9021">
                               <rect width="20" height="20" fill="white"/>
                           </clipPath>
                       </defs>
                   </svg>
                   <a href="#" class="text-blue">Datasheet</a>
               </div>
{{--                   <x-product.default-document-link :document="$product->default_document" class="list_shop_datasheet_product"/>--}}
           </div>
       </div>
    @endfor
</div>

<script>
    function productQuantity(id, type, interval, min) {
        let item = document.getElementById(`product_qty_${id}`);
        let val = parseInt(item.value);
        switch (type) {
            case 'plus':
                item.value = val + interval;
                break;
            case 'minus':
                if (val > min) {
                    item.value = val - interval;
                }
                break;
        }
    }
</script>

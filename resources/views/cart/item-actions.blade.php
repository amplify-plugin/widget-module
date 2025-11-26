<a class="update-from-cart"
   href="#"
   onclick="Amplify.updateCartItem('#cart-item-{cart_item_id}', {cart_item_id});"
   data-toggle="tooltip"
   title="Update item"
><i style="font-size: 1.2rem; font-weight: bolder;" class="icon-repeat"></i>
</a>
<a class="remove-from-cart ml-3"
   href="#"
   data-action-link="{{ route('frontend.carts.remove-item', 'cart_item_id') }}"
   onclick="Amplify.removeCartItem({cart_item_id});"
   data-toggle="tooltip"
   title="Remove item"
><i style="font-size: 1.2rem; font-weight: bolder;" class="icon-cross"></i>
</a>

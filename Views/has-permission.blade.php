@if(customer_check() && customer(true)->can($permission))
    {!!  $slot ?? ''  !!}
@endif

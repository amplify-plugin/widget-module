<script>
    const AMPLIFY_DATE_MOMENT_FORMAT = '{{ carbon2moment_format(config('amplify.basic.date_format')) }}';
    const AMPLIFY_BASE_URL = '{{ config('app.url') }}';
    const AMPLIFY_DATE_FORMAT = '{{ config('amplify.basic.date_format') }}';
    const AMPLIFY_BASE_CURRENCY = '{{ config('amplify.basic.global_currency', 'USD') }}';
    const AMPLIFY_SAYT_CAT_PATH = '{{ \Sayt::getDefaultCatPath() }}';
</script>
<x-cookie-consent />
<!-- Plugin Script -->
@stack('plugin-script')
<!-- Template Script -->
@stack('template-script')
<!-- HTML Default -->
@stack('html-default')
@include('cms::inc.delete_confirm')
@include('cms::inc.order_confirm')
<!-- Custom Script Footer-->
<x-site.script-manager position="footer" />
<!-- Google Analytics -->
<x-site.script-manager position="google_event" />
<!-- Custom Script -->
<script
    @switch(config('amplify.client_code'))
    @case('RHS')
        src="{{ theme_asset('rhsparts/js/rhs-cart.js') }}"
        @break
    @case('NUX')
        src="{{ asset('themes/nudraulix/assets/js/cart.js') }}"
        @break
    @default
        src="{{ asset('assets/js/cart.js') }}"
@endswitch
    id='cart-script' data-cart-page-url="{{ customerCartUrl() }}"></script>
@stack('custom-script')
<!-- Internal Script -->
@stack('internal-script')
<!-- Footer Script -->
@stack('footer-script')
<script>
    $(document).ready(function() {
        renderCartItems();
    });
</script>
<x-no-image-skeleton />
<x-site.toastr-notification />

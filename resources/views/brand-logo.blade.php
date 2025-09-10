<div class="site-branding position-relative" {!! $htmlAttributes !!}>
    <div class="inner">
        <!-- Off-Canvas Toggle (#mobile-menu)-->
        <a class="offcanvas-toggle menu-toggle" href="#mobile-menu" data-toggle="offcanvas"></a>
        <!-- Site Logo-->
        <a class="site-logo" href="{{ route('frontend.index') }}">
            <img src="{{ config('amplify.cms.logo_path', asset('img/Amplify Logo 280 tagline.png')) }}"
                 alt="{{$imageAlt ?? config('app.name', 'Amplify')}}">
        </a>
    </div>
</div>

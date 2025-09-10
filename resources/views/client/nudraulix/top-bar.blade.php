<div {!! $htmlAttributes !!}>
<div class="row">
    <div class="col-12">
        <div class="topbar border-0">
            <div class="topbar-column">
                    @if(config('amplify.cms.email'))
                        @if(strlen(config('amplify.cms.email', '')) > 0)
                            <a class="topbar-contact" href="mailto:{{ config('amplify.cms.email') }}"
                               style="white-space: nowrap">
                                <i class="icon-mail"></i>&nbsp; {{ config('amplify.cms.email') }}
                            </a>
                        @endif
                    @endif
                    @if(config('amplify.cms.phone'))
                        @if(strlen(config('amplify.cms.phone', '')) > 0)
                            <a class="topbar-contact" href="tel:{{ config('amplify.cms.phone') }}"
                               style="white-space: nowrap">
                                <i class="socicon-viber"></i>&nbsp; {{ config('amplify.cms.phone') }}
                            </a>
                        @endif
                    @endif

                <div class="social-button">
                    <a class="topbar-social-icon" href="#">
                        <i class="socicon-facebook"></i>
                    </a>
                    <a class="topbar-social-icon" href="#">
                        <i class="socicon-youtube"></i>
                    </a>
                    <a class="topbar-social-icon" href="#">
                        <i class="socicon-instagram"></i>
                    </a>
                    <a class="topbar-social-icon pt-0" href="#">
                        <img style="height: 11.5px" src="{{ asset('frontend\nudraulix\images\socials\twitter-icon.svg') }}" alt="twitter">
                    </a>
                </div>

                <x-site.language-change/>
            </div>
        </div>
    </div>
</div>
</div>


<style>
    @media (max-width: 660px) {
        .topbar.border-0 {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .topbar {
             height: auto;
        }
        .topbar .topbar-column {
            width: 100% !important;
        }
        .topbar .topbar-column:last-child {
            display: flex;
            justify-content: space-between;
        }
    }
</style>

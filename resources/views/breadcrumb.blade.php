<div {!! $htmlAttributes !!}>
    <div @class(['container-collapse-p0', 'container-fluid' => theme_option('full_screen_header'),  'container' => !theme_option('full_screen_header')])>
        <div class="row">
        @if(!$hideTitle)
            <div class="column col-md">
                <h1 class="cs-truncate-1"
                    data-toggle="tooltip"
                    data-html="true"
                    data-placement="bottom"
                    title="{!!  $displayPageTitle() !!}">
                    {!!  $displayPageTitle() !!}
                </h1>
            </div>
        @endif
        <div class="column col-md">
            <ul class="breadcrumbs cs-truncate-1">
                @foreach($breadcrumbs as $breadcrumb)
                    @if($breadcrumb != $breadcrumbs->last())
                        <li>
                            <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                        </li>
                        <li class="separator">&nbsp;</li>
                    @else
                        <li>{!! $breadcrumb->title !!}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        </div>
    </div>
</div>

<div {!! $htmlAttributes !!}>
    <div class="page-title">
        <div class="@if(theme_option('full_screen_header')) container-fluid @else container @endif">
            @if(!$hideTitle)
                <div class="column">
                    <h1 class="cs-truncate-1"
                        data-toggle="tooltip"
                        data-html="true"
                        data-placement="bottom"
                        title="{!!  $title !!}">
                        {!!  $title !!}
                    </h1>
                </div>
            @endif
            <div class="column">
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

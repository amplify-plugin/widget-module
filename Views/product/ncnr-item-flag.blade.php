<div {!! $htmlAttributes !!}>
    <p class="ncnr-text" @if(!$showFullForm) data-toggle="tooltip" data-placement="top"
       title="Non-Cancelable, Non-Returnable" @endif>
        @if($showFullForm)
            It is non-cancelable, non-returnable
        @else
            NCNR
        @endif
    </p>
</div>

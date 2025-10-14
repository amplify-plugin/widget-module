<div {!! $htmlAttributes !!}>
    <span class="ncnr-text" @if(!$showFullForm) data-toggle="tooltip" data-placement="top"
       title="Non-Cancelable, Non-Returnable" @endif>
        @if($showFullForm)
            This product is non-cancelable, non-returnable
        @else
            NCNR
        @endif
    </span>
</div>

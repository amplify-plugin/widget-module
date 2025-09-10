<div class="checkout-footer margin-top-1x">
    <div class="column">
        @if($index == 0)
            <a class="btn btn-outline-secondary" href="{{ url('carts') }}"><i
                    class="icon-arrow-left"></i><span class="hidden-xs-down">&nbsp;Back To Cart</span>
            </a>
        @else
            <a class="btn btn-outline-secondary" onclick="back('{{ $id }}')">
                <i class="icon-arrow-left"></i><span class="hidden-xs-down">&nbsp;Back</span>
            </a>
        @endif
    </div>
    <div class="column">
        @if($index == 3)
            <button type="submit" class="btn btn-success" onclick="next('{{ $id }}')">
                <span class="hidden-xs-down">
                    Complate&nbsp;</span>
                <i class="icon-circle-check"></i>
            </button>
        @else
            <button type="button" class="btn btn-primary" onclick="next('{{ $id }}')">
                <span class="hidden-xs-down">
                    Continue&nbsp;</span>
                <i class="icon-arrow-right"></i>
            </button>
        @endif
    </div>
</div>

<div {!! $htmlAttributes !!}>
<form action="{{ route('frontend.subscribe') }}" method="post" autocomplete="off" spellcheck="false">
    @csrf
    <div class="x-subscriber">
        <div class="d-flex justify-content-between align-items-center border gap-2">
            <input class="bg-transparent border-0 form-control fs-18 p-1 pl-3 text-dark"
                   id="email"
                   name="email"
                   required
                   type="email" placeholder="{{ $displayPlaceholder() }}">
            <button class="align-items-center btn btn-primary d-flex m-0 rounded rounded-left-0" type="submit">
                <i class="icon-bell mr-1"></i> {{ $displayButtonTitle() }}
            </button>
        </div>
    </div>
</form>
</div>

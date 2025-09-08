<div {!! $htmlAttributes !!}>
    {!! \Form::open([ 'url' => $actionUrl, 'method' => 'post', 'autocomplete' =>"on", 'spellcheck' => 'false']) !!}
    <x-honeypot/>
    @foreach($fields as $field)
        @php
            $type = "rText";
                if (isset($field['type'])) {
                    $type = $field['type'];
                    if (isset($field['inline']) && $field['inline'] == 'true') {
                        $type= preg_replace('/^r(\w+)/im', 'h$1', $type);
                    }
                }
        @endphp
        {!! \Form::$type($field['name'], $field['label'], old($field['name'], ($field['default'] ?? null)), $field['required']) !!}
    @endforeach
    @if($allowCaptcha)
        <div class="d-flex justify-content-start mb-3">

        </div>
    @endif
    <div class="d-flex @if($allowReset == true)justify-content-between @else justify-content-center @endif">
        @if($allowReset == true)
            <button type="reset" class="btn btn-danger font-weight-bold">{{ __($clearButtonTitle) }}</button>
        @endif
        {!! Form::submit('submit', __($submitButtonTitle), true) !!}
    </div>
    {!! \Form::close() !!}
</div>

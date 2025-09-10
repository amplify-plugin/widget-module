<td class="flex-column justify-content-center m-0">
    @isset($is_delete)
        <a href="{{ $route }}"
           class="delete-modal btn btn-outline-warning btn-sm text-decoration-none  {{ $class ?? '' }}"
           onclick="setPositionOffCanvas(false); updateModal(this)"
           data-toggle="modal"
           data-target="#delete-modal"
        >{!! $label !!}
        </a>
    @else
        <a class="btn btn-outline-warning btn-sm text-decoration-none {{ $class ?? '' }}"
           href="{{ $route }}">
            {!! $label !!}
        </a>
    @endisset
</td>

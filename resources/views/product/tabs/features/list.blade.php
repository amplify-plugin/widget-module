<div @class(["col-md-6", "d-none" => empty($group?->group_items) ])>
    <h4>{{ $group->group_name ?? '' }}</h4>
    <ul class="pl-0">
        @foreach ($group?->group_items ?? [] as $item)
            <li class="d-flex justify-content-start gap-2"><b>{!! $item->name ?? null  !!} :</b> {!! $item->value ?? null !!}</li>
        @endforeach
    </ul>
</div>

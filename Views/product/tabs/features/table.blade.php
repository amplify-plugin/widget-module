<div @class(["col-md-6", "d-none" => empty($group->group_items) ])>
    <h4>{{ $group->group_name ?? '' }}</h4>
    <table class="table table-striped">
        @foreach ($group->group_items ?? [] as $item)
            <tr>
                <th>{{ $item->name ?? null }}</th>
                <td>{{ $item->value ?? null }}</td>
            </tr>
        @endforeach
    </table>
</div>

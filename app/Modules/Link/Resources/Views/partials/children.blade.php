<tr data-entry-id="{{ $children->id }}">
    <td><input type="checkbox" name="ids[]" value="{{ $children->id }}" id="select-all" /></td>
    <td> - {{ $children->name }}</td>
    <td>{{ $children->order }}</td>
    <td>
        <span class="label label-info label-green">
            {{ strtoupper($children->status) }}
        </span>
    </td>
    <td>
        <a href="{{ route('admin.menus.edit', $children->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> Edit</a>
        <a href="{{ route('admin.menus.delete', $children->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
    </td>
</tr>

@if ($children->children->count() > 0)
@foreach($children->children as $item)
<tr data-entry-id="{{ $item->id }}">
    <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
    <td> -- {{ $item->name }}</td>
    <td>{{ $item->order }}</td>
    <td>
        <span class="label label-info label-green">
            {{ strtoupper($children->status) }}
        </span>
    </td>
    <td>
        <a href="{{ route('admin.menus.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> Edit</a>
        <a href="{{ route('admin.menus.delete', $item->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
    </td>
</tr>
@endforeach
@endif
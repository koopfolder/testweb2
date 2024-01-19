<tr data-entry-id="{{ $children->id }}">
    <td><input type="checkbox" name="ids[]" value="{{ $children->id }}" id="select-all" /></td>
    <td></td>
    <td>- {{ str_limit($children->title, 60) }}</td>
    <td> <span class="label label-info label-green">{{ strtoupper($children->status) }}</span></td>
    <td>{{ date('d M Y', strtotime($children->created_at)) }}</td>
    <td>
        <a href="{{ route('admin.news.categories.edit', $children->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> แก้ไข</a>
        <a href="{{ route('admin.categories.delete', $children->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> ลบ</a>
    </td>
</tr>

@if ($children->children->count() > 0)
@foreach($children->children as $item)
<tr data-entry-id="{{ $item->id }}">
    <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
    <td></td>
    <td>-- {{ str_limit($item->title, 60) }}</td>
    <td><span class="label label-info label-green">{{ strtoupper($item->status) }}</span></td>
    <td>
        <a href="{{ route('admin.news.categories.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> แก้ไข</a>
        <a href="{{ route('admin.categories.delete', $item->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> ลบ</a>
    </td>
</tr>
@endforeach
@endif
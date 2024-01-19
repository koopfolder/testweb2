<tr data-entry-id="{{ $children->id }}">
    <td><input type="checkbox" name="ids[]" value="{{ $children->id }}" id="select-all" /></td>
    <td> - {{ $children->title }}</td>
    <td>{{ $children->order }}</td>
    <td>
        @if($children->status == 'publish')
            <span class="label label-info">
                {{  trans('article::backend.publish')  }}
            </span>
        @else
            <span class="label label-danger">
                {{  trans('article::backend.draft')  }}
            </span>
        @endif
    </td>
    <td>
        <a href="{{ route('admin.exhibition.master.edit', $children->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('exhibition::backend.edit') }}</a>
        <a href="{{ route('admin.exhibition.master.delete', $children->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('exhibition::backend.delete') }}</a>
    </td>
</tr>

@if ($children->children->count() > 0)
@foreach($children->children as $item)
<tr data-entry-id="{{ $item->id }}">
    <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
    <td> -- {{ $item->title }}</td>
    <td>{{ $item->order }}</td>
    <td>
        @if($item->status == 'publish')
            <span class="label label-info">
                {{  trans('article::backend.publish')  }}
            </span>
        @else
            <span class="label label-danger">
                {{  trans('article::backend.draft')  }}
            </span>
        @endif
    </td>
    <td>
        <a href="{{ route('admin.exhibition.master.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('exhibition::backend.edit') }}</a>
        <a href="{{ route('admin.exhibition.master.delete', $item->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('exhibition::backend.delete') }}</a>
    </td>
</tr>
@endforeach
@endif
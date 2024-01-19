<tr data-entry-id="{{ $children->id }}">
    <td><input type="checkbox" name="ids[]" value="{{ $children->id }}" id="select-all" /></td>
    <td> - {{ $children->name_th }}</td>
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

        <a href="{{ url('preview-menu/' . $children->slug_th) }}" class="btn btn-success" target="_blank"><i class="fa fa-eye"></i> {{ trans('footermenus::backend.preview') }}</a>
        @if($request->segment(3) =='left')
            <a href="{{ route('admin.footermenus.left.edit', $children->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('footermenus::backend.edit') }}</a>
            <a href="{{ route('admin.footermenus.left.delete', $children->id) }}" data-toggle="confirmation" data-title="ต้องการลบข้อมูลหรือไม่ ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('footermenus::backend.delete') }}</a>
        @elseif($request->segment(3)  =='center')
            <a href="{{ route('admin.footermenus.center.edit', $children->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('footermenus::backend.edit') }}</a>
            <a href="{{ route('admin.footermenus.center.delete', $children->id) }}" data-toggle="confirmation" data-title="ต้องการลบข้อมูลหรือไม่ ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('footermenus::backend.delete') }}</a>
        @elseif($request->segment(3)  =='right')
            <a href="{{ route('admin.footermenus.right.edit', $children->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('footermenus::backend.edit') }}</a>
            <a href="{{ route('admin.footermenus.right.delete', $children->id) }}" data-toggle="confirmation" data-title="ต้องการลบข้อมูลหรือไม่ ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('footermenus::backend.delete') }}</a>
        @else

        @endif



    </td>
</tr>

@if ($children->children->count() > 0)
@foreach($children->children as $item)
<tr data-entry-id="{{ $item->id }}">
    <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
    <td> -- {{ $item->name }}</td>
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
        <!--
        <a href="{{ url('preview-menu/' . $item->slug .'?r=1') }}" class="btn btn-success" target="_blank"><i class="fa fa-eye"></i> Preview</a> -->
        <a href="{{ route('admin.menus.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('footermenus::backend.edit') }}</a>
        <a href="{{ route('admin.menus.delete', $item->id) }}" data-toggle="confirmation" data-title="ต้องการลบข้อมูลหรือไม่ ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('footermenus::backend.delete') }}</a>
    </td>
</tr>
@endforeach
@endif
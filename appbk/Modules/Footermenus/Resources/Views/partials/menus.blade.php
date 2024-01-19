@inject('request', 'Illuminate\Http\Request')
@php
    //dd($request->segment(3));
@endphp
@if($request->segment(3) =='left')
    {{ Form::open(['url' => route('admin.footermenus.left.deleteAll')]) }}
@elseif($request->segment(3)  =='center')
    {{ Form::open(['url' => route('admin.footermenus.center.deleteAll')]) }}
@elseif($request->segment(3)  =='right')
    {{ Form::open(['url' => route('admin.footermenus.right.deleteAll')]) }}
@else
    {{ Form::open(['url' => route('admin.footermenus.deleteAll')]) }}
@endif

    <br>
    <div class="row">
        <div class="col-md-12">
            @if($request->segment(3) =='left')
                <a href="{{ route('admin.footermenus.left.create', ['site' => $site]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('footermenus::backend.add') }}</a>
            @elseif($request->segment(3)  =='center')
                <a href="{{ route('admin.footermenus.center.create', ['site' => $site]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('footermenus::backend.add') }}</a>
            @elseif($request->segment(3)  =='right')
                <a href="{{ route('admin.footermenus.right.create', ['site' => $site]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('footermenus::backend.add') }}</a>
            @else
                <a href="{{ route('admin.footermenus.create', ['site' => $site]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('footermenus::backend.add') }}</a>
            @endif
            <button type="submit"  data-toggle="confirmation" data-title="ต้องการลบข้อมูลหรือไม่ ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('footermenus::backend.delete_all') }}</button>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all" /></th>
                        <th>{{ trans('footermenus::backend.name') }}</th>
                        <th>{{ trans('footermenus::backend.order') }}</th>
                        <th>{{ trans('footermenus::backend.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr data-entry-id="{{ $item->id }}">
                        <td width="5px"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
                        <td>{{ $item->name }}</td>
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
                            <a href="{{ url('preview-menu/' . $item->slug_th) }}" class="btn btn-success" target="_blank"><i class="fa fa-eye"></i> {{ trans('footermenus::backend.preview') }}</a>
                            @if($request->segment(3) =='left')
                                <a href="{{ route('admin.footermenus.left.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('footermenus::backend.edit') }}</a>
                                <a href="{{ route('admin.footermenus.left.delete', $item->id) }}" data-toggle="confirmation" data-title="ต้องการลบข้อมูลหรือไม่ ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('footermenus::backend.delete') }}</a>
                            @elseif($request->segment(3)  =='center')
                                <a href="{{ route('admin.footermenus.center.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('footermenus::backend.edit') }}</a>
                                <a href="{{ route('admin.footermenus.center.delete', $item->id) }}" data-toggle="confirmation" data-title="ต้องการลบข้อมูลหรือไม่ ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('footermenus::backend.delete') }}</a>
                            @elseif($request->segment(3)  =='right')
                                <a href="{{ route('admin.footermenus.right.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('footermenus::backend.edit') }}</a>
                                <a href="{{ route('admin.footermenus.right.delete', $item->id) }}" data-toggle="confirmation" data-title="ต้องการลบข้อมูลหรือไม่ ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('footermenus::backend.delete') }}</a>
                            @else

                            @endif
                        </td>
                    </tr>

                    @if ($item->children->count() > 0)
                        @foreach($item->children->sortBy('order') as $children)
                            @include('footermenus::partials.children', ['item' => $children])
                        @endforeach
                    @endif

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
{{ Form::close() }}
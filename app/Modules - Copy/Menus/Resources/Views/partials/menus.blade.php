{{ Form::open(['url' => route('admin.menus.deleteAll')]) }}
    <br>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.menus.create', ['site' => $site]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('menus::backend.add') }}</a>
            <button type="submit"  data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('menus::backend.delete_all') }}</button>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all" /></th>
                        <th>{{ trans('menus::backend.name') }}</th>
                        <th>{{ trans('menus::backend.order') }}</th>
                        <th>{{ trans('menus::backend.status') }}</th>
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
                            
                            <a href="{{ url('preview-menu/' . $item->slug) }}" class="btn btn-success" target="_blank"><i class="fa fa-eye"></i> ดูตัวอย่าง</a>
                            <a href="{{ route('admin.menus.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('menus::backend.edit') }}</a>
                            <a href="{{ route('admin.menus.delete', $item->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('menus::backend.delete') }}</a>
                        </td>
                    </tr>

                    @if ($item->children->count() > 0)
                        @foreach($item->children->sortBy('order') as $children)
                            @include('menus::partials.children', ['item' => $children])
                        @endforeach
                    @endif

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
{{ Form::close() }}
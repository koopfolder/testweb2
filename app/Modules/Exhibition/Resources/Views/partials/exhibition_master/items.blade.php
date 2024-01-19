{{ Form::open(['url' => route('admin.exhibition.master.deleteAll')]) }}
    <br>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.exhibition.master.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('exhibition::backend.add') }}</a>
            <button type="submit"  data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('exhibition::backend.delete_all') }}</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all" /></th>
                        <th>{{ trans('exhibition::backend.title') }}</th>
                        <th>{{ trans('exhibition::backend.order') }}</th>
                        <th>{{ trans('exhibition::backend.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    @php
                        //dd($item);
                    @endphp
                    <tr data-entry-id="{{ $item->id }}">
                        <td width="5px"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
                        <td>{{ $item->title }}</td>
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
                    @if ($item->children->count() > 0)
                        @foreach($item->children->sortBy('order') as $children)
                            @php
                                //dd($children);
                            @endphp
                            @include('exhibition::partials.exhibition_master.children', ['item' => $children])
                        @endforeach
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
{{ Form::close() }}
{{ Form::open(['url' => route('admin.link.deleteAll')]) }}
    <br>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.link.create', ['site' => $site]) }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add
            </a>
            <button type="submit"  data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger">
                <i class="fa fa-trash-o"></i> Delete All
            </button>
            <a href="{{ route('admin.export.index', ['moduleSlug' => 'link', 'table' => 'menu']) }}" class="btn btn-success">
                <i class="fa fa-file-o"></i> Excel
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all" /></th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr data-entry-id="{{ $item->id }}">
                        <td width="5px"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->position }}</td>
                        <td>{{ $item->order }}</td>
                        <td>
                            <span class="label label-info label-green">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.link.edit', $item->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> Edit</a>
                            <a href="{{ route('admin.link.delete', $item->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
                        </td>
                    </tr>

                    @if ($item->children->count() > 0)
                    @foreach($item->children->sortBy('order') as $children)
                        @include('link::partials.children', ['item' => $children])
                    @endforeach
                    @endif

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
{{ Form::close() }}
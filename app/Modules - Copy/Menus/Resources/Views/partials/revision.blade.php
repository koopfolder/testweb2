<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>{{ trans('menus::backend.name') }}</th>
			<th>{{ trans('menus::backend.description') }}</th>
			<th>{{ trans('menus::backend.created') }}</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($revisions as $revision)
		<tr>
			<td>{{ $revision->name }}</td>
			<td>{!! $revision->description !!}</td>
			<td>{{ date('Y M, d H:i', strtotime($revision->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.menus.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-eye"></i> {{ trans('menus::backend.revisions') }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="1px"></th>
			<th>{{ trans('banner::backend.name') }}</th>
			<th>{{ trans('banner::backend.description') }}</th>
			<th>{{ trans('banner::backend.created') }}</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($revisions as $revision)
		<tr>
			<td></td>
			<td>{{ $revision->name }}</td>
			<td>{!! $revision->description !!}</td>
			<td>{{ date('Y M, d H:i', strtotime($revision->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.banner.category.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-undo"></i> {{ trans('banner::backend.revisions') }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
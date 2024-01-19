<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>Description</th>
			<th>Created</th>
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
				<a href="{{ route('admin.room.category.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-undo"></i> Revision</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
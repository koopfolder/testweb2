<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>{{ trans('franchise::backend.franchise_title') }}</th>
			<th>{{ trans('franchise::backend.created') }}</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($revisions as $revision)
		<tr>
			<td>{{ $revision->category_name }}</td>
			<td>{{ date('Y M, d H:i', strtotime($revision->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.franchise.category.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-undo"></i> {{ trans('franchise::backend.revisions') }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="1px"></th>
			<th>{{ trans('single-page::backend.title') }}</th>
			<th>{{ trans('single-page::backend.description') }}</th>
			<th>{{ trans('single-page::backend.created') }}</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($revisions as $revision)
		<tr>
			<td></td>
			<td>{{ $revision->title }}</td>
			<td>{!! $revision->description !!}</td>
			<td>{{ date('Y M, d H:i', strtotime($revision->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.single-page.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-undo"></i> {{ trans('single-page::backend.revisions') }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
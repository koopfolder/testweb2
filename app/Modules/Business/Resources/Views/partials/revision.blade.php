<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>{{ trans('business::backend.description_th') }}</th>
			<th>{{ trans('article::backend.created') }}</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($revisions as $revision)
		<tr>
			<td>{!! $revision->description_th !!}</td>
			<td>{{ date('Y M, d H:i', strtotime($revision->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.business.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-undo"></i> {{ trans('article::backend.revisions') }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
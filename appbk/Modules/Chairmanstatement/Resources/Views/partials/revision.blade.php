<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="1px"></th>
			<th>{{ trans('article::backend.title') }}</th>
			<th>{{ trans('article::backend.position') }}</th>
			<th>{{ trans('article::backend.created') }}</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($revisions as $revision)
		<tr>
			<td></td>
			<td>{{ $revision->title_th }}</td>
			<td>{{ date('Y M, d H:i', strtotime($revision->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.article.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-undo"></i> {{ trans('article::backend.revisions') }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>{{ trans('footermenus::backend.name') }}</th>
			<th>{{ trans('footermenus::backend.description') }}</th>
			<th>{{ trans('footermenus::backend.created') }}</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($revisions as $revision)
		<tr>
			<td>{{ $revision->name_th }}</td>
			<td>{!! $revision->description_th !!}</td>
			<td>{{ date('Y M, d H:i', strtotime($revision->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.menus.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-eye"></i> {{ trans('footermenus::backend.revisions') }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
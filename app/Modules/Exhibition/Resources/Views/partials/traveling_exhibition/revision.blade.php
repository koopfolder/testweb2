<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr> 
			<th width="1px"></th>
			<th>{{ trans('exhibition::backend.title') }}</th>
			<th>{{ trans('exhibition::backend.created') }}</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@php
			//dd($revisions);
		@endphp
		@foreach($revisions as $key => $revision)
		@php
			//dd($revision,$key);
		@endphp
		<tr id="{{ $revision->id }}">
			<td></td>
			<td>{{ $revision->title }}</td>
			<td>{{ date('Y M, d H:i', strtotime($revision->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.exhibition.traveling.reverse', $revision->id) }}" data-toggle="confirmation" data-title="Revision?" class="btn btn-primary"><i class="fa fa-undo"></i> {{ trans('exhibition::backend.revisions') }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
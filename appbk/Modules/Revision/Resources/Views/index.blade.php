@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Revision</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.revision.index') }}">Revision</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.revision.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> Delete selected
							</button>
							<a href="{{ route('admin.revision.export', 'excel') }}" class="btn btn-primary"><i class="fa fa-file-o"></i> Export</a>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>Title</th>
								<th>Layout</th>
								<th>Status</th>
								<th>Created</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($pages as $page)
							<tr data-entry-id="{{ $page->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $page->id }}" id="select-all" /></td>
								<td>
									{{ $page->title }} 
									{!! $page->is_home == 1 ? '<i class="fa fa-home"></i>' : '' !!} 
								</td>
								<td>{{ $page->layout }}</td>
								<td>
									<span class="label label-info label-green">
										{{ strtoupper($page->status) }}
									</span>
								</td>
								<td>{{ date('d M Y, H:i', strtotime($page->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.revision.review', $page->id) }}" class="btn btn-primary"><i class="fa fa-eye"></i> Review</a>
									<a href="{{ route('admin.revision.delete', $page->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
            	</div>
				{{ Form::close() }}
        	</div>
    	</div>
	</div>
</section>
@endsection

@section('javascript')
<script type="text/javascript">
	$(function() {
		 $("#select-all").click(function () {
		     $('input:checkbox').not(this).prop('checked', this.checked);
		 });
	});
</script>
@stop
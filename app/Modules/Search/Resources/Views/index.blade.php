@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Search</h1>
	<ol class="breadcrumb">
    	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.search.index') }}">Search</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
            	<div class="box-header">
              		<h3 class="box-title">Users</h3>
				</div>
				{{ Form::open(['url' => route('admin.users.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New User</a>
							<button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete selected</button>
							<a href="{{ route('admin.users.export', 'excel') }}" class="btn btn-primary"><i class="fa fa-file-o"></i> Export CSV</a>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>ID</th>
								<th>Name</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($search as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->id }}</td>
								<td>{{ $item->name }}</td>
								<td><span class="label label-info label-green">{{ strtoupper($item->status) }}</span></td>
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


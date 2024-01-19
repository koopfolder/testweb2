@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Candidate</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.career.index') }}">Candidate</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.career.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> Delete all
							</button>
							<!--
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'career', 'table' => 'careers']) }}" class="btn btn-success">
								<i class="fa fa-file-o"></i> Excel
							</a> -->
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>Name</th>
								<th>Download</th>
								<th>Status</th>
								<th>Created</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($careers as $career)
							<tr data-entry-id="{{ $career->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $career->id }}" id="select-all" /></td>
								<td>{{ $career->name }}</td>
								<td><a href="{{ url($career->file_path) }}" target="_blank">File</a></td>
								<td><span class="label label-info label-green">{{ $career->status }}</span></td>
								<td>{{ date('d M Y', strtotime($career->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.career.delete', $career->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
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
@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Rooms</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.room.index') }}">Rooms</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.room.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.room.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> Add
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> Delete All
							</button>
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'room', 'table' => 'rooms']) }}" class="btn btn-success">
								<i class="fa fa-file-o"></i> Excel
							</a>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>Image</th>
								<th>Category</th>
								<th>Room Type ID</th>
								<th>Name</th>
								<th>Book Type</th>
								<th>Status</th>
								<th>Created</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td width="100">
									@if ($item->getMedia('room_desktop')->isNotEmpty())
										<img src="{{ asset($item->getMedia('room_desktop')->first()->getUrl()) }}" width="100">
									@endif
								</td>
								<td>{{ $item->category->name }}</td>
								<td>{{ $item->room_type_id }}</td>
								<td>{{ $item->name }}</td>
								<td>{{ $item->book_type }}</td>
								<td>
									<span class="label label-info label-green">
										{{ $item->status == 'publish' ? 'Publish' : 'Draft' }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									<a href="{{ route('home.preview.url', ['slug' => $item->slug, 'module' => 'room']) }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> Preview
									</a>
									<a href="{{ route('admin.room.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> Edit
									</a>
									<a href="{{ route('admin.room.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> Delete
									</a>
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
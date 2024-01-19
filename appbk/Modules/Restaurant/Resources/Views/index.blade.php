@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('restaurant::admin.restaurants') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('restaurant::admin.breadcrumb.home') }}</a></li>
    	<li><a href="{{ route('admin.restaurant.index') }}">{{ trans('restaurant::admin.restaurants') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.restaurant.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.restaurant.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> Add
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> Delete Selected
							</button>
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'restaurant', 'table' => 'restaurants']) }}" class="btn btn-success">
								<i class="fa fa-file-excel-o"></i> Export Excel
							</a>
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="5"><input type="checkbox" id="select-all" /></th>
								<th>Image</th>
								<th>Name</th>
								<th>Open Time</th>
								<th>Status</th>
								<th>Created</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($restaurants as $restaurant)
							<tr data-entry-id="{{ $restaurant->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $restaurant->id }}" id="select-all" /></td>
								<td width="50">
									@if ( $restaurant->getMedia('desktop')->count() > 0)
										<img src="{{ asset($restaurant->getMedia('desktop')->first()->getUrl()) }}" width="100">
									@endif
								</td>
								<td>{{ $restaurant->name }}</td>
								<td>{{ $restaurant->open_hours }}</td>
								<td>
									<span class="label label-info label-green">
										{{ strtoupper($restaurant->status) }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($restaurant->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.restaurant.edit', $restaurant->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> Edit
									</a>
									<a href="{{ route('admin.restaurant.delete', $restaurant->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger">
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
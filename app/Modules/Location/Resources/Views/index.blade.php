@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('location::admin.locations') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('layout::admin.breadcrumb.home') }}</a></li>
    	<li><a href="{{ route('admin.location.index') }}">{{ trans('location::admin.locations') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.location.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.location.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('layout::admin.form.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('layout::admin.form.delete-selected') }}
							</button>
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'location', 'table' => 'locations']) }}" class="btn btn-success">
								<i class="fa fa-file-o"></i> {{ trans('layout::admin.form.export-excel') }}
							</a>
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('layout::admin.form.image') }}</th>
								<th>{{ trans('layout::admin.form.name') }}</th>
								<th>{{ trans('layout::admin.form.status') }}</th>
								<th>{{ trans('layout::admin.form.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($locations as $location)
							<tr data-entry-id="{{ $location->id }}">
								<td width="5px;"><input type="checkbox" name="ids[]" value="{{ $location->id }}" class="select-all" /></td>
								<td width="50px">
									@if ( $location->getMedia('desktop')->count() > 0)
										<img src="{{ asset($location->getMedia('desktop')->first()->getUrl()) }}" width="100">
									@endif
								</td>
								<td>{{ $location->name }}</td>
								<td>
									<span class="label label-info label-green">
										{{ strtoupper($location->status) }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($location->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.location.edit', $location->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('layout::admin.form.edit') }}
									</a>
									<a href="{{ route('admin.location.delete', $location->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('layout::admin.form.delete') }}
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
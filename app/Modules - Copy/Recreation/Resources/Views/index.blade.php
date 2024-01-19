@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('recreation::admin.recreation') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('layout::admin.breadcrumb.home') }}</a></li>
    	<li><a href="{{ route('admin.recreation.index') }}">{{ trans('recreation::admin.recreation') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.recreation.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.recreation.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('layout::admin.form.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('layout::admin.form.delete-selected') }}
							</button>
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'recreation', 'table' => 'recreations']) }}" class="btn btn-success">
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
								<th>Image</th>
								<th>Name</th>
								<th>Timing</th>
								<th>Status</th>
								<th>Created</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($recreations as $recreation)
							<tr data-entry-id="{{ $recreation->id }}">
								<td width="5px"><input type="checkbox" name="ids[]" value="{{ $recreation->id }}" id="select-all" /></td>
								<td width="10px">
									@if ($recreation->getMedia('desktop')->isNotEmpty())
										<img src="{{ asset($recreation->getMedia('desktop')->first()->getUrl()) }}" width="100">
									@endif
								</td>
								<td>{{ $recreation->name }}</td>
								<td>{{ $recreation->timing }}</td>
								<td>
									<span class="label label-info label-green">
										{{ $recreation->status == 'publish' ? 'Publish' : 'Draft' }}
									</span>
								</td>
								<td>{{ date('Y M, d', strtotime($recreation->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.recreation.edit', $recreation->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> Edit</a>
									<a href="{{ route('admin.recreation.delete', $recreation->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
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
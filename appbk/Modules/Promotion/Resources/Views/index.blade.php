@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Promotion</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.promotion.index') }}">Promotion</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.promotion.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.promotion.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('layout::admin.form.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('layout::admin.form.delete-selected') }}
							</button>
							<a href="{{ route('admin.promotion.export', 'excel') }}" class="btn btn-success">
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
							@foreach ($promotions as $promotion)
							<tr data-entry-id="{{ $promotion->id }}">
								<td width="5px;"><input type="checkbox" name="ids[]" value="{{ $promotion->id }}" class="select-all" /></td>
								<td width="100px">
									@if ( $promotion->getMedia('desktop')->count() > 0)
										<img src="{{ asset($promotion->getMedia('desktop')->first()->getUrl()) }}" width="100">
									@endif
								</td>
								<td>{{ $promotion->name }}</td>
								<td>
									<span class="label label-info label-green">
										{{ strtoupper($promotion->status) }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($promotion->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.promotion.edit', $promotion->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('layout::admin.form.edit') }}
									</a>
									<a href="{{ route('admin.promotion.delete', $promotion->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger">
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
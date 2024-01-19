@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('banner::backend.category') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('banner::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.banner.category.index') }}">{{ trans('banner::backend.category') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.banner.category.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.banner.category.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('banner::backend.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('banner::backend.delete_all') }}
							</button>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('banner::backend.name') }}</th>
								<th>{{ trans('banner::backend.status') }}</th>
								<th>{{ trans('banner::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->name }}</td>
								<td>
									@if($item->status == 'publish')
									<span class="label label-info">
										{{  trans('banner::backend.publish')  }}
									</span>
									@else
									<span class="label label-danger">
										{{  trans('banner::backend.draft')  }}
									</span>
									@endif
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.banner.category.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('banner::backend.edit') }}
									</a>
									<a href="{{ route('admin.banner.category.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('banner::backend.delete') }}
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
@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('api::backend.request_media') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('api::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.request-media.index') }}">{{ trans('api::backend.request_media') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('api::backend.name') }}</th>
								<th>{{ trans('api::backend.email') }}</th>
								<th>{{ trans('api::backend.phone') }}</th>
								<th>{{ trans('api::backend.description') }}</th>
								<th>{{ trans('api::backend.created') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->name }}</td>
								<td>{{ $item->email }}</td>
								<td>{{ $item->phone }}</td>
								<td>{{ $item->description }}</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
            	</div>
        	</div>
    	</div>
	</div>
</section>
@endsection
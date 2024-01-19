@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('exhibition::backend.online_exhibition') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('exhibition::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.exhibition.online.index') }}">{{ trans('exhibition::backend.online_exhibition') }}</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.exhibition.online.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.exhibition.online.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('exhibition::backend.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('exhibition::backend.delete_all') }}
							</button>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('exhibition::backend.title') }}</th>
								<th>{{ trans('exhibition::backend.status') }}</th>
								<th>{{ trans('exhibition::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{!! $item->title !!}</td>
								<td>
									<span class="label label-info label-green">
										{{ $item->status == 'publish' ? trans('exhibition::backend.publish')  : trans('exhibition::backend.draft')  }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									@if($item->file_path !='' && file_exists(public_path().$item->file_path))
									<a href="{{ asset($item->file_path) }}" class="btn btn-success" download>
										<i class="fa fa-download"></i> Download File
									</a>
									@endif
									<a href="{{ route('admin.exhibition.online.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('exhibition::backend.edit') }}
									</a>
									<a href="{{ route('admin.exhibition.online.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('exhibition::backend.delete') }}
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
@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('history::backend.history') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('history::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.history.index') }}">{{ trans('history::backend.history') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.history.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.history.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('history::backend.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('history::backend.delete_all') }}
							</button>
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'history', 'table' => 'history']) }}" class="btn btn-success">
								<i class="fa fa-file-o"></i> {{ trans('history::backend.excel') }}
							</a>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('history::backend.year') }}</th>
								<th>{{ trans('history::backend.description') }}</th>
								<th>{{ trans('history::backend.status') }}</th>
								<th>{{ trans('history::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->year }}</td>
								<td>{!! $item->description !!}</td>
								<td>
									<span class="label label-info label-green">
										{{ $item->status == 'publish' ? trans('history::backend.publish')  : trans('history::backend.draft')  }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.history.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('history::backend.edit') }}
									</a>
									<a href="{{ route('admin.history.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('history::backend.delete') }}
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
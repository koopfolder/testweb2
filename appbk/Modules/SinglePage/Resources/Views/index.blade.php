@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('single-page::backend.single_page') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('single-page::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.single-page.index') }}">{{ trans('single-page::backend.single_page') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.single-page.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.single-page.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('single-page::backend.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('single-page::backend.delete_all') }}
							</button>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('single-page::backend.title') }}</th>
								<th>{{ trans('single-page::backend.status') }}</th>
								<th>{{ trans('single-page::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{  $item->title }}</td>
								<td>
									@if($item->status == 'publish')
									<span class="label label-info">
										{{  trans('single-page::backend.publish')  }}
									</span>
									@else
									<span class="label label-danger">
										{{  trans('single-page::backend.draft')  }}
									</span>
									@endif
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									<a href="{{ route('single-page-frontend',\Hashids::encode($item->id)) }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> {{ trans('single-page::backend.frontend_link') }}
									</a>
									<a href="{{ route('preview-single-page-chart',\Hashids::encode($item->id)) }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> {{ trans('single-page::backend.preview') }}
									</a>
									<a href="{{ route('admin.single-page.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('single-page::backend.edit') }}
									</a>
									<a href="{{ route('admin.single-page.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('single-page::backend.delete') }}
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
@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('exhibition::backend.exhibition') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('exhibition::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.exhibition.index') }}">{{ trans('exhibition::backend.exhibition') }}</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.exhibition.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.exhibition.create') }}" class="btn btn-primary">
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
								<th>{{ trans('exhibition::backend.exhibition_category') }}</th>
								<th>{{ trans('exhibition::backend.status') }}</th>
								<th>{{ trans('exhibition::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							@php
								//dd($item);
							@endphp
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{!! $item->title !!}</td>
								<td>{{ (isset($item->ExhibitionMaster->title) ? $item->ExhibitionMaster->title:'') }}</td>
								<td>
									@if($item->status == 'publish')
									<span class="label label-info">
										{{  trans('exhibition::backend.publish')  }}
									</span>
									@else
									<span class="label label-danger">
										{{  trans('exhibition::backend.draft')  }}
									</span>
									@endif
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>

									@if(($item->file_path !='' && file_exists(public_path().$item->file_path) && $item->url_external ==''))
									<a href="{{ asset($item->file_path) }}" class="btn btn-success" download>
										<i class="fa fa-download"></i> ดาวน์โหลดไฟล์
									</a>
									@endif
									@if($item->file_path =='' && $item->url_external !='')
									<a href="{{ asset($item->url_external) }}" class="btn btn-success" download>
										<i class="fa fa-download"></i> ลิงก์ภายนอก
									</a>										
									@endif
									<a href="{{ route('exhibition-detail',$item->slug) }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> {{ trans('exhibition::backend.preview') }}
									</a>
									<a href="{{ route('admin.exhibition.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('exhibition::backend.edit') }}
									</a>
									<a href="{{ route('admin.exhibition.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
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
@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('dashboard::backend.login') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('dashboard::backend.home') }}</a></li>
    	<li><a href="{{ route('report.login') }}">{{ trans('dashboard::backend.login') }}</a></li>
	</ol>
</section>
@php
	//dd($old['keyword']);
@endphp
<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('report.login'),'method'=>'get']) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<label>{{ trans('dashboard::backend.keyword') }}:</label>
							<input type="text" name="keyword" value="{{ (isset($old['keyword']) ? $old['keyword']:'') }}" class="form-control" placeholder="{{ trans('dashboard::backend.keyword') }}">
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<label>{{ trans('dashboard::backend.start_date') }}</label>
							<input type="text" name="start_date" id="start_date" placeholder="{{ trans('dashboard::backend.start_date') }}" class="form-control" value="{{ (isset($old['start_date']) ? $old['start_date']:'') }}" />
						</div>
						<div class="col-xs-6">
							<label>{{ trans('dashboard::backend.end_date') }}</label>
							<input type="text" name="end_date" id="end_date" placeholder="{{ trans('dashboard::backend.end_date') }}" class="form-control" value="{{ (isset($old['end_date']) ? $old['end_date']:'') }}" />
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<label>{{ trans('dashboard::backend.show') }}</label>
							<select name="limit" class="form-control">
								<option value="10" {{ (isset($old['limit']) ? ($old['limit'] =='10' ? 'selected':''):'') }} >10</option>
								<option value="25" {{ (isset($old['limit']) ? ($old['limit'] =='25' ? 'selected':''):'') }}>25</option>
								<option value="50" {{ (isset($old['limit']) ? ($old['limit'] =='50' ? 'selected':''):'') }}>50</option>
								<option value="100" {{ (isset($old['limit']) ? ($old['limit'] =='100' ? 'selected':''):'') }}>100</option>
							</select>
							{{ trans('dashboard::backend.entries') }}
						</div>
						<div class="col-xs-6" style="margin-top: 25px;float: right;">
							<button type="submit" class="btn btn-primary">{{ trans('dashboard::backend.search') }}</button>
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable2" class="table">
						<thead>
							<tr>
								<th>{{ trans('users::backend.name') }}</th>
								<th>{{ trans('users::backend.email') }}</th>
								<th>วันที่เข้าสู่ระบบ</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							@php

							@endphp
							<tr>
								<td>{{ $item->name }}</td>
								<td>{{ $item->email }}</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					{!! isset($items) ? $items->appends(\Input::all())->render():'' !!}  
            	</div>
				{{ Form::close() }}
        	</div>
    	</div>
	</div>
</section>
@endsection
@section('javascript')
<script>
$(function(){
	$("#start_date").datepicker({
		format:'dd/mm/yyyy'
	});

	$("#end_date").datepicker({
		format:'dd/mm/yyyy'
	});
});
</script>

@endsection
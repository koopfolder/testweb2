@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('dashboard::backend.article') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('dashboard::backend.home') }}</a></li>
    	<li><a href="{{ route('report.article') }}">{{ trans('dashboard::backend.article') }}</a></li>
	</ol>
</section>
@php
	//dd($old['keyword']);
@endphp
<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('report.article'),'method'=>'get']) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-6">
							<label>{{ trans('dashboard::backend.keyword') }}:</label>
							<input type="text" name="keyword" value="{{ (isset($old['keyword']) ? $old['keyword']:'') }}" class="form-control" placeholder="{{ trans('dashboard::backend.keyword') }}">
						</div>
						<div class="col-xs-6">
							<label>{{ trans('dashboard::backend.data_type') }}</label>
							<select name="data_type" class="form-control">
								<option value="0" {{ (isset($old['data_type']) ? ($old['data_type'] =='0' ? 'selected':''):'') }}  >{{ trans('dashboard::backend.select') }}</option>
								<option value="sook_library" {{ (isset($old['data_type']) ? ($old['data_type'] =='sook_library' ? 'selected':''):'') }} >{{ trans('article::backend.sook-library') }}</option>
								<option value="interesting_issues" {{ (isset($old['data_type']) ? ($old['data_type'] =='interesting_issues' ? 'selected':''):'') }} >{{ trans('article::backend.interesting-issues') }}</option>
								<option value="e-learning" {{ (isset($old['data_type']) ? ($old['data_type'] =='e-learning' ? 'selected':''):'') }} >{{ trans('article::backend.e-learning') }}</option>
								<option value="training_course" {{ (isset($old['data_type']) ? ($old['data_type'] =='training_course' ? 'selected':''):'') }} >{{ trans('article::backend.training-course') }}</option>
								<option value="news_event" {{ (isset($old['data_type']) ? ($old['data_type'] =='news_event' ? 'selected':''):'') }} >{{ trans('article::backend.news_events') }}</option>
								<option value="include_statistics" {{ (isset($old['data_type']) ? ($old['data_type'] =='include_statistics' ? 'selected':''):'') }} >{{ trans('article::backend.include-statistics') }}</option>
								<option value="articles_research"  {{ (isset($old['data_type']) ? ($old['data_type'] =='articles_research' ? 'selected':''):'') }} >{{ trans('article::backend.articles-research') }}</option>
								<option value="our_service" {{ (isset($old['data_type']) ? ($old['data_type'] =='our_service' ? 'selected':''):'') }}>{{ trans('article::backend.our-service') }}</option>
							</select> 
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
								<th>{{ trans('dashboard::backend.title') }}</th>
								<th>{{ trans('dashboard::backend.data_type') }}</th>
								<th>{{ trans('dashboard::backend.visitors') }}</th>
								<th>{{ trans('dashboard::backend.download') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							@php
								//dd($item->document_reports->count());
								$download = 0;
								if($item->document_reports->count()){
									$item['countdownload'] = $item->document_reports;
									$download  = $item['countdownload'];
								}
								$item['playout'] = $item->page_layout;
							@endphp
							<tr>
								<td>{{ $item->title }}</td>
								<td>{{ $item['playout'] }}</td>
								<td>{{ $item->visitors }}</td>
								<td>{{ number_format($download) }}</td>
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

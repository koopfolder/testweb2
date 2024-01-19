@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('dashboard::backend.media') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('dashboard::backend.home') }}</a></li>
    	<li><a href="{{ route('report.media') }}">{{ trans('dashboard::backend.media') }}</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('report.media'),'method'=>'get']) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-6">
							<label>{{ trans('dashboard::backend.keyword') }}:</label>
							<input type="text" name="keyword" value="{{ (isset($old['keyword']) ? $old['keyword']:'') }}" class="form-control" placeholder="{{ trans('dashboard::backend.keyword') }}">
						</div>
						<div class="col-xs-6">
							<label>{{ trans('dashboard::backend.issue') }}</label>
                                <select name="issue" class="form-control">
                                    <option value"0">ประเด็น</option>
                                    @if($issue->count())
                                        @foreach($issue AS $key=>$value)
                                            @php
                                                $issue_count = $value->children->count();
                                            @endphp
                                            @if ($issue_count > 0)
                                                <optgroup label="{{ $value->name }}">
                                                    @if(!empty($value->issues_id))
                                                        <option value="{{ $value->issues_id }}" {{ (isset($old['issue']) ? ($old['issue'] == $value->issues_id ? 'selected':''):'') }}  >{{ $value->name }}</option>
                                                    @endif
                                                @php
                                                    $children = $value->children->sortBy('name');
                                                @endphp    
                                                @foreach($children as $children)
                                                    @php
                                                        //dd($children);
                                                    @endphp
                                                    <option value="{{ $children->issues_id }}"  {{ (isset($old['issue'])  ? ($old['issue'] == $children->issues_id ? 'selected':''):'') }}>{{ $children->name }}</option>
                                                @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $value->issues_id }}"  {{ (isset($old['issue']) ? ($old['issue'] == $value->issues_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
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
					<table id="datatable" class="table">
						<thead>
							<tr>
								<th>{{ trans('dashboard::backend.title') }}</th>
								<th>{{ trans('dashboard::backend.issue') }}</th>
								<th>{{ trans('dashboard::backend.visitors') }}</th>
								<th>{{ trans('dashboard::backend.download') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							@php
								//dd($item);
							@endphp
							<tr>
								<td>{{ $item->title }}</td>
								<td>{{ (isset($item->issueName->name) ? $item->issueName->name:'') }}</td>
								<td>{{ $item->visitors }}</td>
								<td>{{ number_format($item->download) }}</td>
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

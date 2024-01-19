@extends('layout::app')
@section('content')

<section class="content-header">
	<h1>{{ trans('api::backend.media') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('api::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.api.list-media.index') }}">{{ trans('api::backend.media') }}</a></li>
	</ol>
</section>
<style type="text/css" media="screen">
	.title_color a{
		color:#333;
	}
	.coupon label{
		width: 145px;
	}
	.fg_1{
		display: flex;
	}
	.sg_1{
		margin-left: 3rem;
	}
	.not_trem{
		margin-left: 40px;
   	 margin-right: 30px;
	}
	.modal{
		margin-top: 5rem;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.api.list-media.index'),'method'=>'get']) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-6">
							<label>{{ trans('dashboard::backend.keyword') }}</label>
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
							<label>{{ trans('api::backend.template') }}</label>
							{{ Form::select('template',$template,(isset($old['template']) ? $old['template']:''),['class'=>'form-control']) }}
						</div>
						<div class="col-xs-6">
							<label>{{ trans('api::backend.target') }}</label>
							<select name="target" class="form-control">
                                    <option value="0">กลุ่มเป้าหมาย</option>
                                @if($target->count())
                                    @foreach($target AS $key=>$value)
                                        @php
                                            //dd($value);
                                              $target_count = $value->children->count();
                                        @endphp
                                        @if ($target_count > 0)
                                            <optgroup label="{{ $value->name }}">
                                                @if(!empty($value->target_id))
                                                    <option value="{{ $value->target_id }}" {{ (isset($old['target'])  ? ($old['target'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                                @endif
                                            @php
                                                $children = $value->children->sortBy('name');
                                            @endphp
                                            @foreach($children as $children)
                                                @php
                                                    //dd($old['target']);
                                                @endphp
                                                <option value="{{ $children->target_id }}" {{ (isset($old['target'])  ? ($old['target'] == $children->target_id ? 'selected':''):'') }}>{{ $children->name }}</option>
                                            @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $value->target_id }}" {{ (isset($old['target'])  ? ($old['target'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
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
							@php
                           	$status = ['0'=>'ทั้งหมด','publish' =>trans('api::backend.publish'),'draft'=>trans('api::backend.draft'), 'dol'=>'รอการตรวจสอบ', 'trash'=>'ถังขยะ'];
                            //dd($status);
                        	@endphp
							<label>{{ trans('api::backend.status') }}</label>
							{!! Form::select('status',$status,(isset($old['status']) ? $old['status']:''),['class' => 'form-control']) !!}
						</div>
						<div class="col-xs-6">
							<label>{{ trans('api::backend.users') }}</label>
							{!! Form::select('users',$users,(isset($old['users']) ? $old['users']:''),['class' => 'form-control']) !!}
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
						<div class="col-xs-6">
							<label for="InputName">คัดกรอง API & WebView</label>
							<br>
							{{ Form::radio('filter_api','api', (isset($old['filter_api']) ? ($old['filter_api'] == 'api' ? true:false) : false)) }} API <br>
							{{ Form::radio('filter_api','webview', (isset($old['filter_api']) ? ($old['filter_api'] == 'webview' ? true:false) : false)) }} WebView <br>
							{{ Form::radio('filter_api','none', (isset($old['filter_api']) ? ($old['filter_api'] == 'none' ? true:false) : true)) }} ไม่คัดกรอง
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<button type="submit" class="btn btn-primary">{{ trans('dashboard::backend.search') }}</button>
							<a href="{{ route('admin.api.list-media.export-excel-api-webview') }}" class="btn btn-success">
								<i class="fa fa-file-excel-o"></i> {{ trans('article::backend.export_excel') }} Api & WebView
							</a>						
						</div>
						<div class="col-xs-6">

						</div>
					</div>
				</div>
				{{ Form::close() }}
            	<div class="box-body">
					<div class="row">
					<div class="col-xs-12">
					<table id="" class="table">
					<!--<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
						<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span> -->
						<thead>
							<tr>
								@php	

									$url = \Request::getRequestUri();
									$url_edit = \Request::url();
									//dd($url_edit);
									$input_page = \Request::input('page');
									$input_ordering = \Request::input('ordering');
									
									$status_glyphicon = 'none';
									$glyphicon_show = 'none';

									$data_parse_url =parse_url($url);

									//dd($data_parse_url,isset($data_parse_url['query']));
									if(isset($data_parse_url['query'])){
										$operator = '&';
									}else{
										$operator = '?';
									}

									if(isset($input_ordering)){
										
										parse_str($data_parse_url['query'],$data_parse_str);
										$check_val = explode(":",$data_parse_str['ordering']);
										$status_glyphicon = $check_val[1];
										$operator = '?';
										switch ($check_val[0]) {

											case 'title':

												$glyphicon_show = 'title';
												$data_parse_str['ordering'] = ($check_val[1] == 'asc' ? 'title:desc':'title:asc');
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'status:asc';
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);


												break;
											case 'interesting_issues':

												$glyphicon_show = 'interesting_issues';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'interesting_issues:desc':'interesting_issues:asc');
												$url_interesting_issues = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);
				
												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'status:asc';
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);



												break;
											case 'articles_research':

												$glyphicon_show = 'articles_research';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'articles_research:desc':'articles_research:asc');
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'status:asc';
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);												

												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);



												break;
											case 'include_statistics':

												$glyphicon_show = 'include_statistics';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'include_statistics:desc':'include_statistics:asc');
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);


												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'status:asc';
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);												

												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);

												break;
											case 'notable_books':

												$glyphicon_show = 'notable_books';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'notable_books:desc':'notable_books:asc');
												$url_notable_books = $url_edit.$operator.http_build_query($data_parse_str);

												
												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'status:asc';
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);												
												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);



												break;
											case 'knowledges':
												$glyphicon_show = 'knowledges';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'knowledges:desc':'knowledges:asc');
												$url_knowledges = $url_edit.$operator.http_build_query($data_parse_str);


												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);


												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'status:asc';
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);												

												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);


												break;
											case 'media_campaign':

												$glyphicon_show = 'media_campaign';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'media_campaign:desc':'media_campaign:asc');
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'status:asc';
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);												
												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);


												break;
											case 'status':
												$glyphicon_show = 'status';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'status:desc':'status:asc');
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);


												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);


												break;
											case 'api':
												$glyphicon_show = 'api';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'api:desc':'api:asc');
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);


												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'created_at:asc';
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);


												break;												
											case 'created_at':

												$glyphicon_show = 'created_at';
												$data_parse_str['ordering'] = ($check_val[1]== 'asc' ? 'created_at:desc':'created_at:asc');
												$url_created = $url_edit.$operator.http_build_query($data_parse_str);	


												$data_parse_str['ordering'] = 'title:asc';
												$url_title = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'interesting_issues:asc';
												$url_interesting_issues =$url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'articles_research:asc';
												$url_articles_research = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'include_statistics:asc';
												$url_include_statistics = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'notable_books:asc';
												$url_notable_books =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'knowledges:asc';
												$url_knowledges =  $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'media_campaign:asc';
												$url_media_campaign = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'status:asc';
												$url_status = $url_edit.$operator.http_build_query($data_parse_str);

												$data_parse_str['ordering'] = 'api:asc';
												$url_api = $url_edit.$operator.http_build_query($data_parse_str);

												break;
											
											default:
												// code...
												break;
										}
						
										//dd($input_ordering,$data_parse_url['query'],$data_parse_str,$check_val,$url_title);

									}else{

										$url_title = $url.$operator."ordering=title:asc";
										$url_interesting_issues = $url.$operator."ordering=interesting_issues:asc";
										$url_articles_research = $url.$operator."ordering=articles_research:asc";
										$url_include_statistics = $url.$operator."ordering=include_statistics:asc";
										$url_notable_books =  $url.$operator."ordering=notable_books:asc";
										$url_knowledges = $url.$operator."ordering=knowledges:asc";
										$url_media_campaign = $url.$operator."ordering=media_campaign:asc";
										$url_status = $url.$operator."ordering=status:asc";
										$url_api = $url.$operator."ordering=api:asc";
										$url_created = $url.$operator."ordering=created_at:asc";

									}

								@endphp
								<th class="title_color">
									<a href="{{ $url_title }}">{{ trans('api::backend.title') }}
									@if($glyphicon_show =='title')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>
								<th class="title_color">
									<a href="#">สื่อวาระกลาง
									</a>
								</th>
								<th class="title_color">
									<a href="{{ $url_interesting_issues }}">{{ trans('api::backend.interesting_issues') }}
									@if($glyphicon_show =='interesting_issues')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>
								<th class="title_color">
									<a href="{{ $url_articles_research }}">{{ trans('api::backend.articles_research') }}
									@if($glyphicon_show =='articles_research')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>
								<th class="title_color">
									<a href="{{ $url_include_statistics }}">{{ trans('api::backend.include_statistics') }}
									@if($glyphicon_show =='include_statistics')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>
								<!--<th class="title_color">
									<a href="{{ $url_notable_books }}">{{ trans('api::backend.notable_books') }}
									@if($glyphicon_show =='notable_books')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>-->
								<th class="title_color">
									<a href="{{ $url_knowledges }}">{{ trans('api::backend.knowledges') }}
									@if($glyphicon_show =='knowledges')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>
								<th class="title_color">
									<a href="{{ $url_media_campaign }}">{{ trans('api::backend.media_campaign') }}
									@if($glyphicon_show =='media_campaign')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>
								<th class="title_color" style="width: 14%;">
									<a href="{{ $url_status }}">{{ trans('api::backend.status') }}
									@if($glyphicon_show =='status')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>
								<th class="title_color">
									<a href="{{ $url_api }}">{{ trans('api::backend.api') }}
									@if($glyphicon_show =='api')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>								
								<th class="title_color">
									<a href="{{ $url_created }}">{{ trans('api::backend.created') }}
									@if($glyphicon_show =='created_at')    
										@if($status_glyphicon == 'asc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-down"></span>
										@endif
										@if($status_glyphicon == 'desc')
											<span aria-hidden="true" class="glyphicon glyphicon-chevron-up"></span>
										@endif
									@endif
									</a>
								</th>
								<th class="title_color">
									<a href="#">{{ trans('api::backend.update_by') }}
									</a>
								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							@php
								//dd($item);
								$json_data =json_decode($item->json_data);
								//dd($json_data);											
								
								
							@endphp
							<tr>
								<th>{{ $item->title }}</th>
								<th id="SendMediaTermStatus_{{ $item->id }}">
									
									
									@if($item->SendMediaTermStatus == 50)
									<a href="javascript:" onclick="update('SendMediaTermStatus','{{ $item->id }}')" title="อัพเดทข้อมูล สื่อวาระกลาง = {{ ($item->SendMediaTermStatus == 50 ? trans('api::backend.no'):trans('api::backend.yes')) }}">
										<span class="label label-info">
											{{  trans('api::backend.yes')  }}
										</span>
									</a> 
									@else 
									<a href="javascript:void(0);" >
									<span class="label" style="background-color: gray;">
										{{  trans('api::backend.no')  }}
									</span>
									@endif	
									</a>								
							
								</th>
								<th id="interesting_issues_{{ $item->id }}">
									<a href="javascript:" onclick="update('interesting_issues','{{ $item->id }}')" title="อัพเดทข้อมูล {{ trans('api::backend.interesting_issues') }} = {{ ($item->interesting_issues ==2 ? trans('api::backend.no'):trans('api::backend.yes')) }}">
									@if($item->interesting_issues == 2)
									<span class="label label-info">
										{{  trans('api::backend.yes')  }}
									</span>
									@else 
									<span class="label label-danger">
										{{  trans('api::backend.no')  }}
									</span>
									@endif									
									</a> 
								</th>
								<th id="articles_research_{{ $item->id }}">
									<a href="javascript:" onclick="update('articles_research','{{ $item->id }}')" title="อัพเดทข้อมูล {{ trans('api::backend.articles_research') }} = {{ ($item->articles_research ==2 ? trans('api::backend.no'):trans('api::backend.yes')) }}">
									@if($item->articles_research == 2)
									<span class="label label-info">
										{{  trans('api::backend.yes')  }}
									</span>
									@else 
									<span class="label label-danger">
										{{  trans('api::backend.no')  }}
									</span>
									@endif									
									</a> 
								</th>
								
								<th id="include_statistics_{{ $item->id }}">
									<a href="javascript:" onclick="update('include_statistics','{{ $item->id }}')" title="อัพเดทข้อมูล {{ trans('api::backend.include_statistics') }} = {{ ($item->include_statistics ==2 ? trans('api::backend.no'):trans('api::backend.yes')) }}">
									@if($item->include_statistics == 2)
									<span class="label label-info">
										{{  trans('api::backend.yes')  }}
									</span>
									@else 
									<span class="label label-danger">
										{{  trans('api::backend.no')  }}
									</span>
									@endif									
									</a> 
								</th>
								<!--<th id="notable_books_{{ $item->id }}">
									<a href="javascript:" onclick="update('notable_books','{{ $item->id }}')" title="อัพเดทข้อมูล {{ trans('api::backend.notable_books') }} = {{ ($item->notable_books ==2 ? trans('api::backend.no'):trans('api::backend.yes')) }}">
									@if($item->notable_books == 2)
									<span class="label label-info">
										{{  trans('api::backend.yes')  }}
									</span>
									@else 
									<span class="label label-danger">
										{{  trans('api::backend.no')  }}
									</span>
									@endif									
									</a> 
								</th>-->
								<th id="knowledges_{{ $item->id }}">
									<a href="javascript:" onclick="update('knowledges','{{ $item->id }}')" title="อัพเดทข้อมูล {{ trans('api::backend.knowledges') }} = {{ ($item->knowledges ==2 ? trans('api::backend.no'):trans('api::backend.yes')) }}">
									@if($item->knowledges == 2)
									<span class="label label-info">
										{{  trans('api::backend.yes')  }}
									</span>
									@else 
									<span class="label label-danger">
										{{  trans('api::backend.no')  }}
									</span>
									@endif									
									</a> 
								</th>
								<th id="media_campaign_{{ $item->id }}">
									<a href="javascript:" onclick="update('media_campaign','{{ $item->id }}')" title="อัพเดทข้อมูล {{ trans('api::backend.media_campaign') }} = {{ ($item->media_campaign ==2 ? trans('api::backend.no'):trans('api::backend.yes')) }}">
									@if($item->media_campaign == 2)
									<span class="label label-info">
										{{  trans('api::backend.yes')  }}
									</span>
									@else 
									<span class="label label-danger">
										{{  trans('api::backend.no')  }}
									</span>
									@endif									
									</a> 
								</th>
								<th>
								@php
								
								
	                           	$status = ['publish' =>trans('api::backend.publish'),'draft'=>trans('api::backend.draft')];
	                           	//,'trash' =>trans('api::backend.trash')
	                            //dd($item->status);
	                        	@endphp
								{!! Form::select('status_list_'.$item->id,$status,$item->status,['class' => 'form-control','onchange'=>'update("status","'.$item->id.'")']) !!}
								</th>
								<th id="api_{{ $item->id }}">
									<a href="javascript:" onclick="update('api','{{ $item->id }}')" title="อัพเดทข้อมูล {{ trans('api::backend.api') }} = {{ ($item->api =='publish' ? trans('api::backend.draft'):trans('api::backend.publish')) }}">
										@if($item->api == 'publish')
										<span class="label label-info">
											{{  trans('api::backend.publish')  }}
										</span>
										@else
										<span class="label label-danger">
											{{  trans('api::backend.draft')  }}
										</span>
										@endif									
									</a> 
								</th>								
								<th>{{ date('d M Y', strtotime($item->created_at)) }}</th>
								<th>{{ isset($item->updatedBy) ? $item->updatedBy->name:'' }}</th>
								<th>
									<a href="{{ route('media-detail',\Hashids::encode($item->id)) }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> {{ trans('api::backend.preview') }}
									</a>
									<a href="{{ $json_data->FileAddress }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> {{ trans('api::backend.dol_preview') }}
									</a>
									<a href="{{ route('admin.api.list-media.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('api::backend.edit') }}
									</a>
									<div id="ex{{ $item->id }}" class="modal">
									<div class="box-body">
										<div class="form-group">
											<label for="InputName">{{ trans('article::backend.title') }} <span style="color:red">*</span></label>
											{{ Form::text('title_'.$item->id,$item->title,['class'=>'form-control']) }}
										</div>
										<div class="form-group">
											<label for="InputName">{{ trans('article::backend.description') }}</label>
											{{ Form::textarea('description_'.$item->id,$item->description,['class'=>'form-control']) }}
                                		</div>
										<div class="form-group">
											<label for="InputName">Issues</label>
											@php 
											
													$issue_array = collect($issue2)->pluck('title','issues_id');
													$issue_select = [];
													//dd($json_data->Issues);
													if(gettype($json_data->Issues) =='array'){
														foreach($json_data->Issues AS $key_issues=>$value_issues){
															//dd($key_issues,$value_issues);
															array_push($issue_select,$value_issues->ID);
														}
														//dd($issue_array,$issue_select);
													}else{

													}
												
											@endphp
											<!--@if(gettype($json_data->Issues) =='array')
                                                    <pre>
                                                @php
                                                    print_r($json_data->Issues);
                                                @endphp
                                                    </pre>
                                            @else
                                                {{ Form::text('Issues_view',$json_data->Issues,['class'=>'form-control','readonly'=>'readonly']) }}
                                            @endif-->
											{!! Form::select('issue_'.$item->id.'[]',$issue_array,$issue_select, ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'issue_'.$item->id]) !!}
                                		</div>
										<div class="form-group">
											<label for="InputName">Targets</label>
											@php 
											
													$target_array = collect($target2)->pluck('title','target_id');
													$target_select = [];
													if(gettype($json_data->Targets) =='array'){
														foreach($json_data->Targets AS $key_target=>$value_target){
															//dd($key_target,$value_target);
															array_push($target_select,$value_target->ID);
														}
														//dd($target_array,$target_select);
													}else{

													}
				
											@endphp
											<!-- @if(gettype($json_data->Targets) =='array')
                                                    <pre>
                                                @php
                                                    print_r($json_data->Targets);
                                                @endphp
                                                    </pre>
                                            @else
                                                {{ Form::text('Targets_view',$json_data->Targets,['class'=>'form-control','readonly'=>'readonly']) }}
                                            @endif-->
											{!! Form::select('target_'.$item->id.'[]',$target_array,$target_select, ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'target_'.$item->id]) !!}
                                		</div>
										<div class="form-group">
											<label for="InputName">Settings</label>
											@php 
													$setting_array = collect($setting)->pluck('title','setting_id');
													$setting_select = [];
											
													if(gettype($json_data->Settings) =='array'){
														foreach($json_data->Settings AS $key_setting=>$value_setting){
															//dd($key_setting,$value_setting);
															array_push($setting_select,$value_setting->ID);
														}
														//dd($setting_array,$setting_select);
													}else{

													}
										
											@endphp
											<!--@if(gettype($json_data->Settings) =='array')
                                                    <pre>
                                                @php
                                                    print_r($json_data->Settings);
                                                @endphp
                                                    </pre>
                                            @else
                                                {{ Form::text('Settings_view',$json_data->Settings,['class'=>'form-control','readonly'=>'readonly']) }}
                                            @endif-->
											{!! Form::select('setting_'.$item->id.'[]',$setting_array,$setting_select, ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'setting_'.$item->id]) !!}
                                		</div>	
										<div class="form-group">
											<label for="InputName">Template</label>
											{{ Form::text('Template_view',$json_data->Template,['class'=>'form-control','readonly'=>'readonly']) }}
										</div>
										<div class="form-group">
											<label for="InputName">TemplateDetail </label>
											<!--{{ Form::text('TemplateDetail_'.$item->id,(isset($json_data->TemplateDetail) ? $json_data->TemplateDetail:''),['class'=>'form-control']) }} -->
											<select  name="TemplateDetail_{{ $item->id }}" class="form-control">
											<option value=""> เลือก TemplateDetail </option>
											<optgroup label="Text">
												<option value="01" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "01" ? 'selected':''):'') }} >คู่มือ</option>
												<option value="02" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "02" ? 'selected':''):'') }} >รายงาน</option>
												<option value="03" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "03" ? 'selected':''):'') }} >หนังสือ</option>
												<option value="04" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "04" ? 'selected':''):'') }} >ข่าว</option>
												<option value="05" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "05" ? 'selected':''):'') }} >บทความ</option>
												<option value="22" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "22" ? 'selected':''):'') }} >งานวิจัย</option>
											</optgroup>
											<optgroup label="Visual">
												<option value="06" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "06" ? 'selected':''):'') }} >Infographic</option>
												<option value="07" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "07" ? 'selected':''):'') }}>Logo</option>
												<option value="08" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "08" ? 'selected':''):'') }}>งาน design</option>
												<option value="09" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "09" ? 'selected':''):'') }}>ภาพวาด</option>
												<option value="10" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "10" ? 'selected':''):'') }}>รูปถ่าย</option>
											</optgroup>
											<optgroup label="Multimedia">
												<option value="11" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "11" ? 'selected':''):'') }}>โฆษณา</option>
												<option value="12" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "12" ? 'selected':''):'') }}>รายการโทรทัศน์</option>
												<option value="13" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "13" ? 'selected':''):'') }}>คลิป</option>
												<option value="14" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "14" ? 'selected':''):'') }}>แอนิเมชั่น</option>
												<option value="15" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "15" ? 'selected':''):'') }}>มิวสิควิดีโอ</option>
												<option value="16" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "16" ? 'selected':''):'') }}>เกม, แอพพลิเคชั่น</option>
												<option value="17" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "17" ? 'selected':''):'') }}>สปอตวิทยุ</option>
												<option value="18" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "18" ? 'selected':''):'') }}>สื่อเสียงทางวิทยุ</option>
												<option value="19" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "19" ? 'selected':''):'') }}>เพลง</option>
											</optgroup>
											<optgroup label="Knowledge Package">
												<option value="20" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "20" ? 'selected':''):'') }}>Knowledge Package</option>
											</optgroup>
											<optgroup label="Application">
												<option value="21" {{ (isset($json_data->TemplateDetail)  ? ($json_data->TemplateDetail == "21" ? 'selected':''):'') }}>Application</option>
											</optgroup>
											</select>
											
											<p class="help-block">เลือกประเภทย่อย จะกำหนดค่าตาม default (อ้างอิงเอกสาร MasterData.xlsx sheet “TemplateDetail”)</p>
										</div>
										<div class="form-group">
											<label for="InputName">Creator (ผู้สร้างสรรค์)</label>
											{{ Form::text('Creator_'.$item->id,(isset($json_data->Creator) ? $json_data->Creator:''),['class'=>'form-control']) }}
											<p class="help-block">ในกรณีที่ Template ชื่อ Text , Multimedia , Visual , Application ต้องระบุ Creator ทุกครั้ง</p>
										</div>		
										<div class="form-group">
											<label for="InputName">Production (ผู้ผลิต)</label>
											{{ Form::text('Production_'.$item->id,(isset($json_data->Production) ? $json_data->Production:''),['class'=>'form-control']) }}
											<p class="help-block">ในกรณีที่ Template ชื่อ Multimedia , Visual ต้องระบุ Production ทุกครั้ง</p>
										</div>
										<div class="form-group">
											<label for="InputName">OS (ระบบปฏิบัติการ)</label>
											{{ Form::text('OS_'.$item->id,(isset($json_data->OS) ? $json_data->OS:''),['class'=>'form-control']) }}
											<p class="help-block">ในกรณีที่ Template ชื่อ Application ต้องระบุ OS ทุกครั้ง</p>
										</div>												
										<div class="form-group">
											<label for="InputName">Owner (เจ้าของงาน)</label>
											{{ Form::text('Owner_'.$item->id,(isset($json_data->Owner) ? $json_data->Owner:''),['class'=>'form-control']) }}
										</div>											
										<div class="form-group">
											<label for="InputName">Publisher (สำนักพิมพ์)</label>
											{{ Form::text('Publisher_'.$item->id,(isset($json_data->Publisher) ? $json_data->Publisher:''),['class'=>'form-control']) }}
										</div>
										<div class="form-group">
											<label for="InputName">Source (แหล่งที่มา)</label>
											{{ Form::text('Source_'.$item->id,(isset($json_data->Source) ? $json_data->Source:''),['class'=>'form-control']) }}
											<p class="help-block">ในกรณีที่ Template ชื่อ Text ต้องระบุ Source ทุกครั้ง</p>
										</div>																			
										<div class="form-group">
											<label for="InputName">Format </label>
											{{ Form::text('Format_'.$item->id,(isset($json_data->Format) ? $json_data->Format:''),['class'=>'form-control']) }}
											<p class="help-block">รูปแบบของสื่อ เช่น นามสกุลไฟล์ หรือ mimetype (ในกรณีที่ Template ชื่อ Text , Multimedia , Visual ต้องระบุ Format ทุกครั้ง)</p>
										</div>											
										
										<div class="coupon">
											<div class="container">
												<h3>THRC</h3>
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.status') }} </label>
												{{ Form::radio('status_'.$item->id, 'publish', ($item->status == 'publish' ? true:false), ['onclick'=>'hideReason("not_publish_reason_' . $item->id . '", "publish");']) }} {{ trans('api::backend.publish') }}
												{{ Form::radio('status_'.$item->id, 'draft', ($item->status == 'draft' ? true:false), ['onclick'=>'hideReason("not_publish_reason_' . $item->id . '", "draft");']) }} {{ trans('api::backend.draft') }}
											</div>
											<div class="form-group fg_1" id="not_publish_reason_{{ $item->id }}" {{ ($item->status == 'publish') ? 'style=display:none;' : '' }}>
												<label for="InputName">เหตุผลที่ไม่เผยแพร่ </label>
												@php 
													$tmp_not_publish_reason = array();
													if(isset($item->not_publish_reason)) {
														$tmp_not_publish_reason = explode(",", $item->not_publish_reason);
													
													}
												@endphp
												<select name="not_publish_reason_{{ $item->id }}" id="" class="form-control js-tags-tokenizer sg_1" data-placeholder="เลือกเหตุผล" multiple="multiple">
													<option value="รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" {{ (in_array("รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ", $tmp_not_publish_reason)) ? "selected" : ""  }} >รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ</option>
													<option value="ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" {{ (in_array("ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ", $tmp_not_publish_reason)) ? "selected" : ""  }} >ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ</option>
													<option value="รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ"  {{ (in_array("รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ", $tmp_not_publish_reason)) ? "selected" : ""  }} >รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ</option>
													<option value="เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" {{ (in_array("เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง", $tmp_not_publish_reason)) ? "selected" : ""  }} >เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง </option>
													<option value="ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" {{ (in_array("ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)", $tmp_not_publish_reason)) ? "selected" : ""  }} >ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)</option>
													<option value="การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" {{ (in_array("การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL", $tmp_not_publish_reason)) ? "selected" : ""  }} >การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL </option>
													<option value="ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" {{ (in_array("ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่", $tmp_not_publish_reason)) ? "selected" : ""  }} >ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่) </option>
													<option value="ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" {{ (in_array("ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ",$tmp_not_publish_reason)) ? "selected" : ""  }} >ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ </option>
													<option value="ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL"  {{ (in_array("ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL", $tmp_not_publish_reason)) ? "selected" : ""  }} >ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL</option>
												</select>
											</div>

											@if ($item->SendMediaTermStatus == '50') 

												<div class="form-group">
													<label for="InputName">สื่อวาระกลาง</label>
													{{ Form::radio('term_'.$item->id, '50', ($item->SendMediaTermStatus == '50' ? true:false), ['onclick'=>'hideReason("not_term_' . $item->id . '", "publish");']) }} เป็นสื่อวาระกลาง
													{{ Form::radio('term_'.$item->id, '', ($item->SendMediaTermStatus == '' ? true:false), ['onclick'=>'hideReason("not_term_' . $item->id . '", "draft");']) }} ไม่เป็นสื่อวาระกลาง
												</div>
												<div class="form-group" id="not_term_{{ $item->id }}"  {{ ($item->SendMediaTermStatus != '50') ? '' : 'style=display:none;' }}>
													<label for="InputName"> </label>
													<select name="not_term_{{ $item->id }}" class="form-control js-tags-tokenizer sg_1"  multiple="multiple" data-placeholder="เลือกเหตุผล">
														<option value=""> </option>
														<option value="รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" ? 'selected':''):'') }} >รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ</option>
														<option value="ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" ? 'selected':''):'') }} >ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ</option>
														<option value="รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" ? 'selected':''):'') }} >รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ</option>
														<option value="เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" ? 'selected':''):'') }} >เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง </option>
														<option value="ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" ? 'selected':''):'') }} >ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)</option>
														<option value="การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" ? 'selected':''):'') }} >การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL </option>
														<option value="ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" ? 'selected':''):'') }} >ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่) </option>
														<option value="ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" ? 'selected':''):'') }} >ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ </option>
														<option value="ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" {{ (isset($item->not_publish_reason)  ? ($item->not_publish_reason == "ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" ? 'selected':''):'') }} >ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL</option>
													</select><br><br>
													<div class="fg_1">
														<label for="InputName"> </label>
														<input type="text"  class="form-control not_trem" placeholder="กรอกเหตุผลเพิ่มเติม ( หากไม่มีให้เว้นว่างไว้ )" name="detail_not_trem_{{$item->id}}" >
													</div>
												</div>
											
											@endif

											<div class="form-group">
                                                <label for="InputName">ช่องทางที่เผยแพร่ </label></br>
												{{ Form::checkbox('show_rc'.$item->id,'2', ($item->show_rc == '2' ? true:false)) }} Resourcecenter.thaihealth.or.th</br>
												
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.interesting_issues') }} </label>
												{{ Form::radio('interesting_issues_'.$item->id, '2', ($item->interesting_issues == '2' ? true:false), ['onclick'=>'hideReason("interesting_issues_not_publish_reason_' . $item->id . '", "publish");']) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('interesting_issues_'.$item->id, '1', ($item->interesting_issues == '1' ? true:false), ['onclick'=>'hideReason("interesting_issues_not_publish_reason_' . $item->id . '", "draft");']) }}{{ trans('api::backend.draft') }}
											</div>
											<!-- {{$item->interesting_issues_not_publish_reason}} -->											
											<!-- <div class="form-group fg_1" id="interesting_issues_not_publish_reason_{{ $item->id }}"  {{ ($item->interesting_issues != '2') ? '' : 'style=display:none;' }}>
												<label for="InputName">เหตุผลที่ไม่เผยแพร่ </label>
												<select name="interesting_issues_not_publish_reason_{{ $item->id }}" class="form-control sg_1">
													<option value=""> กรุณาเลือกเหตุผล </option>
													<option value="รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" ? 'selected':''):'') }} >รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ</option>
													<option value="ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" ? 'selected':''):'') }} >ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ</option>
													<option value="รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" ? 'selected':''):'') }} >รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ</option>
													<option value="เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" ? 'selected':''):'') }} >เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง </option>
													<option value="ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" ? 'selected':''):'') }} >ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)</option>
													<option value="การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" ? 'selected':''):'') }} >การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL </option>
													<option value="ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" ? 'selected':''):'') }} >ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่) </option>
													<option value="ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" ? 'selected':''):'') }} >ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ </option>
													<option value="ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" {{ (isset($item->interesting_issues_not_publish_reason) ? ($item->interesting_issues_not_publish_reason == "ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" ? 'selected':''):'') }} >ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL</option>
												</select>
											</div>							 -->
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.articles_research') }} </label>
												{{ Form::radio('articles_research_'.$item->id, '2', ($item->articles_research == '2' ? true:false), ['onclick'=>'hideReason("research_not_publish_reason_' . $item->id . '", "publish");']) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('articles_research_'.$item->id, '1', ($item->articles_research == '1' ? true:false), ['onclick'=>'hideReason("research_not_publish_reason_' . $item->id . '", "draft");']) }}{{ trans('api::backend.draft') }}
											</div>
											<!-- <div class="form-group fg_1" id="research_not_publish_reason_{{ $item->id }}" {{ ($item->articles_research != '2') ? '' : 'style=display:none;' }}>
												<label  for="InputName">เหตุผลที่ไม่เผยแพร่ </label>
												<select  name="research_not_publish_reason_{{ $item->id }}" class="form-control sg_1">
													<option value=""> กรุณาเลือกเหตุผล </option>
													<option value="รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" ? 'selected':''):'') }} >รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ</option>
													<option value="ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" ? 'selected':''):'') }} >ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ</option>
													<option value="รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" ? 'selected':''):'') }} >รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ</option>
													<option value="เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" ? 'selected':''):'') }} >เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง </option>
													<option value="ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" ? 'selected':''):'') }} >ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)</option>
													<option value="การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" ? 'selected':''):'') }} >การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL </option>
													<option value="ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" ? 'selected':''):'') }} >ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่) </option>
													<option value="ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" ? 'selected':''):'') }} >ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ </option>
													<option value="ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" {{ (isset($item->research_not_publish_reason)  ? ($item->research_not_publish_reason == "ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" ? 'selected':''):'') }} >ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL</option>
												</select>
											</div> -->
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.include_statistics') }} </label>
												{{ Form::radio('include_statistics_'.$item->id, '2', ($item->include_statistics == '2' ? true:false), ['onclick'=>'hideReason("stat_not_publish_reason_' . $item->id . '", "publish");']) }} {{ trans('api::backend.publish') }}
												{{ Form::radio('include_statistics_'.$item->id, '1', ($item->include_statistics == '1' ? true:false), ['onclick'=>'hideReason("stat_not_publish_reason_' . $item->id . '", "draft");']) }} {{ trans('api::backend.draft') }}
											</div>
											<!-- <div class="form-group fg_1" id="stat_not_publish_reason_{{ $item->id }}" {{ ($item->include_statistics != '2') ? '' : 'style=display:none;' }}>
												<label for="InputName">เหตุผลที่ไม่เผยแพร่ </label>
												<select name="stat_not_publish_reason_{{ $item->id }}" class="form-control sg_1">
												<option value=""> กรุณาเลือกเหตุผล </option>
													<option value="รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" ? 'selected':''):'') }} >รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ</option>
													<option value="ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" ? 'selected':''):'') }} >ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ</option>
													<option value="รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" ? 'selected':''):'') }} >รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ</option>
													<option value="เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" ? 'selected':''):'') }} >เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง </option>
													<option value="ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" ? 'selected':''):'') }} >ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)</option>
													<option value="การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" ? 'selected':''):'') }} >การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL </option>
													<option value="ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" ? 'selected':''):'') }} >ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่) </option>
													<option value="ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" ? 'selected':''):'') }} >ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ </option>
													<option value="ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" {{ (isset($item->stat_not_publish_reason)  ? ($item->stat_not_publish_reason == "ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" ? 'selected':''):'') }} >ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL</option>
												</select>
											</div> -->
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.knowledges') }} </label>
												{{ Form::radio('knowledges_'.$item->id, '2', ($item->knowledges == '2' ? true:false), ['onclick'=>'hideReason("knowledge_not_publish_reason_' . $item->id . '", "publish");']) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('knowledges_'.$item->id, '1', ($item->knowledges == '1' ? true:false), ['onclick'=>'hideReason("knowledge_not_publish_reason_' . $item->id . '", "draft");']) }}{{ trans('api::backend.draft') }}
											</div>
											<!-- <div class="form-group fg_1" id="knowledge_not_publish_reason_{{ $item->id }}" {{ ($item->knowledges != '2') ? '' : 'style=display:none;' }}>
												<label  for="InputName">เหตุผลที่ไม่เผยแพร่ </label>
												<select  name="knowledge_not_publish_reason_{{ $item->id }}" class="form-control sg_1">
												<option value=""> กรุณาเลือกเหตุผล </option>
													<option value="รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" ? 'selected':''):'') }} >รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ</option>
													<option value="ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" ? 'selected':''):'') }} >ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ</option>
													<option value="รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" ? 'selected':''):'') }} >รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ</option>
													<option value="เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" ? 'selected':''):'') }} >เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง </option>
													<option value="ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" ? 'selected':''):'') }} >ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)</option>
													<option value="การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" ? 'selected':''):'') }} >การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL </option>
													<option value="ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" ? 'selected':''):'') }} >ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่) </option>
													<option value="ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" ? 'selected':''):'') }} >ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ </option>
													<option value="ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" {{ (isset($item->knowledge_not_publish_reason)  ? ($item->knowledge_not_publish_reason == "ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" ? 'selected':''):'') }} >ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL</option>
												</select>
											</div> -->
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.media_campaign') }} </label>
												{{ Form::radio('media_campaign_'.$item->id, '2', ($item->media_campaign == '2' ? true:false), ['onclick'=>'hideReason("campaign_not_publish_reason_' . $item->id . '", "publish");']) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('media_campaign_'.$item->id, '1', ($item->media_campaign == '1' ? true:false), ['onclick'=>'hideReason("campaign_not_publish_reason_' . $item->id . '", "draft");']) }}{{ trans('api::backend.draft') }}
											</div>
											<!-- <div class="form-group fg_1" id="campaign_not_publish_reason_{{ $item->id }}" {{ ($item->media_campaign != '2') ? '' : 'style=display:none;' }}>
												<label  for="InputName">เหตุผลที่ไม่เผยแพร่ </label>
												<select  name="campaign_not_publish_reason_{{ $item->id }}" class="form-control sg_1">
												<option value=""> กรุณาเลือกเหตุผล </option>
													<option value="รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ" ? 'selected':''):'') }} >รายงานโครงการ/ รายงานงวด/ ภาพกิจกรรมในโครงการฯ/ ภาพข่าวในโครงการฯ</option>
													<option value="ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ" ? 'selected':''):'') }} >ข่าวที่ตัดปะ/ โควทคำ/ โควทข่าว/ โควทภาพ หรือข่าวจากแหล่งต่างๆ</option>
													<option value="รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ" ? 'selected':''):'') }} >รายงานอื่นๆ ของสำนักหรือของภาคี เช่น รายงานการดำเนินงาน รายงานประจำปี รายงานสรุปผลโครงการ ฯลฯ</option>
													<option value="เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง" ? 'selected':''):'') }} >เป็นสื่อที่ใช้ในการขับเคลื่อนงานในเชิงพื้นที่ที่ เหมาะสำหรับผู้นำการเปลี่ยนแปลง </option>
													<option value="ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)" ? 'selected':''):'') }} >ตัวอักษรเล็ก ภาพไม่ชัดเจน (ส่วนใหญ่เป็นสื่อ Infographic)</option>
													<option value="การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL" ? 'selected':''):'') }} >การตั้งชื่อไม่สอดคล้องกับตัวสื่อ ขอให้ใส่ชื่อให้สมบูรณ์ (ไม่ควรใช้ชื่อไฟล์สื่อเป็นชื่อสื่อ) ขอให้ปรับชื่อของสื่อใน DOL </option>
													<option value="ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่)" ? 'selected':''):'') }} >ไฟล์ที่เป็นการออกแบบ หรือโครงสร้าง หรือภาพถ่าย หรือ logo ขอให้ใส่สื่อใหม่เป็น Jpeg หรือ PNG ใน DOL (สำนักต้องทำขั้นตอนใน DOL ใหม่) </option>
													<option value="ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ" ? 'selected':''):'') }} >ปลายทาง link URL จำกัดสิทธิ์เข้าถึง หรือมีการโยกย้ายปรับปรุงที่ส่งผลกระทบกับตัวสื่อ ทำให้หาสื่อไม่เจอ </option>
													<option value="ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" {{ (isset($item->campaign_not_publish_reason)  ? ($item->campaign_not_publish_reason == "ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL" ? 'selected':''):'') }} >ข้อมูลไม่สมบูรณ์ขาดหน้าปกสื่อ ขอให้เพิ่มเติมการใส่รูปภาพหน้าปกสื่อที่เป็น Link , คลิป, ไฟล์เสียง, Multimedia ต่างๆ ใน DOL</option>
												</select>
											</div> -->
															
											<div class="form-group">
                                                <label for="InputName"> </label></br>
												{{ Form::checkbox('show_dol'.$item->id,'2',($item->show_dol == '2' ? true:false)) }} Persona Health</br>
												{{ Form::checkbox('show_learning'.$item->id,'2',($item->show_learning == '2' ? true:false)) }} Learningpartner.thaihealth.or.th</br>
											</div>
											<div class="form-group">
                                                <label for="InputName">วันที่เผยแพร่ </label>	
												<input class="showtime_date" type="date" name="{{'start_date'.$item->id}}" style="margin-left:0;" placeholder="{{ trans('dashboard::backend.start_date') }}" class="form-control" value="{{$item->start_date}}"/><br>
												<label for="InputName">
													{{ Form::checkbox('chk_end_date','1', ($item->end_date != '' ? true : false), ['onclick'=>'setReadOnly("set_end_date_' . $item->id . '");']) }} วันที่สิ้นสุด 
												</label>
												<input id="set_end_date_{{$item->id}}" class="showtime_date" type="date" name="{{'end_date'.$item->id}}" class="form-control" value="{{$item->end_date}}" placeholder="{{ trans('dashboard::backend.start_date') }}" {{ ($item->end_date != '' ? '' : 'readonly') }} />
											</div>
											<div class="form-group">
												<label>{{ trans('api::backend.tags') }} </label>
												{!! Form::select('tags_'.$item->id.'[]',$tags,json_decode($item->tags) ?? old('tags'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'tags_'.$item->id]) !!}
												<p class="help-block">ใช้งานร่วมกันทั้ง API และ WebView</p>
											</div>
											<div class="form-group">
												@php 
													$keywords_dol = collect($json_data->Keywords);
													$keywords_dol_array = [];
													if($keywords_dol->count() > 0){
														foreach($keywords_dol AS $key_dol=>$value_dol){
															$keywords_dol_array[$value_dol] = $value_dol;
														}
													}
													
													//dd($json_data->Keywords,$tags,json_decode($item->tags),$keywords_dol->count(),$keywords_dol_array);
												@endphp
												<label>{{ trans('api::backend.tags') }} (Dol)</label>
												{!! Form::select('tags_dol_'.$item->id.'[]',$keywords_dol_array,$json_data->Keywords ?? old('tags_dol'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'tags_dol_'.$item->id]) !!}
												<p class="help-block">ใช้งานร่วมกันทั้ง API และ WebView</p>
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.recommend') }} </label>
												{{ Form::radio('recommend_'.$item->id, '2', ($item->recommend == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
												{{ Form::radio('recommend_'.$item->id, '1', ($item->recommend == '1' ? true:false)) }}{{ trans('api::backend.no') }}
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.highlight') }} </label>
												{{ Form::radio('featured_'.$item->id, '2', ($item->featured == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
												{{ Form::radio('featured_'.$item->id, '1', ($item->featured == '1' ? true:false)) }}{{ trans('api::backend.no') }}
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('article::backend.cover_image_desktop') }} (1366px * 768px)</label>
												@if ($item->getMedia('cover_desktop')->isNotEmpty())
												<div><img src="{{ asset($item->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
												@endif
												<div class="areaImage">{!! Form::file('cover_desktop_'.$item->id,['id'=>'cover_desktop_'.$item->id]) !!}</div>
												<p class="help-block">นามสกุลไฟล์: jpg, png (ไม่เกิน 5M) ในกรณีที่อัพโหลดรูปภาพหน้าปก ระบบจะใช้รูปภาพหน้าปกที่อัพโหลดแทนการใช้รูปภาพหน้าปกจาก Dol</p>
											</div>	
											<div class="form-group">
												<label for="InputName">{{ trans('article::backend.cover_image_desktop') }} (Dol)</label>
												@if ($json_data->ThumbnailAddress !='')
												<div><img src="{{ $json_data->ThumbnailAddress }}" width="250"></div>
												@endif
											</div>											
										</div>
										<br>		
										<div class="coupon">
											<div class="container">
												<h3>API</h3>
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.api') }} </label>
												{{ Form::radio('api_'.$item->id, 'publish', ($item->api == 'publish' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('api_'.$item->id, 'draft', ($item->api == 'draft' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>
											<div class="form-group">
												<label>{{ trans('api::backend.sex') }} </label>
												@php
													$tmp_sex_select = json_decode($item->sex);
													if(count(json_decode($item->sex)) != count($sex)) {
														$tmp_sex_select = array();
														foreach ($sex as $key => $value) {
															array_push($tmp_sex_select, $key);
														}
													} 
												@endphp				
												{{-- 
													{!! Form::select('sex_'.$item->id.'[]', $sex, json_decode($item->sex) ?? old('sex'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'sex_'.$item->id]) !!}
												--}}
												{!! Form::select('sex_'.$item->id.'[]', $sex, $tmp_sex_select ?? old('sex'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'sex_'.$item->id]) !!}
												<p class="help-block">ใช้งานร่วมกันทั้ง API และ WebView</p>
											</div>
											<div class="form-group">
												<label>{{ trans('api::backend.age') }} </label>
												{{--
													{!! Form::select('age_'.$item->id.'[]',$age,json_decode($item->age) ?? old('age'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'age_'.$item->id]) !!}
												--}}
												@php
													$tmp_age_select = json_decode($item->age);
													if(count(json_decode($item->age)) != count($age)) {
														$tmp_age_select = array();
														foreach ($age as $key => $value) {
															array_push($tmp_age_select, $key);
														}
													} 
												@endphp		
												{!! Form::select('age_'.$item->id.'[]',$age, $tmp_age_select  ?? old('age'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple','id'=>'age_'.$item->id]) !!}
												<p class="help-block">ใช้งานร่วมกันทั้ง API และ WebView</p>
											</div> 
										</div>
										<br>
										<div class="coupon">
											<div class="container">
												<h3>WebView</h3>
											</div>
											<div class="form-group">
												<label for="InputName">URL Example: <a href="{{ route('media-list-webview') }}" target="_blank">{{ route('media-list-webview') }}</a></label>
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.web_view') }} </label>
												{{ Form::radio('web_view_'.$item->id, '1', ($item->web_view == '1' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('web_view_'.$item->id, '0', ($item->web_view == '0' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>		
										</div>
										<br>
										<div class="coupon">
											<div class="container">
												<h3>Ncds</h3>
											</div>
											@php
											//dd($item->health_literacy);
											@endphp
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.health-literacy') }} </label>
												{{ Form::radio('health_literacy_'.$item->id, '2', ($item->health_literacy == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('health_literacy_'.$item->id, '1', ($item->health_literacy == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.ncds_2') }} </label>
												{{ Form::radio('ncds_2_'.$item->id, '2', ($item->ncds_2 == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('ncds_2_'.$item->id, '1', ($item->ncds_2 == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
												<br>
												<label for="InputName">สถานการณ์ NCDs</label>
												{{ Form::radio('ncds_2_situation'.$item->id, '1', ($item->ncds_2_situation == '1' ? true:false)) }} ทั่วไป 
												{{ Form::radio('ncds_2_situation'.$item->id, '2', ($item->ncds_2_situation == '2' ? true:false)) }} ตามโรค
											</div>

											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.ncds_4') }} </label>
												{{ Form::radio('ncds_4_'.$item->id, '2', ($item->ncds_4 == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('ncds_4_'.$item->id, '1', ($item->ncds_4 == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.ncds_6') }} </label>
												{{ Form::radio('ncds_6_'.$item->id, '2', ($item->ncds_6 == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('ncds_6_'.$item->id, '1', ($item->ncds_6 == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>										
										</div>	
										<br>
										<div class="coupon">
											<div class="container">
												<h3>Thaihealth Watch</h3>
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.panel_discussion') }} </label>
												{{ Form::radio('panel_discussion_'.$item->id, '2', ($item->panel_discussion == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('panel_discussion_'.$item->id, '1', ($item->panel_discussion == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.health_trends') }} </label>
												{{ Form::radio('health_trends_'.$item->id, '2', ($item->health_trends == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('health_trends_'.$item->id, '1', ($item->health_trends == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>											
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.points_to_watch_article') }} </label>
												{{ Form::radio('points_to_watch_article_'.$item->id, '2', ($item->points_to_watch_article == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('points_to_watch_article_'.$item->id, '1', ($item->points_to_watch_article == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>											
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.points_to_watch_video') }} </label>
												{{ Form::radio('points_to_watch_video_'.$item->id, '2', ($item->points_to_watch_video == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('points_to_watch_video_'.$item->id, '1', ($item->points_to_watch_video == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>											
											<div class="form-group">
												<label for="InputName">{{ trans('api::backend.points_to_watch_gallery') }} </label>
												{{ Form::radio('points_to_watch_gallery_'.$item->id, '2', ($item->points_to_watch_gallery == '2' ? true:false)) }}{{ trans('api::backend.publish') }}
												{{ Form::radio('points_to_watch_gallery_'.$item->id, '1', ($item->points_to_watch_gallery == '1' ? true:false)) }}{{ trans('api::backend.draft') }}
											</div>											
										</div>
										<br>	
										{{  Form::hidden('UploadFileID_'.$item->id,$json_data->UploadFileID) }}
										{{  Form::hidden('ThumbnailAddress_'.$item->id,$json_data->ThumbnailAddress) }}	
										{{  Form::hidden('FileAddress_'.$item->id,$json_data->FileAddress) }}	
										{{  Form::hidden('Template_'.$item->id,$json_data->Template) }}
									</div>
									    <a onclick="transfer('{{ $item->id }}')" class="btn btn-success pull-righ"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('api::backend.submit') }}</a>
										<a href="#" rel="modal:close"  class="btn btn-default">ปิด</a>
									</div>
									<!-- Link to open the modal -->
									<a href="#ex{{ $item->id }}" rel="modal:open" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('api::backend.edit') }}แบบ Modal
									</a>
									{{-- ปุ่มสำหรับย้ายสื่อไปที่ถังขยะ --}}
									@if (is_null($item->media_trash))
									<button class="btn btn-danger" onclick="movetotrash('{{$item->id}}')">
										<i class="fa fa-trash"></i> ย้ายไปที่ถังขยะ
									</button>
									@else
									<button class="btn btn-danger" onclick="recycletrash('{{$item->id}}')">
										<i class="fa fa-recycle"></i> กู้คืนสื่อจากถังขยะ
									</button>
									<button class="btn btn-danger" onclick="delecttrash('{{$item->id}}')">
										<i class="fa fa-trash"></i> ลบสื่อออกถาวร
									</button>
									@endif
									{{-- 
										ปุ่มสำหรับลบไฟล์ออก 
										จะขึ้นเฉพาะสื่อที่มีการดาวน์โหลดไฟล์ลงมาเท่านั้น
									--}}
									@if (!is_null($item->local_path))
										<button class="btn btn-danger" onclick="deletemediafile('{{$item->id}}')">
											<i class="fa fa-trash"></i> ลบไฟล์สื่อ
										</button>
									@endif
								</th>
							</tr>
							@endforeach
						</tbody>
					</table>
					{!! isset($items) ? $items->appends(\Input::all())->render():'' !!}  
					</div>
					</div>
            	</div>
				
        	</div>
    	</div>
	</div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('javascript')
<script src="{{ asset('adminlte/bower_components/axios/axios.min.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/jquery-loading-master/dist/jquery.loading.js') }}"></script>

<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let url_list_media_update_status = '{{  route('admin.api.list-media.update-status') }}';
let url_list_media_transfer = '{{ route('admin.api.list-media.transfer') }}';
let user_id = '{{  auth()->user()->id }}';
//console.log(url_list_media_update_status);
$(function(){
	$("#start_date").datepicker({
		format:'dd/mm/yyyy'
	});

	$("#end_date").datepicker({
		format:'dd/mm/yyyy'
	});

	$(".js-tags-tokenizer").select2({
            tags: true,
            tokenSeparators: [',', ' '],
			width: 500
    })
    

});


function movetotrash(id) {
	Swal.fire({
		icon: 'question',
		title: "ท่านต้องการย้ายสื่อ \n" + id + "\nไปไว้ที่ถังขยะใช่หรือไม่ ?",   
		showCancelButton: true,
		confirmButtonColor: '#154734',
		cancelButtonColor: '#d33',
		confirmButtonText:  "ใช่ต้องการ",
		cancelButtonText: 'ไม่ต้องการ'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          },
               type: "post",
			   url: "{{ route('movetotrash') }}",
               data: {
   					'id': id
                    },
				beforeSend: function () {
                 $('.content').loading('toggle');
                 },
				success: function (response) {
					console.log('55')
					if (response.status == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                showConfirmButton: false,
                                timer: 1700
                            }).then(() => {
                                location.reload();
                            });
                        }
                }
            });
		}
	})

}

function recycletrash(id) {
	Swal.fire({
		icon: 'question',
		title: "ท่านต้องการกู้คืนสื่อ \n" + id + "\nใช่หรือไม่ ?",   
		showCancelButton: true,
		confirmButtonColor: '#154734',
		cancelButtonColor: '#d33',
		confirmButtonText:  "ใช่ต้องการ",
		cancelButtonText: 'ไม่ต้องการ'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          },
               type: "post",
			   url: "{{ route('recycletrash') }}",
               data: {
   					'id': id
                    },
				beforeSend: function () {
                 $('.content').loading('toggle');
                 },
				success: function (response) {
					if (response.status == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                showConfirmButton: false,
                                timer: 1700
                            }).then(() => {
                                location.reload();
                            });
                        }
                }
            });
		}
	})

}


function deletemediafile(id) {
	Swal.fire({
		icon: 'question',
		title: "ท่านต้องการลบไฟล์สื่อ \n" + id + "\n ใช่หรือไม่ ?",   
		showCancelButton: true,
		confirmButtonColor: '#154734',
		cancelButtonColor: '#d33',
		confirmButtonText:  "ใช่ต้องการ",
		cancelButtonText: 'ไม่ต้องการ'
	}).then((result) => {
		if (result.isConfirmed) {
			if (result.isConfirmed) {
			$.ajax({
				headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          },
               type: "post",
			   url: "{{ route('deletemediafile') }}",
               data: {
   					'id': id
                    },
				beforeSend: function () {
                 $('.content').loading('toggle');
                 },
				success: function (response) {
					if (response.status == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                showConfirmButton: false,
                                timer: 1700
                            }).then(() => {
                                location.reload();
                            });
                        }
                }
            });
		}
		}
	})	
	
}

function delecttrash(id) {
	Swal.fire({
		icon: 'question',
		title: "ท่านต้องการลบสื่อถาวร \n" + id + "\n ใช่หรือไม่ ?",   
		showCancelButton: true,
		confirmButtonColor: '#154734',
		cancelButtonColor: '#d33',
		confirmButtonText:  "ใช่ต้องการ",
		cancelButtonText: 'ไม่ต้องการ'
	}).then((result) => {
		if (result.isConfirmed) {
			if (result.isConfirmed) {
			$.ajax({
				headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          },
               type: "post",
			   url: "{{ route('delecttrash') }}",
               data: {
   					'id': id
                    },
				beforeSend: function () {
                 $('.content').loading('toggle');
                 },
				success: function (response) {
					if (response.status == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                showConfirmButton: false,
                                timer: 1700
                            }).then(() => {
                                location.reload();
                            });
                        }
                }
            });
		}
		}
	})	
	
}

function transfer(id){
	//console.log('transfer'+id);
	$('.content').loading('toggle');
	let title = $("input[name=title_"+id+"]").val();
	let description = $("textarea[name=description_"+id+"]").val();

	let status = $("input[name=status_"+id+"]:checked").val();
	let api = $("input[name=api_"+id+"]:checked").val();
	let web_view = $("input[name=web_view_"+id+"]:checked").val();
	let interesting_issues = $("input[name=interesting_issues_"+id+"]:checked").val();
	let articles_research = $("input[name=articles_research_"+id+"]:checked").val();
	let include_statistics = $("input[name=include_statistics_"+id+"]:checked").val();
	let knowledges = $("input[name=knowledges_"+id+"]:checked").val();
	let media_campaign = $("input[name=media_campaign_"+id+"]:checked").val();
	let health_literacy = $("input[name=health_literacy_"+id+"]:checked").val();
	let recommend = $("input[name=recommend_"+id+"]:checked").val();
	let featured = $("input[name=featured_"+id+"]:checked").val();
	let ncds_2 = $("input[name=ncds_2_"+id+"]:checked").val();
	let ncds_2_situation = $("input[name=ncds_2_situation"+id+"]:checked").val();
	let ncds_4 = $("input[name=ncds_4_"+id+"]:checked").val();
	let ncds_6 = $("input[name=ncds_6_"+id+"]:checked").val();
	let panel_discussion = $("input[name=panel_discussion_"+id+"]:checked").val();
	let health_trends = $("input[name=health_trends_"+id+"]:checked").val();
	let points_to_watch_article = $("input[name=points_to_watch_article_"+id+"]:checked").val();
	let points_to_watch_video = $("input[name=points_to_watch_video_"+id+"]:checked").val();
	let points_to_watch_gallery = $("input[name=points_to_watch_gallery_"+id+"]:checked").val();

	let ThumbnailAddress = $("input[name=ThumbnailAddress_"+id+"]").val();
	let UploadFileID = $("input[name=UploadFileID_"+id+"]").val();
	let FileAddress = $("input[name=FileAddress_"+id+"]").val();
	let Template = $("input[name=Template_"+id+"]").val();
	
	let tags = $('#tags_'+id).val();
	let tags_dol = $('#tags_dol_'+id).val();
	let issue = $('#issue_'+id).val();
	let target = $('#target_'+id).val();
	let setting = $('#setting_'+id).val();

	let template_detail = $("select[name=TemplateDetail_"+id+"]").val();
	let creator = $("input[name=Creator_"+id+"]").val();
	let production = $("input[name=Production_"+id+"]").val();
	let os = $("input[name=OS_"+id+"]").val();
	let owner = $("input[name=Owner_"+id+"]").val();
	let publisher = $("input[name=Publisher_"+id+"]").val();
	let format = $("input[name=Format_"+id+"]").val();
	let source = $("input[name=Source_"+id+"]").val();

	let show_rc = $("input[name=show_rc"+id+"]:checked").val();
    let show_dol = $("input[name=show_dol"+id+"]:checked").val();
    let show_learning = $("input[name=show_learning"+id+"]:checked").val();

	let start_date = $("input[name=start_date"+id+"]").val();
	let end_date = $("input[name=end_date"+id+"]").val();
	let not_publish_reason = $("select[name=not_publish_reason_"+id+"]").val();

	// not publish reason
	// let research_not_publish_reason = $("select[name=research_not_publish_reason_"+id+"]").val();
	// let stat_not_publish_reason = $("select[name=stat_not_publish_reason_"+id+"]").val();
	// let knowledge_not_publish_reason = $("select[name=knowledge_not_publish_reason_"+id+"]").val();
	// let campaign_not_publish_reason = $("select[name=campaign_not_publish_reason_"+id+"]").val();
	// let interesting_issues_not_publish_reason = $("select[name=interesting_issues_not_publish_reason_"+id+"]").val();

	// กรณีสื่อวาระกลาง
	let term = $("input[name=term_" + id + "]:checked").val();
	let not_term = $("select[name=not_term_" + id + "]").val();
	let detail_not_trem = $("input[name=detail_not_trem_"+id+"]").val();

	let sex = $('#sex_'+id).val();
	let age = $('#age_'+id).val();
	var input = document.getElementById('cover_desktop_'+id);
	var file = '';
		if (!input) {
          //alert("Um, couldn't find the fileinput element.");
        }
        else if (!input.files) {
          //alert("This browser doesn't seem to support the `files` property of file inputs.");
        }
        else if (!input.files[0]) {
          //alert("Please select a file before clicking 'Load'");               
        }
        else {
         	file = input.files[0];
		} 
	//console.log('transfer'+id,title,description,status,api,web_view,interesting_issues,articles_research,include_statistics,knowledges,media_campaign,health_literacy,tags,sex,age,file,recommend,featured,ThumbnailAddress,UploadFileID,FileAddress,Template,ncds_2,ncds_4,ncds_6);
	
	let formData = new FormData();

	formData.append('id',id);
    formData.append('title',title);
	formData.append('template_detail',template_detail);
	formData.append('creator',creator);
	formData.append('production',production);
	formData.append('os',os);
	formData.append('owner',owner);
	formData.append('publisher',publisher);
	formData.append('format',format);
	formData.append('source',source);
	formData.append('description',description); 
	formData.append('status',status); 
	formData.append('api',api); 
	formData.append('web_view',web_view); 
	formData.append('interesting_issues',interesting_issues); 
	formData.append('articles_research',articles_research); 
	formData.append('include_statistics',include_statistics); 
	formData.append('knowledges',knowledges); 
	formData.append('media_campaign',media_campaign); 
	formData.append('health_literacy',health_literacy); 
	formData.append('tags',tags); 
	formData.append('tags_dol',tags_dol); 
	formData.append('issue',issue); 
	formData.append('target',target); 
	formData.append('setting',setting); 
	formData.append('sex',sex);
	formData.append('age',age); 
	formData.append('featured',featured); 
	formData.append('recommend',recommend); 
	formData.append('ThumbnailAddress',ThumbnailAddress); 
	formData.append('UploadFileID',UploadFileID); 
	formData.append('FileAddress',FileAddress); 
	formData.append('Template',Template); 
	formData.append('cover_desktop',file);
	formData.append('ncds_2',ncds_2);
	formData.append('ncds_2_situation',ncds_2_situation);	
	formData.append('ncds_4',ncds_4);
	formData.append('ncds_6',ncds_6);
	formData.append('panel_discussion',panel_discussion);
	formData.append('health_trends',health_trends);
	formData.append('points_to_watch_article',points_to_watch_article);
	formData.append('points_to_watch_video',points_to_watch_video);
	formData.append('points_to_watch_gallery',points_to_watch_gallery);
	formData.append('updated_by',user_id); 
    formData.append('show_rc',show_rc); 
    formData.append('show_dol',show_dol); 
    formData.append('show_learning',show_learning); 

	formData.append('start_date',start_date); 	
	formData.append('end_date',end_date); 	
	formData.append('not_publish_reason',not_publish_reason); 


	// formData.append('research_not_publish_reason',research_not_publish_reason); 
	// formData.append('stat_not_publish_reason',stat_not_publish_reason); 
	// formData.append('knowledge_not_publish_reason',knowledge_not_publish_reason); 
	// formData.append('campaign_not_publish_reason',campaign_not_publish_reason); 
	// formData.append('interesting_issues_not_publish_reason',interesting_issues_not_publish_reason); 


	formData.append('term', term); 
	formData.append('not_term', not_term); 
	formData.append('detail_not_trem', detail_not_trem); 
	
	axios.post(url_list_media_transfer,
	  formData,
	  {
        headers: {
        'Content-Type': 'multipart/form-data'
      	}
	  })
	  .then(function (response) {
	        // handle success
	    console.log(response.data);
		// 00 = สำเร็จ
		// 01 = มีปัญหาในการ Login
		// 02 = มีปัญหาที่ข้อมูลส่งมา
		// 500 = มีปัญหาที่ฝั่ง Web Services

	    if(response.status === 200){
			let status_code = 00;
			let message = 'บันทึกสำเร็จ';
			let icon = 'success';

			//warning
			if(response.data.data_json.Code =="02"){
				icon = 'warning';
				message = response.data.data_json.Description;
			}

			$('.content').loading('toggle');

			Swal.fire({
				icon: icon,
				title: message
			});

			location.reload();
		}else{
			$('.content').loading('toggle');
			Swal.fire({
				icon: 'error',
				title: 'ระบบขัดข้องกรุณาติดต่อผู้ดูแลระบบ'
			});
		}


	  })
	  .catch(function (error) {
	        // handle error
	        console.log(error);
	  })
	  .then(function () {
	        // always executed
	});


}


function update(field,id){
	let val;

	$('.content').loading('toggle');
	//console.log(field,id,val);

	if(field =='status'){
		val = $('select[name="status_list_'+id+'"]').val();
		//console.log(val);
	}

	

	axios.post(url_list_media_update_status,{
		field:field,
		id:id,
		val:val,
		media_type:'media'
	  })
	  .then(function (response) {
	        // handle success
	    //console.log(response.data);
	    $('.content').loading('toggle');
	    if(response.status === 200){

			console.log(response.data.field);
			switch(response.data.field) {

		  	  	case 'api':
			    	if(response.data.status_data === true){
			    		$("#api_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.api') }} = {{ trans('api::backend.draft')}}");
			    		$("#api_"+id+' a span').attr('class', 'label label-info');
				    	$("#api_"+id+' a span').text('{{ trans('api::backend.publish') }}');
			    	}else{
			    		$("#api_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.api') }} = {{ trans('api::backend.publish')}}");
			    		$("#api_"+id+' a span').attr('class', 'label label-danger');
				    	$("#api_"+id+' a span').text('{{ trans('api::backend.draft') }}');
			    	}
			    break;
				case 'SendMediaTermStatus':
			    	if(response.data.status_data === true){
			    		$("#SendMediaTermStatus_"+id+' a span').attr("title", "อัพเดทข้อมูล สื่อวาระกลาง = {{ trans('api::backend.no')}}");
			    		$("#SendMediaTermStatus_"+id+' a span').attr('class', 'label label-info');
				    	$("#media_camSendMediaTermStatus_paign_"+id+' a span').text('{{ trans('api::backend.yes') }}');
			    	}else{
			    		$("#SendMediaTermStatus_"+id+' a span').attr("title", "อัพเดทข้อมูล สื่อวาระกลาง = {{ trans('api::backend.yes')}}");
			    		$("#SendMediaTermStatus_"+id+' a span').attr('class', 'label label-danger');
				    	$("#SendMediaTermStatus_"+id+' a span').text('{{ trans('api::backend.no') }}');
			    	}
			    break;
			  	case 'media_campaign':
			    	if(response.data.status_data === true){
			    		$("#media_campaign_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.media_campaign') }} = {{ trans('api::backend.no')}}");
			    		$("#media_campaign_"+id+' a span').attr('class', 'label label-info');
				    	$("#media_campaign_"+id+' a span').text('{{ trans('api::backend.yes') }}');
			    	}else{
			    		$("#media_campaign_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.media_campaign') }} = {{ trans('api::backend.yes')}}");
			    		$("#media_campaign_"+id+' a span').attr('class', 'label label-danger');
				    	$("#media_campaign_"+id+' a span').text('{{ trans('api::backend.no') }}');
			    	}
			    break;
			  	case 'knowledges':
			    	if(response.data.status_data === true){
			    		$("#knowledges_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.knowledges') }} = {{ trans('api::backend.no')}}");
			    		$("#knowledges_"+id+' a span').attr('class', 'label label-info');
				    	$("#knowledges_"+id+' a span').text('{{ trans('api::backend.yes') }}');
			    	}else{
			    		$("#knowledges_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.knowledges') }} = {{ trans('api::backend.yes')}}");
			    		$("#knowledges_"+id+' a span').attr('class', 'label label-danger');
				    	$("#knowledges_"+id+' a span').text('{{ trans('api::backend.no') }}');
			    	}
			    break;
			  	case 'notable_books':
			    	if(response.data.status_data === true){
			    		$("#notable_books_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.notable_books') }} = {{ trans('api::backend.no')}}");
			    		$("#notable_books_"+id+' a span').attr('class', 'label label-info');
				    	$("#notable_books_"+id+' a span').text('{{ trans('api::backend.yes') }}');
			    	}else{
			    		$("#notable_books_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.notable_books') }} = {{ trans('api::backend.yes')}}");
			    		$("#notable_books_"+id+' a span').attr('class', 'label label-danger');
				    	$("#notable_books_"+id+' a span').text('{{ trans('api::backend.no') }}');
			    	}
			    break;
			  	case 'include_statistics':
			    	if(response.data.status_data === true){
			    		$("#include_statistics_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.include_statistics') }} = {{ trans('api::backend.no')}}");
			    		$("#include_statistics_"+id+' a span').attr('class', 'label label-info');
				    	$("#include_statistics_"+id+' a span').text('{{ trans('api::backend.yes') }}');
			    	}else{
			    		$("#include_statistics_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.include_statistics') }} = {{ trans('api::backend.yes')}}");
			    		$("#include_statistics_"+id+' a span').attr('class', 'label label-danger');
				    	$("#include_statistics_"+id+' a span').text('{{ trans('api::backend.no') }}');
			    	}
			    break;
			  	case 'articles_research':
			    	if(response.data.status_data === true){
			    		$("#articles_research_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.articles_research') }} = {{ trans('api::backend.no')}}");
			    		$("#articles_research_"+id+' a span').attr('class', 'label label-info');
				    	$("#articles_research_"+id+' a span').text('{{ trans('api::backend.yes') }}');
			    	}else{
			    		$("#articles_research_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.articles_research') }} = {{ trans('api::backend.yes')}}");
			    		$("#articles_research_"+id+' a span').attr('class', 'label label-danger');
				    	$("#articles_research_"+id+' a span').text('{{ trans('api::backend.no') }}');
			    	}
			    break;
			  	case 'interesting_issues':
			    	if(response.data.status_data === true){
			    		$("#interesting_issues_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.interesting_issues') }} = {{ trans('api::backend.no')}}");
			    		$("#interesting_issues_"+id+' a span').attr('class', 'label label-info');
				    	$("#interesting_issues_"+id+' a span').text('{{ trans('api::backend.yes') }}');
			    	}else{
			    		$("#interesting_issues_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.interesting_issues') }} = {{ trans('api::backend.yes')}}");
			    		$("#interesting_issues_"+id+' a span').attr('class', 'label label-danger');
				    	$("#interesting_issues_"+id+' a span').text('{{ trans('api::backend.no') }}');
			    	}
			    break;

			
			  default:
			    // code block

			}	    	
	    }

	  })
	  .catch(function (error) {
	        // handle error
	        console.log(error);
	  })
	  .then(function () {
	        // always executed
	});

}


function setReadOnly(tmp) {
	if($('#' + tmp).is('[readonly]')) {
		$('#' + tmp).attr('readonly', false);
	} else {
		$('#' + tmp).attr('readonly', true);
		$('#' + tmp).val("");
	}
}

function hideReason(id, val) {

	console.log(id);

	if(val === "draft") {
		$('#' + id).show();
	} else if (val === "publish") {
		$('#' + id).hide();
	}
}

</script>
@endsection
@section('css')
<style type="text/css">
	.modal{
		max-width: 800px !important;
	}
	.coupon {
		border: 5px dotted #bbb;
		width: 100%;
		border-radius: 15px;
		margin: 0 auto;
		max-width: 733px;
	}
	.container {
		padding: 2px 16px;
		background-color: #f1f1f1;
		width: 100%;
	}
	.coupon .form-group{
		padding: 5px 16px;
	}
	.showtime_date{
		max-width: 200px;
	}


</style>
@endsection
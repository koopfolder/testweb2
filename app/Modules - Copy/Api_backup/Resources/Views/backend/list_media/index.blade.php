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
                           	$status = ['0'=>trans('api::backend.status'),'publish' =>trans('api::backend.publish'),'draft'=>trans('api::backend.draft')];
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
						<div class="col-xs-6" style="margin-top: 25px;float: right;">
							<button type="submit" class="btn btn-primary">{{ trans('dashboard::backend.search') }}</button>
						</div>
					</div>
				</div>
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
								<th class="title_color">
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
								</th>
								<th class="title_color">
									<a href="#">{{ trans('api::backend.knowledges') }}
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
									<a href="#">{{ trans('api::backend.media_campaign') }}
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
								<th class="title_color">
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
								$json_data =json_decode($item->json_data);
								//dd($item,$json_data);
							@endphp
							<tr>
								<th>{{ $item->title }}</th>
								<th>
									<span class="label label-info label-green">
										{{ $item->interesting_issues == 2 ? trans('api::backend.yes')  : trans('api::backend.no')  }}
									</span>
								</th>
								<th>
									<span class="label label-info label-green">
										{{ $item->articles_research == 2 ? trans('api::backend.yes')  : trans('api::backend.no')  }}
									</span>
								</th>
								<th>
									<span class="label label-info label-green">
										{{ $item->include_statistics == 2 ? trans('api::backend.yes')  : trans('api::backend.no')  }}
									</span>
								</th>
								<th>
									<span class="label label-info label-green">
										{{ $item->notable_books == 2 ? trans('api::backend.yes')  : trans('api::backend.no')  }}
									</span>
								</th>
								<th>
									<span class="label label-info label-green">
										{{ $settings['knowledges'] == $item->id ? trans('api::backend.yes')  : trans('api::backend.no')  }}
									</span>
								</th>
								<th>
									<span class="label label-info label-green">
										{{ $settings['media_campaign'] == $item->id ? trans('api::backend.yes')  : trans('api::backend.no')  }}
									</span>
								</th>
								<th>
									@if($item->status == 'publish')
									<span class="label label-info">
										{{  trans('api::backend.publish')  }}
									</span>
									@else
									<span class="label label-danger">
										{{  trans('api::backend.draft')  }}
									</span>
									@endif
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
								</th>
							</tr>
							@endforeach
						</tbody>
					</table>
					{!! isset($items) ? $items->appends(\Input::all())->render():'' !!}  
					</div>
					</div>
            	</div>
				{{ Form::close() }}
        	</div>
    	</div>
	</div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('javascript')
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
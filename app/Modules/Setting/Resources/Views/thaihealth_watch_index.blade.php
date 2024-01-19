@extends('layout::app')

@section('content')
<section class="content-header">
	<h1>{{ trans('setting::backend.setting_thaihealth_watch') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('setting::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.thaihealth_watch_setting.index') }}">{{ trans('setting::backend.setting_thaihealth_watch') }}</a></li>
	</ol>
</section>
<section class="content">
	<div class="box box-default">
    	<div class="box-body">
    		{{ Form::open(['url' => route('admin.thaihealth_watch_setting.index'), 'files' => true]) }}
        	<div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_thaihealth_watch" data-toggle="tab">{{ trans('setting::backend.thaihealth_watch') }}</a></li>
                            <li><a href="#tab_panel_discussion" data-toggle="tab">{{ trans('setting::backend.panel-discussion') }}</a></li>
                            <li><a href="#tab_points_to_watch" data-toggle="tab">{{ trans('setting::backend.points_to_watch') }}</a></li>
                            <li><a href="#tab_health_trends" data-toggle="tab">{{ trans('setting::backend.health_trends') }}</a></li>                        
                        </ul>
                        <div class="tab-content col-md-12">
                            <div class="tab-pane active" id="tab_thaihealth_watch">   
                                <div class="form-group">
                                    <label for="InputEmailAddress">{{ trans('setting::backend.thaihealth_watch_cover_image') }}</label>
                                    @if ($thaihealth_watch_cover_image)
                                        <div style="margin:10px 0;"><img src="{{ url($thaihealth_watch_cover_image) }}" width="50%"></div>
                                    @endif
                                    {!! Form::file('thaihealth_watch_cover_image') !!}
                                    <p class="help-block">{{ trans('setting::backend.fileupload_note') }}</p>
                                </div>                                                              
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.thaihealth_watch_single_page') }} </label>
                                    {{ Form::select('thaihealth_watch_single_page',$single_page,$thaihealth_watch_single_page,['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.thaihealth_watch_main_banner') }} </label>
                                    {{ Form::select('thaihealth_watch_main_banner',$banner_category,$thaihealth_watch_main_banner,['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.thaihealth_watch_footer_banner') }} </label>
                                    {{ Form::select('thaihealth_watch_footer_banner',$banner_category,$thaihealth_watch_footer_banner,['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.thaihealth_watch_main_video') }} </label>
                                    {{ Form::select('thaihealth_watch_main_video',$single_page,$thaihealth_watch_main_video,['class' => 'form-control']) }}
                                </div>                                                                
                            </div> 
                            <div class="tab-pane" id="tab_panel_discussion">
                                <div class="form-group">
                                <label for="InputEmailAddress">{{ trans('setting::backend.panel_discussion_cover_image') }}</label>
                                    @if ($panel_discussion_cover_image)
                                        <div style="margin:10px 0;"><img src="{{ url($panel_discussion_cover_image) }}" width="50%"></div>
                                    @endif
                                    {!! Form::file('panel_discussion_cover_image') !!}
                                    <p class="help-block">{{ trans('setting::backend.fileupload_note') }}</p>
                                </div> 
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.panel_discussion_title') }} </label>
                                    {{ Form::text('panel_discussion_title',$panel_discussion_title,['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.panel_discussion_description') }} </label>
                                    {{ Form::textarea ('panel_discussion_description',$panel_discussion_description,['class'=>'form-control ckeditor']) }}
                                </div>
                                    
                            </div>
                            <div class="tab-pane" id="tab_points_to_watch">
                                <div class="form-group">
                                <label for="InputEmailAddress">{{ trans('setting::backend.points_to_watch_cover_image') }}</label>
                                    @if ($points_to_watch_cover_image)
                                        <div style="margin:10px 0;"><img src="{{ url($points_to_watch_cover_image) }}" width="50%"></div>
                                    @endif
                                    {!! Form::file('points_to_watch_cover_image') !!}
                                    <p class="help-block">{{ trans('setting::backend.fileupload_note') }}</p>
                                </div> 
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.points_to_watch_article') }} </label>
                                    {{ Form::textarea ('points_to_watch_article',$points_to_watch_article,['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.points_to_watch_video') }} </label>
                                    {{ Form::textarea ('points_to_watch_video',$points_to_watch_video,['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.points_to_watch_gallery') }} </label>
                                    {{ Form::textarea ('points_to_watch_gallery',$points_to_watch_gallery,['class'=>'form-control']) }}
                                </div>                                                                    
                            </div>
                            <div class="tab-pane" id="tab_health_trends">
                                <div class="form-group">
                                <label for="InputEmailAddress">{{ trans('setting::backend.health_trends_cover_image') }}</label>
                                    @if ($health_trends_cover_image)
                                        <div style="margin:10px 0;"><img src="{{ url($health_trends_cover_image) }}" width="50%"></div>
                                    @endif
                                    {!! Form::file('health_trends_cover_image') !!}
                                    <p class="help-block">{{ trans('setting::backend.fileupload_note') }}</p>
                                </div> 
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.health_trends_description') }} </label>
                                    {{ Form::textarea ('health_trends_description',$health_trends_description,['class'=>'form-control']) }}
                                </div>                                                                  
                            </div>                                                         
                        </div><!-- End tap-content -->
                    </div><!-- End nav-tabs-custom -->
                </div><!-- End col-md-12 -->
            </div><!-- End Row -->
            <div class="box-footer">
                <button class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('setting::backend.submit') }}</button>
            </div>
         	{{ Form::close() }}
       	</div>
    </div>
</section>
@endsection
@section('javascript')
<script type="text/javascript">
    $(".js-tags-tokenizer").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    })
</script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('adminlte/bower_components/bootstrap-datetimepicke/bootstrap-datetimepicker.min.css') }}">
<style type="text/css">
    .select2{
       width: 250px !important;
    }
</style>
@endsection
@extends('layout::app')
@section('content')
@php
    //dd(get_loaded_extensions());
    //dd(phpinfo());
@endphp
<section class="content-header">
    <h1>{{ trans('article::backend.add_health-trends') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('article::backend.home') }}</a></li>
        <li><a href="{{ route('admin.thaihealth-watch.health-trends.index') }}">{{ trans('article::backend.health-trends') }}</a></li>
        <li class="active">{{ trans('article::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.thaihealth-watch.health-trends.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('article::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('article::backend.cover_image') }}</a></li>
                            <li><a href="#tab_gallery" data-toggle="tab">{{ trans('article::backend.gallery') }}</a></li>
                            <li><a href="#tab_video" data-toggle="tab">{{ trans('article::backend.video') }}</a></li>
                            <li ><a href="#tab_attachments" data-toggle="tab">{{ trans('article::backend.attachments') }}</a>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('article::backend.seo') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title',old('title'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.short_description') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('short_description',old('short_description'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.description') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description',old('description'),['class'=>'form-control ckeditor']) }}
                                </div>                             
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.start_date') }} <span style="color:red">*</span></label>
                                    <div class='input-group date' id='datetimepicker1'>
                                        {{ Form::text('start_date',$data->start_date ?? old('start_date'),['class'=>'form-control start_date']) }}
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.end_date') }}</label>
                                    <div class='input-group date' id='datetimepicker2'>
                                        {{ Form::text('end_date',$data->end_date ?? old('end_date'),['class'=>'form-control end_date']) }}
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">นามสกุลไฟล์: jpg, png (ไม่เกิน 5M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_gallery">
                                <div class="form-group pull-right">
                                    <img src="{{ asset('thrc/images/if_plus_add_blue.png') }}" title="Add Gallery" id="add_gallery" alt="add_gallery">
                                </div>
                                <div id="zone_gallery">
                                    
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_video">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.video') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('video_path',old('video_path'),['class'=>'form-control ckeditor_video']) }}
                                </div>           
                            </div>
                            <div class="tab-pane" id="tab_attachments">
                                <div class="form-group plus">
                                    <img src="{{ asset('thrc/images/if_plus_add_blue.png') }}" title="Add Document" id="add_document" alt="add_document">
                                </div>
                                <div id="zone_document">
                                    
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_title') }}</label>
                                    {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_keywords') }}</label>
                                    {!! Form::text('meta_keywords', old('meta_keywords'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_description') }}</label>
                                    {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                            <a href="{{ route('admin.thaihealth-watch.health-trends.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('article::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('article::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('article::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('article::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status,old('status'),['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('article::backend.highlight') }} </label>
                        {{ Form::radio('featured', '2', true) }}{{ trans('article::backend.yes') }}
                        {{ Form::radio('featured', '1', false) }}{{ trans('article::backend.no') }}
                    </div>
                    <!--<div class="form-group">
                        <label for="InputName">{{ trans('article::backend.recommend') }} </label>
                        {{ Form::radio('recommend', '2', true) }}{{ trans('article::backend.yes') }}
                        {{ Form::radio('recommend', '1', false) }}{{ trans('article::backend.no') }}
                    </div>-->
                    <div class="form-group">
                        <label>{{ trans('article::backend.tags') }} <span style="color:red">*</span></label>
                        {!! Form::select('tags[]',$tags,old('tags'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                    </div>                      
                </div>
            </div>
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

    
    jQuery(document).ready(function(){

        jQuery('#add_gallery').click(function(){
            //console.log("Add gallery click");
            gallery_length = jQuery('.gallery').length;
            html_gallery = '<div class="form-group gallery" id="gl_'+(gallery_length+1)+'">';
            html_gallery += '<label for="InputName">{{ trans("article::backend.gallery_desktop") }} (1366px * 768px)</label>';
            html_gallery += '<div class="areaImage">{!! Form::file("gallery_desktop[]",["id"=>"file_gallery"]) !!} <img class="del_gallery" onclick="JavaScript:del_gallery('+(gallery_length+1)+');" src="{{ asset("thrc/images/if_plus_add_minus.png") }}" title="Delete Gallery" id="delete_gallery" alt="delete_gallery"></div>';
            html_gallery += '<p class="help-block">นามสกุลไฟล์: jpg, png (ไม่เกิน 5M)</p>';
            html_gallery += '</div>';
            jQuery('#zone_gallery').append(html_gallery);
        });

        jQuery('#add_document').click(function(){
            //console.log("Add document click");
            document_length = jQuery('.document').length;
            html_document = '<div class="form-group document" id="dm_'+(document_length+1)+'">';
            html_document += '<label for="InputName">{{ trans("article::backend.attachments") }}</label>';
            html_document += '<div>{{ Form::text("document_name[]","",["id"=>"name_document"]) }}</div>';
            html_document += '<div class="areaImage">{!! Form::file("document[]",["id"=>"file_document"]) !!}<img class="del_document" onclick="JavaScript:del_document('+(document_length+1)+');" src="{{ asset("thrc/images/if_plus_add_minus.png") }}" title="Delete attachments" id="delete_document" alt="delete_document"></div>';
            html_document += '<p class="help-block">นามสกุลไฟล์: xlsx,xls,doc,docx,pdf,zip (ไม่เกิน 50M)</p>';
            html_document += '</div>';
            jQuery('#zone_document').append(html_document);
        });

        
    });

    function del_gallery(id){
        console.log(id);
        jQuery('#gl_'+id).hide('slow');
        jQuery('#gl_'+id+' #file_gallery').remove();
    }

    function del_document(id){
        jQuery('#dm_'+id).hide('slow');
        jQuery('#dm_'+id+' #file_document').remove();
    }

    $(function (){

        // var start_date = new Date();
        //     start_date.setDate(start_date.getDate());

        $('#datetimepicker1').datetimepicker({ 
            //format:'DD/MM/YYYY',
            //format:'YYYY/MM/DD',
            //minDate: start_date
        });
        //console.log(date);
        $('#datetimepicker2').datetimepicker({ 
            //format:'DD/MM/YYYY',
            //format:'YYYY/MM/DD',
            //minDate:start_date
        });


    });
</script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('adminlte/bower_components/bootstrap-datetimepicke/bootstrap-datetimepicker.min.css') }}">
<style type="text/css">
    .plus{
        margin-left:93%;
        cursor: pointer;
    }
    #tab_gallery{
        padding: 10px 0;
        margin-bottom: 30px;
    }
    .del_gallery{
        margin-top: 10px;
        cursor: pointer;
    }
</style>
@endsection
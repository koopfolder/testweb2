@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('activities::backend.add_activities') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('sustainability::backend.home') }}</a></li>
        <li><a href="{{ route('admin.history.index') }}">{{ trans('activities::backend.activities') }}</a></li>
        <li class="active">{{ trans('sustainability::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.activities.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('sustainability::backend.description') }}</a></li>
                            <li ><a href="#tab_cover_image" data-toggle="tab">{{ trans('sustainability::backend.cover_image') }}</a></li>
                            <li><a href="#tab_gallery" data-toggle="tab">{{ trans('article::backend.gallery') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('sustainability::backend.title_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title_th',old('title_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('sustainability::backend.title_en') }}</label>
                                    {{ Form::text('title_en',old('title_en'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('sustainability::backend.edit_time') }}</label>

                                    <div class='input-group date' id='datetimepicker1'>
                                        {{ Form::text('date_event',$data->date_event ?? old('date_event'),['class'=>'form-control date_event']) }}
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('sustainability::backend.description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description_th',old('description_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('sustainability::backend.description_en') }} </label>
                                    {{ Form::textarea('description_en',old('description_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_mobile') }} (768px * auto)</label>
                                    <div class="areaImage">{!! Form::file('cover_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_gallery">
                                <div class="form-group plus">
                                    <img src="{{ asset('rooster/images/if_plus_add_blue.png') }}" title="Add Gallery" id="add_gallery" alt="add_gallery">
                                </div>
                                <div id="zone_gallery">

                                </div>
                            </div>
                            <a href="{{ route('admin.activities.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('article::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('sustainability::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('sustainability::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $user_id = auth()->user()->id;
                            $permission_status = RoosterHelpers::getPermissionsstatus($user_id);
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                            if($permission_status->count()){
                                $check_publish = json_decode($permission_status->role->permissions_data)->publish;
                                $check_draft = json_decode($permission_status->role->permissions_data)->draft;
                                if($check_publish =='0'){
                                    unset($status['publish']);
                                }
                                if($check_draft =='0'){
                                    unset($status['draft']);
                                }
                            }
                        @endphp
                        <label>{{ trans('sustainability::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
<script async="" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#add_gallery').click(function(){
            console.log("Add gallery click");
            gallery_length = jQuery('.gallery').length;
            html_gallery = '<div class="form-group gallery" id="gl_'+(gallery_length+1)+'">';
            html_gallery += '<label for="InputName">{{ trans("article::backend.gallery_desktop") }} (1366px * 768px)</label>';
            html_gallery += '<div class="areaImage">{!! Form::file("gallery_desktop[]",["id"=>"file_gallery"]) !!} <img class="del_gallery" onclick="JavaScript:del_gallery('+(gallery_length+1)+');" src="{{ asset("rooster/images/if_plus_add_minus.png") }}" title="Delete Gallery" id="delete_gallery" alt="delete_gallery"></div>';
            html_gallery += '<p class="help-block">Format file: jpeg, png (no more than 2M)</p>';
            html_gallery += '</div>';
            jQuery('#zone_gallery').append(html_gallery);
        });
    });

    function del_gallery(id){
        console.log(id);
        jQuery('#gl_'+id).hide('slow');
        jQuery('#gl_'+id+' #file_gallery').remove();
    }

    $(function (){
        $('#datetimepicker1').datetimepicker();
    });
</script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<style type="text/css">
    .plus{
        margin-left:93%;
        cursor: pointer;
    }
    .del_gallery{
        margin-left: 201px;
        margin-top: -71px;
        cursor: pointer;
    }
</style>
@endsection

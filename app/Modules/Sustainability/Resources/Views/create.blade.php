@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('article::backend.add_news_events') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('article::backend.home') }}</a></li>
        <li><a href="{{ route('admin.history.index') }}">{{ trans('article::backend.news_events') }}</a></li>
        <li class="active">{{ trans('article::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.article.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('article::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('article::backend.cover_image') }}</a></li>
                            <li><a href="#tab_gallery" data-toggle="tab">{{ trans('article::backend.gallery') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('article::backend.seo') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.title_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title_th',old('title_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.title_en') }}</label>
                                    {{ Form::text('title_en',old('title_en'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description_th',old('description_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.description_en') }} </label>
                                    {{ Form::textarea('description_en',old('description_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.new') }} </label>
                                    {{ Form::radio('new', '1', true) }}{{ trans('article::backend.yes') }}
                                    {{ Form::radio('new', '2', false) }}{{ trans('article::backend.no') }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.highlight') }} </label>
                                    {{ Form::radio('featured', '1', true) }}{{ trans('article::backend.yes') }}
                                    {{ Form::radio('featured', '2', false) }}{{ trans('article::backend.no') }}
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
                            <a href="{{ route('admin.article.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('article::backend.back') }}</a>
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
                        <label>{{ trans('article::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status', ['publish' =>trans('article::backend.publish'), 'draft' =>trans('article::backend.draft')], old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
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
</script>
@endsection
@section('css')
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
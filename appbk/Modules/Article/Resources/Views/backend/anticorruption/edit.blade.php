@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('article::backend.edit_news_events') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('article::backend.home') }}</a></li>
        <li><a href="{{ route('admin.banner.index') }}">{{ trans('article::backend.news_events') }}</a></li>
        <li class="active">{{ trans('article::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.article.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('article::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('article::backend.cover_image') }}</a></li>
                            <li><a href="#tab_gallery" data-toggle="tab">{{ trans('article::backend.gallery') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('article::backend.seo') }}</a></li>
                            <li><a href="#tab_revisions" data-toggle="tab">{{ trans('article::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName" class="col-md-4">{{ trans('article::backend.title_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title_th',$data->title_th ?? old('title_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.title_en') }}</label>
                                    {{ Form::text('title_en',$data->title_en ?? old('title_en'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description_th',$data->description_th ?? old('description_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.description_en') }} </label>
                                    {{ Form::textarea('description_en',$data->description_en ?? old('description_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('sustainability::backend.edit_time') }}</label>
                                    <div class='input-group date' id='datetimepicker1'>
                                        {{ Form::text('date_event',Carbon\Carbon::parse($data->date_event)->format('m/d/Y H:i A') ?? old('date_event'),['class'=>'form-control date_event']) }}
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.new') }} </label>
                                    {{ Form::radio('new', '1', ($data->new == '1' ? true:false)) }}{{ trans('article::backend.yes') }}
                                    {{ Form::radio('new', '2', ($data->new == '2' ? true:false)) }}{{ trans('article::backend.no') }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.highlight') }} </label>
                                    {{ Form::radio('featured', '2', ($data->featured == '2' ? true:false)) }}{{ trans('article::backend.yes') }}
                                    {{ Form::radio('featured', '1', ($data->featured == '1' ? true:false)) }}{{ trans('article::backend.no') }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    @if ($data->getMedia('cover_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_mobile') }} (768px * auto)</label>
                                    @if ($data->getMedia('cover_mobile')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('cover_mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_gallery">
                                <div class="form-group pull-right">
                                    <img src="{{ asset('rooster/images/if_plus_add_blue.png') }}" title="Add Gallery" id="add_gallery" alt="add_gallery">
                                </div>
                                <div id="zone_gallery">
                                    @if ($data->getMedia('gallery_desktop')->isNotEmpty())
                                        @foreach($data->getMedia('gallery_desktop') AS $key=>$value)
                                        <div class="form-group gallery" id="gl_{{ $key+1 }}" data-id="{{ $value->id }}">
                                            <label for="InputName">{{ trans("article::backend.gallery_desktop") }} (1366px * 768px)</label>
                                            <div class="areaImage"><img src="{{ asset($value->getUrl()) }}" width="250"><img class="del_gallery" onclick="JavaScript:del_gallery({{ $key+1 }});" src="{{ asset("rooster/images/if_plus_add_minus.png") }}" title="Delete Gallery" id="delete_gallery" alt="delete_gallery"></div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_title') }}</label>
                                    {!! Form::text('meta_title',$data->meta_title ?? old('meta_title'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_keywords') }}</label>
                                    {!! Form::text('meta_keywords',$data->meta_keywords ?? old('meta_keywords'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_description') }}</label>
                                    {!! Form::textarea('meta_description',$data->meta_description ?? old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_revisions">
                                @include('article::partials.revision', ['revisions' => $revisions])
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
                        {!! Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('javascript')
<script async="" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#add_gallery').click(function(){
            console.log("Add gallery click");
            gallery_length = jQuery('.gallery').length;
            html_gallery = '<div class="form-group gallery" id="gl_'+(gallery_length+1)+'" data-id="">';
            html_gallery += '<label for="InputName">{{ trans("article::backend.gallery_desktop") }} (1366px * 768px)</label>';
            html_gallery += '<div class="areaImage">{!! Form::file("gallery_desktop[]",["id"=>"file_gallery"]) !!} <img class="del_gallery" onclick="JavaScript:del_gallery('+(gallery_length+1)+');" src="{{ asset("rooster/images/if_plus_add_minus.png") }}" title="Delete Gallery" id="delete_gallery" alt="delete_gallery"></div>';
            html_gallery += '<p class="help-block">Format file: jpeg, png (no more than 2M)</p>';
            html_gallery += '</div>';
            jQuery('#zone_gallery').append(html_gallery);
        });
        
    });

    function del_gallery(id){
        jQuery('#gl_'+id).hide('slow');
        jQuery('#gl_'+id+' #file_gallery').remove();

        media_id = jQuery('#gl_'+id).attr('data-id');
        console.log(media_id);
        if(media_id !=''){
            jQuery.ajax({
                url: '{{ route("admin.article.ajaxdeletegallery") }}',
                type: "POST",
                dataType: 'json',
                data: {_token:jQuery('meta[name="csrf-token"]').attr('content'),id:media_id},
                success:function(response){
                    console.log(response);
                    // if(response.status === true){
                    //     Command: toastr["success"]("Update Successfully");
                    //     location.reload();
                    // }else{
                    //     Command: toastr["error"]("Error !");
                    //     location.reload();
                    // }
                }
            });
        }
    }

    $(function () {
          $('#datetimepicker1').datetimepicker({
            
          });
    });
</script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<style type="text/css">
    .del_anticorruption{
        margin-top: -5px;
        cursor: pointer;
    }
    .file_name{
        padding-bottom: 35px;
    }
    .file_name input{width: 100%;}
</style>
@endsection
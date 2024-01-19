@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('exhibition::backend.edit_revolving_exhibition') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('exhibition::backend.home') }}</a></li>
        <li><a href="{{ route('admin.exhibition.revolving.index') }}">{{ trans('exhibition::backend.revolving_exhibition') }}</a></li>
        <li class="active">{{ trans('exhibition::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.exhibition.revolving.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('exhibition::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('exhibition::backend.cover_image') }}</a></li>
                            <li><a href="#tab_gallery" data-toggle="tab">{{ trans('exhibition::backend.gallery') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('exhibition::backend.seo') }}</a></li>
                            <li><a href="#tab_revisions" data-toggle="tab">{{ trans('exhibition::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title',$data->title ?? old('title'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.short_description') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('short_description',$data->short_description ?? old('short_description'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.description') }}<span style="color:red">*</span></label>
                                    {{ Form::textarea('description',$data->description ?? old('description'),['class'=>'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    @if ($data->getMedia('cover_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_gallery">
                                <div class="form-group pull-right">
                                    <img src="{{ asset('thrc/images/if_plus_add_blue.png') }}" title="Add Gallery" id="add_gallery" alt="add_gallery">
                                </div>
                                <div id="zone_gallery">
                                    @if($data->getMedia('gallery_desktop')->isNotEmpty())
                                        @foreach($data->getMedia('gallery_desktop') AS $key=>$value)
                                        <div class="form-group gallery col-md-6" id="gl_{{ $key+1 }}" data-id="{{ $value->id }}">
                                            <label for="InputName">{{ trans("exhibition::backend.gallery_desktop") }} (1366px * 768px)</label>
                                            <div class="areaImage">
                                                <div><img src="{{ asset($value->getUrl()) }}" width="250"></div>
                                                <div><img class="del_gallery" onclick="JavaScript:del_gallery({{ $key+1 }});" src="{{ asset("thrc/images/if_plus_add_minus.png") }}" title="Delete Gallery" id="delete_gallery" alt="delete_gallery"></div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>{{ trans('exhibition::backend.meta_title') }}</label>
                                    {!! Form::text('meta_title',$data->meta_title ?? old('meta_title'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('exhibition::backend.meta_keywords') }}</label>
                                    {!! Form::text('meta_keywords',$data->meta_keywords ?? old('meta_keywords'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('exhibition::backend.meta_description') }}</label>
                                    {!! Form::textarea('meta_description',$data->meta_description ?? old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_revisions">
                                @include('exhibition::partials.revolving_exhibition.revision', ['revisions' => $revisions])
                            </div>
                            <a href="{{ route('admin.exhibition.revolving.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('exhibition::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('exhibition::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('exhibition::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('exhibition::backend.publish'),'draft'=>trans('exhibition::backend.draft')];
                        @endphp
                        <label>{{ trans('exhibition::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('exhibition::backend.highlight') }} </label>
                        {{ Form::radio('featured', '2', ($data->featured == '2' ? true:false)) }}{{ trans('exhibition::backend.yes') }}
                        {{ Form::radio('featured', '1', ($data->featured == '1' ? true:false)) }}{{ trans('exhibition::backend.no') }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('article::backend.recommend') }} </label>
                        {{ Form::radio('recommend', '2', ($data->recommend == '2' ? true:false)) }}{{ trans('article::backend.yes') }}
                        {{ Form::radio('recommend', '1', ($data->recommend == '1' ? true:false)) }}{{ trans('article::backend.no') }}
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
            html_gallery += '<label for="InputName">{{ trans("exhibition::backend.gallery_desktop") }} (1366px * 768px)</label>';
            html_gallery += '<div class="areaImage">{!! Form::file("gallery_desktop[]",["id"=>"file_gallery"]) !!} <img class="del_gallery" onclick="JavaScript:del_gallery('+(gallery_length+1)+');" src="{{ asset("thrc/images/if_plus_add_minus.png") }}" title="Delete Gallery" id="delete_gallery" alt="delete_gallery"></div>';
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
                url: '{{ route("admin.exhibition.revolving.ajaxdeletegallery") }}',
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
        margin-top: 10px;
        cursor: pointer;
    }
    #zone_gallery{
        display: inline-block;
        width: 100%;
    }
</style>
@endsection
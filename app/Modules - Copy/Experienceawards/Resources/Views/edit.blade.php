@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('experienceawards::backend.edit_experienceawards') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('sustainability::backend.home') }}</a></li>
        <li><a href="{{ route('admin.experienceawards.index') }}">{{ trans('experienceawards::backend.experienceawards') }}</a></li>
        <li class="active">{{ trans('sustainability::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.experienceawards.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('sustainability::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('sustainability::backend.cover_image') }}</a></li>
                            <li><a href="#tab_revisions" data-toggle="tab">{{ trans('sustainability::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('sustainability::backend.description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description_th',$data->description_th ?? old('description_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('sustainability::backend.description_en') }} </label>
                                    {{ Form::textarea('description_en',$data->description_en ?? old('description_en'),['class'=>'form-control ckeditor']) }}
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
                            <div class="tab-pane" id="tab_revisions">
                                @include('sustainability::partials.corporategovpolicy_revision', ['revisions' => $revisions])
                            </div>
                            <a href="{{ route('admin.experienceawards.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('sustainability::backend.back') }}</a>
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
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('sustainability::backend.order') }} <span style="color:red"></span></label>
                        {!! Form::number('order',$data->order,['class' => 'form-control']); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('javascript')
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

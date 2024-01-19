@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('article::backend.anticorruption') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('article::backend.home') }}</a></li>
        <li><a href="{{ route('admin.article.anticorruption.create') }}">{{ trans('article::backend.anticorruption') }}</a></li>
        <li class="active">{{ trans('article::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.article.anticorruption.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('article::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('article::backend.cover_image') }}</a></li>
                            <li><a href="#tab_attachment" data-toggle="tab">{{ trans('article::backend.attachment') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('article::backend.seo') }}</a></li>
                        </ul>
                        @php
                           // dd($item->count());
                        @endphp
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.title_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title_th',$item->title_th ?? old('title_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.title_en') }}</label>
                                    {{ Form::text('title_en',$item->title_en ?? old('title_en'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description_th',$item->description_th ?? old('description_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.description_en') }} </label>
                                    {{ Form::textarea('description_en',$item->description_en ?? old('description_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    @if ($item->count() && $item->getMedia('cover_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($item->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_mobile') }} (768px * auto)</label>
                                    @if ($item->count() && $item->getMedia('cover_mobile')->isNotEmpty())
                                    <div><img src="{{ asset($item->getMedia('cover_mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_attachment">
                                <div class="col-md-12 pull-right" style="text-align: right;">
                                    <img src="{{ asset('rooster/images/if_plus_add_blue.png') }}" title="Add anticorruption" id="add_anticorruption" alt="add_anticorruption">
                                </div>
                                <div id="zone_anticorruption">
                                    @if($item->count() && collect($item->documents)->isNotEmpty())
                                        @foreach($item->documents AS $key => $value)
                                        <div class="form-group anticorruption col-md-12" id="an_{{ $key+1 }}" data-id="{{ $value->id }}">
                                            <div class="col-md-6">
                                                <label for="InputName" class="col-md-3">File Name (TH):</label>
                                                <div class="file_name col-md-6">{{ Form::text("file_name_anticorruption_th_case_edit[$value->id]",$value->title_th,["id"=>"file_name_anticorruption_th"]) }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="InputName" class="col-md-3">File Name (EN):</label>
                                                <div class="file_name col-md-6">{{ Form::text("file_name_anticorruption_en_case_edit[$value->id]",$value->title_en,["id"=>"file_name_anticorruption_en"]) }}</div>
                                            </div>
                                            <div class="areaImage col-md-6">
                                                <label class="col-md-3"> Attached File (TH):</label>
                                                <div class="col-md-9">
                                                    <a href="{{ $value->file_path_th }}" target="_blank">{{ $value->title_th }}</a>
                                                </div>
                                            </div>
                                            <div class="areaImage col-md-6">
                                                <label class="col-md-3"> Attached File (EN):</label>
                                                <div class="col-md-9">
                                                        <a href="{{ $value->file_path_en }}" target="_blank">{{ $value->title_en }}</a>
                                                        <img class="del_anticorruption" onclick="JavaScript:del_anticorruption({{ $key+1 }});" src="{{ asset("rooster/images/if_plus_add_minus.png") }}" title="Delete Anticorruption" id="delete_anticorruption" alt="delete_anticorruption">
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $document_last_index = $value->id;
                                        @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_title') }}</label>
                                    {!! Form::text('meta_title',$item->meta_title ?? old('meta_title'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_keywords') }}</label>
                                    {!! Form::text('meta_keywords',$item->meta_keywords ?? old('meta_keywords'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('article::backend.meta_description') }}</label>
                                    {!! Form::textarea('meta_description',$item->meta_description ?? old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
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
                        <label>{{ trans('article::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status'), ['class' => 'form-control']) !!}
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
        jQuery('#add_anticorruption').click(function(){
            anticorruption_length = jQuery('.anticorruption').length;
            //console.log(anticorruption_length);
            html_anticorruption = '<div class="form-group anticorruption col-md-12" id="an_'+(anticorruption_length+1)+'">';
            html_anticorruption += '<div class="col-md-6"><label for="InputName" class="col-md-3">File Name (TH):</label>';
            html_anticorruption += '<div class="file_name col-md-6">{{ Form::text("file_name_anticorruption_th[]",'',["id"=>"file_name_anticorruption_th"]) }}</div></div>';
            html_anticorruption += '<div class="col-md-6"><label for="InputName" class="col-md-3">File Name (EN):</label>';
            html_anticorruption += '<div class="file_name col-md-6">{{ Form::text("file_name_anticorruption_en[]",'',["id"=>"file_name_anticorruption_en"]) }}</div></div>';
            html_anticorruption += '<div class="areaImage col-md-6"><label class="col-md-3">Attached File (TH):</label><div class="col-md-6">{!! Form::file("anticorruption_th[]",["id"=>"file_anticorruption_th"]) !!}</div><div class="col-md-3"></div><p class="help-block col-md-6">Format file: xls,xlsx,doc,docx,pdf (no more than 10M)</p></div>';

            html_anticorruption += '<div class="areaImage col-md-6"><label class="col-md-3">Attached File (EN):</label><div class="col-md-6">{!! Form::file("anticorruption_en[]",["id"=>"file_anticorruption_en"]) !!}</div><div class="col-md-3"><img class="del_anticorruption" onclick="JavaScript:del_anticorruption('+(anticorruption_length+1)+');" src="{{ asset("rooster/images/if_plus_add_minus.png") }}" title="Delete Attachment" id="delete_anticorruption" alt="delete_anticorruption"></div><p class="help-block col-md-6">Format file: xls,xlsx,doc,docx,pdf (no more than 10M)</p></div>';

            html_anticorruption += '</div>';
            jQuery('#zone_anticorruption').append(html_anticorruption);


        });
    });

    function del_anticorruption(id){

        console.log(id);
        file_id = jQuery('#an_'+id).attr('data-id');
        console.log(file_id);

        if(file_id !=''){
            jQuery.ajax({
                url: '{{ route("admin.article.ajaxdeletefile") }}',
                type: "POST",
                dataType: 'json',
                data: {_token:jQuery('meta[name="csrf-token"]').attr('content'),id:file_id},
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

        jQuery('#an_'+id).hide('slow');
        jQuery('#an_'+id+' #file_anticorruption_th').remove();
        jQuery('#an_'+id+' #file_anticorruption_en').remove();
        jQuery('#an_'+id+' #file_name_anticorruption_th').remove();
        jQuery('#an_'+id+' #file_name_anticorruption_en').remove();
    }

    $(function (){
        $('#datetimepicker1').datetimepicker();
    });
</script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<style type="text/css">
    .del_anticorruption{
        margin-top: -5px;
        cursor: pointer;
        float: right;
    }
    .file_name{padding-bottom: 35px;}
    .file_name input, .areaImage input{width: 100%; padding:5px 5px;}
    .anticorruption{
        border-bottom: 1px solid #ddd;
        padding: 20px 0;
        margin-bottom: 20px;
    }
</style>
@endsection
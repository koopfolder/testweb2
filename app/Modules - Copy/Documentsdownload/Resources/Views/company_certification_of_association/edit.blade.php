@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('documentsdownload::backend.edit_company_certification_of_association') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('documentsdownload::backend.home') }}</a></li>
        <li><a href="{{ route('admin.documents-download.company-certification-of-association.index') }}">{{ trans('documentsdownload::backend.company_certification_of_association') }}</a></li>
        <li class="active">{{ trans('documentsdownload::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.documents-download.company-certification-of-association.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('documentsdownload::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('documentsdownload::backend.cover_image') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('documentsdownload::backend.title_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('title_th',$data->title_th ?? old('title_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('documentsdownload::backend.title_en') }}</label>
                                    {{ Form::textarea('title_en',$data->title_en ?? old('title_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Attached File (TH)</label>
                                    @if($data->file_path_th !='' && file_exists(public_path().$data->file_path_th))
                                    <a href="{{ asset($data->file_path_th) }}" download>
                                        {{ $data->file_name_th }}
                                    </a>
                                    @endif
                                    <div class="areaImage">{!! Form::file('attached_file_th') !!}</div>
                                    <p class="help-block">Format: xlsx,xls,doc,pdf (no more than 10M)</p>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Attached File (EN)</label>
                                    @if($data->file_path_en !='' && file_exists(public_path().$data->file_path_en))
                                    <a href="{{ asset($data->file_path_en) }}" download>
                                        {{ $data->file_name_en }}
                                    </a>
                                    @endif
                                    <div class="areaImage">{!! Form::file('attached_file_en') !!}</div>
                                    <p class="help-block">Format: xlsx,xls,doc,pdf (no more than 10M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('documentsdownload::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    @if ($data->getMedia('cover_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('documentsdownload::backend.cover_image_mobile') }} (768px * auto)</label>
                                    @if ($data->getMedia('cover_mobile')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('cover_mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>              
                            <a href="{{ route('admin.documents-download.company-certification-of-association.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('documentsdownload::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('documentsdownload::backend.submit') }}</button>                                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('documentsdownload::backend.setting') }}</h3>
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
                        <label>{{ trans('documentsdownload::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
<script type="text/javascript">
</script>
@endsection
@section('css')
<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered{
        margin:-8px !important;
    }
</style>
@endsection
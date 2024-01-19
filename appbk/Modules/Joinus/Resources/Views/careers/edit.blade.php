@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('joinus::backend.edit_manager') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('joinus::backend.home') }}</a></li>
        <li><a href="{{ route('admin.position.index') }}">{{ trans('joinus::backend.manager') }}</a></li>
        <li class="active">{{ trans('joinus::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.position.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('article::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('joinus::backend.icon') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.position_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('position_th',$data->position_th ?? old('position_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.position_en') }}</label>
                                    {{ Form::text('position_en',$data->position_en ?? old('position_en'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.job_description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('job_description_th',$data->job_description_th ?? old('job_description_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.job_description_en') }} </label>
                                    {{ Form::textarea('job_description_en',$data->job_description_en ?? old('job_description_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.qualifications_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('qualifications_th',$data->qualifications_th ?? old('qualifications_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.qualifications_en') }} </label>
                                    {{ Form::textarea('qualifications_en',$data->qualifications_en ?? old('qualifications_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.amount') }}</label>
                                    {{ Form::text('amount',$data->amount ?? old('amount'),['class'=>'form-control']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.icon_desktop') }} </label>
                                    @if ($data->getMedia('icon_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('icon_desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('icon_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.icon_mobile') }} </label>
                                    @if ($data->getMedia('icon_mobile')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('icon_mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('icon_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
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
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
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

    });
</script>
@endsection
@section('css')
<style type="text/css">

</style>
@endsection
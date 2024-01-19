@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('joinus::backend.add_position') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('joinus::backend.home') }}</a></li>
        <li><a href="{{ route('admin.position.index') }}">{{ trans('joinus::backend.position') }}</a></li>
        <li class="active">{{ trans('joinus::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.position.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('joinus::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('joinus::backend.icon') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.position_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('position_th',old('position_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.position_en') }}</label>
                                    {{ Form::text('position_en',old('position_en'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.job_description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('job_description_th',old('job_description_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.job_description_en') }} </label>
                                    {{ Form::textarea('job_description_en',old('job_description_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.qualifications_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('qualifications_th',old('qualifications_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.qualifications_en') }} </label>
                                    {{ Form::textarea('qualifications_en',old('qualifications_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.amount') }}</label>
                                    {{ Form::text('amount',old('amount'),['class'=>'form-control']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.icon_desktop') }} </label>
                                    <div class="areaImage">{!! Form::file('icon_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('joinus::backend.icon_mobile') }} </label>
                                    <div class="areaImage">{!! Form::file('icon_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.position.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('joinus::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('joinus::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('joinus::backend.setting') }}</h3>
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
                        <label>{{ trans('joinus::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status'), ['class' => 'form-control']) !!}
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

</style>
@endsection
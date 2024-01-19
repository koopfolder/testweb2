@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('manager::backend.add_manager') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('manager::backend.home') }}</a></li>
        <li><a href="{{ route('admin.history.index') }}">{{ trans('manager::backend.manager') }}</a></li>
        <li class="active">{{ trans('manager::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.manager.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('manager::backend.description') }}</a></li>
                            <li><a href="#tab_image" data-toggle="tab">{{ trans('manager::backend.images') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.manager_categories') }} <span style="color:red">*</span></label>
                                    {{ Form::select('categories_id',$categories,old('categories_id'),['class'=>'form-control select2','id'=>'categories_id']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.manager_type') }} <span style="color:red">*</span></label>
                                    {{ Form::radio('bord_and_management_type', 'management', true,['class'=>'']) }} Directors are executive directors

                                    {{ Form::radio('bord_and_management_type', 'not_management', false,['class'=>'']) }} Directors are non-executive directors
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.name_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('name_th',old('name_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.name_en') }}</label>
                                    {{ Form::text('name_en',old('name_en'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.position_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('position_th',old('position_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.position_en') }} </label>
                                    {{ Form::textarea('position_en',old('position_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.education_th') }}</label>
                                    {{ Form::textarea('education_th', old('education_th'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.education_en') }} </label>
                                    {{ Form::textarea('education_en', old('education_en'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.iod_training_th') }}</label>
                                    {{ Form::textarea('iod_training_th', old('iod_training_th'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.iod_training_en') }} </label>
                                    {{ Form::textarea('iod_training_en', old('iod_training_en'), ['class' => 'form-control ckeditor']) }}
                                </div>

                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.work_experience_th') }} </label>
                                    {{ Form::textarea('work_experience_th', old('work_experience_th'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.work_experience_en') }} </label>
                                    {{ Form::textarea('work_experience_en', old('work_experience_en'), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group">
                                    <label for="InputName">Image(Desktop) (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                 <div class="form-group">
                                    <label for="InputName">Image (Mobile) (768px * auto)</label>
                                    <div class="areaImage">{!! Form::file('mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.manager.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('manager::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('manager::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('manager::backend.setting') }}</h3>
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
                        <label>{{ trans('manager::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('manager::backend.order') }}</label>
                        {{ Form::text('order',old('order'),['class'=>'form-control']) }}
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
        jQuery("#categories_id").select2();

    });
</script>
@endsection
@section('css')
<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered{
        margin:-8px !important;
    }
</style>
@endsection
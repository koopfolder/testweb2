@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('manager::backend.edit_manager') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('manager::backend.home') }}</a></li>
        <li><a href="{{ route('admin.banner.index') }}">{{ trans('manager::backend.manager') }}</a></li>
        <li class="active">{{ trans('manager::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.manager.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('manager::backend.description') }}</a></li>
                            <li><a href="#tab_image" data-toggle="tab">{{ trans('manager::backend.images') }}</a></li>
                            <li><a href="#tab_revisions" data-toggle="tab">{{ trans('manager::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.manager_categories') }} <span style="color:red">*</span></label>
                                    {{ Form::select('categories_id',$categories,$data->categories_id ?? old('categories_id'),['class'=>'form-control select2','id'=>'categories_id']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.manager_type') }} <span style="color:red">*</span></label>
                                    {{ Form::radio('bord_and_management_type', 'management',($data->bord_and_management_type == 'management' ? true:false),['class'=>'']) }} Directors are executive directors
                                    {{ Form::radio('bord_and_management_type', 'not_management',($data->bord_and_management_type == 'not_management' ? true:false),['class'=>'']) }} Directors are non-executive directors
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.name_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('name_th',$data->name_th ?? old('name_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.name_en') }}</label>
                                    {{ Form::text('name_en',$data->name_en ?? old('name_en'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.position_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('position_th',$data->position_th ?? old('position_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.position_en') }} </label>
                                    {{ Form::textarea('position_en',$data->position_en ?? old('position_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.education_th') }}</label>
                                    {{ Form::textarea('education_th',$data->education_th ?? old('education_th'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.education_en') }} </label>
                                    {{ Form::textarea('education_en',$data->education_en ?? old('education_en'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.iod_training_th') }}</label>
                                    {{ Form::textarea('iod_training_th',$data->iod_training_th ?? old('iod_training_th'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.iod_training_en') }} </label>
                                    {{ Form::textarea('iod_training_en',$data->iod_training_en ?? old('iod_training_en'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.work_experience_th') }} </label>
                                    {{ Form::textarea('work_experience_th',$data->work_experience_th ?? old('work_experience_th'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.work_experience_en') }} </label>
                                    {{ Form::textarea('work_experience_en',$data->work_experience_en ?? old('work_experience_en'), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group col-md-6">
                                    <label for="InputName">Image(Desktop) <span style="color:red">*</span> (1366px * 768px)</label>
                                    @if ($data->getMedia('desktop')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                 <div class="form-group col-md-6">
                                    <label for="InputName">Image (Mobile) <span style="color:red">*</span> (768px * auto)</label>
                                    @if ($data->getMedia('mobile')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_revisions">
                                @include('manager::partials.revision', ['revisions' => $revisions])
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
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('manager::backend.order') }}</label>
                        {{ Form::text('order',$data->order ?? old('order'),['class'=>'form-control']) }}
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
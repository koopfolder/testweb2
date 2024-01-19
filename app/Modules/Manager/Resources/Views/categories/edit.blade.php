@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('manager::backend.edit_categories') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('manager::backend.home') }}</a></li>
        <li><a href="{{ route('admin.banner.index') }}">{{ trans('manager::backend.manager_categories') }}</a></li>
        <li class="active">{{ trans('manager::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.manager.categories.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('manager::backend.description') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.categories_name_th') }} <span style="color:red">*</span></label>
                                    {{ Form::text('name_th',$data->name_th ?? old('name_th'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('manager::backend.categories_name_en') }} </label>
                                    {{ Form::text('name_en',$data->name_en ?? old('name_en'),['class'=>'form-control']) }}
                                </div>
                            </div>
                            <a href="{{ route('admin.manager.categories.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('manager::backend.back') }}</a>
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
        jQuery("#years").select2();

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
@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('business::backend.business') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('business::backend.home') }}</a></li>
        <li><a href="{{ route('admin.business.index') }}">{{ trans('business::backend.business') }}</a></li>
        <li class="active">{{ trans('business::backend.add') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.business.store'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('business::backend.description') }}</a></li>
                            <li><a href="#tab_revisions" data-toggle="tab">{{ trans('business::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('business::backend.description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description_th',$data->description_th ?? old('description_th'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('business::backend.description_en') }} </label>
                                    {{ Form::textarea('description_en',$data->description_en ?? old('description_en'),['class'=>'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_revisions">
                                @include('business::partials.revision', ['revisions' => $revisions])
                            </div>
                            <!--
                            <a href="{{ route('admin.business.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('business::backend.back') }}</a> -->
                            <a href="{{ route('preview-business') }}" class="btn btn-success" target="_blank">
                                <i class="fa fa-eye"></i> Preview
                            </a>

                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('business::backend.submit') }}</button>                                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('business::backend.setting') }}</h3>
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
                        <label>{{ trans('business::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, $data->status ?? old('status'), ['class' => 'form-control']) !!}
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
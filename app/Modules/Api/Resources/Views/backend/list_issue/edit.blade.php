@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('api::backend.edit_issue') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('api::backend.home') }}</a></li>
        <li><a href="{{ route('admin.api.list-issue.index') }}">{{ trans('api::backend.issue') }}</a></li>
        <li class="active">{{ trans('api::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.api.list-issue.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('api::backend.description') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('api::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('name',$data->name,['class'=>'form-control','readonly'=>'readonly']) }}
                                </div>
                            </div>
                            <a href="{{ route('admin.api.list-issue.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('api::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('api::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('api::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('api::backend.publish'),'draft'=>trans('api::backend.draft')];
                        @endphp
                        <label>{{ trans('api::backend.status') }} <span style="color:red">*</span></label>
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
@extends('layout::app')
@section('content')
@php
    //dd(get_loaded_extensions());
    //dd(phpinfo());
@endphp
<section class="content-header">
    <h1>{{ trans('api::backend.add_sex') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('api::backend.home') }}</a></li>
        <li><a href="{{ route('admin.sex.index') }}">{{ trans('api::backend.sex') }}</a></li>
        <li class="active">{{ trans('api::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.sex.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
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
                                    <label for="InputName">{{ trans('api::backend.sex') }} <span style="color:red">*</span></label>
                                    {{ Form::text('name',old('name'),['class'=>'form-control']) }}
                                </div>
                            </div>
                            <a href="{{ route('admin.sex.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('api::backend.back') }}</a>
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
                        {!! Form::select('status',$status,old('status'),['class' => 'form-control']) !!}
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

    });
</script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('adminlte/bower_components/bootstrap-datetimepicke/bootstrap-datetimepicker.min.css') }}">
<style type="text/css">
    .plus{
        margin-left:93%;
        cursor: pointer;
    }
    #tab_gallery{
        padding: 10px 0;
        margin-bottom: 30px;
    }
    .del_gallery{
        margin-top: 10px;
        cursor: pointer;
    }
</style>
@endsection
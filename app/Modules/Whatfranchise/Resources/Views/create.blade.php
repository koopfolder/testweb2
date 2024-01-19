@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('whatfranchise::backend.add_whatfranchise') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('whatfranchise::backend.home') }}</a></li>
        <li><a href="{{ route('admin.whatfranchise.index') }}">{{ trans('whatfranchise::backend.whatfranchise') }}</a></li>
        <li class="active">{{ trans('whatfranchise::backend.add') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.whatfranchise.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('whatfranchise::backend.description') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('article::backend.seo') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('whatfranchise::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title', old('title'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('whatfranchise::backend.description') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description', old('description'), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                    <div class="form-group">
                                        <label>{{ trans('article::backend.meta_title') }}</label>
                                        {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('article::backend.meta_keywords') }}</label>
                                        {!! Form::text('meta_keywords', old('meta_keywords'), ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('article::backend.meta_description') }}</label>
                                        {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                    </div>
                            </div>
                            <a href="{{ route('admin.whatfranchise.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('whatfranchise::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('whatfranchise::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('whatfranchise::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('whatfranchise::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
@endsection
@section('css')
<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered{
        margin:-8px !important;
    }
</style>
@endsection
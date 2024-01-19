@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('single-page::backend.add_single_page') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('single-page::backend.home') }}</a></li>
        <li><a href="{{ route('admin.single-page.index') }}">{{ trans('single-page::backend.single_page') }}</a></li>
        <li class="active">{{ trans('single-page::backend.add') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.single-page.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('single-page::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('article::backend.cover_image') }}</a></li>
                            <li><a href="#tab_video" data-toggle="tab">{{ trans('single-page::backend.video') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('article::backend.seo') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('single-page::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title', old('title'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('single-page::backend.short_description') }} </label>
                                    {{ Form::textarea('short_description',old('short_description'),['class'=>'form-control']) }}
                                </div>                                
                                <div class="form-group">
                                    <label for="InputName">{{ trans('single-page::backend.description') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description', old('description'), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">นามสกุลไฟล์: jpg, png (ไม่เกิน 5M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_video">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('single-page::backend.video') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('video_path',old('video_path'),['class'=>'form-control ckeditor_video']) }}
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
                            <a href="{{ route('admin.single-page.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('single-page::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('single-page::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('single-page::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('single-page::backend.status') }} <span style="color:red">*</span></label>
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
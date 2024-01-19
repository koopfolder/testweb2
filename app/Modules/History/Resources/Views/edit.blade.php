@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('history::backend.edit_history') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('history::backend.home') }}</a></li>
        <li><a href="{{ route('admin.banner.index') }}">{{ trans('history::backend.history') }}</a></li>
        <li class="active">{{ trans('history::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.history.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('history::backend.description') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('article::backend.seo') }}</a></li>
                            <li><a href="#tab_revisions" data-toggle="tab">{{ trans('history::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('history::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title',$data->title ?? old('title'), ['class' => 'form-control']) }}
                                 </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('history::backend.description') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description', $data->description ?? old('description'), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                    <div class="form-group">
                                        <label>{{ trans('article::backend.meta_title') }}</label>
                                        {!! Form::text('meta_title',$data->meta_title ?? old('meta_title'), ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('article::backend.meta_keywords') }}</label>
                                        {!! Form::text('meta_keywords',$data->meta_keywords ?? old('meta_keywords'), ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('article::backend.meta_description') }}</label>
                                        {!! Form::textarea('meta_description',$data->meta_description ?? old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                    </div>
                            </div>
                            <div class="tab-pane" id="tab_revisions">
                                @include('history::partials.revision', ['revisions' => $revisions])
                            </div>    
                                                        
                            <a href="{{ route('admin.history.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('history::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('history::backend.submit') }}</button>                                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('history::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('history::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
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
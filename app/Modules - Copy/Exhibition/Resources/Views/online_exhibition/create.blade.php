@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('exhibition::backend.add_online_exhibition') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('exhibition::backend.home') }}</a></li>
        <li><a href="{{ route('admin.exhibition.online.index') }}">{{ trans('exhibition::backend.online_exhibition') }}</a></li>
        <li class="active">{{ trans('exhibition::backend.add') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.exhibition.online.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('exhibition::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('exhibition::backend.cover_image') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title',old('title'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.description') }}</label>
                                    {{ Form::textarea('description',old('description'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.url_external') }}</label>
                                    <div class="areaImage">{{ Form::text('url_external',old('url_external'),['class'=>'form-control ckeditor']) }}</div>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Attached File<span style="color:red">*</span></label>
                                    <div class="areaImage">{!! Form::file('attached_file') !!}</div>
                                    <p class="help-block">Format: Zip (no more than 10M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.exhibition.online.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('exhibition::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('exhibition::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('exhibition::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('exhibition::backend.status') }} <span style="color:red">*</span></label>
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
    .select2-container .select2-selection--single .select2-selection__rendered{
        margin:-8px !important;
    }
</style>
@endsection
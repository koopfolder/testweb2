@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>{{ trans('layout::admin.form.add')}} {{ trans('recreation::admin.recreation') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('layout::admin.breadcrumb.add')}}</a></li>
        <li><a href="{{ route('admin.recreation.index') }}">{{ trans('recreation::admin.recreation') }}</a></li>
        <li class="active">{{ trans('layout::admin.form.add')}}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.recreation.create'), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Description</a></li>
                            <li><a href="#tab_image" data-toggle="tab">Image</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">Name <span style="color:red">*</span></label>
                                    {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                                </div>                         
                                <div class="form-group">
                                    <label for="InputName">Description </label>
                                    {{ Form::textarea('desription', old('desription'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Timing </label>
                                    {{ Form::text('timing', old('timing'), ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group">
                                    <label for="InputName">Image(Desktop) (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                 <div class="form-group">
                                    <label for="InputName">Image(Mobile) (768px * auto)</label>
                                    <div class="areaImage">{!! Form::file('mobile') !!}</div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.recreation.index') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> {{ trans('layout::admin.form.back')}}
                            </a>
                            <button class="btn btn-success pull-right">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('layout::admin.form.submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Setting</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Status <span style="color:red">*</span></label>
                        {!! Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], old('status'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        {!! Form::select('link', $menus, old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

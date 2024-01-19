@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>{{ trans('layout::admin.form.edit') }} {{ trans('location::admin.locations') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('layout::admin.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.location.index') }}">{{ trans('location::admin.locations') }}</a></li>
        <li class="active">{{ trans('layout::admin.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.location.edit', $location->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">{{ trans('layout::admin.form.description') }}</a></li>
                            <li><a href="#tab_image" data-toggle="tab">{{ trans('layout::admin.form.image') }}</a></li>
                            <li><a href="#tab_revision" data-toggle="tab">Revision</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">Name <span style="color:red;">*</span></label>
                                    {{ Form::text('name', $location->name ?? old('name'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="km">KM</label>
                                    {{ Form::text('kilometre',  old('kilometre', $location->kilometre), ['class' => 'form-control']) }}
                                </div>  
                                <div class="form-group">
                                    <label for="InputEmailAddress">Description</label>
                                    <textarea name="description" class="form-control ckeditor">{!! $location->description ?? old('description') !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group">
                                    <label>Image (desktop) 560px * 338px</label>
                                    @if ($location->getMedia('desktop')->isNotEmpty())
                                        <div><img src="{{ asset($location->getMedia('desktop')->first()->getUrl()) }}" width="250"></div>
                                        <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif                                    
                                    {{ Form::file('desktop') }}
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                </div> 
                                <div class="form-group">
                                    <label>Image (mobile) 768px * auto</label>
                                    @if ($location->getMedia('mobile')->isNotEmpty())
                                        <div><img src="{{ asset($location->getMedia('mobile')->first()->getUrl()) }}" width="250"></div>
                                        <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    {{ Form::file('mobile') }}
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                </div> 
                            </div>
                            <div class="tab-pane" id="tab_revision">
                                @include('location::partials.revision', ['revisions' => $revisions])
                            </div>
                            <a href="{{ route('admin.location.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>                            
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
                        <label>Status <span style="color:red;">*</span></label>
                        {!! Form::select('status', ['publish' => 'Publish',  'draft' => 'Draft'], old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Edit Promotion</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.promotion.index') }}">Promotion</a></li>
        <li class="active">Add</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.promotion.edit', $promotion->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Description</a></li>
                            <li><a href="#tab_image" data-toggle="tab">Image</a></li>
                            <li><a href="#tab_revision" data-toggle="tab">Revision</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">Name <span style="color:red;">*</span></label>
                                    {{ Form::text('name', $promotion->name ?? old('name'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputEmailAddress">Description</label>
                                    <textarea name="description" class="form-control ckeditor">{!! $promotion->description ?? old('description') !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group">
                                    <label>Image (desktop) 560px * 338px</label>
                                    @if ($promotion->getMedia('desktop')->isNotEmpty())
                                    <div><img src="{{ asset($promotion->getMedia('desktop')->first()->getUrl()) }}" width="250"></div>
                                    @endif
                                    {{ Form::file('desktop') }}
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                </div> 
                                <div class="form-group">
                                    <label>Image (mobile) 768px * auto</label>
                                    @if ($promotion->getMedia('mobile')->isNotEmpty())
                                    <div><img src="{{ asset($promotion->getMedia('desktop')->first()->getUrl()) }}" width="250"></div>
                                    @endif
                                    {{ Form::file('mobile') }}
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                </div> 
                            </div>
                            <div class="tab-pane" id="tab_revision">
                                @include('promotion::partials.revision', ['revisions' => $revisions])
                            </div>
                            <a href="{{ route('admin.promotion.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
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
                        {!! Form::select('status', ['publish' => 'Publish',  'draft' => 'Draft'], $promotion->status, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        {!! Form::select('link', $menus, $promotion->link, ['class' => 'form-control']) !!}
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Edit Category of Room</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.room.category.index') }}">Category of Room</a></li>
        <li class="active">Edit</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.room.category.edit', $category->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">Description</a></li>
                            <li><a href="#tab_image" data-toggle="tab">Images</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">SEO</a></li>
                            <li><a href="#tab_revision" data-toggle="tab">Revision</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">Name <span style="color:red">*</span></label>
                                    {{ Form::text('name', old('name', $category->name), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Description</label>
                                    {{ Form::textarea('description', old('description', $category->description), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group">
                                    <label for="InputName">Cover Image(Desktop) (1366px * 768px)</label>
                                    @if ($category->getMedia('cover_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($category->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif                                    
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                 <div class="form-group">
                                    <label for="InputName">Cover Image (Mobile) (768px * auto)</label>
                                    @if ($category->getMedia('cover_mobile')->isNotEmpty())
                                    <div><img src="{{ asset($category->getMedia('cover_mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif                                      
                                    <div class="areaImage">{!! Form::file('cover_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>

                                <div class="form-group">
                                    <label for="InputName">Image(Desktop) (1366px * 768px)</label>
                                    @if ($category->getMedia('desktop')->isNotEmpty())
                                    <div><img src="{{ asset($category->getMedia('desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif                                    
                                    <div class="areaImage">{!! Form::file('desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                 <div class="form-group">
                                    <label for="InputName">Image (Mobile) (768px * auto)</label>
                                    @if ($category->getMedia('mobile')->isNotEmpty())
                                    <div><img src="{{ asset($category->getMedia('mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif                                      
                                    <div class="areaImage">{!! Form::file('mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>Meta Title</label>
                                    {!! Form::text('meta_title', old('meta_title', $category->meta_title), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Keywords</label>
                                    {!! Form::text('meta_keywords', old('meta_keywords', $category->meta_keywords), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    {!! Form::textarea('meta_description', old('meta_description', $category->meta_description), ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>    
                            <div class="tab-pane" id="tab_revision">
                                @include('room::partials.categories.revision', ['revisions' => $revisions])
                            </div>                            
                            <a href="{{ route('admin.room.category.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
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
                        <label>Status <span style="color:red">*</span></label>
                        {!! Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], old('status', $category->status), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Use the Room as a detail.</label>
                        {!! Form::select('use_room_detail', $rooms, old('use_room_detail', $category->use_room_detail), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
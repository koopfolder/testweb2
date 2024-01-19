@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Edit Room</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.banner.index') }}">Room</a></li>
        <li class="active">Edit</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.room.edit', $room->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">Description</a></li>
                            <li><a href="#tab_image" data-toggle="tab">Images</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">SEO</a></li>
                            <li><a href="#tab_revisions" data-toggle="tab">Revisions</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">Name <span style="color:red">*</span></label>
                                    {{ Form::text('name', $room->name ?? old('name'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Description</label>
                                    {{ Form::textarea('description', $room->description ?? old('description'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Features</label>
                                    {{ Form::textarea('features', $room->features ?? old('features'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Amenities</label>
                                    {{ Form::textarea('amenities', $room->amenities ?? old('amenities'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Other Features</label>
                                    {{ Form::textarea('other_features', $room->other_features ?? old('other_features'), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group col-md-6">
                                    <label for="InputName">Room (Desktop) <span style="color:red">*</span> (1366px * 768px)</label>
                                    @if ($room->getMedia('room_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($room->getMedia('room_desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('room_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                 <div class="form-group col-md-6">
                                    <label for="InputName">Room (Mobile) <span style="color:red">*</span> (768px * auto)</label>
                                    @if ($room->getMedia('room_mobile')->isNotEmpty())
                                    <div><img src="{{ asset($room->getMedia('room_mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif                                    
                                    <div class="areaImage">{!! Form::file('room_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                                <div class="col-md-12"><hr></div>
                                <div class="form-group col-md-6">
                                    <label for="InputName">Cover (Desktop) <span style="color:red">*</span> (1366px * 768px)</label>
                                    @if ($room->getMedia('cover_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($room->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                 <div class="form-group col-md-6">
                                    <label for="InputName">Cover (Mobile) <span style="color:red">*</span> (768px * auto)</label>
                                    @if ($room->getMedia('cover_mobile')->isNotEmpty())
                                    <div><img src="{{ asset($room->getMedia('cover_mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif                                    
                                    <div class="areaImage">{!! Form::file('cover_mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                                <div class="col-md-12"><hr></div>
                                <div class="form-group col-md-6">
                                    <label for="InputName">Image(Desktop) <span style="color:red">*</span> (1366px * 768px)</label>
                                    @if ($room->getMedia('desktop')->isNotEmpty())
                                    <div><img src="{{ asset($room->getMedia('desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                 <div class="form-group col-md-6">
                                    <label for="InputName">Image (Mobile) <span style="color:red">*</span> (768px * auto)</label>
                                    @if ($room->getMedia('mobile')->isNotEmpty())
                                    <div><img src="{{ asset($room->getMedia('mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif                                    
                                    <div class="areaImage">{!! Form::file('mobile') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>Meta Title</label>
                                    {!! Form::text('meta_title', $room->meta_title ?? old('meta_title'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Keywords</label>
                                    {!! Form::text('meta_keywords', $room->meta_keywords ?? old('meta_keywords'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    {!! Form::textarea('meta_description', $room->meta_description ?? old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>    
                            <div class="tab-pane" id="tab_revisions">
                                @include('room::partials.revision', ['revisions' => $revisions])
                            </div>    
                                                        
                            <a href="{{ route('admin.room.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
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
                        <label>Room Type ID</label>
                        {{ Form::text('room_type_id', old('room_type_id', $room->room_type_id), ['class' => 'form-control']) }}
                    </div>                
                    <div class="form-group">
                        <label>Category <span style="color:red">*</span></label>
                        {!! Form::select('category_id', $categories, old('category_id', $room->category_id), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Book Type <span style="color:red">*</span></label>
                        {!! Form::select('book_type', ['contact-us-to-book' => 'Contact Us to Book', 'make-a-reservation' => 'Make a Reservation'], old('book_type', $room->book_type), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Status <span style="color:red">*</span></label>
                        {!! Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], old('status', $room->status), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Guest</label>
                        {!! Form::text('guest', old('guest', $room->guest), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Room</label>
                        {!! Form::select('room', $rooms, old('room', $room->room), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Create Page</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.pages.index') }}">Pages</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.pages.create'), 'files' => true]) }}
        {{ Form::hidden('user_id', auth()->user()->id) }}
        {{ Form::hidden('type', 'page') }}
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label for="InputName">Title <span style="color:red">*</span></label>
                        {{ Form::text('title', old('title'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="Excerpt">Content#1</label>
                        {{ Form::textarea('excerpt', old('excerpt'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="InputEmailAddress">Content#2</label>
                        <textarea id="editor" name="content" class="form-control">{!! old('content') !!}</textarea>
                    </div>
                     <div class="form-group">
                        <div><input type="file" name="file"></div>
                        <p class="help-block">File type: PDF (no more than 2M)</p>
                    </div>
                    {{--  <div class="form-group">
                        <label for="InputName">URL</label>
                        {{ Form::text('link', old('link'), ['class' => 'form-control', 'placeholder' => 'Enter Link']) }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">Video</label>
                        {{ Form::text('video', old('video'), ['class' => 'form-control', 'placeholder' => 'Enter Video']) }}
                    </div>  --}}
                    <div class="form-group">
                        <label for="InputName">Category Banner</label>
                    
                            <select class="form-control select2" 
                                    name="categoryBanner[]" 
                                    multiple="multiple" 
                                    data-select2order="false" 
                                    data-placeholder="Select a Category Banner"
                                    style="width: 100%;">
                                @if ($banners)
                                @foreach($banners as $banner)
                                    <option value="{{ $banner->id }}">{{$banner->title}}</option>
                                @endforeach
                                @endif
                            </select>
                  
                    </div>
                    <div class="form-group">
                        <label for="InputName">Caption 1</label>
                        {{ Form::textarea('caption1', old('caption'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">Caption 2</label>
                        {{ Form::text('caption2', old('caption'), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                    <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update</button>
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
                        <label>Layout <span style="color:red">*</span></label>
                        {!! Form::select('layout', ['home' => 'Home Page', 'about-us' => 'About Us', 'content' => 'Content', 'calendar' => 'Calendar', 'faq' => 'FAQ', 'contact' => 'Contact', 'news' => 'News', 'news-detail' => 'News Detail', 'services' => 'Student Services', 'we' => 'Where We Are', 'program' => 'Program'], old('layout'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Status <span style="color:red">*</span></label>
                        {!! Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], old('status'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_home" value="1" 
                                @if (old('is_home'))
                                checked="checked"
                                @endif
                                > Use in Home Page (ใช้สำหรับตั้งค่าเป็นหน้าแรกเท่านั้น)
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Image Desktop</h3>
                </div>
                <div class="box-body">
                     <div class="form-group">
                        <div><input type="file" name="image"></div>
                        <p class="help-block">File type: jpeg, png (no more than 2M)</p>
                        <p class="help-block">* for share facebook</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Image Mobile</h3>
                </div>
                <div class="box-body">
                     <div class="form-group">
                        <div><input type="file" name="mobile"></div>
                        <p class="help-block">File type: jpeg, png (no more than 2M)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO</h3>
                </div>
                <div class="box-body">

                    <div class="form-group">
                        <label>Meta Keywords</label>
                        {!! Form::text('meta_keywords', old('meta_keywords'), ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        <label>Meta Description</label>
                        {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                    </div>

                </div>
            </div>
        </div>
        {{--  <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Tags</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::text('tags', old('tags'), ['class' => 'form-control', 'id' => 'tags']) !!}
                    </div>
                </div>
            </div>
        </div>  --}}
    </div>
</section>
@endsection

@section('javascript')
<script type="text/javascript">
	$(function() {

        var options = {
            filebrowserImageBrowseUrl: baseUrl + '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: baseUrl + '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: baseUrl + '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: baseUrl + '/laravel-filemanager/upload?type=Files&_token='
        };
        CKEDITOR.replace('editor', options);
        CKEDITOR.replace('excerpt', options);
        CKEDITOR.replace('caption1', options);

		$("#select-all").click(function () {
		    $('input:checkbox').not(this).prop('checked', this.checked);
		});
        $("#addInputFile").click(function() {
            $(".fileUpdload").append( "<div class='fileUpload'><input type='file' name='files[]'></div>" );
        });

        $('#tags').tagsInput({
            'height':'50px',
            'width':'100%'
        });
        $('#banners').tagsInput({
            'height':'50px',
            'width':'100%'
        });

        $('.select2').select2({
            tags: false,
            maximumSelectionLength: 2
        });
        $(".select2").on("select2:select", function (evt) {
              var element = evt.params.data.element;
              var $element = $(element);
              $element.detach();
              $(this).append($element);
              $(this).trigger("change");
        });

	});
</script>
@stop


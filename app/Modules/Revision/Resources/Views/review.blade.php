@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Review revision</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.revision.index') }}">Revision</a></li>
        <li class="active">Review</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.revision.review', $page->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label for="InputName">Title <span style="color:red">*</span></label>
                        {{ Form::text('title', $page->title ?? old('title'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="Excerpt">Excerpt</label>
                        {{ Form::textarea('excerpt', $page->excerpt ?? old('excerpt'), ['class' => 'form-control', 'id' => 'excerpt']) }}
                    </div>
                    <div class="form-group">
                        <label for="Content">Content</label>
                        <textarea id="editor" name="content" class="form-control">{!! $page->content ?? old('content') !!}</textarea>
                    </div>
                     <div class="form-group">
                        <label>File (PDF)</label>
                        @if ($page->getMedia('file')->isNotEmpty())
                            <div style="border-bottom: 1px solid #ccc;">
                                <span class="pull-right">
                                    <a href="{{ route('admin.pages.deleteFile', $page->id) }}" data-toggle="confirmation" data-title="Are You Sure?">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </span>
                                <a href="{{ asset($page->getMedia('file')->first()->getUrl()) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                            </div>
                            <br>
                        @endif

                         
                        <div><input type="file" name="file"></div>
                        <p class="help-block">File type: PDF (no more than 2M)</p>
                    </div>

                    {{--  <div class="form-group">
                        <label for="InputName">URL</label>
                        {{ Form::text('link', $page->link ?? old('link'), ['class' => 'form-control', 'placeholder' => 'Enter Link']) }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">Video</label>
                        {{ Form::text('video', $page->video ?? old('video'), ['class' => 'form-control', 'placeholder' => 'Enter Video']) }}
                    </div>  --}}
                    <div class="form-group">
                        <label for="InputName">Category Banner</label>
                            <?php
                            $categoryBanners = explode(',', $page->banners);
                            $flipped = array_flip($categoryBanners);
                            ?>
                            <select class="form-control select2Banner" name="categoryBanner[]" multiple="multiple" 
                                    data-placeholder="Select a Category Banner" style="width: 100%;">
                                @if ($banners)

                                @foreach($banners as $banner)
                                    <option value="{{ $banner->id }}"
                                    @if (array_key_exists($banner->id, $flipped))
                                        selected="selected"
                                    @endif
                                    >{{$banner->title}}</option>
                                @endforeach
                                @endif
                            </select>
                  
                    </div>
                    <div class="form-group">
                        <label for="InputName">Caption 1</label>
                        {{ Form::textarea('caption1', $page->caption1 ?? old('caption1'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">Caption 2</label>
                        {{ Form::text('caption2', $page->caption2 ?? old('caption2'), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{ route('admin.revision.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                    <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update</button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Setting</h3>
                    <span class="pull-right">
                        <a href="{{ url('preview/' . $page->slug) }}" target="_blank">Preview</a>
                    </span>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Layout</label>
                        {!! Form::select('layout', ['home' => 'Home Page', 'about-us' => 'About Us', 'content' => 'Content', 'calendar' => 'Calendar', 'faq' => 'FAQ', 'contact' => 'Contact', 'news' => 'News', 'news-detail' => 'News Detail', 'services' => 'Student Services', 'we' => 'Where We Are', 'program' => 'Program'], $page->layout ?? old('layout'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        {!! Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], $page->status ?? old('status'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_home" value="1" 
                                @if ($page->is_home == 1)
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
                    @if (!$page->getMedia('image')->isEmpty())
                     <div class="form-group">
                        @if ($page->getMedia('image')->isNotEmpty())
                            <div>
                                <a href="{{ route('admin.pages.deleteImage', [$page->id, 'image']) }}" data-toggle="confirmation" data-title="Are You Sure?">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                            <div>
                                <img src="{{ asset($page->getMedia('image')->first()->getUrl()) }}" width="100%" height="auto">
                            </div>
                        @endif
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="fileUpdload"><input type="file" name="image"></div>
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
                    @if (!$page->getMedia('mobile')->isEmpty())
                     <div class="form-group">
                        @if ($page->getMedia('mobile')->isNotEmpty())
                            <div>
                                <a href="{{ route('admin.pages.deleteImage', [$page->id, 'mobile']) }}" data-toggle="confirmation" data-title="Are You Sure?">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                            <div><img src="{{ asset($page->getMedia('mobile')->first()->getUrl()) }}" width="100%" height="auto"></div>
                        @endif
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="fileUpdload"><input type="file" name="mobile"></div>
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
                        {!! Form::text('meta_keywords', $page->meta_keywords ?? old('meta_keywords'), ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        <label>Meta Description</label>
                        {!! Form::textarea('meta_description', $page->meta_description ?? old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
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
                        {!! Form::text('tags', $page->tagList, ['class' => 'form-control', 'id' => 'tags']) !!}
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
            'height':'120px',
            'width':'100%'
        });

        $('.select2Banner').select2({
            tags: false,
            maximumSelectionLength: 2
        });
        $(".select2Banner").on("select2Banner:select", function (evt) {
              var element = evt.params.data.element;
              var $element = $(element);
              $element.detach();
              $(this).append($element);
              $(this).trigger("change");
        });

    });
</script>
@stop


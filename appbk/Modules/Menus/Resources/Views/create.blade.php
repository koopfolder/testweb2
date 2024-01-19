@extends('layout::app')

@section('content')
<section class="content-header">
    <h1>{{ trans('menus::backend.add_menu') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('menus::backend.home') }}</a></li>
        <li><a href="{{ route('admin.menus.index') }}">{{ trans('menus::backend.menu') }}</a></li>
        <li class="active">{{ trans('menus::backend.add') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.menus.create'), 'files' => true]) }}
        {{ Form::hidden('user_id', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">{{ trans('menus::backend.description') }}</a></li>
                            <li><a href="#tab_image" data-toggle="tab">{{ trans('menus::backend.image') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('menus::backend.seo') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('menus::backend.name') }}<span style="color:red;">*</span></label>
                                    {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('menus::backend.description') }}</label>
                                        {{ Form::textarea('description', old('description'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('menus::backend.link_type') }} <span style="color:red;">*</span></label>
                                    {!! Form::select('link_type', ['external' => 'External', 'internal' => 'Internal'], old('link_type'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group" id="url_external">
                                    <label for="InputName">{{ trans('menus::backend.link') }} <span style="color:red;">*</span></label>
                                    {{ Form::text('url_external', old('url_external'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group" id="layout">
                                    <label>{{ trans('menus::backend.layout') }} <span style="color:red;">*</span></label>
                                    {!! Form::select('layout', $layouts, old('layout'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group" id="zone_data_single_page">
                                    <label>เลือกข้อมูล</label>
                                    {{ Form::text('params_case_single_page_items','',['class'=>'form-control','id'=>'params_case_single_page_items','readonly'=>'readonly']) }}
                                    {{ Form::hidden('params_case_single_page_items_id','',['class'=>'form-control','id'=>'params_case_single_page_items_id']) }}
                                    <a data-toggle="modal" class="btn btn-info" id="btnModal" data-target="#myModalSinglePage">เลือก</a>
                                </div>
                                <div class="form-group" id="zone_data_exhibition_single_page">
                                    <label>เลือกข้อมูล</label>
                                    {{ Form::text('params_case_exhibition_single_page_items','',['class'=>'form-control','id'=>'params_case_exhibition_single_page_items','readonly'=>'readonly']) }}
                                    {{ Form::hidden('params_case_exhibition_single_page_items_id','',['class'=>'form-control','id'=>'params_case_exhibition_single_page_items_id']) }}
                                    <a data-toggle="modal" class="btn btn-info" id="btnModal" data-target="#myModalExhibitionSinglePage">เลือก</a>
                                </div>
                                <div class="form-group" id="url_rss">
                                        <label for="InputName">RSS Link<span style="color:red;">*</span></label>
                                        {{ Form::text('url_rss', old('url_rss'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group" id="zone_exhibition_category" >
                                    <label>{{ trans('exhibition::backend.exhibition_category') }}</label>
                                    <select name="exhibition_category" class="form-control">
                                        <option value="0">เลือกหมวดหมู่</option>
                                        @foreach($exhibition_masters as $exhibition_master)
                                        <option value="{{ $exhibition_master->id }}" @if(old('category_id') == $exhibition_master->id) selected="selected" @endif>{{ $exhibition_master->title }}</option>
                                        @if ($exhibition_master->children->isNotEmpty())
                                            @foreach($exhibition_master->children->sortBy('order') as $children)
                                            <option value="{{ $children->id }}" @if(old('category_id') == $children->id) selected="selected" @endif>
                                                - {{ $children->title }}
                                            </option>
                                            @endforeach
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="zone_article_category" >
                                    <label>{{ trans('article::backend.health-literacy-category3') }}</label>
                                    <select name="article_category_id" class="form-control">
                                        <option value="0">เลือกหมวดหมู่</option>
                                        @foreach($article_category as $value)
                                        <option value="{{ $value->id }}" @if(old('article_category_id') == $value->id) selected="selected" @endif>{{ $value->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="target" value="1"
                                            @if (old('target'))
                                            checked="checked"
                                            @endif> {{ trans('menus::backend.open_new_page') }}
                                    </label>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('menus::backend.image') }} (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('image_desktop') !!}</div>
                                    <p class="help-block">นามสกุลไฟล์: jpg, png (ไม่เกิน 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>Meta Title</label>
                                    {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control','placeholder'=>'Resource Center ศูนย์บริการข้อมูล สสส. | ศูนย์เรียนรู้สุขภาวะ']) !!}
                                </div>                     
                                <div class="form-group">
                                    <label>Meta Keywords</label>
                                    {!! Form::text('meta_keywords', old('meta_keywords'), ['class' => 'form-control','placeholder'=>'Resource Center ศูนย์บริการข้อมูล สสส.,ศูนย์เรียนรู้สุขภาวะ']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'rows' => 4,'placeholder'=>'Resource Center ศูนย์บริการข้อมูล สสส. | ศูนย์เรียนรู้สุขภาวะ']) !!}
                                </div>                                
                            </div>
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('menus::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('menus::backend.submit') }}</button>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('menus::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>{{ trans('menus::backend.parent') }}</label>
                        <select name="parent_id" class="form-control">
                            <option value="0">Root</option>
                            @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" @if(old('parent_id') == $menu->id) selected="selected" @endif>{{ $menu->name }}</option>
                            @if ($menu->children->isNotEmpty())
                                @foreach($menu->children->sortBy('order') as $children)
                                <option value="{{ $children->id }}" @if(old('parent_id') == $children->id) selected="selected" @endif>
                                    - {{ $children->name }}
                                </option>
                                @endforeach
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('menus::backend.order') }}</label>
                        {{ Form::text('order', 0, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label>Status <span style="color:red;">*</span></label>
                        {!! Form::select('status', ['publish' => trans('menus::backend.publish'),  'draft' => trans('menus::backend.draft')], old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal SinglePage -->
<div class="modal fade" id="myModalSinglePage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe src="{{ route('admin.single-page.index_iframe') }}" id="iframe-items" style="border: 4px solid #e6d3d3;-moz-border-radius: 15px;border-radius: 15px;overflow: hidden;"  height="600" width="800"></iframe>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!-- Modal myModalExhibitionSinglePage -->
<div class="modal fade" id="myModalExhibitionSinglePage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe src="{{ route('admin.exhibition.index_iframe') }}" id="iframe-items" style="border: 4px solid #e6d3d3;-moz-border-radius: 15px;border-radius: 15px;overflow: hidden;"  height="600" width="800"></iframe>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



@endsection
@section('javascript')

<script type="text/javascript">
    
	$(function() {
        
        $("select[name=link_type]").on('change', function(event) {
            var link_type = $(this).val();
            if (link_type == 'internal') {
                $("#url_external").hide();
                $("#layout").show();
            }else {
                $("#url_external").show();
                $("#layout").hide();
                $('#zone_data_exhibition_single_page,#zone_data_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
            }
        });
        $("select[name=link_type]").trigger('change');



        $("select[name=layout]").on('change', function(event) {
            var layout = $( this ).val();
            switch(layout) {
                case 'home':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'rss_feed_generator':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'request_media':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'contact_us':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'news_list':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'our_service_list':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'sook_library_list':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'training_course_list':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'e_learning_list':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'interesting_issues_list':
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'rss_feed':
                    $('#url_rss').show();
                    $('#zone_data_exhibition_single_page,#zone_data_single_page,#zone_exhibition_category,#zone_article_category').hide();
                    break;
                case 'single_page':
                    $('#zone_data_single_page').show();
                    $('#zone_data_exhibition_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'exhibition_single_page':
                    $('#zone_data_exhibition_single_page').show();
                    $('#zone_data_single_page,#zone_exhibition_category,#url_rss,#zone_article_category').hide();
                    break;
                case 'exhibition_list':
                    $('#zone_exhibition_category').show();
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#url_rss,#zone_article_category').hide();
                    break;
                case 'exhibition_list_case_closed_to_visitors':
                    $('#zone_exhibition_category').show();
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#url_rss,#zone_article_category').hide();
                    break;
                case 'health_literacy_list':
                    $('#zone_article_category').show();
                    $('#zone_data_single_page,#zone_data_exhibition_single_page,#url_rss,#exhibition_list_case_closed_to_visitors,#zone_exhibition_category').hide();
                    break;
                    
                
                default:
                     
                    break;
            }
        });


        $("select[name=layout]").trigger('change');
        $('.select2').select2({
            tags: false
        });
        
        $(".select2").on("select2:select", function (evt) {
              var element = evt.params.data.element;
              var $element = $(element);
              $element.detach();
              $(this).append($element);
              $(this).trigger("change");
        });

    });

    function getItemCaseSinglePage(id,text){
        //console.log("get Item",id,text);
        jQuery('#params_case_single_page_items_id').val(id);
        jQuery('#params_case_single_page_items').val(text);
        jQuery('#myModal').modal('hide');
    }


    function getItemCaseExhibition(id,text){
        //console.log("get Item",id,text);
        jQuery('#params_case_exhibition_single_page_items_id').val(id);
        jQuery('#params_case_exhibition_single_page_items').val(text);
        jQuery('#myModal').modal('hide');
    }
    
</script>

@stop


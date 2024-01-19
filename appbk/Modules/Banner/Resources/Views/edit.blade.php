@extends('layout::app')

@section('content')
<section class="content-header">
    <h1>{{ trans('banner::backend.edit_banner') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('banner::backend.home') }}</a></li>
        <li><a href="{{ route('admin.banner.index') }}">{{ trans('banner::backend.banner') }}</a></li>
        <li class="active">{{ trans('banner::backend.edit_banner') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.banner.edit', $item->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">{{ trans('banner::backend.description') }}</a></li>
                            <li><a href="#tab_image" data-toggle="tab">{{ trans('banner::backend.image') }}</a></li>
                            <li><a href="#tab_revision" data-toggle="tab">{{ trans('banner::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('banner::backend.name') }}</label>
                                    {!! Form::text('name', old('name', $item->name), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('banner::backend.description') }}</label>
                                    {!! Form::textarea('description', old('description', $item->description), ['class' => 'form-control ckeditor']) !!}
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('banner::backend.use_internal_data_link') }}</label>
                                    {{ Form::radio('use_content','news_events',($item->use_content == 'news_events' ? true:false)) }} ข่าวสารและกิจกรรม
                                    {{ Form::radio('use_content','not_use',($item->use_content == 'not_use' ? true:false)) }} ไม่ใช้
                                </div>
                                <div class="form-group" id="zone_data_news_events" style="{{ ($item->use_content == 'news_events' ? '':'display:none') }}">
                                    <?php
                                        $params_case_items = '';
                                        if($item->use_content ==='news_events'){
                                            $params_case_items = json_decode($item->use_content_params);
                                        }
                                    ?>
                                    <label>เลือกข้อมูล</label>
                                    {{ Form::text('params_case_items',($params_case_items !='' ? $params_case_items->text:''),['class'=>'form-control','id'=>'params_case_items','readonly'=>'readonly']) }}
                                    {{ Form::hidden('params_case_items_id',($params_case_items !='' ? $params_case_items->id:''),['class'=>'form-control','id'=>'params_case_items_id']) }}
                                    <a data-toggle="modal" class="btn btn-info" id="btnModal" data-target="#myModal">เลือก</a>
                                </div>
                                <div class="form-group" id="zone_link" style="{{ ($item->use_content == 'not_use' ? '':'display:none') }}">
                                    <label>{{ trans('banner::backend.link') }}</label>
                                    {{ Form::text('link',old('link',$item->link), ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group col-md-12">
                                    <label for="InputName">{{ trans('banner::backend.image') }} (1366px * 768px)</label>
                                    @if (!$item->getMedia('desktop')->isEmpty())
                                    <div><img src="{{ asset($item->getMedia('desktop')->first()->getUrl()) }}" width="250"></div>
                                    @endif
                                    <div class="areaImage">{!! Form::file('desktop') !!}</div>
                                    <p class="help-block">นามสกุลไฟล์: jpeg, png (ไม่เกิน 2M)</p>
                                </div>
                                <!--
                                <div class="form-group col-md-6">
                                    <label for="InputName">Video(Desktop)</label>
                                    @if($item->video_path !='' && file_exists(public_path().$item->video_path))
                                    <div>
                                        <video width="320" height="240" controls>
                                          <source src="{{ URL($item->video_path) }}" type="video/mp4">
                                        </video>
                                    </div>
                                    @endif
                                    <div class="areaImage">{!! Form::file('video') !!}</div>
                                    <p class="help-block">Format: mp4 (ไม่เกิน 50M)</p>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="InputName">Image (Mobile) (768px * auto)</label>
                                    @if (!$item->getMedia('mobile')->isEmpty())
                                    <div><img src="{{ asset($item->getMedia('mobile')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (ไม่เกิน 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('mobile') !!}</div>
                                </div>
                                -->
                            </div>
                            <div class="tab-pane" id="tab_revision">
                                @include('banner::partials.revision', ['revisions' => $revisions])
                            </div>
                            <a href="{{ route('admin.banner.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('banner::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('banner::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('banner::backend.setting') }} <span style="color:red">*</span></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('banner::backend.status') }}</label>
                        {!! Form::select('status',$status, old('status', $item->status), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('banner::backend.category') }} <span style="color:red">*</span></label>
                        {!! Form::select('category_id', $categories, $item->category_id, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <iframe src="{{ URL('admin/article-iframe') }}" id="iframe-items" style="border: 4px solid #e6d3d3;-moz-border-radius: 15px;border-radius: 15px;overflow: hidden;"  height="600" width="800"></iframe>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection
@section('javascript')
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('input[name="use_content"]').click(function(){
            val = jQuery(this).val();
            if(val ==='not_use'){
                jQuery('input[name="params_case_items"]').val('');
                jQuery('input[name="params_case_items_id"]').val('');
                jQuery('#zone_link').show('slow');
                jQuery('#zone_data_news_events').hide('slow');
            }else if(val ==='business'){
                jQuery('input[name="params_case_items"]').val('');
                jQuery('input[name="params_case_items_id"]').val('');
                jQuery('#zone_link').hide('');
                jQuery('#zone_data_news_events').hide('');
                jQuery('input[name="link"]').val('');
            }else{
                jQuery('input[name="link"]').val('');
                jQuery('#zone_link').hide('slow');
                jQuery('#zone_data_news_events').show('slow');
            }
        });
    });
    function getItemCaseArticle(id,text){
            //console.log("get Item",id,text);
            jQuery('#params_case_items_id').val(id);
            jQuery('#params_case_items').val(text);
            jQuery('#myModal').modal('hide');
    }
</script>
@endsection
@section('css')
<style type="text/css">
    .modal-content{
        box-shadow:none !important;
        background-color:rgba(0, 0, 0, 0) !important;
    }
</style>
@endsection

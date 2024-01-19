@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('api::backend.edit_media') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('api::backend.home') }}</a></li>
        <li><a href="{{ route('admin.api.list-media.index') }}">{{ trans('api::backend.media') }}</a></li>
        <li class="active">{{ trans('api::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.api.list-media.edit', $data->id), 'files' => true]) }}
        {{ Form::hidden('updated_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('api::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('article::backend.cover_image') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title',$data->title ?? old('title'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.description') }}<span style="color:red">*</span></label>
                                    {{ Form::textarea('description',$data->description ?? old('description'),['class'=>'form-control ckeditor']) }}
                                </div>
                                @php
                                    //dd($data);
                                    $json = '';
                                    if($data->json_data !=''){
                                        $json = json_decode($data->json_data);
                                    }
                                @endphp
                                @if(gettype($json) =='object')
                                    @foreach($json AS $key=>$value)
                                        @php
                                            //dd($key,$value,gettype($json->Keywords),json_encode($json->Keywords));
                                            if(gettype($value) =='array'){
                                               //print_r($value);
                                            }
                                        @endphp
                                        <div class="form-group">
                                            <label for="InputName">{{ $key }}</label>
                                            @if(gettype($value) =='array')
                                                    <pre>
                                                @php
                                                    print_r($value);
                                                @endphp
                                                    </pre>
                                            @else
                                                {{ Form::text($key,$value,['class'=>'form-control','readonly'=>'readonly']) }}
                                            @endif
                                        </div>
                                    @endforeach
                                @else

                                @endif
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('article::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    @if ($data->getMedia('cover_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">นามสกุลไฟล์: jpg, png (ไม่เกิน 5M)</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.api.list-media.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('api::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('api::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('api::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('api::backend.publish'),'draft'=>trans('api::backend.draft')];
                        @endphp
                        <label>{{ trans('api::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.highlight') }} </label>
                        {{ Form::radio('featured', '2', ($data->featured == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('featured', '1', ($data->featured == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.interesting_issues') }} </label>
                        {{ Form::radio('interesting_issues', '2', ($data->interesting_issues == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('interesting_issues', '1', ($data->interesting_issues == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.recommend') }} </label>
                        {{ Form::radio('recommend', '2', ($data->recommend == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('recommend', '1', ($data->recommend == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.articles_research') }} </label>
                        {{ Form::radio('articles_research', '2', ($data->articles_research == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('articles_research', '1', ($data->articles_research == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.include_statistics') }} </label>
                        {{ Form::radio('include_statistics', '2', ($data->include_statistics == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('include_statistics', '1', ($data->include_statistics == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>
                    <!--
                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.notable_books') }} </label>
                        {{ Form::radio('notable_books', '2', ($data->notable_books == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('notable_books', '1', ($data->notable_books == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>-->

                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.knowledges') }} </label>
                        {{ Form::radio('knowledges', '2', ($data->knowledges == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('knowledges', '1', ($data->knowledges == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.media_campaign') }} </label>
                        {{ Form::radio('media_campaign', '2', ($data->media_campaign == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('media_campaign', '1', ($data->media_campaign == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>
                    @php
                        //dd($data->health_literacy);
                    @endphp
                    <div class="form-group">
                        <label for="InputName">{{ trans('api::backend.health-literacy') }} </label>
                        {{ Form::radio('health_literacy', '2', ($data->health_literacy == '2' ? true:false)) }}{{ trans('api::backend.yes') }}
                        {{ Form::radio('health_literacy', '1', ($data->health_literacy == '1' ? true:false)) }}{{ trans('api::backend.no') }}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('api::backend.tags') }} <span style="color:red">*</span></label>
                        {!! Form::select('tags[]',$tags,json_decode($data->tags) ?? old('tags'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('api::backend.sex') }} </label>
                        {!! Form::select('sex[]',$sex,json_decode($data->sex) ?? old('sex'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('api::backend.age') }} </label>
                        {!! Form::select('age[]',$age,json_decode($data->age) ?? old('age'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                    </div>                    

                </div>
            </div>
        </div>
    </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('javascript')
    <script>
        
        $(".js-tags-tokenizer").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })
        
    </script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<style type="text/css">
    .plus{
        margin-left:93%;
        cursor: pointer;
    }
    .del_gallery{
        margin-top: 10px;
        cursor: pointer;
    }
    #zone_gallery{
        display: inline-block;
        width: 100%;
    }
</style>
@endsection
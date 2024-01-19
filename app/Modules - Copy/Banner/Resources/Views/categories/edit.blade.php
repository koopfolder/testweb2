@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>{{ trans('banner::backend.edit_banner_category') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('banner::backend.home') }}</a></li>
        <li><a href="{{ route('admin.banner.category.index') }}">{{ trans('banner::backend.category') }}</a></li>
        <li class="active">{{ trans('banner::backend.edit_banner_category') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.banner.category.edit', $item->id), 'files' => true]) }}
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
                                    <label for="InputName">{{ trans('banner::backend.name') }} <span style="color:red">*</span></label>
                                    {{ Form::text('name', old('name', $item->name), ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('banner::backend.image') }}(1366px * 768px)</label>
                                    @if ($item->getMedia('desktop')->isNotEmpty())
                                    <div><img src="{{ asset($item->getMedia('desktop')->first()->getUrl()) }}" width="250"></div>
                                    @endif                                    
                                    <div class="areaImage">{!! Form::file('desktop') !!}</div>
                                    <p class="help-block">นามสกุลไฟล์: jpg, png (ไม่เกิน 2M)</p>
                                </div>
                                <!--
                                 <div class="form-group">
                                    <label for="InputName">Image(Mobile) (768px * auto)</label>
                                    @if ($item->getMedia('mobile')->isNotEmpty())
                                    <div><img src="{{ asset($item->getMedia('mobile')->first()->getUrl()) }}" width="250"></div>
                                    @endif                                           
                                    <div class="areaImage">{!! Form::file('mobile') !!}</div>
                                    <p class="help-block">ประเภทไฟล์: jpeg, png (no more than 2M)</p>
                                </div>
                                -->
                            </div>
                            <div class="tab-pane" id="tab_revision">
                                @include('banner::partials.categories.revision', ['revisions' => $revisions])
                            </div>                            
                            <a href="{{ route('admin.banner.category.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('banner::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('banner::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('banner::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('banner::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status', $item->status), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

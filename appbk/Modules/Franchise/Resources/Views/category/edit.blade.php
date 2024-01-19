@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('franchise::backend.edit_franchise_category') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('franchise::backend.home') }}</a></li>
        <li><a href="{{ route('admin.franchise.category.index') }}">{{ trans('franchise::backend.franchise_category') }}</a></li>
        <li class="active">{{ trans('franchise::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.franchise.category.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('franchise::backend.franchise_description') }}</a></li>
                            <li ><a href="#tab_revisions" data-toggle="tab">{{ trans('franchise::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.franchise_title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('category_name',$data->category_name ?? old('category_name'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.franchise_description') }}</label>
                                    {{ Form::textarea('description',$data->description ?? old('description'),['class'=>'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_revisions">
                                @include('franchise::partials.category.revision', ['revisions' => $revisions])
                            </div>
                            <a href="{{ route('admin.franchise.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('article::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('franchise::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('franchise::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('franchise::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('javascript')

@endsection
@section('css')

@endsection
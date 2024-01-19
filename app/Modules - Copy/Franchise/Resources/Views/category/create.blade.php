@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('franchise::backend.add_franchise_category') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('franchise::backend.home') }}</a></li>
        <li><a href="{{ route('admin.franchise.category.index') }}">{{ trans('franchise::backend.franchise_category') }}</a></li>
        <li class="active">{{ trans('franchise::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.franchise.category.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('franchise::backend.franchise_description') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.franchise_title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('category_name',old('category_name'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.franchise_description') }}</label>
                                    {{ Form::textarea('description',old('description'),['class'=>'form-control ckeditor']) }}
                                </div>
                            </div>
                            <a href="{{ route('admin.franchise.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('franchise::backend.back') }}</a>
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
                            $status = ['publish' =>trans('franchise::backend.publish'),'draft'=>trans('franchise::backend.draft')];
                        @endphp
                        <label>{{ trans('franchise::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
<script type="text/javascript">
    jQuery(document).ready(function(){

    });
</script>
@endsection
@section('css')

@endsection

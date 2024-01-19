@extends('layout::app')

@section('content')
<section class="content-header">
	<h1>{{ trans('setting::backend.setting_ncds') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('setting::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.ncds_setting.index') }}">{{ trans('setting::backend.setting_ncds') }}</a></li>
	</ol>
</section>
<section class="content">
	<div class="box box-default">
    	<div class="box-body">
    		{{ Form::open(['url' => route('admin.ncds_setting.index'), 'files' => true]) }}
        	<div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_ncds" data-toggle="tab">{{ trans('setting::backend.ncds') }}</a></li>
                            <li><a href="#tab_master_data_ncds_2" data-toggle="tab">{{ trans('setting::backend.master_data_ncds_2') }}</a></li>
                        </ul>
                        <div class="tab-content col-md-12">
                            
                            <div class="tab-pane active" id="tab_ncds">
                                <div class="form-group">
                                    <label for="InputEmailAddress">{{ trans('setting::backend.ncds_cover_image') }}</label>
                                    @if ($ncds_cover_image)
                                        <div style="margin:10px 0;"><img src="{{ url($ncds_cover_image) }}" width="50%"></div>
                                    @endif
                                    {!! Form::file('ncds_cover_image') !!}
                                    <p class="help-block">{{ trans('setting::backend.fileupload_note') }}</p>
                                </div>                                 
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.ncds_1') }} </label>
                                    {{ Form::select('ncds_1',$single_page,$ncds_1,['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.ncds_3') }} </label>
                                    {{ Form::select('ncds_3',$single_page,$ncds_3,['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.ncds_7') }} </label>
                                    {{ Form::select('ncds_7',$banner_category,$ncds_7,['class' => 'form-control']) }}
                                </div>
                            </div> 
                            @php
                                //dd($categorys,$ncds_2_disease,$ncds_2_area);   
                            @endphp
                            <div class="tab-pane" id="tab_master_data_ncds_2">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.category') }} </label>
                                    {!! Form::select('category_id[]',$categorys,$categorys_select ?? old('category_id'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.disease') }} </label>
                                    {!! Form::select('ncds_2_disease[]',$ncds_2_disease,$ncds_2_disease_select ?? old('ncds_2_disease'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.area') }} </label>
                                    {!! Form::select('ncds_2_area[]',$ncds_2_area,$ncds_2_area_select ?? old('ncds_2_area'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                                </div>                                                                
                            </div>

                        </div><!-- End tap-content -->
                    </div><!-- End nav-tabs-custom -->
                </div><!-- End col-md-12 -->
            </div><!-- End Row -->
            <div class="box-footer">
                <button class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('setting::backend.submit') }}</button>
            </div>
         	{{ Form::close() }}
       	</div>
    </div>
</section>
@endsection
@section('javascript')
<script type="text/javascript">
    $(".js-tags-tokenizer").select2({
        tags: true,
        tokenSeparators: [',', ' ']
    })
</script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('adminlte/bower_components/bootstrap-datetimepicke/bootstrap-datetimepicker.min.css') }}">
<style type="text/css">
    .select2{
       width: 250px !important;
    }
</style>
@endsection
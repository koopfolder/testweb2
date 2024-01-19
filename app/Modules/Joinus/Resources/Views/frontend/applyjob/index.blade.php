@php
	$lang = \App::getLocale();
	$keywords = RoosterHelpers::getSetting(['slug'=>'meta_keywords','retrieving_results'=>'first']);
	$description = RoosterHelpers::getSetting(['slug'=>'meta_description','retrieving_results'=>'first']);
@endphp
@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
	$days =  RoosterHelpers::Days();
	$month = RoosterHelpers::months();
	$year = RoosterHelpers::getYear();
	$prefix = RoosterHelpers::getPrefix();
	//dd($prefix);
@endphp

@extends('layouts.app')
@section('content')
 <div class="max-width">
    @include('partials.breadcrumb')
	<h1 class="title">
		{{ trans('joinus::frontend.job_application') }}
	</h1>
	<div class="line-blue-center-l"></div>	
{{ Form::open(['url' => route('apply-job-store'), 'files' => true,'id'=>'contactForm']) }}
<div class="bg-while pd-tblr-34 font-s-20 carrers">
	<div class="columns">
		<div class="column is-3">
			<h2 class="font-c-blue">{{ trans('joinus::frontend.position_applied_for') }}</h2>
		</div>
		<div class="column">
			<h2>{{ $data->position }}
			{{ FORM::hidden('position_id',$data->id) }}</h2>
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.prefix') }}<span class="font-red">*</span></div>
		</div>
		<div class="column is-4">
			<div class="select-type">
				{{ Form::select('prefix',$prefix, '',['class'=>'']) }}
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.name') }}<span class="font-red">*</span></div>
		</div>
		<div class="column is-4">
			{{ Form::text('name','',['class'=>'','required'=>'required']) }}
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.surname') }}<span class="font-red">*</span></div>
		</div>
		<div class="column is-4">
			{{ Form::text('surname','',['class'=>'','required'=>'required']) }}
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.sex') }}</div>
		</div>
		<div class="column is-2">
			<div class="columns">
				<div class="column nopadding">
					<div class="pd-tb-10">
						<label class="input-check">{{ trans('joinus::frontend.male') }}
						  {{ Form::radio('sex','M',true,['class'=>'','id'=>'sex_m']) }}
						  <span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div class="column nopadding">
					<div class="pd-tb-10">
						<label class="input-check">{{ trans('joinus::frontend.female') }}
						  {{ Form::radio('sex','F',false,['class'=>'','id'=>'sex_f']) }}
						  <span class="checkmark"></span>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.birthdate') }}<span class="font-red">*</span></div>
		</div>
		<div class="column nopadding is-6">
			<div class="columns">
				<div class="column">
					<div class="select-type">
						{{ Form::select('day',$days,['class'=>'']) }}
					</div>
				</div>
				<div class="column">
					<div class="select-type">
						{{ Form::select('month',$month,['class'=>'']) }}
					</div>
				</div>
				<div class="column">
					<div class="select-type">
						{{ Form::select('year',$year,['class'=>'']) }}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.nationality') }}<span class="font-red">*</span></div>
		</div>
		<div class="column is-4">
			{{ Form::text('nationality','',['class'=>'','required'=>'required']) }}
		</div>
	</div>
	<!--
	<div class="columns">
		<div class="column is-2">
			<div class="pd-tb-10">{{ trans('joinus::frontend.province') }}</div>
		</div>
		<div class="column is-4">{{ Form::text('province','',['class'=>'']) }}</div>
	</div> -->
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.email_address') }}<span class="font-red">*</span></div>
		</div>
		<div class="column is-4">{{ Form::text('email','',['class'=>'','required'=>'required']) }}</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.phone_number') }}<span class="font-red">*</span></div>
		</div>
		<div class="column is-4">{{ Form::text('phone','',['class'=>'','required'=>'required']) }}</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.id_type') }}<span class="font-red">*</span></div>
		</div>
		<div class="column is-6">
			<div class="columns">
				<div class="column nopadding">
					<div class="pd-tb-10">
						<label class="input-check">{{ trans('joinus::frontend.identification_number') }}
						  {{ Form::radio('id_type','identification_number',true,['class'=>'']) }}
						  <span class="checkmark"></span>
						</label>
					</div>
				</div>
				<div class="column nopadding">
					<div class="pd-tb-10">
						<label class="input-check">{{ trans('joinus::frontend.passport_number') }}
						  {{ Form::radio('id_type','passport_number',false,['class'=>'']) }}
						  <span class="checkmark"></span>
						</label>
					</div>
				</div>
			</div>
			<div class="column is-8 nopadding">{{ Form::text('id_no','',['class'=>'','required'=>'required']) }}</div>
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.education') }}</div>
		</div>
		<div class="column is-4">
			{{ Form::text('education','',['class'=>'']) }}
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.educational_institutions') }}</div>
		</div>
		<div class="column is-4">
			{{ Form::text('educational_institutions','',['class'=>'']) }}
		</div>
		<div class="column is-1 textright">
			<div class="pd-tb-10">{{ trans('joinus::frontend.other') }}</div>
		</div>
		<div class="column is-4">
			{{ Form::text('educational_institutions_other','',['class'=>'']) }}
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.field_of_study_major') }}</div>
		</div>
		<div class="column is-4">{{ Form::text('field_of_study_major','',['class'=>'']) }}</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.work_experience') }} (1)
			</div>
		</div>
		<div class="column is-4">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.position') }}
		</div>
		<div class="column is-4">
			{{ Form::text('position_1','',['class'=>'']) }}
		</div>
		<div class="column is-2">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.company') }}
		</div>
		<div class="column is-4">
			{{ Form::text('company_1','',['class'=>'']) }}
		</div>
		<div class="column is-2">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.duration_of_service') }}
		</div>
		<div class="column is-2">
			{{ Form::text('duration_of_service_year_1','',['class'=>'']) }}
		</div>
		<div class="column is-1">
			<div class="pd-tb-10">{{ trans('joinus::frontend.year') }}</div>
		</div>
		<div class="column is-2">
			{{ Form::text('duration_of_service_month_1','',['class'=>'']) }}
		</div>
		<div class="column is-1">
			<div class="pd-tb-10">{{ trans('joinus::frontend.month') }}</div>
		</div>
	</div>

	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.work_experience') }} (2)
			</div>
		</div>
		<div class="column is-4">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.position') }}
		</div>
		<div class="column is-4">
			{{ Form::text('position_2','',['class'=>'']) }}
		</div>
		<div class="column is-2">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.company') }}
		</div>
		<div class="column is-4">
			{{ Form::text('company_2','',['class'=>'']) }}
		</div>
		<div class="column is-2">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.duration_of_service') }}
		</div>
		<div class="column is-2">
			{{ Form::text('duration_of_service_year_2','',['class'=>'']) }}
		</div>
		<div class="column is-1">
			<div class="pd-tb-10">{{ trans('joinus::frontend.year') }}</div>
		</div>
		<div class="column is-2">
			{{ Form::text('duration_of_service_month_2','',['class'=>'']) }}
		</div>
		<div class="column is-1">
			<div class="pd-tb-10">{{ trans('joinus::frontend.month') }}</div>
		</div>
	</div>

	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.work_experience') }} (3)
			</div>
		</div>
		<div class="column is-4">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.position') }}
		</div>
		<div class="column is-4">
			{{ Form::text('position_3','',['class'=>'']) }}
		</div>
		<div class="column is-2">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.company') }}
		</div>
		<div class="column is-4">
			{{ Form::text('company_3','',['class'=>'']) }}
		</div>
		<div class="column is-2">
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
		</div>
		<div class="column is-3">
			{{ trans('joinus::frontend.duration_of_service') }}
		</div>
		<div class="column is-2">
			{{ Form::text('duration_of_service_year_3','',['class'=>'']) }}
		</div>
		<div class="column is-1">
			<div class="pd-tb-10">{{ trans('joinus::frontend.year') }}</div>
		</div>
		<div class="column is-2">
			{{ Form::text('duration_of_service_month_3','',['class'=>'']) }}
		</div>
		<div class="column is-1">
			<div class="pd-tb-10">{{ trans('joinus::frontend.month') }}</div>
		</div>
	</div>




	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.attached_images') }}</div>
		</div>
		<div class="column is-6">
			<div class="block-file">
				{{ Form::text('uploadFile_attached_images','',['class'=>'fileinput','disabled'=>'disabled','id'=>'uploadFile_attached_images']) }}
				<div class="fileUpload btn btn-blue">
				    <span>{{ trans('joinus::frontend.select') }}</span>
				    {{ Form::file('attached_images',['class'=>'upload','id'=>'upload_attached_images']) }}
				</div>
			</div>
			<div class="font-s-19 font-c-gray">{{ trans('joinus::frontend.file_note_case_image') }}</div>
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">{{ trans('joinus::frontend.attachment_history') }}</div>
		</div>
		<div class="column is-6">
			<div class="block-file">
				{{ Form::text('uploadFile_attachment_history','',['class'=>'fileinput','disabled'=>'disabled','id'=>'uploadFile_attachment_history']) }}
				<div class="fileUpload btn btn-blue">
				    <span>{{ trans('joinus::frontend.select') }}</span>
				    {{ Form::file('attachment_history',['class'=>'upload','id'=>'upload_attachment_history']) }}
				</div>
			</div>
			<div class="font-s-19 font-c-gray">
				{{ trans('joinus::frontend.file_note') }}
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column is-3">
			<div class="pd-tb-10">
				{{ trans('joinus::frontend.other_documents') }}
			</div>
		</div>
		<div class="column is-6">
			<div class="block-file">
			    {{ Form::text('uploadFile_other_documents','',['class'=>'fileinput','disabled'=>'disabled','id'=>'uploadFile_other_documents']) }}
				<div class="fileUpload btn btn-blue">
				    <span>{{ trans('joinus::frontend.select') }}</span>
				    {{ Form::file('other_documents',['class'=>'upload','id'=>'upload_other_documents']) }}
				</div>
			</div>
			<div class="block-file">
				{{ Form::text('uploadFile_other_documents_2','',['class'=>'fileinput','disabled'=>'disabled','id'=>'uploadFile_other_documents_2']) }}
				<div class="fileUpload btn btn-blue">
				    <span>{{ trans('joinus::frontend.select') }}</span>
				    {{ Form::file('other_documents_2',['class'=>'upload','id'=>'upload_other_documents_2']) }}
				</div>
			</div>
			<div class="block-file">
				{{ Form::text('uploadFile_other_documents_3','',['class'=>'fileinput','disabled'=>'disabled','id'=>'uploadFile_other_documents_3']) }}
				<div class="fileUpload btn btn-blue">
				    <span>{{ trans('joinus::frontend.select') }}</span>
				    {{ Form::file('other_documents_3',['class'=>'upload','id'=>'upload_other_documents_3']) }}
				</div>
			</div>
			<div class="font-s-19 font-c-gray">{{ trans('joinus::frontend.file_note') }}</div>
			<div class="pd-tb-10">
				<span class="input-check">
					{{ Form::radio('accept_data','true',false,['class'=>'']) }} </span>
				<span class="input-check-span">{{ trans('joinus::frontend.accept_data') }}</span>
			</div>
			<div class="mg-b-20">
				{!! \Recaptcha::render([ 'lang' => 'th' ]) !!}
			</div>
		</div>
	</div>
	<div class="columns">
		<button class="btn btn-blue">{{ trans('joinus::frontend.submit_an_application') }}</button>
		<button class="btn btn-gray mg-lr-20">{{ trans('joinus::frontend.cancel') }}</button>
	</div>
</div>
	{{ Form::close() }}
</div>
@php
	$inputs = $request->all();
	if(array_key_exists('success', $inputs)){
		$success ='1';
	}else{
		$success = '0';
	}
@endphp
@endsection
@section('meta')
    @parent
    <title>{{ trans('joinus::frontend.job_application') }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $description->value }}">
    <meta name="keywords" content="{{ $keywords->value }}">
    <meta name="author" content="Pylon Public Company Limited Foundation Professional">
@endsection
@section('style')
    @parent
    <style type="text/css">
    @media only screen and (max-width:767px){
    	.g-recaptcha{transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;}
	    }
	}
    </style>
@endsection
@section('js')
	@parent
	<script src="{{ asset('rooster/js/plugins/ui/1.12.1/jquery-ui.js') }}"></script>
	<script src="{{ asset('rooster/js/plugins/jquery-validation-1.17.0/dist/jquery.validate.js') }}"></script>
	<script src="{{ asset('rooster/js/plugins/bootstrap-sweetalert-master/dist/sweetalert.js') }}"></script>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			check_msg = '{{ $success }}';
			if(check_msg ==1){
        		swal("{{ Lang::get('frontend.save_success') }}","", "success");
    		}
			jQuery("#contactForm").validate();
			jQuery('select[name="prefix"]').change(function(){
				if(jQuery(this).val() == '1'){
					jQuery("#sex_m").prop("checked",true);
				}else{
					jQuery("#sex_f").prop( "checked",true);
				}
			});
		});
		document.getElementById("upload_attached_images").onchange = function(){
		    document.getElementById("uploadFile_attached_images").value = this.value;
		};

		document.getElementById("upload_attachment_history").onchange = function(){
		    document.getElementById("uploadFile_attachment_history").value = this.value;
		};

		document.getElementById("upload_other_documents").onchange = function(){
		    document.getElementById("uploadFile_other_documents").value = this.value;
		};

		document.getElementById("upload_other_documents_2").onchange = function(){
		    document.getElementById("uploadFile_other_documents_2").value = this.value;
		};

		document.getElementById("upload_other_documents_3").onchange = function(){
		    document.getElementById("uploadFile_other_documents_3").value = this.value;
		};

	</script>
@endsection
@section('style')
	@parent
	<link rel="stylesheet" href="{{ asset('rooster/js/plugins/bootstrap-sweetalert-master/dist/sweetalert.css') }}">
@endsection
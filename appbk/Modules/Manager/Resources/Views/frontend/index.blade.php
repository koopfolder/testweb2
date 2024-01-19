@php
	$lang = \App::getLocale();
	$keywords = RoosterHelpers::getSetting(['slug'=>'meta_keywords','retrieving_results'=>'first']);
	$description = RoosterHelpers::getSetting(['slug'=>'meta_description','retrieving_results'=>'first']);
	if($lang =='th'){
		$site_name = RoosterHelpers::getSetting(['slug'=>'company_name_th','retrieving_results'=>'first']);
	}else{
		$site_name = RoosterHelpers::getSetting(['slug'=>'company_name_en','retrieving_results'=>'first']);
	}
@endphp
@extends('layouts.app')
@section('content')
<div class="max-width">
@include('partials.breadcrumb')
<h1 class="title">{{ trans('manager::frontend.board_of_director') }}</h1>
<div class="line-blue-center-l"></div>
<div class="bg-while pd-tblr-34">
	<div class="columns">
		<div class="column is-4">
			<div class="images">
				@if ($data->getMedia('desktop')->isNotEmpty())
		            <div class="textcenter"><img src="{{ asset($data->getMedia('desktop')->first()->getUrl()) }}" ></div>
		        @endif
			</div>
		</div>
		<div class="column">
			<div class="detail">
				<h1 class="font-c-blue">{{ $data->name }}</h1>
				<div>
					{!! $data->position !!}
				</div>
				<div>
					<div class="font-s-22 font-c-blue">{{ trans('manager::frontend.education') }}</div>
					{!! $data->education !!}
				</div>
				<div>
					<div class="font-s-22 font-c-blue">{{ trans('manager::frontend.iod_training') }}</div>
					{!! $data->iod_training !!}
				</div>
				<div>
					<div class="font-s-22 font-c-blue">{{ trans('manager::frontend.work_experience') }}</div>
					{!! $data->work_experience !!}
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endsection


@section('meta')
    @parent
    <title>{{ $data->name }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $description->value }}">
    <meta name="keywords" content="{{ $keywords->value }}">
    <meta name="author" content="Pylon Public Company Limited Foundation Professional">
@endsection
@section('js')
	@parent
@endsection
@section('style')
	@parent

@endsection
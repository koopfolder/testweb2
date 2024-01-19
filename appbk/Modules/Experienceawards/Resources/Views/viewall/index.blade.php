@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
	$keywords = RoosterHelpers::getSetting(['slug'=>'meta_keywords','retrieving_results'=>'first']);
	$description = RoosterHelpers::getSetting(['slug'=>'meta_description','retrieving_results'=>'first']);
@endphp
		@extends('layouts.app')
		@section('content')
		<div class="chart-position desktop">
		    <img src="{{ asset('themes/pylon/images/bn-awart.jpg') }}">
		</div>
		   <div class="max-width">
		   		@include('partials.breadcrumb')
		   		<div class="chart bg-while pd-tblr-322">
			<h1 class="title">{{ Lang::get('frontend.company_experiences') }}</h1>
			<div class="line-blue-center-l"></div>
			@if(method_exists('App\Modules\Article\Http\Controllers\NewsController', 'getData'))
				@php
				$lang = \App::getLocale();
				$data = App\Modules\Experienceawards\Http\Controllers\ExperienceawardsController::getDataViewAll('experience-awards');
					//dd($data_experience);
					//dd($data);
					//dd($data['items']->currentPage());
				@endphp
				@if($data->isNotEmpty())
					<div class="aword-more">
					@foreach($data AS $key=>$value)
						@php
							//dd($value);
						@endphp
							<div class="aword-block-more">
								<div class="image">
									@if ($value->getMedia('cover_desktop')->isNotEmpty())
									<a href="#">
						           		<img src="{{ asset($value->getMedia('cover_desktop')->first()->getUrl()) }}" >
						           	</a>
						            @endif
								</div>
								<div class="block-col-new-detail">
									<a href="#">
										@if($lang =='en')
											@if($value->description_en !='')
												{{  strip_tags($value->description_en) }}
											@else
												{{  strip_tags($value->description_th) }}
											@endif
										@else
												{{  strip_tags($value->description_th) }}
										@endif
									</a>
								</div>
							</div>
						@endforeach <!-- End foreach -->
					</div>
					<div class="textcenter">
						<div class="pagination-nav">
							{!! isset($data)?$data->appends(\Input::all())->render():'' !!}
						</div>
					</div>
					@endif <!-- End $data['items']->isNotEmpty() -->
				@endif <!-- End method_exists -->
				</div>
			</div>
			</div>
@endsection
@section('meta')
    @parent
    <title>{{ Lang::get('frontend.company_experiences') }}</title>
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
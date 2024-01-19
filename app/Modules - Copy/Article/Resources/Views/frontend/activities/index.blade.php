@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app')
@section('content')
@php
//dd($data);
@endphp
<div class="max-width">
 	@include('partials.breadcrumb')
	<div class="desktop">
		<div class="block-content-new">
			<h1 class="title">{{ Lang::get('frontend.news2') }}</h1>
			<div class="line-blue-center-l"></div>
			<div class="pd-tb-30">
				<div class="detail-image">
			        @if ($data->getMedia('gallery_desktop')->isNotEmpty())
											<ul class="imageGallery">
												  @foreach($data->getMedia('gallery_desktop') AS $key=>$value)
												  <li data-thumb="{{ asset($value->getUrl()) }}" data-src="{{ asset($value->getUrl()) }}">
												    <img src="{{ asset($value->getUrl()) }}" />
												  </li>
													@endforeach
											</ul>
			        @elseif($data->getMedia('cover_desktop')->isNotEmpty())
			        			<img src="{{ asset($data->getMedia('cover_desktop')->first()->getUrl()) }}">
			        @endif
				</div>
				<div class="content-news-detail">
					<div class="sharethis-inline-share-buttons"></div>
					<div class="textcenter">
						<h1>
							@if($lang =='en')
								@if($data->title_en !='')
								{{ $data->title_en }}
								@else
								{{ $data->title_th }}
								@endif
							@else
								{{ $data->title_th }}
							@endif
						</h1>
						<p>

							@if($lang =='th')
								{{ Carbon\Carbon::parse($data->date_event)->format('d/m/').(Carbon\Carbon::parse($data->date_event)->format('Y')+543) }}
							@else
								{{ Carbon\Carbon::parse($data->date_event)->format('d/m/Y') }}
							@endif
						</p>
					</div>
					<div>
						@if($lang =='en')
							@if($data->title_en !='')
								{!! $data->description_en !!}
							@else
								{!! $data->description_th !!}
							@endif
						@else
							{!! $data->description_th !!}
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="mobile">
			<div class="block-content-new">
				<h1 class="title">{{ Lang::get('frontend.news2') }}</h1>
				<div class="line-blue-center-l"></div>
					<div class="detail-image">
			        @if ($data->getMedia('gallery_desktop')->isNotEmpty())
											<ul class="imageGallery">
												  @foreach($data->getMedia('gallery_desktop') AS $key=>$value)
												  <li data-thumb="{{ asset($value->getUrl()) }}" data-src="{{ asset($value->getUrl()) }}">
												    <img src="{{ asset($value->getUrl()) }}" />
												  </li>
													@endforeach
											</ul>
			        @elseif($data->getMedia('cover_desktop')->isNotEmpty())
			        			<img src="{{ asset($data->getMedia('cover_mobile')->first()->getUrl()) }}">
			        @endif
				</div>
					<div class="detail">
						<div class="sharethis-inline-share-buttons"></div>
						<h1>
							@if($lang =='en')
								@if($data->title_en !='')
								{{ $data->title_en }}
								@else
								{{ $data->title_th }}
								@endif
							@else
								{{ $data->title_th }}
							@endif
						</h1>
						<div>
							@if($lang =='th')
								{{ Carbon\Carbon::parse($data->date_event)->format('d/m/').(Carbon\Carbon::parse($data->date_event)->format('Y')+543) }}
							@else
								{{ Carbon\Carbon::parse($data->date_event)->format('d/m/Y') }}
							@endif
						</div>
						<div>
							@if($lang =='en')
								@if($data->title_en !='')
									{!! $data->description_en !!}
								@else
									{!! $data->description_th !!}
								@endif
							@else
								{!! $data->description_th !!}
							@endif
						</div>
					</div>
		</div>
	</div>

</div>
@endsection
@section('meta')
    @parent
    <title></title>
    <title>{{ $data->meta_title }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $data->meta_description }}">
    <meta name="keywords" content="{{ $data->meta_keywords}}">
    <meta name="author" content="Pylon Public Company Limited Foundation Professional">
@endsection
@section('js')
	@parent
<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5a8c2a183c527d001363a686&product=sop' async='async'></script>
<script type="text/javascript">
	 $(document).ready(function() {
    $('.imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:9,
        slideMargin:0,
        enableDrag: false,
        currentPagerPosition:'left',
        responsive : [
            {
                breakpoint:1000,
                settings: {
                    item:1,
                    slideMove:1,
                    slideMargin:6,
                  }
            },
            {
                breakpoint:480,
                settings: {
                    item:1,
                    slideMove:1
                  }
            }
        ],
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide'
            });
        }   
    });  
  });
</script>

@endsection
@section('style')
	@parent

@endsection
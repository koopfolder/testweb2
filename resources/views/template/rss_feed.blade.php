@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    $params_case_items = json_decode($menu->layout_params);
    $rss_feed = ThrcHelpers::getRss(['url'=>$params_case_items->url_rss]);
    //$rss_feed = ThrcHelpers::getRss(['url'=>'http://thrc.hap.com/th/rss/feed/interesting_issues.xml']);
    //dd("Rss Feed",$params_case_items,$rss_feed);
@endphp
@extends('layouts.app')
@section('content')
@include('partials.search')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_inside">
                    <h1>{{ $menu['name'] }}</h1>
                </div>
            </div>
            <div class="row row_learning">
                
                @if($rss_feed)
                        @foreach($rss_feed['items'] AS $key=>$value)
                        @php
                            //dd($value);
                            $image = asset('themes/thrc/images/no-image-icon-3.jpg');
                            if(isset($value->data['child'][""]["image"])){
                                $image = $value->data['child'][""]["image"]["0"]["data"];
                            }else if(isset($value->data['child'][""]["enclosure"])){
                                $image = $value->data['child'][""]["enclosure"]["0"]["attribs"][""]["url"];
                            }

                        @endphp
                        <div class="col-xs-12 col-sm-4 item_learning">
                            <a href="{{ $value->data['child'][""]["link"]["0"]["data"] }}">
                                <figure>
                                    <img src="{{ $image}}"><div class="lmicon icon_document"></div>
                                </figure>
                                <figcaption>
                                        <h1 class="dotmaster">{{ $value->data['child'][""]["title"]["0"]["data"] }}</h1>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->data['child'][""]["pubDate"]["0"]["data"])->format('d/m/').(Carbon\Carbon::parse($value->data['child'][""]["pubDate"]["0"]["data"])->format('Y')+543) }}</div>
                                            <div class="newsview"></div>
                                        </div>
                                        <div class="readmore" href="{{ $value->data['child'][""]["link"]["0"]["data"] }}">อ่านต่อ</div>
                                </figcaption>
                            </a>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>{{ (!empty($menu['meta_title']) ? $menu['meta_title']:$menu['name']) }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $menu['meta_description'] }}">
    <meta name="keywords" content="{{ $menu['meta_keywords'] }}">
    <meta name="author" content="">
@endsection
@section('style')
	@parent
<script>
    $(document).ready(function(){
        $('.owl-lmhl').on('initialized.owl.carousel changed.owl.carousel', function(event){ 
             $(".dotmaster").trigger("update.dot");
        });
        $(".owl-lmhl").owlCarousel({
            loop:true,
            margin:0,
            nav:false,
            dots:true,
             autoplay:true,
            autoplayTimeout:6000,
            //navText: ["<img src='images/owl-left-direction-arrow.svg'>","<img src='images/owl-right-thin-chevron.svg'>"],
            slideBy: 1,
            responsive:{
                0:{
                    items:1,
                    margin:0
                    //slideBy: 3
                },
                500:{
                    items:1
                },
                768:{
                    margin:0,
                    items:1
                }
            }
        }); 


		{{--  $.ajax({
			url: '{{ route("admin.manager.AjaxUpdateOrder") }}',
			type: "POST",
			dataType: 'json',
			data: {_token:jQuery('meta[name="csrf-token"]').attr('content')},
			success:function(response){
				console.log(response);
			}
		});  --}}


    });
</script>
@endsection
@section('js')
	@parent

@endsection
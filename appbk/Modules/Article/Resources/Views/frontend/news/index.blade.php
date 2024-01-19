@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app')
@section('content')
@php
	//dd($data);
@endphp
    <section class="row">
        <div class="col-xs-12 bg_topbar">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>ข่าวประชาสัมพันธ์</h2>
                        <h1>News information</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="row row_overflow">
        <div class="col-xs-12 wrap_content">
            <div class="container wrap_newdwetail">
                <dic class="row">
                    <div class="col-xs-12 newdetail_name">
                        <h1>{{ $data->title }}</h1>
                        <div class="news_boxdate">
                            <div class="list_view">{{ ($data->hit !='' ? $data->hit.' K':'') }}</div>
                            <div class="list_date">{{ Carbon\Carbon::parse($data->created_at)->format('d/m/').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                            <div class="icon_sharesocial">
                                <div class="sharethis-inline-share-buttons"></div>
                            </div>
                        </div>
                    </div>
                </dic>
                <div class="row ctd4">
                    <div class="col-xs-12 col-sm-6 box_text">
                    		<div class="training_detail">
                                {!! $data->description !!}
                            </div>
                    		<!--
                            <div class="training_detail">
                                วันอบรม :  วันพุธที่ 23 มกราคม 2562 <br>
                                เริ่มลงทะเบียน : 12.30 น. <br>
                                เวลาอบรม : 13.00 - 16.30 น. <br>
                                สถานที่ : สำนักงานใหญ่ ไทยแฟรนไชส์เซ็นเตอร์  <br>
                            </div>
                            <p>
                                อยากเปิดร้านค้าในห้างสรรพสินค้าจะต้องให้ความสำคัญกับเรื่องของ "BRAND" เป็นอย่างมาก เพราะในห้างสรรพสินค้านั้น ต่างมีร้านค้าอยู่หลากหลาย โดยมีระดับสินค้าและบริการที่ต้องเป็นมาตรฐาน ซึ่งร้านค้าเหล่านี้เราอาจจะเรียกได้ว่า "RETAIL SHOP"
                            </p>
                            <p>
                                ตราสินค้า (Brand) คือ รูปแบบภาพพจน์ แนวคิดในรูปอัตลักษณ์ คำขวัญ และผลงานการออกแบบของสินค้าหรือผลิตภัณฑ์นั้นๆ โดยจะแสดงออกด้วยสัญลักษณ์ที่จะสื่อถึงบริษัท สินค้า บริการ หรือกลุ่มผู้ขายที่แตกต่างจากคู่แข่งขัน โดยการสร้างตราสินค้าให้เป็นที่จดจำของลูกค้าจะเกิดขึ้นได้จากการโฆษณา การบอกต่อ หรือจากการพบเห็น ดังนั้น การออกแบบ Brand ที่มีเอกลักษณ์โดดเด่นจึงเป็นสิ่งสำคัญของการสร้างแบรนด์
                            </p>
                            <p>
                                วัตถุประสงค์ :  เพื่อสร้างแบรนด์และยอดขายให้กับร้านค้า 
                            </p>
                            <div class="detail_topic">แบรนด์สำคัญอย่างไร? การหาจุดเด่นและจุดแตกต่างของสินค้าหรือผลิตภัณฑ์</div>
                            <p>
                                การนำเสนอสินค้าหรือผลิตภัณฑ์ใดๆ จำเป็นอย่างยิ่งที่จะต้องมีจุดเด่น จุดแตกต่าง หรือจุดที่น่าสนใจของสินค้านั้นๆ เพื่อดึงดูดกับกลุ่มเป้าหมายได้ถูกต้อง และตรงกับความต้องการของลูกค้าจริงๆ ซึ่งจุดเด่นดังกล่าวอาจเป็นสิ่งง่ายๆ แต่พอเพียงที่จะสร้างเหตุผลในการซื้อของลูกค้า การสร้างแบรนด์และการจดจำได้ รวมถึงการเข้าใจในเหตุผลนี้ จึงเป็นสิ่งที่เราต้องค้นหา
                            </p>
                            <div class="detail_topic">Brand Image</div>
                            <p>
                               เป็นภาพที่ลูกค้ามองเข้ามาหาแบรนด์ โดยแบรนด์กับภาพลักษณ์นี้อาจจะไม่ใช่สิ่งที่ต้องดูสวยหรู แต่ต้องมีความชัดเจน และเหมาะกับความต้องการของลูกค้า ดังนั้น จึงจำเป็นที่จะต้องสร้างแบรนด์หรือค้นหาภาพลักษณ์นี้ 
                            </p>
							-->
                    </div>

                    <div class="col-xs-12 col-sm-6 yellow_box">
                        <div class="photoslide_news">
                             <div id="slider" class="flexslider">
							        @if ($data->getMedia('gallery_desktop')->isNotEmpty())
											<ul class="slides">
												@foreach($data->getMedia('gallery_desktop') AS $key=>$data)
													<li>
														<img src="{{ asset($data->getUrl()) }}" />
													</li>
												@endforeach
											</ul>
							        @elseif($data->getMedia('cover_mobile')->isNotEmpty())
							        		<ul class="slides">
							        			<li><img src="{{ asset($data->getMedia('cover_mobile')->first()->getUrl()) }}">
							        			</li>
							        		</ul>
							        @endif
                                </div>
                                <div id="carousel" class="flexslider">
							        @if ($data->getMedia('gallery_desktop')->isNotEmpty())
											<ul class="slides">
												@foreach($data->getMedia('gallery_desktop') AS $key=>$data)
													<li>
														<img src="{{ asset($data->getUrl()) }}" />
													</li>
												@endforeach
											</ul>
							        @elseif($data->getMedia('cover_mobile')->isNotEmpty())
							        		<ul class="slides">
							        			<li><img src="{{ asset($data->getMedia('cover_mobile')->first()->getUrl()) }}">
							        			</li>
							        		</ul>
							        @endif
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="row">
        <div class="col-xs-12 wrap_relatecontent">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>ข่าวประชาสัมพันธ์ที่เกี่ยวข้อง</h2>
                        <div class="owl-morecontent owl-carousel">
                            @if(collect($related_data)->isNotEmpty())
                                @foreach($related_data AS $key=>$value)
                                @php
                                    $value->urlnews= $value->id;
                                    //dd($value);
                                @endphp
                            <div class="item">
                                <a href="{{ $value->urlnews }}" class="related_news">
                                    <figure>
                                        @if ($value->getMedia('cover_desktop')->isNotEmpty())
                                            <img src="{{ asset($value->getMedia('cover_desktop')->first()->getUrl()) }}" class="img-responsive">
                                        @endif
                                    </figure>
                                    <h4 class="dotmaster">{{ $value->title }}</h4>
                                    <div class="news_boxdate">
                                        <div class="list_view">{{ ($value->hit !='' ? $value->hit.' K':'') }}</div>
                                        <div class="list_date">{{ Carbon\Carbon::parse($value->created_at)->format('d/m/').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                    </div>
                                    <div class="desc_morenews dotmaster">
                                        {!! $value->shot_description !!}
                                    </div>
                                    <div class="btn_morenews">อ่านต่อ <img src="{{ asset('themes/dbdfranchise/images/arrow-right.svg') }}" alt=""></div>
                                </a>
                            </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('meta')
    @parent
    <title></title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
@endsection
@section('js')
	@parent
<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5c712326e206ca00119044d6&product=inline-share-buttons' async='async'></script>
<script src="{{ asset('themes/dbdfranchise/owlcarousel/owl.carousel.min.js') }}"></script>
<script defer src="{{ asset('themes/dbdfranchise/flexslider/jquery.flexslider.js') }}"></script>
<script type="text/javascript" src="{{ asset('themes/dbdfranchise/flexslider/demo/js/shCore.js') }}"></script>
<script type="text/javascript" src="{{ asset('themes/dbdfranchise/flexslider/demo/js/shBrushJScript.js') }}"></script>
    
<script type="text/javascript">
$(function(){
  SyntaxHighlighter.all();
});
$(window).on( "load", function() {
  $('#carousel').flexslider({
    animation: "slide",
    controlNav: false,
    directionNav: true,
    animationLoop: false,
    slideshow: false,
    itemWidth: 132,
    itemMargin: 10,
    minItems: 4,
    maxItems: 4,
    asNavFor: '#slider',
    start: function(slider){
        if (Modernizr.mq('(max-width: 767px)')) {
            $('.box_text').css('margin-top', $('.yellow_box').outerHeight() + 50);  
        }
	}
  });
 
  $('#slider').flexslider({
    animation: "slide",
    controlNav: false,
    directionNav: true,
    animationLoop: false,
    slideshow: false,
    sync: "#carousel",
    start: function(slider){
        if (Modernizr.mq('(max-width: 767px)')) {
            $('.box_text').css('margin-top', $('.yellow_box').outerHeight() + 50);  
        }
	}
  });
});
</script>

<script>
$(document).ready(function(){
    $(".owl-morecontent").on('changed.owl.carousel initialized.owl.carousel', function(event) {
        $(".dotmaster").trigger("update.dot");
    }).owlCarousel({
        loop:false,
        //margin:20,
        nav:false,
        dots:false,
        autoplay:false,
        rewind: true,
        autoplayTimeout:5000,
        slideBy: 1,
        responsive:{
            0:{
                items:2,
                margin:25
            },
            575:{
                margin:40,
                items:3
            },
            992:{
                margin:40,
                items:4
            },
            1025:{
                margin:40,
                items:4
            }
        }
    });
    
});    

</script>
@endsection
@section('style')
	@parent
	<link rel="stylesheet" href="{{ asset('themes/dbdfranchise/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/dbdfranchise/owlcarousel/assets/owl.theme.default.min.css') }}">
   	<link rel="stylesheet" href="{{ asset('themes/dbdfranchise/flexslider/flexslider.css') }}" type="text/css" media="screen" />
    <style>
        .flexslider{
           background-color: transparent;
            margin-bottom: 13px;
        }
        .slides li{
            cursor: pointer;
        }
         
    </style>
@endsection
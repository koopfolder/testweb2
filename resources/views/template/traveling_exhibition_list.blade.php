@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
@endphp
@if(method_exists('App\Modules\Exhibition\Http\Controllers\FrontendController', 'getDataListExhibition'))   
    @php
        //dd($menu);
        $data = App\Modules\Exhibition\Http\Controllers\FrontendController::getDataListExhibition(['page_layout'=>'traveling_exhibition','title'=>$menu->name]);  
        //dd($data['items']->count());
    @endphp
@endif
@extends('layouts.app')
@section('content')
@include('partials.search')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_inside">
                    <h1>{{ $data['title_h1'] }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 wrap_lmhl">
                   <div class="owl-lmhl owl-carousel owl-theme">

                       @if($data['items']->count())
                            @foreach($data['items'] AS $key=>$value)
                                @php
                                    //dd($value);
                                @endphp
                                @if($key == 0)
                                    <a href="{{ $value['url'] }}" class="item_lmhl">
                                        <figure>
                                            <img src="{{ $value['cover_desktop'] }}"><div class="lmicon"></div>
                                        </figure><figcaption>
                                            <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                            <div class="dateandview">
                                                <div class="newsdate">{{ Carbon\Carbon::parse(($value['updated_at'] != null ? $value['updated_at']:$value['created_at']))->format('d.m.').(Carbon\Carbon::parse(($value['updated_at'] != null ? $value['updated_at']:$value['created_at']))->format('Y')+543) }}</div>
                                                <div class="newsview">{{ number_format(number_format($value['hit']))  }}</div>
                                            </div>
                                            {!! $value['description'] !!}
                                            <p class="dotmaster"></p>
                                           <div class="lmhl_readmore" href="{{ $value['url'] }}">อ่านต่อ</div>
                                        </figcaption>
                                   </a>
                                @php
                                    break;
                                @endphp
                                @endif
                            @endforeach
                       @endif

                    </div>
                </div>
            </div>
            <div class="row row_learning">
                
                @if($data['items']->count())
                    @foreach($data['items'] AS $key=>$value)
                        @php
                            //dd($value);
                        @endphp
                        @if($key > 0)
                        <div class="col-xs-12 col-sm-4 item_learning">
                            <a href="{{ $value['url'] }}">
                                <figure>
                                    <img src="{{ $value['cover_desktop'] }}"><div class="lmicon icon_document"></div>
                                </figure>
                                <figcaption>
                                        <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse(($value['updated_at'] != null ? $value['updated_at']:$value['created_at']))->format('d.m.').(Carbon\Carbon::parse(($value['updated_at'] != null ? $value['updated_at']:$value['created_at']))->format('Y')+543) }}</div>
                                            <div class="newsview">{{ number_format($value['hit']) }}</div>
                                        </div>
                                        <div class="readmore" href="{{ $value['url'] }}">อ่านต่อ</div>
                                </figcaption>
                            </a>
                        </div>
                        @endif
                    @endforeach
                @endif

            </div>
            @if($data['items']->count() == 0)
                <div class="dateandview" style="text-align:center;">
                        ไม่พบผลลัพธ์ที่ค้นหา
                </div>  
            @endif            
            <div class="row">
                <div class="col-xs-12 wrap_btn_viewmore">
                    <button class="btn_viewmore">View More <img src="{{ asset('themes/thrc/images/angle-arrow-down.svg') }}"></button>
                </div>
            </div>
        </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>{{ (!empty($menu->meta_title) ? $menu->meta_title:$menu->name) }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $menu->meta_description }}">
    <meta name="keywords" content="{{ $menu->meta_keywords }}">
    <meta name="author" content="สำนักงานกองทุนสนับสนุนการสร้างเสริมสุขภาพ (สสส.)">
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
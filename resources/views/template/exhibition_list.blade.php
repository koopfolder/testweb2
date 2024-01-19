@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd($menu);
@endphp
@if(method_exists('App\Modules\Exhibition\Http\Controllers\FrontendController', 'getDataListExhibition'))   
    @php
        $data = App\Modules\Exhibition\Http\Controllers\FrontendController::getDataListExhibition($menu);  
        //dd($data['items_is_closed_to_visitors']->count());
    @endphp
@endif
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
            <div class="row">
                <div class="col-xs-12 wrap_lmhl">
                   <div class="owl-lmhl owl-carousel owl-theme">

                       @if($data['items_open_to_watch']->count())
                            @foreach($data['items_open_to_watch'] AS $key=>$value)
                                @php
                                    //dd($value);
                                @endphp
                                    <a href="{{ $value['url'] }}" class="item_lmhl item_newmedia">
                                        <figure>
                                            <div><img src="{{ $value['cover_desktop'] }}"><div class="lmicon icon_document"></div></div>
                                        </figure><figcaption>
                                            <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                            <div class="dateandview">
                                                <div class="newsdate">{{ Carbon\Carbon::parse($value['created_at'])->format('d.m.').(Carbon\Carbon::parse($value['created_at'])->format('Y')+543) }}</div>
                                                <div class="newsview">{{ number_format($value['hit']) }}</div>
                                            </div>
                                            {!! $value['description'] !!}
                                            <p class="dotmaster"></p>
                                           <div class="lmhl_readmore" href="{{ $value['url'] }}">อ่านต่อ</div>
                                        </figcaption>
                                   </a>
                            @endforeach
                       @endif

                    </div>
                </div>
            </div>
            <div class="row row_learning">
                
                @if($data['items_is_closed_to_visitors']->count())
                    @foreach($data['items_is_closed_to_visitors'] AS $key=>$value)
                        @php
                            //dd($value);
                            if(!empty($value->url_external) && empty($value->file_path)){
                                $value['url'] = $value->url_external;
                            }else if(!empty($value->file_path) && empty($value->url_external)){
                                $value['url'] = asset($value->file_path);
                            }else if (empty($value->file_path) && empty($value->url_external)){
                                $value['url'] = route('exhibition-detail',$value->slug);
                            }

                                $value['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                                //dd($value);
                        @endphp
                        <div class="col-xs-12 col-sm-4 item_learning">
                            <a href="{{ $value['url'] }}">
                                <figure>
                                    <img src="{{ $value['cover_desktop'] }}"><div class="lmicon icon_document"></div>
                                </figure>
                                <figcaption>
                                        <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value['created_at'])->format('d.m.').(Carbon\Carbon::parse($value['created_at'])->format('Y')+543) }}</div>
                                            <div class="newsview">{{ number_format($value['hit']) }}</div>
                                        </div>
                                        <div class="readmore" href="{{ $value['url'] }}">อ่านต่อ</div>
                                </figcaption>
                            </a>
                        </div>
                    @endforeach
                @endif

            </div>
            @if($data['items_open_to_watch']->count() == 0)
                <div class="dateandview" style="text-align:center;">
                        ไม่พบผลลัพธ์ที่ค้นหา
                </div>  
            @endif            
            <div class="row">
                <div class="col-xs-12 wrap_btn_viewmore">
                    {!! isset($data['items_is_closed_to_visitors'])? $data['items_is_closed_to_visitors']->appends(\Input::all())->render():'' !!}  
                </div>
            </div>
        </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>{{ ($menu->meta_title !='' ? $menu->meta_title:$menu->name) }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $menu->meta_description }}">
    <meta name="keywords" content="{{ $menu->meta_keywords }}">
    <meta name="author" content="THRC">
@endsection
@section('style')
	@parent
    <style>
        .item_newmedia.item_lmhl figure{
            width: 35%;
        }
        .item_newmedia.item_lmhl figure > div{
            position: relative;
            padding-bottom: 58%;
        }
        .owl-carousel .owl-item img{
            position: absolute;
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            top: 50%;
            left: 50%;
            -ms-transform: translate( -50%, -50%);
            -webkit-transform: translate( -50%, -50%);
            transform: translate( -50%, -50%);
        }
        .item_newmedia.item_lmhl figcaption{
            width: 65%;
        }
        .item_newmedia.item_lmhl figcaption p{
            height: 7.2em;
        }
        .item_learning figure{
            padding-bottom: 58%;
        }
        .item_learning figure img{
            position: absolute;
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            top: 50%;
            left: 50%;
            -ms-transform: translate( -50%, -50%);
            -webkit-transform: translate( -50%, -50%);
            transform: translate( -50%, -50%);
        }
        .owl-lmhl.owl-theme .owl-nav.disabled+.owl-dots{
            width: 35%;
        }
        @media (max-width: 767px){
            .owl-lmhl.owl-theme .owl-nav.disabled+.owl-dots{
                width: 100%;
            }
            .item_newmedia.item_lmhl figure{
                width: 100%;
            }
            .item_newmedia.item_lmhl figcaption{
                width: 100%;
            }
        }
    </style>
@endsection
@section('js')
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

    });
</script>
@endsection
@inject('request', 'Illuminate\Http\Request')
@php
	//dd("Test");
@endphp
@if(method_exists('App\Modules\Article\Http\Controllers\ThaihealthWatchController', 'getDataThaihealthWatchPointsToWatchArticle')) 
    @php
        $data = App\Modules\Article\Http\Controllers\ThaihealthWatchController::getDataThaihealthWatchPointsToWatchArticle($request->all());  
        //dd($data,$request->all());
    @endphp
@endif
@extends('layouts.app_thaihealth_watch')
@section('content')
@include('partials.search')
<div id="app"></div>
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_inside2">
                    <h1>ประเด็นที่น่าจับตา บทความ</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 wrap_lmhl">
                   <div class="owl-lmhl owl-carousel owl-theme">

                    @if($data['items']->count())
                        @foreach($data['items'] AS $key=>$value)
                            @php
                                //dd($menu);
                                //dd($value);
                                $value->url = route('thaihealthwatch-detail',$value->slug);
                                //$value->cover_desktop = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');

                                if($value->getMedia('cover_desktop')->isNotEmpty()){
                                    $value->cover_desktop = asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                                }else if($value->dol_cover_image !=''){
                                    $value->cover_desktop = $value->dol_cover_image;
                                }else{
                                    $value->cover_desktop = asset('themes/thrc/images/no-image-icon-3.jpg');
                                }

                            @endphp
                            @if($key == 0)
                                <a href="{{ $value->url }}" class="item_lmhl">
                                    <figure>
                                        <img src="{{ $value->cover_desktop }}" class="img-responsive"><div class="lmicon"></div>
                                    </figure><figcaption>
                                        <h1 class="dotmaster">{{ $value->title }}</h1>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ number_format($value->hit) }}</div>
                                        </div>
                                        {!! $value['description'] !!}
                                        <p class="dotmaster"></p>
                                    <div class="lmhl_readmore" href="{{ $value->url }}">อ่านต่อ</div>
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
                            $value->url = route('thaihealthwatch-detail',$value->slug);
                            //$value->cover_desktop = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                            if($value->getMedia('cover_desktop')->isNotEmpty()){
                                $value->cover_desktop = asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                            }else if($value->dol_cover_image !=''){
                                $value->cover_desktop = $value->dol_cover_image;
                            }else{
                                $value->cover_desktop = asset('themes/thrc/images/no-image-icon-3.jpg');
                            }
                        @endphp
                        @if($key > 0)
                        <div class="col-xs-12 col-sm-4 item_learning">
                            <a href="{{ $value->url }}">
                                <figure>
                                    <img src="{{ $value->cover_desktop }}" class="img-responsive"><div class="lmicon icon_document"></div>
                                </figure>
                                <figcaption>
                                        <h1 class="dotmaster">{{ $value->title }}</h1>
                                        <div class="dateandview2">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ number_format($value->hit) }}</div>
                                        </div>
                                        <div class="readmore" href="{{ $value->url }}">อ่านต่อ</div>
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
                    {!! isset($data['items'])?$data['items']->appends(\Input::all())->render():'' !!}  
                </div>
            </div>
        </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>ThaihealthWatch</title>
    <meta charset="UTF-8">
    <meta name="description" content="ThaihealthWatch">
    <meta name="keywords" content="ThaihealthWatch">
    <meta name="author" content="">
@endsection
@section('style')
	@parent

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
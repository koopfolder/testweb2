@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
@endphp
@if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getDataMediaWebView'))   
    @php
        $data = App\Modules\Api\Http\Controllers\FrontendController::getDataMediaWebView($request->all());  
        //dd($data['items']->total());

        // if($data['items']->hasMorePages() === true){
        //     echo "Case True ".$data['items']->hasMorePages();
        // }else{
        //     echo "Case False ".$data['items']->hasMorePages();
        // }
        //dd($data['items']->count(),gettype($data['items']),$request->all());
        //$data['items']->hasMorePages() === false
    @endphp
@endif
@extends('layouts.app_web_view')
@section('content')
@include('partials.search_web_view')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_sp">
                <div class="col-xs-12 head_inside">
                    <h1>ผลการค้นหา</h1>
                </div>
                <div class="col-xs-6 col-sm-7 sp_result_text">@if($data['items']->total()) {{ number_format($data['items']->total()).' รายการ' }} @endif</div>
                <div class="col-xs-6 col-sm-5 sp_display">
                    <span>แสดงผล</span>
                    <div class="show_spcolumn active">
                        <img src="{{ asset('themes/thrc/images/icon_column.svg') }}">
                    </div>
                    <div class="show_splist">
                        <img src="{{ asset('themes/thrc/images/icon_text-lines.svg') }}"></div>
                </div>
            </div>
            <div class="row row_learning row_sp_column">
                
                @if($data['items']->total())
                    @foreach($data['items'] AS $key=>$value)
                        @php
                            
                            
                            $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                            $model_type='App\Modules\Api\Models\ListMedia';
                            if($value->article_type =='media'){
                                $value->url = route('media-detail-webview',Hashids::encode($value->id));
                            }else{
                                $model_type='App\Modules\Article\Models\Article';
                                $value->url = route('article-detail-webview',$value->slug);
                            }
                            

                            if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                //dd($value->cover_desktop,$model_type);
                                $value->cover_desktop = (isset($value->cover_desktop->id)  ?  asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                            }

                            //dd($value);

                            switch ($value->template) {
                                case 'Application':
                                    $icon = 'icon_webmedia';
                                    break;
                                case 'KnowledgePackage':
                                    $icon = 'icon_book';
                                    break;
                                case 'Multimedia':
                                    $icon = 'icon_vdomedia';
                                    break;
                                case 'Text':
                                    $icon = 'icon_book';
                                    break;
                                case 'Visual':
                                    $icon = 'icon_imagesmedia';
                                    break;
                                
                                default:
                                    $icon = 'icon_book';
                                    break;
                            }

                        @endphp
                        <div class="col-xs-12 col-sm-4 item_learning">
                            <a href="{{ $value->url }}">
                                <figure>
                                    <img src="{{ $value->cover_desktop }}" alt="{{ $value->title }}" class="img-responsive">
                                    <div class="lmicon {{ $icon }}"></div>
                                </figure>
                                <figcaption>
                                        <h1 class="dotmaster">{{ $value->title }}</h1>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ number_format($value->hit) }}</div>
                                        </div>
                                        <div class="readmore" href="{{ $value->url }}">อ่านต่อ</div>
                                </figcaption>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row row_learning row_sp_list">

                @if($data['items']->total())
                    @foreach($data['items'] AS $key=>$value)
                        @php
                            //dd($value);
                            $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                            $model_type='App\Modules\Api\Models\ListMedia';
                            if($value->article_type =='media'){
                                $value->url = route('media-detail-webview',Hashids::encode($value->id));
                            }else{
                                $model_type='App\Modules\Article\Models\Article';
                                $value->url = route('article-detail-webview',$value->slug);
                            }
                            

                            if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                //dd($value->cover_desktop,$model_type);
                                $value->cover_desktop = (isset($value->cover_desktop->id)  ?  asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                            }
                    @endphp                
                    <div class="col-xs-12 item_learning">
                        <a href="{{ $value->url }}">
                            <div class="col-xs-2">
                                <figure style="margin-top:20px;">
                                    <img src="{{ $value->cover_desktop }}" alt="{{ $value->title }}" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-10">
                            <figcaption>
                                <h1 class="dotmaster">{{ $value->title }}</h1>
                                    <div class="dateandview">
                                        <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                        <div class="newsview">{{ number_format($value->hit) }}</div>
                                    </div>
                                    <p class="dotmaster">
                                        {{ strip_tags($value->description) }}
                                    </p>
                                    <div class="readmore" href="{{ $value->url }}">อ่านต่อ</div>
                            </figcaption>
                            </div>
                        </a>
                    </div> 
                    @endforeach
                @endif                     
            </div>

            @if($data['items']->total() == 0)
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
    <title>ผลการค้นหา</title>
    <meta charset="UTF-8">
    <meta name="description" content="ผลการค้นหา">
    <meta name="keywords" content="ผลการค้นหา">
    <meta name="author" content="THRC">
@endsection
@section('style')
	@parent
<style>
        .box_search{
            margin-top: 15px;
            background-color: #eee;
            border-radius: 7px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .btn_search_sp{
            background-color: #ef7e25;
            width: 100%;
            height: 35px;
            text-align: center;
            color: #fff;
            border: 0;
        }
        .btn_search_sp img{
            width: 15px;
            height: auto;
            margin-right: 5px;
        }
        .sp_result_text{
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 15px;
            padding-left: 50px;
        }
        .sp_display{
            text-align: right;
            margin-bottom: 25px;
        }
        .sp_display span{
            display: inline-block;
            font-size: 1.1rem;
            color: #333;
            vertical-align: middle;
        }
        .sp_display div{
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
            cursor: pointer;
        }
        .sp_display div img{
            display: block;
            width: 22px;
            height: auto;
            -webkit-filter: grayscale(100%);
            filter: grayscale(100%);
        }
        .sp_display div.active img{
            -webkit-filter: grayscale(0%);
            filter: grayscale(0%);
        }
        .item_learning p{
            height: 2em;
            line-height: 1;
            color: #666;
        }
        .row_sp_list .item_learning{
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .row_sp_list{
            display: none;
            margin-bottom: 70px;
        }
        @media (max-width: 767px) {
            .btn_search_sp, .btn_reset{
                margin-top: 15px;
            }
            .box_search{
                margin-top: 10;
                padding: 15px;
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

        $( '.show_spcolumn' ).click(function (event) {
          if (  $( ".row_sp_column" ).is( ":hidden" ) ) {
              $('.show_splist').removeClass('active');
              $(this).addClass('active');
              $('.row_sp_list').hide();
              $('.row_sp_column').fadeIn();
              $(".dotmaster").trigger("update.dot");
          } else {
              
          }
          event.stopPropagation();
        });
        $( '.show_splist' ).click(function (event) {
          if (  $( ".row_sp_list" ).is( ":hidden" ) ) {
              $('.show_spcolumn').removeClass('active');
              $(this).addClass('active');
              $('.row_sp_column').hide();
              $('.row_sp_list').fadeIn();
              $(".dotmaster").trigger("update.dot");
          } else {
              
          }
          event.stopPropagation();
        });

    });
</script>
@endsection
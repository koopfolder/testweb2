@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app')
@section('content')
@php
    $segment_2 = \Request::segment(2);
    $segment_3 = \Request::segment(3);
	//dd($data);
    
@endphp
@if(method_exists('App\Modules\Exhibition\Http\Controllers\FrontendController', 'getDetailID'))   
    @php
        $response = App\Modules\Exhibition\Http\Controllers\FrontendController::getDetailID($menu);
    @endphp
@endif
@include('partials.search')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_content">
                <div class="col-xs-12 col-sm-8 left_content">
                    <div class="head_inside">
                        <h1>{{ $menu->name }}</h1>
                    </div>
                    <div class="dateandview">
                        <div class="newsdate">{{ Carbon\Carbon::parse(($response['data']->updated_at != null ? $response['data']->updated_at:$response['data']->created_at))->format('d.m.').(Carbon\Carbon::parse(($response['data']->updated_at != null ? $response['data']->updated_at:$response['data']->created_at))->format('Y')+543) }}</div>
                        <div class="newsview">{{ $response['data']->hit }}</div>
                        <div class="sharesym">Share</div>
                        <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                        <div class="icon_book"></div>
                    </div>
                    <figcaption class="text_detail_book">
                         {!! $response['data']->description !!}
                    </figcaption>
                </div>
                <div class="col-xs-12 col-sm-4">
                    
                    <h1 class="head_right">Related</h1>
                    <div class="mCustomScrollbar relatescrollbar">
                        @if($response['related_data']->count())
                            @foreach($response['related_data'] AS $key =>$value)
                            @php
                                if(!empty($value->url_external) && empty($value->file_path)){
                                    $value['url'] = $value->url_external;
                                }else if(!empty($value->file_path) && empty($value->url_external)){
                                    $value['url'] = asset($value->file_path);
                                }else if (empty($value->file_path) && empty($value->url_external)){
                                    $value['url'] = route('exhibition-detail',$value->slug);
                                }

                                $value->cover_desktop = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}">
                                    <figure>
                                        <img data-src="{{ $value->cover_desktop }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ $value->hit }}</div>
                                        </div>
                                    </figcaption>
                                </a>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <h1 class="head_right">Most View</h1>
                    <div class="mCustomScrollbar mostviewscrollbar">
                        @if($response['most_view_data']->count())
                            @foreach($response['most_view_data'] AS $key =>$value)
                            @php
                                if(!empty($value->url_external) && empty($value->file_path)){
                                    $value['url'] = $value->url_external;
                                }else if(!empty($value->file_path) && empty($value->url_external)){
                                    $value['url'] = asset($value->file_path);
                                }else if (empty($value->file_path) && empty($value->url_external)){
                                    $value['url'] = route('exhibition-detail',$value->slug);
                                }
                                $value->cover_desktop = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}">
                                    <figure>
                                        <img data-src="{{ $value->cover_desktop }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ $value->hit }}</div>
                                        </div>
                                    </figcaption>
                                </a>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <h1 class="head_right">Recommend</h1>
                    <div class="mCustomScrollbar recommendscrollbar">
                        @if($response['recommend']->count())
                            @foreach($response['recommend'] AS $key =>$value)
                            @php
                                if(!empty($value->url_external) && empty($value->file_path)){
                                    $value['url'] = $value->url_external;
                                }else if(!empty($value->file_path) && empty($value->url_external)){
                                    $value['url'] = asset($value->file_path);
                                }else if (empty($value->file_path) && empty($value->url_external)){
                                    $value['url'] = route('exhibition-detail',$value->slug);
                                }
                                $value->cover_desktop = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}">
                                    <figure>
                                        <img data-src="{{ $value->cover_desktop }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ $value->hit }}</div>
                                        </div>
                                    </figcaption>
                                </a>
                            </div>
                            @endforeach
                        @endif
                    </div>


                </div>
            </div>
        </div>
    </section>
<!--
    <section class="row wow fadeInDown bg_relate">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_inside">
                    <h1>ลิงค์ที่เกี่ยวข้อง</h1>
                </div>
            </div>
            <div class="row row_relate">
                <div class="col-xs-12 col-sm-4 item_relate">
                    <a href="#">
                        <figure><img src="{{ asset('themes/thrc/images/article_relate01.jpg') }}">
                        </figure><figcaption>
                            <h1 class="dotmaster">การพนันแฝงในงานวัด</h1>
                            <p class="dotmaster">กระบวนการเปลี่ยนสภาพของกิจกรรมงานวัด และผลกระทบที่มีต่อพฤติกรรมการเล่นพนัน ของเยาวชนไทย</p>
                            <div class="readmore" href="#">อ่านต่อ</div>
                        </figcaption>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 item_relate">
                    <a href="#">
                        <figure><img src="{{ asset('themes/thrc/images/article_relate02.jpg') }}">
                        </figure><figcaption>
                            <h1 class="dotmaster">รายงานสถานการณ์ผู้สูงอายุไทย พ.ศ.2552</h1>
                            <p class="dotmaster">จากข้อมูลของรายงานสถานการณ์ที่ประมวลโดยหน่วย
งานต่างๆ ทั้งหมด</p>
                            <div class="readmore" href="#">อ่านต่อ</div>
                        </figcaption>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 item_relate">
                    <a href="#">
                        <figure><img src="{{ asset('themes/thrc/images/article_relate03.jpg') }}">
                        </figure><figcaption>
                            <h1 class="dotmaster">มโนทัศน์ใหม่ นิยามผู้สูงอายุและการขยายอายุ</h1>
                            <p class="dotmaster">สังคมไทยได้ก้าวสู่ความท้าทายใหม่ในการเข้าสู่สังคมผู้
สูงวัยอย่างเต็มรูปแบบแล้ว ด้วยภาวะสุขภาพของ...</p>
                            <div class="readmore" href="#">อ่านต่อ</div>
                        </figcaption>
                    </a>
                </div>
            </div>
        </div>
    </section>
-->
@endsection
@section('meta')
    @parent
    <title>{{ ($menu->meta_title !='' ? $menu->meta_title :$menu->title) }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $menu->meta_description }}">
    <meta name="keywords" content="{{ $menu->meta_keywords }}">
    <meta name="author" content="THRC">

    <meta property="og:url"    content="{{  Request::url() }}" />
    <meta property="og:type"   content="article" />
    <meta property="og:title"  content="{{ ($menu->meta_title !='' ? $menu->meta_title :$menu->title) }}" />
    <meta property="og:description" content="{{ $menu->meta_description }}" />
    <meta property="og:image" content="{{ $value->cover_desktop  }}" />

@endsection
@section('js')
	@parent
<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5d627dd20388510012a26233&product=inline-share-buttons' async='async'></script>
<script>
    $(document).ready(function($) {
        $(".mCustomScrollbar").mCustomScrollbar({
            callbacks:{
                onScrollStart:function(){
                  $(".dotmaster").trigger("update.dot");
                }
            }
        });

        $('.sharesym').click(function(event) {
            $('.sharethis-inline-share-buttons,.st-btn').toggle('slow');
        });
    });
</script>
@endsection
@section('style')
	@parent
<style type="text/css">
    .sharethis-inline-share-buttons{
        padding: 5px;
        /*display: none !important;*/
    }
</style>
@endsection
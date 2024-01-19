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
@include('partials.search')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_content">
                <div class="col-xs-12 col-sm-8 left_content">
                    <div class="head_inside">
                        <h1>{{ $data->title }}</h1>
                    </div>
                    <div class="dateandview">
                        <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                        <div class="newsview">{{ $data->hit }}</div>
                        <div class="sharesym">Share</div>
                        <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                        <div class="icon_book"></div>
                    </div>
                    <figcaption class="text_detail_book">
                        {!! $data->description !!}
                    </figcaption>
                    @php
                        $date = date('Y-m-d H:i:s');
                        $start_date = $data->start_date;
                        $end_date = $data->end_date;
                        $data['hashid'] = $data->id;
                    @endphp
                    @if(strtotime($start_date) <= strtotime($date) && strtotime($end_date) >= strtotime($date))
                    <div class="book_list">
                        <figcaption>
                            <div class="wrap_btn_bd">
                                <a href="{{ route('book-an-exhibition',$data['hashid']) }}" class="btn_dbcontact">จองนิทรรศการ</a>
                            </div>
                        </figcaption>
                    </div>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-4">
                    
                    <h1 class="head_right">Related</h1>
                    <div class="mCustomScrollbar relatescrollbar">
                        @if($related_data->count())
                            @foreach($related_data AS $key =>$value)
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
                                        <img src="{{ $value->cover_desktop }}">
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
                        @if($most_view_data->count())
                            @foreach($most_view_data AS $key =>$value)
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
                                        <img src="{{ $value->cover_desktop }}">
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
                        @if($recommend->count())
                            @foreach($recommend AS $key =>$value)
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
                                        <img src="{{ $value->cover_desktop }}">
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
    <title>{{ $data->title }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $data->short_description }}">
    <meta name="keywords" content="{{ $data->title }}">
    <meta name="author" content="THRC">

    <meta property="og:url"    content="{{  Request::url() }}" />
    <meta property="og:type"   content="article" />
    <meta property="og:title"  content="{{ $data->title }}" />
    <meta property="og:description" content="{{ $data->short_description }}" />
    <meta property="og:image" content="{{ ($data->getMedia('cover_desktop')->isNotEmpty() ? asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'))  }}" />

@endsection
@section('js')
	@parent
<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5d627dd20388510012a26233&product=inline-share-buttons' async='async'></script>
<script type="text/javascript">

    $(document).ready(function($) {
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
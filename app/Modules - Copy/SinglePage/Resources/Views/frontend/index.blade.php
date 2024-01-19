@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app')
@section('content')
@include('partials.search')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_content">
                <div class="col-xs-12 col-sm-12 left_content">
                    <div class="head_inside">
                        <h1>{{ $data->title }}</h1>
                    </div>
                    <div class="dateandview">
                        <div class="newsdate">{{ Carbon\Carbon::parse(($data->updated_at != null ? $data->updated_at:$data->created_at))->format('d.m.').(Carbon\Carbon::parse(($data->updated_at != null ? $data->updated_at:$data->created_at))->format('Y')+543) }}</div>
                        <div class="newsview">{{ number_format($data->hit) }}</div>
                        <div class="sharesym">Share</div>
                        <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                        <div class="icon_book"></div>
                    </div>
                    <figcaption class="text_detail_book">
                         {!! $data->description !!}
                    </figcaption>
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
    <title>{{ ($data->meta_title !='' ? $data->meta_title :$data->title) }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $data->meta_description }}">
    <meta name="keywords" content="{{ $data->meta_keywords }}">
    <meta name="author" content="THRC">

    <meta property="og:url"    content="{{  Request::url() }}" />
    <meta property="og:type"   content="article" />
    <meta property="og:title"  content="{{ ($data->meta_title !='' ? $data->meta_title :$data->title) }}" />
    <meta property="og:description" content="{{ $data->meta_description }}" />
    <meta property="og:image" content="{{ asset('themes/thrc/images/no-image-icon-3.jpg')  }}" />
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
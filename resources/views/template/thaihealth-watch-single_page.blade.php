@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app_thaihealth_watch')
@section('content')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="col-xs-12 banner-detail">
                @if($data->getMedia('cover_desktop')->isNotEmpty())
                <img src="{{ asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}">
                @endif
            </div>
        </div>
    </section>
    <section class="row">
        <div class="container">
            <div class="row row-about">
                <div class="col-xs-12 col-md-12 about-thwatch wow fadeInLeft">
                    <h1>{{ $data->title }}</h1>
                    {!! $data->description !!}
                </div>

                <!--<div class="col-xs-12 col-md-6 about-img wow fadeInRight">
                    <div><img src="{{ asset('themes/thrc/thaihealth-watch/images/Path 3286.png') }}" alt=""></div>
                </div>
                <div class="col-xs-12 text-detail">
                    <p>สังคมในปัจจุบันนั้นมีความเปลี่ยนแปลงไปอย่างรวดเร็วในทุกมิติ โดยเฉพาะมิติทางสังคมและสิ่งแวดล้อมอันเนื่องมาจากกระแสโลกาภิวัตน์ที่เข้มข้นอย่างต่อเนื่อง ส่งผลให้เกิดการเคลื่อนย้ายของประชากรและ สังคมอย่างเสรีมากขึ้นกว่าเมื่อก่อน อีกทั้งยังส่งผลถึงบริบททางเศรษฐกิจของประเทศไทยอีกด้วย จากเดิมที่เคยเป็นเศรษฐกิจสังคมอุตสาหกรรมก็ก าลังจะเปลี่ยนผ่านเป็นเศรษฐกิจสังคมดิจิทัล ดังนั้นบริบท ทางสังคมในแง่ของการด ารงชีวิตของประชากรไทยก็ต้องมีการปรับเปลี่ยนตามไปด้วย จึงจะเห็นได้ว่าสังคมไทยนั้นได้มีการพลวัตอยู่อย่างต่อเนื่องในทางกลับกันคุณภาพชีวิตของคนไทยและการรับมือกับการ เปลี่ยนแปลงที่เกิดขึ้นอย่างรวดเร็วและเข้มข้นนี้กลับยังไม่มีคุณภาพเท่าที่ควร ด้วยเหตุเหล่านี้จึงได้ส่งผลกระทบต่อระบบสุขภาพของคนไทยทั้งทางตรงและทางอ้อมโดยปัจจัยหลักๆที่มีผลกระทบต่อบริบท สุขภาพคนไทย</p>
                    <img src="images/2.png" alt="">
                    <p>ขณะที่ประชากรวัยเด็กและวัยแรงงานจะมีจ านวนลดลง โดยกลุ่มวัยเด็กจะลดลงอย่างรวดเร็วจาก 11.79ล้านคนในปี 2558 เหลือเพียง 8.17 ล้านคนในปี 2583 ส่วนกลุ่มวัยท างานมีแนวโน้มลดลงจาก 43.0 ล้านคน เป็น35.2 ล้านคนในช่วงเวลาเดียวกัน และยังมีปัญหาผลิตผลจากแรงงานต่ า นอกจากนี้คนไทยไม่นิยมท างานระดับล่างท าให้ต้องพึ่งการน าเข้าแรงงานจากประเทศเพื่อนบ้าน เป็นช่องทางท าให้เกิด ปัญหาโรคและภัยสุขภาพเพิ่มมากขึ้นส่วนกลุ่มผู้สูงอายุมีแนวโน้มเพิ่มขึ้นจาก 10.3 ล้านคน (ร้อยละ 16.2) ในปี 2558 เป็น 20.5 ล้านคน (ร้อยละ 32.1)ในปี 2583 ท าให้มีผู้ป่วยโรคเรื้อรังต่างๆ เพิ่มมากขึ้น สะท้อนภาระค่าใช้จ่ายทางสุขภาพ ขณะเดียวกันผู้สูงอายุ านวนมากมีรายได้ไม่พอต่อการยังชีพ</p>
                </div>-->

                <div class="col-xs-12 detail-socail">
                    <!--<a href="#" class="fb-icon">
                        <img src="{{ asset('themes/thrc/thaihealth-watch/images/facebook_icon.svg') }}" alt="">Facebook
                    </a>
                    <a href="#" class="line-icon">
                        <img src="{{ asset('themes/thrc/thaihealth-watch/images/line_icon.svg') }}" alt="">Line
                    </a>
                    <a href="#" class="mail-icon">
                        <img src=" {{ asset('themes/thrc/thaihealth-watch/images/email_icon.svg') }}" alt="">E-mail
                    </a>-->
                    <div class="sharethis-inline-share-buttons"></div>
                </div>
            </div>
        </div>
    </section>
    <!--
    <section class="row wow fadeInDown bg_related">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_related">
                    <h2>บทความที่่คล้ายกัน</h2>
                </div>
                <div class="col-xs-12">
                    <div class="related-carousel owl-carousel owl-theme">
                        <a href="#" class="item_related">
                            <figure>
                                <div><img src="{{ asset('themes/thrc/thaihealth-watch/images/2.png') }}"></div>
                                <figcaption>
                                    <h5>องค์การอนามัยโลก(WHO) เล็งเห็นว่ากลุ่มโรค NCDs นั้น ถือเป็นปัญหาใหญ่ที่กำลังทวีความรุนแรงขึ้น...</h5>
                                    <p>สำหรับประเทศไทยเอง สถิติล่าสุดพบว่ามีถึง 14 ล้านคนที่เป็นโรค ในกลุ่มโรค NCDs และที่สำคัญยังถือเป็นสาเหตุหลักการเสียชีวิต ของ ประชากรทั้งประเทศ โดยจากสถิติปี พ.ศ. 2552</p>
                                    <div class="dateandview">20 มีนาคม 2564<i class="fas fa-eye"></i>29</div>
                                </figcaption>
                            </figure>
                        </a>
                        <a href="#" class="item_related">
                            <figure>
                                <div><img src="{{ asset('themes/thrc/thaihealth-watch/images/Path 3287.png') }}"></div>
                                <figcaption>
                                    <h5>องค์การอนามัยโลก(WHO) เล็งเห็นว่ากลุ่มโรค NCDs นั้น ถือเป็นปัญหาใหญ่ที่กำลังทวีความรุนแรงขึ้น...</h5>
                                    <p>สำหรับประเทศไทยเอง สถิติล่าสุดพบว่ามีถึง 14 ล้านคนที่เป็นโรค ในกลุ่มโรค NCDs และที่สำคัญยังถือเป็นสาเหตุหลักการเสียชีวิต ของ ประชากรทั้งประเทศ โดยจากสถิติปี พ.ศ. 2552</p>
                                    <div class="dateandview">20 มีนาคม 2564<i class="fas fa-eye"></i>29</div>
                                </figcaption>
                            </figure>
                        </a>
                        <a href="#" class="item_related">
                            <figure>
                                <div><img src="{{ asset('themes/thrc/thaihealth-watch/images/Path 3290.png') }}    "></div>
                                <figcaption>
                                    <h5>องค์การอนามัยโลก(WHO) เล็งเห็นว่ากลุ่มโรค NCDs นั้น ถือเป็นปัญหาใหญ่ที่กำลังทวีความรุนแรงขึ้น...</h5>
                                    <p>สำหรับประเทศไทยเอง สถิติล่าสุดพบว่ามีถึง 14 ล้านคนที่เป็นโรค ในกลุ่มโรค NCDs และที่สำคัญยังถือเป็นสาเหตุหลักการเสียชีวิต ของ ประชากรทั้งประเทศ โดยจากสถิติปี พ.ศ. 2552</p>
                                    <div class="dateandview">20 มีนาคม 2564<i class="fas fa-eye"></i>29</div>
                                </figcaption>
                            </figure>
                        </a>
                        <a href="#" class="item_related">
                            <figure>                           
                                <div><img src="{{ asset('themes/thrc/thaihealth-watch/images/Path 3291.png') }}       "></div>
                                <figcaption>
                                    <h5>องค์การอนามัยโลก(WHO) เล็งเห็นว่ากลุ่มโรค NCDs นั้น ถือเป็นปัญหาใหญ่ที่กำลังทวีความรุนแรงขึ้น...</h5>
                                    <p>สำหรับประเทศไทยเอง สถิติล่าสุดพบว่ามีถึง 14 ล้านคนที่เป็นโรค ในกลุ่มโรค NCDs และที่สำคัญยังถือเป็นสาเหตุหลักการเสียชีวิต ของ ประชากรทั้งประเทศ โดยจากสถิติปี พ.ศ. 2552</p>
                                    <div class="dateandview">20 มีนาคม 2564<i class="fas fa-eye"></i>29</div>
                                </figcaption>
                            </figure>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>-->
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
        /*$(".related-carousel").owlCarousel({
            loop:false,
            rewind: true,
            margin:15,
            nav:false,
            navText: ['<span><i class="fas fa-chevron-left"></i></span>','<span><i class="fas fa-chevron-right"></i></span>'],
            autoplayHoverPause: false,
            dots:false,
            autoplay:true,
            autoplayTimeout:6000,
            smartSpeed: 1000,
            stagePadding: 0,
            slideBy: 1,
            responsive:{
                0:{
                    items:1
                },
                500:{
                    items:2
                },
                768:{
                    items:3
                },
                1201:{
                    items:4
                }
            }
        }); */       
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
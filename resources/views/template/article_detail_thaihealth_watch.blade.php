@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app_thaihealth_watch')
@section('content')
@php
    $segment_1 = \Request::segment(1);
    $segment_2 = \Request::segment(2);
	//dd($data);
    $status_file = true;
    if(!Auth::check()){

        if(!empty($cookie)){
             //dd($cookie);
             if(gettype($cookie) =='object'){
                if($cookie->download >= 5 ){
                   $status_file = false;
                }
             }else{
                $status_file = true;
             }

        }else{
            $status_file = true;
        }

    }
    //dd($data);
@endphp
@include('partials.search')
<div id="app"></div>
    <section class="row">
        <div class="container">
            <div class="row row-about">
                <div class="col-xs-12 col-md-12 about-thwatch wow fadeInLeft">

                    <h1>{{ $data->title }}</h1>
                    @if($data->video_path !='')
                    {!! $data->video_path !!}    
                    @endif
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

                <div class="col-xs-12 col-md-12">
                    @if($attachments->count() >0)
                    <div class="head_inside">
                    <h2>ดาวน์โหลดไฟล์</h2>
                    </div>
                        @foreach($attachments AS $key=>$value)
                        @php
                            //dd($value);
                        @endphp
                        <div class="pdf_boxdownload">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="pdf_filename">
                                        @if($status_file === false)
                                            <a href="#" data-id="{{ $value->id }}" class="download">{{ ($key+1) }}.{{ $value->title }}</a>
                                        @else
                                            <a href="{{ asset($value->file_path) }}" data-id="{{ $value->id }}" download="{{ $value->title }}.{{ $value->file_type }}" class="download">{{ ($key+1) }}.{{ $value->title }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div><!--download--> 
                <div class="col-xs-12 col-md-12">
                        @if($data->dol_url !='' && $data->dol_template !='Multimedia')
                        <figcaption>
                            <div class="wrap_btn_bd">
                                @if($status_file === false)
                                <a href="#""><button class="btn_bddownload">อ่านต่อ</button></a>
                                @else
                                <a href="{{ $data->dol_url }}" target="_blank"><button class="btn_bddownload">อ่านต่อ</button></a>
                                @endif
                            </div>
                        </figcaption>
                        @endif
                    </div>                               

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
    <section class="row wow fadeInDown bg_related">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_related">
                    <h2>บทความที่่คล้ายกัน</h2>
                </div>
                <div class="col-xs-12">
                    <div class="related-carousel owl-carousel owl-theme">
                    @if($related_data->count())
                            @foreach($related_data AS $key =>$value)
                            @php
                                //dd($value);
                                $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                $model_type='App\Modules\Api\Models\ListMedia';
                                if($value->data_type =='media'){
                                    $value->url = route('media-detail',Hashids::encode($value->id));
                                }else{
                                    //URL($segment_1."/".$value->slug);
                                    $model_type='App\Modules\Article\Models\Article'; 
                                    $value->url = route('thaihealthwatch-detail',$value->slug);
                                    $image = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                                }
                                
                                if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                    $image = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                    //dd($image,$model_type);
                                    $image = (isset($image->id)  ?  asset('media/'.$image->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                                }

                            @endphp
                            <a href="{{ $value->url }}" class="item_related">
                                <figure>
                                    <div><img src="{{ $image }}" alt="{{ $value->title }}"></div>
                                    <figcaption>
                                        <h5>{{ $value->title }}</h5>
                                        <p>สำหรับประเทศไทยเอง สถิติล่าสุดพบว่ามีถึง 14 ล้านคนที่เป็นโรค ในกลุ่มโรค NCDs และที่สำคัญยังถือเป็นสาเหตุหลักการเสียชีวิต ของ ประชากรทั้งประเทศ โดยจากสถิติปี พ.ศ. 2552</p>
                                        <div class="dateandview">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}<i class="fas fa-eye"></i>{{ number_format($value->hit) }}</div>
                                    </figcaption>
                                </figure>
                            </a>

                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

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
    <meta property="og:image" content="{{ ($data->getMedia('cover_desktop')->isNotEmpty() ? asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'))  }}" />

@endsection
@section('js')
	@parent
<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5d627dd20388510012a26233&product=inline-share-buttons' async='async'></script>
<script type="text/javascript">
    var check_download = 0;

    $(document).ready(function($) {
        check_auth = '{{ Auth::check() }}';
        $('.download').click(function(){
            
                status_file = '{{ $status_file }}';
                download_id = $(this).attr('data-id');

                //console.log(download_id,typeof status_file);

                if(status_file == false){

                    Swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'กรุณาสมัครสมาชิกสำหรับดาวน์โหลดไฟล์หรืออ่านต่อ',
                        showConfirmButton: false,
                        timer: 5000
                    });

                }else{


                    $.ajax({
                        url: '{{ route("document-download.update") }}',
                        type: "POST",
                        dataType: 'json',
                        data: {_token:jQuery('meta[name="csrf-token"]').attr('content'),id:download_id},
                        success:function(response){
                            //console.log(response);
                            if(response.status == true){
                                if(!check_auth){
                                    check_download = response.log_download;
                                    if(response.log_download >=5){

                                        $('.download').removeAttr("download");
                                        $('.download').removeAttr("href");

                                        Swal.fire({
                                          position: 'center',
                                          type: 'error',
                                          title: 'กรุณาสมัครสมาชิกสำหรับดาวน์โหลดไฟล์หรืออ่านต่อ',
                                          showConfirmButton: false,
                                          timer: 5000
                                        });
                                    }
                                }
                            }
                        }/*End success*/
                    });/*End $.ajax*/

                    
                }


        });

        $('.btn_bddownload').click(function(event) {
            
            let dol_UploadFileID =  '{{ $data->dol_UploadFileID }}';
            //console.log("Check btn_bddownload",dol_UploadFileID);
           //console.log(check_download);
            status_file = '{{ $status_file }}';
           

                //console.log(download_id,typeof status_file);

                if(status_file == false){

                    Swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'กรุณาสมัครสมาชิกสำหรับดาวน์โหลดไฟล์หรืออ่านต่อ',
                        showConfirmButton: false,
                        timer: 5000
                    });

                }else{


                    $.ajax({
                        url: '{{ route("dol-download.update") }}',
                        type: "POST",
                        dataType: 'json',
                        data: {_token:jQuery('meta[name="csrf-token"]').attr('content')},
                        success:function(response){
                            //console.log(response);
                            if(response.status == true){
                                if(!check_auth){
                                    check_download = response.log_download;
                                    if(response.log_download >=5){

                                        $('.wrap_btn_bd a').removeAttr("download");
                                        $('.wrap_btn_bd a').removeAttr("href");
                                        Swal.fire({
                                          position: 'center',
                                          type: 'error',
                                          title: 'กรุณาสมัครสมาชิกสำหรับดาวน์โหลดไฟล์หรืออ่านต่อ',
                                          showConfirmButton: false,
                                          timer: 5000
                                        });
                                    }
                                }
                            }
                        }/*End success*/
                    });/*End $.ajax*/

                    
                }            

            if(dol_UploadFileID !='' && check_download <4 && status_file !=false){

                setTimeout(function(){  

                    $('.wrap_btn_bd a').attr('href','#');
                    $('.wrap_btn_bd a').attr('target','_self');
                    $.ajax({
                            url: '{{ route("dol-download.files") }}',
                            type: "POST",
                            dataType: 'json',
                            data: {_token:jQuery('meta[name="csrf-token"]').attr('content'),dol_UploadFileID:dol_UploadFileID},
                            success:function(response){
                                //console.log(response);
                                if(response.status == true){
                                    $('.wrap_btn_bd a').attr('href',response.data);
                                    $('.wrap_btn_bd a').attr('target','_blank');
                                }
                            }/*End success*/
                    });/*End $.ajax*/                    

                },3);

            }else{
                Swal.fire({
                    position: 'center',
                    type: 'error',
                    title: 'กรุณาสมัครสมาชิกสำหรับดาวน์โหลดไฟล์หรืออ่านต่อ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

        });
        


        $(".related-carousel").owlCarousel({
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
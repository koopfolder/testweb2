@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app')
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
    $json = ($data->dol_json_data !='' ? json_decode($data->dol_json_data):''); 
    $check_template = (isset($json->Template) ? $json->Template:'');
    //dd($json,$check_template);
@endphp
@include('partials.search_health_literacy3')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_content">
                <div class="col-xs-12 col-sm-8 left_content">

                    @if($json !='' && $check_template =='Multimedia')

                            <div class="head_inside">
                                <h1>{{ $data->title }}</h1>
                            </div>
                            <div class="dateandview">
                                <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                                <div class="newsview">{{ number_format($data->hit) }}</div>
                                <div class="sharesym">Share</div>
                                <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                                <div class="icon_book icon_vdomedia"></div>
                            </div>
                            <figure class="img_detail_book">
                                <div class="wrap_vdoiframe">
                                    @php
                                        $format_type = '';
                                        switch ($json->Format) {
                                            case ".avi":
                                                $embed = ThrcHelpers::convertYoutube($json->FileAddress); 
                                                $format_type = 'youtube'; 
                                                break;
                                            case "mp4":
                                                $format_type = 'mp4'; 
                                                $json->FileAddress = $data->dol_url;
                                                break;
                                            default: 
                                        }
                                    @endphp
                                    @if($format_type =='youtube')
                                        {!! $embed !!}
                                    @elseif($format_type =='mp4')
                                        <video width="420" height="315" controls>
                                            <source src="{{ $json->FileAddress }}" type="video/mp4">
                                        </video>
                                    @else
                                    
                                    @endif
                                </div>
                            </figure>
                            <figcaption class="text_detail_book">
                                <p>{!! $data->description !!}</p>
                            <div class="detail_vdo">
                                    <div><span>ความยาว  :</span> {{ $json->Duration  }} นาที</div>
                                    <div><span>ผลิตโดย  :</span>  {{ $json->Production  }}</div>
                                    <div><span>อำนวยการผลิต :</span>  {{ $json->Creator  }}</div>
                            </div>
                            </figcaption>
                    @else
                        <div class="head_inside">
                            <h1>{{ $data->title }}</h1>
                        </div>
                        <div class="dateandview">
                            <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                            <div class="newsview">{{ number_format($data->hit) }}</div>
                            <div class="sharesym">Share</div>
                            <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                            <div class="icon_book"></div>
                        </div>
                        <div class="img_detail_book">
                            <div class="flexweb">
                                <ul class="slides">
                                    @if($data->getMedia('cover_desktop')->isNotEmpty() && !$data->getMedia('gallery_desktop')->isNotEmpty())
                                        <li><figure><img data-src="{{ asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}"></figure></li>
                                    @endif
                                    @if ($data->getMedia('gallery_desktop')->isNotEmpty())
                                        @foreach($data->getMedia('gallery_desktop') AS $key=>$value)
                                        <li><figure><img data-src="{{ asset($value->getUrl()) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" /></figure></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <figcaption class="text_detail_book">
                            {!! $data->description !!}
                        </figcaption>
                    @endif

                    <div class="pdf_download">
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
                    </div><!--pdf download-->
                    <div class="book_list">
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

                </div>
                <div class="col-xs-12 col-sm-4">
                <h1 class="head_right">Selected For You</h1> 
                      <div class="mCustomScrollbar mostviewscrollbar" id="select_for_data">
                            <div class="loader_select"></div>
                     </div> 
                    <h1 class="head_right">Related</h1>
                    <div class="mCustomScrollbar relatescrollbar">
                        @if($related_data->count())
                            @foreach($related_data AS $key =>$value)
                            @php
                                $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                $model_type='App\Modules\Api\Models\ListMedia';
                                if($value->data_type =='media'){
                                    $value->url = route('media-detail',Hashids::encode($value->id));
                                }else{
                                    //URL($segment_1."/".$value->slug);
                                    $model_type='App\Modules\Article\Models\Article'; 
                                    $value->url = route('health-literacy-detail',$value->slug);
                                    $image = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                                }
                                
                                if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                    $image = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                    //dd($image,$model_type);
                                    $image = (isset($image->id)  ?  asset('media/'.$image->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                                }
                            @endphp
                            <div class="item_related">
                              <a href="{{ $value->url }}?view-item=view_related">
                                    <figure>
                                        <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ number_format($value->hit) }}</div>
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
                                $value->url = URL($segment_1."/".$value->slug);
                                $value->cover_desktop = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}?view-item=view_most">
                                    <figure>
                                        <img data-src="{{ $value->cover_desktop }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ number_format($value->hit) }}</div>
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
                                $value->url = URL($segment_1."/".$value->slug);
                                $value->cover_desktop = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}?view-item=view_recommend">
                                    <figure>
                                        <img data-src="{{ $value->cover_desktop }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value->created_at)->format('d.m.').(Carbon\Carbon::parse($value->created_at)->format('Y')+543) }}</div>
                                            <div class="newsview">{{ number_format($value->hit) }}</div>
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
<!--    <section class="row wow fadeInDown bg_relate">
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
@php 

 $description=trim(strip_tags($data->description));
 $description= str_replace("&nbsp;","",$description);
 $description = preg_replace("/&#?[a-z0-9]+;/i","",$description);
 $description = preg_replace('/\s+/', '', $description);

 $category_id = str_replace(array("[", "]"), "",$data->category_id);
 $category_id = explode(",", $category_id);

@endphp
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
    $(".mCustomScrollbar").mCustomScrollbar({
            callbacks:{
                onScrollStart:function(){
                  $(".dotmaster").trigger("update.dot");
                }
            }
    });
    
    $(function(){
      SyntaxHighlighter.all();
    });

    $(window).on("load", function() {
        $('.flexweb').flexslider({
            animation: "slide",
            controlNav: true,
            directionNav: false,
            start: function(slider){
            }
        });
    });

    $(document).ready(function($) {
        var title =  "{{$data->id}}";
        var category_id =  "{{$category_id[0]}}";
        var tags =  '{{$data->tags}}';
        var event = "view_item";
        var Issue =  '{{$data->Issues}}';
        var Target =  '{{$data->Targets}}';
        var Setting =  '{{$data->Settings}}';

        var tech = getUrlParameter('view-item');
        if(tech != false){
            var event = tech;
        }   

        clickview(event,title,tags,category_id,Issue,Target,Setting)

        var test_t=null;
        var cookie_id = cxGetCookie("connectx");
        var description_data="{{$description}}"; 
        var name =  "{{$data->title}}";   
        var description =clear_data(description_data);
        var Issue_data =clear_data(Issue,"set_data");
        var Target_data =clear_data(Target,"set_data");
        var Setting_data =clear_data(Setting,"set_data");
        var tags_data =clear_data(tags,"set_data");
        var title =clear_data(title);
        


       $.ajax({
                        url: "{{ route('seleted_for_you') }}",
                        method: "POST",
                        data: {    
                            "_token": "{{ csrf_token() }}", 
                            "media_id":title,
                            "cookie_id":cookie_id,
                            "title":name,
                            "description":description,
                            "issues":Issue_data,
                            "tag":Target_data,
                            "category_id":category_id,
                            "targets":tags_data,
                            "setting":Setting_data,
                           
                        },
                         beforeSend: function() {
                                $('.loader_select').show();
                            },
                        success: function(response) {   
                            $('.loader_select').hide();
                           response.forEach((value, index) => {
                            
                                html = `  <div class="item_related">
                                            <a href="${value.url}">
                                                <figure>
                                                    <img  src="${value.image_path}"  onerror="this.onerror=null;this.src='{{ asset('themes/thrc/images/Placeholder.png') }}';">
                                                </figure><figcaption>
                                                    <p class="dotmaster">${value.title}</p>
                                                    <div class="dateandview">
                                                        <div class="newsdate">${value.created_at}</div>
                                                        <div class="newsview">${value.hit}</div>
                                                    </div>
                                                </figcaption>
                                            </a>
                                        </div>`;
                                $('#mCSB_1_container').append(html);    
            
                                
        
                              
                           });
                        }
        });


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
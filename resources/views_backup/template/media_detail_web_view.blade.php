@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app_detail_web_view')
@section('content')
@php
    //dd($data->template);
    $json = ($data->json_data !='' ? json_decode($data->json_data):'');
    if($data->getMedia('cover_desktop')->isNotEmpty()){
        $image= asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635'));
    }else{
        $image = (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
    }
    //$json->FileAddress;
    $template = $data->template;
    $file_download = $file_download;
    // if(!Auth::check()){
    //     if(!empty($cookie)){
    //          if(gettype($cookie) =='object'){
    //             if($cookie->download >= 5 ){
    //                $file_download='#';
    //             }
    //          }else{
    //             $file_download = $file_download;
    //          }
    //     }else{
    //         $file_download = $file_download;
    //     }
    // }
    //dd($template,$json,$data,$cookie);
@endphp
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_content">
                @if($template =='Text')
                <div class="col-xs-12 col-sm-8 left_content">
                    <div class="head_inside">
                        <h1>{{ $data->title }}</h1>
                    </div>
                    <div class="dateandview">
                        <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                        <div class="newsview">{{ number_format($data->hit) }}</div>
                        <div class="icon_book"></div>
                    </div>
                    <figure class="img_detail_book">
                            <img src="{{ $image }}" alt="{{ $data->title }}">
                    </figure>
                    <figcaption class="text_detail_book">
                            <p>{!! $data->description !!}</p>
                    </figcaption>
                    <div class="book_list">
                        <figcaption>
                            <div class="wrap_btn_bd">
                                @if($file_download !='')
                                <a href="{{ $file_download }}" ><button class="btn_bddownload">อ่านต่อ</button></a>
                                @endif
                            </div>
                        </figcaption>
                    </div>
                </div>
                @elseif($template =='Application')
                <div class="col-xs-12 col-sm-8 left_content">
                    <div class="head_inside">
                        <h1>{{ $data->title }}</h1>
                    </div>
                    <div class="dateandview">
                        <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                        <div class="newsview">{{ number_format($data->hit) }}</div>
                        <div class="icon_book icon_webmedia"></div>
                    </div>                    
                    <figure class="img_detail_book">
                        <img src="{{ $image }}" alt="{{ $data->title }}">
                    </figure>
                    <figcaption class="text_detail_book">
                        <p>{!! $data->description !!}</p>
                        <a class="link_website_media" href="{{ $json->FileAddress }}">{{ $json->FileAddress }}</a>
                    </figcaption>
                    <div class="wrap_btn_im">
                        @if($file_download !='')
                            <a href="{{ $file_download }}" ><button class="btn_bddownload">อ่านต่อ</button></a>
                        @endif
                    </div>
                </div>
                <script type="text/javascript">
                    $(function(){
                      SyntaxHighlighter.all();
                    });
                    $(window).on( "load", function() {
                        $('.flexweb').flexslider({
                            animation: "slide",
                            controlNav: true,
                            directionNav: false,
                            start: function(slider){
                        }
                      });
                    });
                </script>
                @elseif($template =='KnowledgePackage')
                <div class="col-xs-12 col-sm-8 left_content">
                    <div class="head_inside">
                        <h1>{{ $data->title }}</h1>
                    </div>
                    <div class="dateandview">
                        <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                        <div class="newsview">{{ number_format($data->hit) }}</div>
                        <div class="icon_book"></div>
                    </div>
                    <figure class="img_detail_book">
                        <img src="{{ $image }}" alt="{{ $data->title }}">
                    </figure>
                    <figcaption class="text_detail_book">
                            <p>{!! $data->description !!}</p>
                    </figcaption>
                    <div class="wrap_btn_im">
                            @if($file_download !='')
                                <a href="{{ $file_download }}" ><button class="btn_bddownload">อ่านต่อ</button></a>
                            @endif
                    </div>
                </div>
                @elseif($template =='Multimedia')
                <div class="col-xs-12 col-sm-8 left_content">
                        <div class="head_inside">
                            <h1>{{ $data->title }}</h1>
                        </div>
                        <div class="dateandview">
                            <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                            <div class="newsview">{{ number_format($data->hit) }}</div>
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
                        <div class="wrap_btn_im">
                            @if($file_download !='')
                                <a href="{{ $file_download }}" ><button class="btn_bddownload">อ่านต่อ</button></a>
                            @endif
                        </div>
                </div>
                @elseif($template =='Visual')
                <div class="col-xs-12 col-sm-8 left_content">
                        <div class="head_inside">
                            <h1>{{ $data->title }}</h1>
                        </div>
                        <div class="dateandview">
                            <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                            <div class="newsview">{{ number_format($data->hit) }}</div>
                            <div class="icon_book icon_imagesmedia"></div>
                        </div>
                        <figure class="img_detail_book">
                            <img src="{{ $image }}" alt="{{ $data->title }}">
                        </figure>
                        <figcaption class="text_detail_book">
                            <p>{!! $data->description !!}</p>
                        </figcaption>
                        <div class="wrap_btn_im">
                            @if($file_download !='')
                                <a href="{{ $file_download }}" ><button class="btn_bddownload">อ่านต่อ</button></a>
                            @endif
                        </div>
                </div>
                @else
                <div class="col-xs-12 col-sm-8 left_content">
                    <div class="head_inside">
                        <h1>{{ $data->title }}</h1>
                    </div>
                    <div class="dateandview">
                        <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                        <div class="newsview">{{ number_format($data->hit) }}</div>
                        <div class="icon_book"></div>
                    </div>
                    <figure class="img_detail_book">
                        <img src="{{ $image }}" alt="{{ $data->title }}">
                    </figure>
                    <figcaption class="text_detail_book">
                        {!! $data->description !!}
                    </figcaption>
                    <div class="wrap_btn_im">
                        @if($file_download !='')
                            <a href="{{ $file_download }}" ><button class="btn_bddownload">อ่านต่อ</button></a>
                        @endif
                    </div>
                </div>      
                @endif
                <div class="col-xs-12 col-sm-4">

                    <h1 class="head_right">Related</h1>
                    <div class="mCustomScrollbar relatescrollbar">
                        @if($related_data->count())
                            @php
                                //dd($related_data);
                            @endphp
                            @foreach($related_data AS $key =>$value)
                            @php
                                if($value->data_type =='media'){
                                    $value->url = route('media-detail-webview',Hashids::encode($value->id));
                                    $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                    if($value->getMedia('cover_desktop')->isNotEmpty()){
                                        $image= asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                                    }else{
                                        $image = (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
                                    }
                                }else{
                                    //URL($segment_1."/".$value->slug); 
                                    $value->url = route('article-detail-webview',$value->slug);
                                    $image = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                                }
                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}">
                                    <figure>
                                        <img src="{{ $image }}" alt="{{ $value->title }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('d.m.').(Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('Y')+543) }}</div>
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

@endsection
@section('meta')
    @parent

@endsection
@section('js')
	@parent
<script>
    $(document).ready(function($) {
        check_auth = '{{ Auth::check() }}';
        //console.log(check_auth);
        $(".mCustomScrollbar").mCustomScrollbar({
            callbacks:{
                onScrollStart:function(){
                  $(".dotmaster").trigger("update.dot");
                }
            }
        });

        // $('.btn_bddownload').click(function(event) {
            
        //     path_file_download = '{{ $file_download }}';

        //     if(path_file_download !='#'){

        //         $.ajax({
        //             url: '{{ route("media-download.update") }}',
        //             type: "POST",
        //             dataType: 'json',
        //             data: {_token:jQuery('meta[name="csrf-token"]').attr('content'),id:'{{ $data->id }}'},
        //             success:function(response){
        //                 //console.log(response);
        //                 if(response.status == true){

        //                    if(!check_auth){
        //                         //console.log("!check_auth");
        //                         if(response.log_download >=5){

        //                             $('.wrap_btn_im a').removeAttr("download");
        //                             $('.wrap_btn_im a').removeAttr("href");

        //                             Swal.fire({
        //                               position: 'center',
        //                               type: 'error',
        //                               title: 'กรุณาสมัครสมาชิกสำหรับอ่านต่อ',
        //                               showConfirmButton: false,
        //                               timer: 5000
        //                             });
        //                         }
        //                    }
        //                 }
        //             }/*End success*/
        //         });/*End $.ajax*/

        //     }else{

        //         Swal.fire({
        //           position: 'center',
        //           type: 'error',
        //           title: 'กรุณาสมัครสมาชิกสำหรับอ่านต่อ',
        //           showConfirmButton: false,
        //           timer: 5000
        //         });

        //     }

        // });



    });

</script>
@endsection
@section('style')
	@parent
<style type="text/css">

</style>
@endsection
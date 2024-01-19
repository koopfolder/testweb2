@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app_detail_web_view')
@section('content')
@php
    $segment_1 = \Request::segment(1);
    $segment_2 = \Request::segment(2);
	//dd($data);
    $status_file = true;
    // if(!Auth::check()){

    //     if(!empty($cookie)){
    //          //dd($cookie);
    //          if(gettype($cookie) =='object'){
    //             if($cookie->download >= 5 ){
    //                $status_file = false;
    //             }
    //          }else{
    //             $status_file = true;
    //          }

    //     }else{
    //         $status_file = true;
    //     }

    // }
    //dd($data);
@endphp
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_content">
                <div class="col-xs-12 col-sm-8 left_content">
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
                                    <li><figure><img src="{{ asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}"></figure></li>
                                @endif
                                @if ($data->getMedia('gallery_desktop')->isNotEmpty())
                                    @foreach($data->getMedia('gallery_desktop') AS $key=>$value)
                                    <li><figure><img src="{{ asset($value->getUrl()) }}" /></figure></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <figcaption class="text_detail_book">
                         {!! $data->description !!}
                    </figcaption>
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
                                <a href="{{ $data->dol_url }}" ><button class="btn_bddownload">อ่านต่อ</button></a>
                            </div>
                        </figcaption>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    
                    <h1 class="head_right">Related</h1>
                    <div class="mCustomScrollbar relatescrollbar">
                        @if($related_data->count())
                            @foreach($related_data AS $key =>$value)
                            @php
                                $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                $model_type='App\Modules\Api\Models\ListMedia';
                                if($value->data_type =='media'){
                                    $value->url = route('media-detail-webview',Hashids::encode($value->id));
                                }else{
                                    //URL($segment_1."/".$value->slug);
                                    $model_type='App\Modules\Article\Models\Article'; 
                                    $value->url = route('article-detail-webview',$value->slug);
                                    $image = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                                }
                                
                                if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                    $image = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                    //dd($image,$model_type);
                                    $image = (isset($image->id)  ?  asset('media/'.$image->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                                }

                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}">
                                    <figure>
                                        <img src="{{ $image }}">
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
@endsection
@section('meta')
    @parent
    <title>{{ ($data->meta_title !='' ? $data->meta_title :$data->title) }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $data->meta_description }}">
    <meta name="keywords" content="{{ $data->meta_keywords }}">
    <meta name="author" content="THRC">

    <meta property="og:url"    content="{{  route('article-detail',$data->slug) }}" />
    <meta property="og:type"   content="article" />
    <meta property="og:title"  content="{{ ($data->meta_title !='' ? $data->meta_title :$data->title) }}" />
    <meta property="og:description" content="{{ $data->meta_description }}" />
    <meta property="og:image" content="{{ ($data->getMedia('cover_desktop')->isNotEmpty() ? asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'))  }}" />

@endsection
@section('js')
	@parent
<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5d627dd20388510012a26233&product=inline-share-buttons' async='async'></script>
<script type="text/javascript">

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

        $('.sharesym').click(function(event) {
            $('.sharethis-inline-share-buttons,.st-btn').toggle('slow');
        });   

    });


    $(document).ready(function($) {

        $('.btn_bddownload').click(function(event) {
            
            let dol_UploadFileID =  '{{ $data->dol_UploadFileID }}';
            //console.log("Check btn_bddownload",dol_UploadFileID);

            if(dol_UploadFileID !=''){

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

            }

        });
        
    });    



</script>
@endsection
@section('style')
	@parent
<style type="text/css">

</style>
@endsection
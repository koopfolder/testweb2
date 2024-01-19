@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app')
@section('content')
@php
    //dd($data);
    $json = ($data->json_data !='' ? json_decode($data->json_data):'');

    {{-- $file_download จะถูกส่งมาจาก FrontendController::getDetailMedia --}}

    if($json->Format =="mp4"){
        $json->FileAddress = $file_download;
    }

    $json_main = $json;
    $UploadFileID = $json->UploadFileID;
    if($data->getMedia('cover_desktop')->isNotEmpty()){
        $image= asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635'));
    }else{
        $image = (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
        
        {{-- กรณีสื่อที่ download ไฟล์จาก ดีโอแอลมาแล้วจะ ให้ link มาที่ thrc --}}
        $image = (is_null($data->thumbnail_address)) ? $image : asset('mediadol') . "/" . $UploadFileID . "/" . $data->thumbnail_address;

    }
    $file_download_dol = (gettype($json) == 'object' ? $json->FileAddress : null);

    //$json->FileAddress;
    $template = $data->template;
    $file_download = $file_download;
    if(!Auth::check()){
      
        if(!empty($cookie)){
             
             if(gettype($cookie) =='object'){
                
                if($cookie->download >= 5 ){
                   $file_download='#';
                }
             }else{
                $file_download = $file_download;
             }

        }else{
            $file_download = $file_download;
        }

    }
    //dd("test",$cookie,$file_download);
    $target = '_blank';
    if($file_download =='#'){
        $target = '_self';
    }

    //dd($template,$json,$data,$cookie);
    {{-- กรณีสื่อที่ download ไฟล์จาก ดีโอแอลมาแล้วจะ ให้ link มาที่ thrc --}}
    $file_download = (is_null($data->local_path)) ? $file_download : asset('mediadol') . "/" . $UploadFileID . "/" . $data->local_path ;

@endphp
@include('partials.search')

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
                        <div class="sharesym">Share</div>
                        <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                        <div class="icon_book"></div>
                    </div>
                    <figure class="img_detail_book">
                            <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $data->title }}">
                    </figure>
                    <figcaption class="text_detail_book">
                            <p>{!! $data->description !!}</p>
                    </figcaption>
                    <figcaption class="text_detail_book">
                        <figcaption>
                            <div class="wrap_btn_bd">
                                @if($file_download !='')
                                <a href="{{ $file_download }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                @elseif(!empty($file_download_dol))
                                <a href="{{ $file_download_dol }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                
                                @endif
                            </div>
                        </figcaption>
                    </figcaption>
                </div>
                @elseif($template =='Application')
                
                <div class="col-xs-12 col-sm-8 left_content">
                    <div class="head_inside">
                        <h1>{{ $data->title }}</h1>
                    </div>
                    <div class="dateandview">
                        <div class="newsdate">{{ Carbon\Carbon::parse($data->created_at)->format('d.m.').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                        <div class="newsview">{{ number_format($data->hit) }}</div>
                        <div class="sharesym">Share</div>
                        <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                        <div class="icon_book icon_webmedia"></div>
                    </div>                    
                    <figure class="img_detail_book">
                        <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $data->title }}">
                    </figure>
                    <figcaption class="text_detail_book">
                        <p>{!! $data->description !!}</p>
                        <a class="link_website_media" href="{{ $json->FileAddress }}">{{ $json->FileAddress }}</a>
                    </figcaption>
                    <div class="wrap_btn_bd">
                        @if($file_download !='')
                            <a href="{{ $file_download }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                            @elseif(!empty($file_download_dol))
                                <a href="{{ $file_download_dol }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                
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
                        <div class="sharesym">Share</div>
                        <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                        <div class="icon_book"></div>
                    </div>
                    <figure class="img_detail_book">
                        <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $data->title }}">
                    </figure>
                    <figcaption class="text_detail_book">
                            <p>{!! $data->description !!}</p>
                    </figcaption>
                    <div class="wrap_btn_bd">
                            @if($file_download !='')
                                <a href="{{ $file_download }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                @elseif(!empty($file_download_dol))
                                <a href="{{ $file_download_dol }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                
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
                                            break;
                                        case "[DV]-DigitalViral/WebFilm":
                                            $format_type = 'mp4'; 
                                            break;
                                        default: 
                                    }
                                @endphp
                
                                @if($format_type =='youtube')
                                    {!! $embed !!}
                                @elseif($format_type =='mp4')
                                    @php
                                        if(!empty($json->DirectLink)){
                                            $filePath = $json->DirectLink;
                                        }else{
                                            $filePath = $json->FileAddress;
                                        }
                                        if(!is_null($data->local_path)) {
                                            $filePath = asset('/mediadol/' . $UploadFileID) . "/" . $data->local_path;
                                        }
                                    @endphp
                                    <video width="100%" height="auto" controls>
                                        <source src="{{ $filePath }}" type="video/mp4">
                                    </video>
                                @else
                                
                                @endif
                            </div>
                        </figure>
                        <figcaption class="text_detail_book">
                            <p>{!! $data->description !!}</p>
                        <div class="detail_vdo">
                                <div><span>ความยาว  :</span> {{ !empty($json->Duration) ? $json->Duration : ''  }} นาที</div>
                                <div><span>ผลิตโดย  :</span>  {{ !empty($json->Production) ? $json->Production : ''  }}</div>
                                <div><span>อำนวยการผลิต :</span>  {{ !empty($json->Creator) ? $json->Creator :''  }}</div>
                        </div>
                        </figcaption>
                        <div class="wrap_btn_bd">
                            @if($file_download !='')
                            <!--  <a href="{{ $file_download }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a> -->
                            @elseif(!empty($file_download_dol))
                                <a href="{{ $file_download_dol }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                
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
                            <div class="sharesym">Share</div>
                            <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                            <div class="icon_book icon_imagesmedia"></div>
                        </div>
                        @php
                        if(!empty($data->local_path)){
                            $uploadfile = json_decode($data->json_data);
                           $uploadfile = $uploadfile->UploadFileID;
                           $image = "https://resourcecenter.thaihealth.or.th/mediadol/".$uploadfile.'/'.$data->local_path;
                           
                           
                        }
                        @endphp
                        
                        {{-- <figure class="img_detail_book" style="width: 65% !important; margin-left: 125px;">
                            <div class="col-12 d-flex align-items-center mt-3 @if(pathinfo($image, PATHINFO_EXTENSION) == 'pdf') pdfview @else jpgview @endif pl-0 pr-0">
                                @if(pathinfo($image, PATHINFO_EXTENSION) == 'pdf')
                                    <iframe src="{{ $image }}" width='100%' height='700px' frameborder='0'></iframe>
                                @else
                                <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $data->title }}">
                                @endif
                            </div>
                        </figure> --}}

                        <figure class="img_detail_book" style="width: 65% !important; margin-left: 125px;">
                            <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $data->title }}">
                        </figure>

                        <figcaption class="text_detail_book">
                            <p>{!! $data->description !!}</p>
                        </figcaption>
                        <div class="wrap_btn_bd">
                            @if($file_download !='')
                                <a href="{{ $file_download }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                @elseif(!empty($file_download_dol))
                                <a href="{{ $file_download_dol }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                
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
                        <div class="sharesym">Share</div>
                        <div class="sharethis-inline-share-buttons" style="display: none;"></div>
                        <div class="icon_book"></div>
                    </div>
                    <figure class="img_detail_book">
                        <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $data->title }}">
                    </figure>
                    <figcaption class="text_detail_book">
                        {!! $data->description !!}
                    </figcaption>
                    <div class="wrap_btn_bd">
                        @if($file_download !='')
                            <a href="{{ $file_download }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                            @elseif(!empty($file_download_dol))
                                <a href="{{ $file_download_dol }}" target="{{ $target }}"><button class="btn_bddownload">อ่านต่อ</button></a>
                                
                                @endif
                    </div>
                </div>      
                @endif
                <div class="col-xs-12 col-sm-4">
                <h1 class="head_right">Selected For You</h1> 
                      <div class="mCustomScrollbar mostviewscrollbar" id="select_for_data">
                            <div class="loader_select"></div>
                     </div> 
                    <h1 class="head_right">Related</h1>
                    <div class="mCustomScrollbar relatescrollbar">
                        @if($related_data->count())
                            @php
                                //dd($related_data);
                            @endphp
                            @foreach($related_data AS $key =>$value)
                            @php
                                
                                $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                $model_type='App\Modules\Api\Models\ListMedia';
                                if($value->data_type =='media'){
                                    $value->url = route('media-detail',Hashids::encode($value->id));
                                }else{
                                    //URL($segment_1."/".$value->slug);
                                    $model_type='App\Modules\Article\Models\Article'; 
                                    $value->url = route('article-detail',$value->slug);
                                    $image = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                                }
                                
                                if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                    $image = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                    //dd($image,$model_type);
                                    $image = (isset($image->id)  ?  asset('media/'.$image->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                                }

                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}?view-item=view_related" onclick="clickview('view_related','{{$value['id']}}','{{($value['tags']!= null)?$value['tags']:'null'}}','{{($value['category_id']!= null)?$value['category_id']:'null'}}')">
                                    <figure>
                                        <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value->title }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('d.m.').(Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('Y')+543) }}</div>
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
                            @php
                                //dd($most_view_data);
                            @endphp
                            @foreach($most_view_data AS $key =>$value)
                            @php
                                $value->url = route('media-detail',Hashids::encode($value->id));
                                $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                if($value->getMedia('cover_desktop')->isNotEmpty()){
                                    $image= asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                                }else{
                                    $image = (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
                                }
                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}?view-item=view_most" onclick="clickview('view_most','{{$value['id']}}','{{($value['tags']!= null)?$value['tags']:'null'}}','{{($value['category_id']!= null)?$value['category_id']:'null'}}')">
                                    <figure>
                                        <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value->title }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('d.m.').(Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('Y')+543) }}</div>
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
                        @if($recommend_data->count())
                            @php
                                //dd($recommend_data);
                            @endphp
                            @foreach($recommend_data AS $key =>$value)
                            @php
                                $value->url = route('media-detail',Hashids::encode($value->id));
                                $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                if($value->getMedia('cover_desktop')->isNotEmpty()){
                                    $image= asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                                }else{
                                    $image = (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
                                }
                            @endphp
                            <div class="item_related">
                                <a href="{{ $value->url }}?view-item=view_recommend"  onclick="clickview('view_recommend','{{$value['id']}}','{{($value['tags']!= null)?$value['tags']:'null'}}','{{($value['category_id']!= null)?$value['category_id']:'null'}}')">
                                    <figure>
                                        <img data-src="{{ $image }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value->title }}">
                                    </figure><figcaption>
                                        <p class="dotmaster">{{ $value->title }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('d.m.').(Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('Y')+543) }}</div>
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
    <title>{{ ($data->title !='' ? $data->title :$json_main->Title) }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ strip_tags($data->description) }}">
    <meta name="keywords" content="{{ ($json_main->Keywords !='' ? implode(",",$json_main->Keywords):'') }}">
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
<script>
    var check_download = 0;
    let dol_UploadFileID =  '{{ $UploadFileID }}';
    //console.log(dol_UploadFileID);
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
        //console.log(check_auth);
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

        $('.btn_bddownload').click(function(event) {
            //console.log(check_download);
            path_file_download = '{{ $file_download }}';

            if(path_file_download !='#'){

                

                //console.log("Check btn_bddownload",dol_UploadFileID);

                $.ajax({
                    url: '{{ route("media-download.update") }}',
                    type: "POST",
                    dataType: 'json',
                    data: {_token:jQuery('meta[name="csrf-token"]').attr('content'),id:'{{ $data->id }}'},
                    success:function(response){
                        //console.log(response);
                        if(response.status == true){

                           if(!check_auth){
                                //console.log("!check_auth");
                                check_download = response.log_download;
                                if(response.log_download >=5){

                                    $('.wrap_btn_bd a').removeAttr("download");
                                    $('.wrap_btn_bd a').removeAttr("href");

                                    Swal.fire({
                                      position: 'center',
                                      type: 'error',
                                      title: 'กรุณาสมัครสมาชิกสำหรับอ่านต่อ',
                                      showConfirmButton: false,
                                      timer: 5000
                                    });
                                }
                           }
                        }
                    }/*End success*/
                });/*End $.ajax*/


                if(dol_UploadFileID !='' && check_download <4){
                    //console.log(typeof $('.wrap_btn_bd a').attr('href'));
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



            }else{

                Swal.fire({
                  position: 'center',
                  type: 'error',
                  title: 'กรุณาสมัครสมาชิกสำหรับอ่านต่อ',
                  showConfirmButton: false,
                  timer: 5000
                });

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
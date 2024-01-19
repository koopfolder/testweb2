@inject('request', 'Illuminate\Http\Request')
@php

    $health_literacy = ThrcHelpers::getHealthLiteracy(['limit'=>'1','retrieving_results'=>'first','page_layout'=>'health-literacy-main','featured'=>false]);

    $interesting_issues = ThrcHelpers::getInterestingIssues(['limit'=>'4','retrieving_results'=>'get','page_layout'=>'interesting_issues','featured'=>false]);

    $news = ThrcHelpers::getNews(['limit'=>'4','retrieving_results'=>'get','page_layout'=>'news_event','featured'=>false]);
    //$knowledges_id = ThrcHelpers::getSetting(['slug'=>'knowledges','retrieving_results'=>'first']);
    //if(collect($knowledges_id)->isNotEmpty() && $knowledges_id->value !=''){
    $knowledges = ThrcHelpers::getMediaKnowledges([]);
    //}
    //$media_campaign_id = ThrcHelpers::getSetting(['slug'=>'media_campaign','retrieving_results'=>'first']);
    //if(collect($media_campaign_id)->isNotEmpty() && $media_campaign_id->value !=''){
    $media_campaign = ThrcHelpers::getMediaCampaign([]);
    //}
    $notable_books = ThrcHelpers::getNotableBooks($request->all());
    //dd(collect($media_campaign)->isNotEmpty());
    //dd($knowledges,$media_campaign);

    //$target = ThrcHelpers::getTarget($request->all());
    $issue =  ThrcHelpers::getIssue($request->all());
    $articles_research = ThrcHelpers::getDataArticlesResearch(['limit'=>'1','retrieving_results'=>'get','page_layout'=>'articles_research','featured'=>false]);
    $thaihealth_watch= ThrcHelpers::getDataThaihealthWatch(['limit'=>'1','retrieving_results'=>'get','page_layout'=>'thaihealth_watch','featured'=>false]);
    $learning_area_creates_direct_experience= ThrcHelpers::getDataLearningAreaCreatesDirectExperience(['limit'=>'1','retrieving_results'=>'get','page_layout'=>'learning_area_creates_direct_experience','featured'=>false]);
    $include_statistics = ThrcHelpers::getDataIncludeStatistics(['limit'=>'1','retrieving_results'=>'get','page_layout'=>'include_statistics','featured'=>false]);
    $time_cache_issue  = ThrcHelpers::time_cache(15);
    $time_cache_media  = ThrcHelpers::time_cache(5);

    $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
    $logo = ($logo_value ? asset($logo_value->value) :asset('themes/thrc/images/no-image-icon-3.jpg'));

    $event_calendar = ThrcHelpers::getEventCalendar();
    
    //dd($event_calendar->count());

    //dd($learning_area_creates_direct_experience);

@endphp
@php
				
@endphp
@extends('layouts.app')
@section('content')
@include('partials.banners.main')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_search">
                <div class="col-xs-12 col-sm-5 col-md-4 col-md-offset-3 text_search">
                    <h1>SEARCH</h1>
                  <!--  <p>Lorem Ipsum is simply dummy text of the printing 
  and typesetting industry. </p> -->
                </div>
                <div class="col-xs-12 col-sm-7 col-md-5 wrap_searchbox">
                    <div class="searchbox">
                        {{ Form::open(['url' => route('media-list'),'method' =>'get','id'=>'form_search']) }}
                        <div class="input_seach">{{ Form::text('keyword',old('keyword'),['class'=>'','placeholder'=>'คำค้น','maxlength'=>'50','id'=>'keyword']) }}<div><img id="icon_search" src="{{ asset('themes/thrc/images/search.svg') }}"></div></div>
                                <div class="selectbox">
                                    <select name="issue">
                                        <option value="0" >ประเด็น</option>
                                        @if($issue->count())
                                            @foreach($issue AS $key=>$value)
                                                @php
                                                    
                                                    if (Cache::has('issue_count_'.$value->id)){
                                                        $issue_count = Cache::get('issue_count_'.$value->id);
                                                    }else{
                                                        $issue_count = $value->children->count();
                                                        Cache::put('issue_count_'.$value->id,$issue_count,$time_cache_issue);
                                                        $issue_count = Cache::get('issue_count_'.$value->id);
                                                    }

                                                @endphp
                                                @if ($issue_count > 0)
                                                    @php
                                                        //dd($value->parent_id);

                                                        if (Cache::has('issue_children_'.$value->id)){
                                                            $children = Cache::get('issue_children_'.$value->id);
                                                        }else{

                                                            $children = $value->children->sortBy('name');
                                                            Cache::put('issue_children_'.$value->id,$children,$time_cache_issue);
                                                            $children = Cache::get('issue_children_'.$value->id);
                                                        }

                                                    @endphp
                                                    <optgroup label="{{ $value->name }}">
                                                        @if(!empty($value->issues_id))
                                                        <option value="{{ $value->issues_id }}">{{ $value->name }}</option>
                                                        @endif
                                                    @foreach($children as $children)
                                                        @php
                                                            //dd($children);
                                                        @endphp
                                                        <option value="{{ $children->issues_id }}">{{ $children->name }}</option>
                                                    @endforeach
                                                    </optgroup>
                                                @else
                                                    <option value="{{ $value->issues_id }}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                        <button class="btn_search">ค้นหา</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
    <section class="row wow fadeInDown rowinteresting">
        <div class="container">
            <div class="row row_head_interesting">
                <div class="col-xs-12 head_interesting">
                    <h1>ประเด็นที่น่าสนใจ</h1>
                    <a href="{{ route('list-interestingissues') }}">ดูทั้งหมด</a>
                </div>
            </div>
            <div class="row">
                @if($health_literacy->count())
                    @foreach($health_literacy AS $key=>$value)
                        @php
                            //dd($value); 
                        @endphp
                        <figure class="col-xs-12 col-sm-5 item_interesting_hl">
                                <a href="{{ route('list-health-literacy-category') }}">
                                    <img data-src="{{ $value['cover_desktop'] }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
                                </a>
                        </figure>
                    @endforeach
                @endif  
                <div class="col-xs-12 col-sm-7 wrap_item_interesting">
                    <div class="row">
                        @if($interesting_issues->count())
                            @foreach($interesting_issues AS $key=>$value)
                                
                                @php
                                    //dd($value); 
                                @endphp
                                <figure class="col-xs-12 col-sm-6 item_interesting">
                                        <a href="{{ $value['url'] }}">
                                            <img data-src="{{ $value['cover_desktop'] }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
                                            <figcaption>
                                                <img src="{{ asset('themes/thrc/images/open-book.svg') }}">
                                                <h1>{{ $value['title'] }}</h1>
                                                <p class="dotmaster">{{ strip_tags($value['description']) }}</p>
                                            </figcaption>
                                        </a>
                                </figure>
                                
                            @endforeach
                        @endif  
                    </div>
                </div>
                
            </div><!-- End Row -->
        </div>
    </section>
    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_article_book">
                <div class="col-xs-12 col-sm-3">
                    <div class="row">
                        <div class="col-xs-12 head_article">
                            <h1>บทความ / งานวิจัย</h1>
                            <a href="{{ route('list-articles-research') }}"></a>
                        </div>
                    </div>
                    <div class="row">
                        @if($articles_research->count())
                            @foreach($articles_research AS $key=>$value)
                                @php
                                    //dd($value);
                                @endphp
                                <figure class="col-xs-12 item_article">
                                    <a href="{{ $value['url'] }}">
                                        <div class="photo_coverarticle">
                                            <img data-src="{{ $value['cover_desktop']  }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value['title'] }}" class="img-responsive">
                                        </div>
                                        <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                        <p class="dotmaster">{{ strip_tags($value['description']) }}</p>
                                        <div class="readmore">อ่านต่อ</div>
                                    </a>
                                </figure>          
                            @endforeach
                        @endif
                    </div>
                </div>
                @php
                   //dd($articles_research);
                @endphp
                <div class="col-xs-12 col-sm-3">
                    <div class="row">
                        <div class="col-xs-12 head_article">
                            <h1>Thaihealth Watch</h1>
                            <a href="{{ route('list-thaihealth-watch') }}" class="click_thaihealth_watch"></a>
                        </div>
                    </div>
                    <div class="row">
                        @if($thaihealth_watch->count())
                            @foreach($thaihealth_watch AS $key=>$value)
                                @php
                                    // if(Auth::check()){
                                    //     $value['url'] = $value['url'];
                                    //     $value['description'] = $value['description'];
                                    // }else{
                                    //     $value['url'] = '#';
                                    //     $value['description'] = substr($value['description'],0,50).'...';
                                    // }

                                    $value['url'] = $value['url'];
                                    $value['description'] = $value['description'];
                                @endphp
                                <figure class="col-xs-12 item_article">
                                    <a href="{{ $value['url'] }}" class="click_thaihealth_watch">
                                    	<div class="photo_coverarticle">
                                        <img data-src="{{ $value['cover_desktop']  }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value['title'] }}" class="img-responsive">
                                    	</div>
                                        <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                        <p class="dotmaster">{{  strip_tags($value['description']) }}</p>
                                        <div class="readmore">อ่านต่อ</div>
                                    </a>
                                </figure>          
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <div class="row">
                        <div class="col-xs-12 head_article">
                            <h1>รวมข้อมูลสถิติ</h1>
                            <a href="{{ route('list-include-statistics') }}" class="click_include_statistics"></a>
                        </div>
                    </div>
                    <div class="row">
                        @if($include_statistics->count())
                            @foreach($include_statistics AS $key=>$value)
                                @php
                                    // if(Auth::check()){
                                    //     $value['url'] = $value['url'];
                                    //     $value['description'] = $value['description'];
                                    // }else{
                                    //     $value['url'] = '#';
                                    //     $value['description'] = substr($value['description'],0,50).'...';
                                    // }

                                    $value['url'] = $value['url'];
                                    $value['description'] = $value['description'];
                                @endphp
                                <figure class="col-xs-12 item_article">
                                    <a href="{{ $value['url'] }}" class="click_include_statistics">
                                    	<div class="photo_coverarticle">
                                        <img data-src="{{ $value['cover_desktop']  }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value['title'] }}" class="img-responsive">
                                    	</div>
                                        <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                        <p class="dotmaster">{{  strip_tags($value['description']) }}</p>
                                        <div class="readmore">อ่านต่อ</div>
                                    </a>
                                </figure>          
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <div class="bg_book">
                        <h1>หนังสือเด่น</h1>
                        <div class="owl-book owl-carousel owl-theme">
                        @if($notable_books->count())
                            @foreach($notable_books AS $key=>$value)
                                @php
                                    //dd($value);
                                    // if($json->Format =='pdf'){
                                    //     $path_file = route('preview-flipbook',\Hashids::encode($key));
                                    // }
                                @endphp
                                <div>
                                    <a href="{{ $value['link'] }}" target="_blank">
                                    <div class="bookimg">
                                        <img data-src="{{ $value['cover_desktop'] }}"  src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value['title'] }}" class="img-responsive">
                                    </div>
                                    <div class="wrap_textbook">
                                        <p class="dotmaster">
                                            {{ $value['title'] }}
                                        </p>
                                    </div>
                                    </a>
                                </div>       
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_knowledge">
                @if(collect($knowledges)->isNotEmpty() && $knowledges->id !='')
                @php
                    $json = ($knowledges->json_data !='' ? json_decode($knowledges->json_data):'');
                    //dd($json);
                    if($knowledges->template !='Multimedia'){
                        $knowledges->urlknowledges = $knowledges->id;
                    }
                    $knowledges->cover_desktop = $knowledges->getMedia('cover_desktop')->isNotEmpty() ? asset($knowledges->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));

                    //dd($knowledges);
                @endphp
                <div class="col-xs-12 col-sm-6 item_knowledge">
                    
                        <figcaption>
                            <div class="head_article ">
                                <h1>ชุดความรู้</h1>
                                <a href="{{ route('knowledges-list',['filter'=>'knowledges']) }}">ดูทั้งหมด</a>
                            </div>
                            
                            <p class="dotmaster">{{ $knowledges->title }}</p>
                            
                        </figcaption>
                    <a href="{{ $knowledges->urlknowledges }}">
                        @if($knowledges->template =='Multimedia')
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
                                <figure class="knowledge_video">
                                    {!! $embed !!}
                                </figure>
                            @elseif($format_type =='mp4')
                                <figure class="knowledge_video">
                                    <video width="420" height="315" controls>
                                        <source src="{{ $json->FileAddress }}" type="video/mp4">
                                      </video>
                                </figure>
                            @else
                            
                            @endif
                        @else
                        	<div class="photo_coverarticle">
                            <img data-src="{{ $knowledges->cover_desktop }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $knowledges->title }}" class="img-responsive">
                        	</div>
                        @endif
                    </a>
                </div>
                @endif
                @if(collect($media_campaign)->isNotEmpty() && $media_campaign->id !='')
                @php
                    $json = ($media_campaign->json_data !='' ? json_decode($media_campaign->json_data):'');
                    //dd($json);
                    if($media_campaign->template !='Multimedia'){
                    $media_campaign->urlmediacampaign = $media_campaign->id;
                    }
                    $media_campaign->cover_desktop = $media_campaign->getMedia('cover_desktop')->isNotEmpty() ? asset($media_campaign->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
                @endphp
                <div class="col-xs-12 col-sm-6 item_knowledge">
                    
                        <figcaption>
                            <div class="head_article ">
                                <h1>สื่อรณรงค์</h1>
                                <a href="{{ route('media-campaign-list',['filter'=>'media_campaign']) }}">ดูทั้งหมด</a>
                            </div>
                            
                            <p class="dotmaster">{{ $media_campaign->title }}</p>
                        </figcaption>
                        <a href="{{ $media_campaign->urlmediacampaign }}">
                        @if($media_campaign->template =='Multimedia')
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
                                <figure class="knowledge_video">
                                    {!! $embed !!}
                                </figure>
                            @elseif($format_type =='mp4')
                                <figure class="knowledge_video">
                                    <video width="420" height="315" controls>
                                        <source src="{{ $json->FileAddress }}" type="video/mp4">
                                      </video>
                                </figure>
                            @else

                            @endif
                        @else
                        	<div class="photo_coverarticle">
                            <img data-src="{{ $media_campaign->cover_desktop  }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $media_campaign->title }}" class="img-responsive">
                        	</div>
                        @endif
                    </a>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-9">
                    <div class="head_news">
                        <h1>พื้นที่เรียนรู้สร้างประสบการณ์ตรง</h1>
                        <a href="{{ route('list-learning-area-creates-direct-experience') }}">ดูทั้งหมด</a>
                    </div>
                    <div class="wrap_slidecomingsoon">
                        <div class="owl-calendar owl-carousel owl-theme">
                            @if($learning_area_creates_direct_experience->count())
                                @foreach($learning_area_creates_direct_experience AS $key=>$value)
                                <div class="item_comingsoon">
                                    <a href="{{ $value['url']  }}"><img data-src="{{ $value['cover_desktop']  }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value['title'] }}" class="img-responsive"></a>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 item_knowledge box_calendarhome">
                    <figcaption>
                        <div class="head_news ">
                            <h1>ปฏิทินกิจกรรม</h1>
                            <a href="{{ route('list-event-calendar') }}"></a>
                        </div>
                        
                        <div class="wrap_calendarhome">
                            @if($event_calendar->count() >0)
                            
                            <div class="owl-calendar owl-carousel owl-theme">
                                @foreach($event_calendar AS $key=>$value)
                                <div class="wrap_calendar_list">
                                    <div class="c_date">{{ Carbon\Carbon::parse($value->start_date)->format('j') }}</div>
                                    <div class="c_month">{{ ThrcHelpers::month($value->start_date) }}</div>
                                    <a href="{{ route('article-detail',$value->slug) }}" class="dotmaster">{{ $value->title }}</a>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="owl-calendar owl-carousel owl-theme">
                                @php
                                    $data_now =  Carbon\Carbon::now();
                                @endphp
                                <div class="wrap_calendar_list">
                                    <div class="c_date">{{ Carbon\Carbon::parse($data_now)->format('j') }}</div>
                                    <div class="c_month">{{ ThrcHelpers::month($data_now) }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </figcaption>
                    
                </div>
            </div>
        </div>
    </section>

    <section class="row wow fadeInDown" style="margin: 30px;"></section>
<!--    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_news">
                    <h1>ข่าวสารและกิจกรรม</h1>
                    <a href="{{ route('list-news-event') }}">ดูทั้งหมด</a>
                </div>
            </div>
            <div class="row row_newsd">
                <div class="col-xs-12 col-sm-5">
                    @if($news->count())
                        @foreach($news AS $key=>$value)
                            @php
                                //dd($value);
                            @endphp
                            @if($key ==0)
                            <div class="row hl_news">
                                    <figure class="col-xs-12 col-sm-6">
                                        <a href="{{ route('news-event-detail',$value['slug']) }}">
                                            <img data-src="{{ asset($value['cover_desktop']) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value['title'] }}">
                                        </a>
                                    </figure>
                                    <figcaption class="col-xs-12 col-sm-6">
                                        <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                        <p class="dotmaster">{{ $value['description']  }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value['created_at'])->format('d.m.').(Carbon\Carbon::parse($value['created_at'])->format('Y')+543) }}</div>
                                            <div class="newsview">{{ $value['hit'] }}</div>
                                        </div>
                                        <a class="readmore" href="{{ route('news-event-detail',$value['slug']) }}">อ่านต่อ</a>
                                    </figcaption>
                                </div>
                            @php
                                break;   
                            @endphp
                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="col-xs-12 col-sm-4 wrap_news_list">
                        @if($news->count())
                        @foreach($news AS $key=>$value)
                            @if($key >0)
                            <a href="{{ route('news-event-detail',$value['slug']) }}" class="news_list">
                                <figure>
                                    <img data-src="{{ asset($value['cover_desktop']) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value['title'] }}">
                                </figure><figcaption>
                                    <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                    <p class="dotmaster">{{ $value['description']  }}</p>
                                        <div class="dateandview_s">
                                            <div class="newsdate">{{ Carbon\Carbon::parse($value['created_at'])->format('d.m.').(Carbon\Carbon::parse($value['created_at'])->format('Y')+543) }}</div>
                                            <div class="newsview">{{ $value['hit'] }}</div>
                                        </div>
                                </figcaption>
                            </a>
                            @endif
                            @endforeach
                        @endif
                </div> 

                

            </div>
        </div>
    </section> -->

    @include('partials.banners.footer')
@endsection
@section('meta')
    @parent
    <title>ศูนย์บริการข้อมูล</title>
    <meta charset="UTF-8">
    <meta name="description" content="ศูนย์บริการข้อมูล">
    <meta name="keywords" content="ศูนย์บริการข้อมูล">
    <meta name="author" content="THRC">
    <meta property="og:image" content="{{ $logo  }}" />

@endsection
@section('style')
	@parent
<style>
    optgroup{
        background-color: #002c3e;
    }
    option{
        background-color: #002c3e;
    }
</style>
@endsection
@section('js')
	@parent
<script>
    $(document).ready(function() {
        url_ajax = "{{ route('ajaxData') }}";
        check_register = "{{ (session('status') ? 1:0) }}";
        check_login = "{{ (session('login_status') ? session('login_status'):'') }}";  
        check_activate = "{{ (session('activate_status') ? session('activate_status'):'') }}";
        check_forgotpassword = "{{ (session('forgotpassword_status') ? session('forgotpassword_status'):'') }}";
        check_resetpassword = "{{ (session('resetpassword_status') ? session('resetpassword_status'):'') }}";
        check_request_media = "{{ (session('request_media') ? session('request_media'):'') }}";
        check_book_an_exhibition = "{{ (session('book_an_exhibition') ? session('book_an_exhibition'):'') }}";
        check_update= "{{ (session('update') ? 1:0) }}";

        if(check_login =='success'){
            $('.iconuser img').click();
            // Swal.fire({
            //   position: 'center',
            //   type: 'success',
            //   title: 'เปิดใช้งานบัญชีสำเร็จ',
            //   showConfirmButton: false,
            //   timer: 5000
            // });
        }

        if(check_update ==1){
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'แก้ไขข้อมูลสำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_login =='not_success'){
            Swal.fire({
              position: 'center',
              type: 'error',
              title: 'ไม่พบบัญชีผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_register ==1){
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'สมัครสมาชิกสำเร็จ กรุณาเปิดใช้งานบัญชีที่อีเมลของคุณก่อนเข้าสู่ระบบ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_activate =='success'){
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'เปิดใช้งานบัญชีสำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_activate =='not_success'){
            Swal.fire({
              position: 'center',
              type: 'error',
              title: 'ไม่พบบัญชี หรือ บัญชีเปิดใช้สำเร็จแล้ว',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_forgotpassword =='success'){
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'ส่งอีเมลลืมรหัสผ่านสำเร็จ กรุณาเปิดรีเซ็ตรหัสผ่านใหม่ที่อีเมลของคุณ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_forgotpassword =='not_success'){
            Swal.fire({
              position: 'center',
              type: 'error',
              title: 'ส่งอีเมลลืมรหัสผ่านไม่สำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_resetpassword =='success'){
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'รีเซ็ตรหัสผ่านสำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }


        if(check_request_media =='success'){
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'ส่งคำขอรับสื่อสำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_book_an_exhibition =='success'){
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'จองนิทรรศการสำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        $('#keyword').keyup(function(){
            //console.log($(this).val().length);
            if($(this).val().length >= 2){
                //console.log("Case True");
                $('#keyword').typeahead({
                    source:  function (query, process) {
                    return $.get(url_ajax, { query: query }, function (data) {
                            //console.log(data);
                            return process(data);
                        });
                    }
                });
            }
        });

        $('#icon_search').click(function(){
            $('#form_search').submit();
        });

    });
</script>
@endsection
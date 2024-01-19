@inject('request', 'Illuminate\Http\Request')
@php

    $ncds = ThrcHelpers::getSetting(['slug' => 'ncds_cover_image', 'retrieving_results' => 'first']);
    $ncds_logo = $ncds ? asset($ncds->value) : asset('themes/thrc/images/no-image-icon-3.jpg');
    //dd($ncds_logo);

    $interesting_issues = ThrcHelpers::getInterestingIssues(['limit' => '4', 'retrieving_results' => 'get', 'page_layout' => 'interesting_issues', 'featured' => false]);
    $news = ThrcHelpers::getNews(['limit' => '4', 'retrieving_results' => 'get', 'page_layout' => 'news_event', 'featured' => false]);
    //$knowledges_id = ThrcHelpers::getSetting(['slug'=>'knowledges','retrieving_results'=>'first']);
    //if(collect($knowledges_id)->isNotEmpty() && $knowledges_id->value !=''){
    $knowledges = ThrcHelpers::getMediaKnowledges([]);
    //}
    //$media_campaign_id = ThrcHelpers::getSetting(['slug'=>'media_campaign','retrieving_results'=>'first']);
    //if(collect($media_campaign_id)->isNotEmpty() && $media_campaign_id->value !=''){
    $media_campaign = ThrcHelpers::getMediaCampaign([]);
    //}
    //$notable_books = ThrcHelpers::getNotableBooks($request->all());
    //$notable_books = collect();
    //dd($notable_books);
    //dd(collect($media_campaign)->isNotEmpty());
    //dd($knowledges,$media_campaign);

    //$target = ThrcHelpers::getTarget($request->all());
    $issue = ThrcHelpers::getIssue($request->all());
    $articles_research = ThrcHelpers::getDataArticlesResearch(['limit' => '2', 'retrieving_results' => 'get', 'page_layout' => 'articles_research', 'featured' => false]);
    //$thaihealth_watch= ThrcHelpers::getDataThaihealthWatch(['limit'=>'1','retrieving_results'=>'get','page_layout'=>'thaihealth_watch','featured'=>false]);
    $learning_area_creates_direct_experience = ThrcHelpers::getDataLearningAreaCreatesDirectExperience(['limit' => '1', 'retrieving_results' => 'get', 'page_layout' => 'learning_area_creates_direct_experience', 'featured' => false]);
    $include_statistics = ThrcHelpers::getDataIncludeStatistics(['limit' => '1', 'retrieving_results' => 'get', 'page_layout' => 'include_statistics', 'featured' => false]);
    $time_cache_issue = ThrcHelpers::time_cache(15);
    $time_cache_media = ThrcHelpers::time_cache(5);

    $logo_value = ThrcHelpers::getSetting(['slug' => 'logo_desktop', 'retrieving_results' => 'first']);
    $logo = $logo_value ? asset($logo_value->value) : asset('themes/thrc/images/no-image-icon-3.jpg');
    $event_calendar = ThrcHelpers::getEventCalendar();

    $thaihealth_watch = ThrcHelpers::getSetting(['slug' => 'thaihealth_watch_cover_image', 'retrieving_results' => 'first']);
    $thaihealth_watch_logo = $thaihealth_watch ? asset($thaihealth_watch->value) : asset('themes/thrc/images/no-image-icon-3.jpg');

    //dd($event_calendar->count());

    //dd($learning_area_creates_direct_experience);

    //env('SSO_USER') ='test';

    //dd(env('SSO_USER'));

@endphp
@php

@endphp
@extends('layouts.app')
@section('content')
    @include('partials.banners.main')

    <style>
        .newbanner2::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translate(-50%, 0);
            width: 100%;
            height: 100%;
            background-color: #003b49;
            width: 100vw;
        }

        .newbanner2 a::before {
            display: block;
            content: "";
            width: 100%;
            padding-bottom: 32%;
        }

        .newbanner2 a img {
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .head-watch::before {
            padding-bottom: 0% !important;
        }

        .arrow {
            width: 0;
            height: 0;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
            border-left: 20px solid #000000;
        }
    </style>
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
                        {{ Form::open(['url' => route('media-list'), 'method' => 'get', 'id' => 'form_search']) }}
                        {{ csrf_field() }}
                        <div class="input_seach">
                            {{ Form::text('keyword', old('keyword'), ['class' => '', 'placeholder' => 'คำค้น', 'maxlength' => '50', 'id' => 'keyword']) }}
                            <div><img id="icon_search" src="{{ asset('themes/thrc/images/search.svg') }}"></div>
                        </div>
                        <div class="selectbox">
                            <select id="search_opt" name="issue">
                                <option value="0">ประเด็น</option>
                                @if ($issue->count())
                                    @foreach ($issue as $key => $value)
                                        @php

                                            if (Cache::has('issue_count_' . $value->id)) {
                                                $issue_count = Cache::get('issue_count_' . $value->id);
                                            } else {
                                                $issue_count = $value->children->count();
                                                Cache::put('issue_count_' . $value->id, $issue_count, $time_cache_issue);
                                                $issue_count = Cache::get('issue_count_' . $value->id);
                                            }

                                        @endphp
                                        @if ($issue_count > 0)
                                            @php
                                                //dd($value->parent_id);

                                                if (Cache::has('issue_children_' . $value->id)) {
                                                    $children = Cache::get('issue_children_' . $value->id);
                                                } else {
                                                    $children = $value->children->sortBy('name');
                                                    Cache::put('issue_children_' . $value->id, $children, $time_cache_issue);
                                                    $children = Cache::get('issue_children_' . $value->id);
                                                }

                                            @endphp
                                            <optgroup label="{{ $value->name }}">
                                                @if (!empty($value->issues_id))
                                                    <option value="{{ $value->issues_id }}">{{ $value->name }}</option>
                                                @endif
                                                @foreach ($children as $children)
                                                    @php
                                                        //dd($children);
                                                    @endphp
                                                    <option value="{{ $children->issues_id }}">{{ $children->name }}
                                                    </option>
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

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>


    <section class="row wow fadeInDown rowinteresting">
        <div class="container">
            <div class="row">
                <figure class="col-xs-12 col-sm-5 item_interesting_hl">
                    <div class="row row_head_interesting">
                        <div class="col-xs-12 head_interesting">
                            <h1>NCDs</h1>
                        </div>
                    </div>
                    <a href="{{ route('ncds-list') }}">
                        <img src="{{ $ncds_logo }}">
                        <!--<figcaption>
                                                                    <h1 class="dotmaster">การออกกำลังกาย</h1>
                                                                    <p class="dotmaster">It is a long established </p>
                                                                </figcaption>-->
                    </a>
                </figure>
                <div class="col-xs-12 col-sm-7 wrap_item_interesting">
                    <div class="row row_head_interesting">
                        <div class="col-xs-12 head_news">
                            <h1>ประเด็นที่น่าสนใจ </h1>
                            <a href="{{ route('list-interestingissues') }}">ดูทั้งหมด</a>
                        </div>
                    </div>
                    <div class="row">
                        @if ($interesting_issues->count())
                            @foreach ($interesting_issues as $key => $value)
                                @php
                                    //dd($value);
                                @endphp
                                <figure class="col-xs-12 col-sm-6 item_interesting">
                                    <a href="{{ $value['url'] }}"
                                        onclick="clickview('view_item','{{ $value['id'] }}','{{ $value['tags'] != null ? $value['tags'] : 'null' }}','{{ $value['category_id'] != null ? $value['category_id'] : 'null' }}')">
                                        <img data-src="{{ $value['cover_desktop'] }}"
                                            src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
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
            </div>
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
                        @if ($articles_research->count())
                            @foreach ($articles_research as $key => $value)
                                @php
                                    //dd($value);
                                @endphp
                                @if ($key == 0)
                                    <figure class="col-xs-12 item_article">
                                        <a href="{{ $value['url'] }}"
                                            onclick="clickview('view_item','{{ $value['id'] }}','{{ $value['tags'] != null ? $value['tags'] : 'null' }}','{{ $value['category_id'] != null ? $value['category_id'] : 'null' }}')">
                                            <div class="photo_coverarticle">
                                                <img src="{{ $value['cover_desktop'] }}" alt="{{ $value['title'] }}"
                                                    class="img-responsive">
                                            </div>
                                            <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                            <p class="dotmaster">{{ strip_tags($value['description']) }}</p>
                                            <div class="readmore">อ่านต่อ</div>
                                        </a>
                                    </figure>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                @php
                    //dd($articles_research);
                @endphp
                <div class="col-xs-12 col-sm-3">
                    <div class="row">
                        <div class="col-xs-12 head_article" style="margin: 16px;">
                            <h1></h1>
                        </div>
                    </div>
                    <div class="row">
                        @if ($articles_research->count())
                            @foreach ($articles_research as $key => $value)
                                @php
                                    $value['url'] = $value['url'];
                                    $value['description'] = $value['description'];
                                @endphp
                                @if ($key == 1)
                                    <figure class="col-xs-12 item_article">
                                        <a href="{{ $value['url'] }}" class="click_thaihealth_watch"
                                            onclick="clickview('view_item','{{ $value['id'] }}','{{ $value['tags'] != null ? $value['tags'] : 'null' }}','{{ $value['category_id'] != null ? $value['category_id'] : 'null' }}')">
                                            <div class="photo_coverarticle">
                                                <img src="{{ $value['cover_desktop'] }}" alt="{{ $value['title'] }}"
                                                    class="img-responsive">
                                            </div>
                                            <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                            <p class="dotmaster">{{ strip_tags($value['description']) }}</p>
                                            <div class="readmore">อ่านต่อ</div>
                                        </a>
                                    </figure>
                                @endif
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
                        @if ($include_statistics->count())
                            @foreach ($include_statistics as $key => $value)
                                @php
                                    // if(Auth::check()){
                                    // $value['url'] = $value['url'];
                                    // $value['description'] = $value['description'];
                                    // }else{
                                    // $value['url'] = '#';
                                    // $value['description'] = substr($value['description'],0,50).'...';
                                    // }

                                    $value['url'] = $value['url'];
                                    $value['description'] = $value['description'];
                                @endphp
                                <figure class="col-xs-12 item_article">
                                    <a href="{{ $value['url'] }}" class="click_include_statistics"
                                        onclick="clickview('view_item','{{ $value['id'] }}','{{ $value['tags'] != null ? $value['tags'] : 'null' }}','{{ $value['category_id'] != null ? $value['category_id'] : 'null' }}')">
                                        <div class="photo_coverarticle">
                                            <img src="{{ $value['cover_desktop'] }}" alt="{{ $value['title'] }}"
                                                class="img-responsive">
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
                <div class="col-xs-12 col-sm-3" id="app">
                    <notable-books-component title="หนังสือเด่น" api_url="{{ route('api.notable-books') }}"
                        access_token="{{ env('NDCS_AUTH', '$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
                        path_owl_left_direction_arrow="{{ asset('themes/thrc/images/owl-left-direction-arrow.svg') }}"
                        path_owl_right_thin_chevron="{{ asset('themes/thrc/images/owl-right-thin-chevron.svg') }}"></notable-books-component>
                </div>
            </div>
        </div>
    </section>
    <section class="row wow fadeInDown wrap-newbanner">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 newbanner">
                    <a href="{{ route('thaihealth-watch') }}"><img src="{{ $thaihealth_watch_logo }}"
                            alt="thaihealth-watch"></a>
                </div>
            </div>
        </div>
    </section>
    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_knowledge">
                @if (collect($knowledges)->isNotEmpty() && $knowledges->id != '')
                    @php
                        $json = $knowledges->json_data != '' ? json_decode($knowledges->json_data) : '';
                        //dd($json);
                        if ($knowledges->template != 'Multimedia') {
                            $knowledges->urlknowledges = $knowledges->id;
                        }
                        $knowledges->cover_desktop = $knowledges->getMedia('cover_desktop')->isNotEmpty() ? asset($knowledges->getFirstMediaUrl('cover_desktop', 'thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));

                        //dd($knowledges);

                    @endphp
                    <div class="col-xs-12 col-sm-6 item_knowledge">

                        <figcaption>
                            <div class="head_article ">
                                <h1>ชุดความรู้</h1>
                                <a href="{{ route('knowledges-list', ['filter' => 'knowledges']) }}">ดูทั้งหมด</a>
                            </div>

                            <p class="dotmaster">{{ $knowledges->title }}</p>

                        </figcaption>
                        <a href="{{ $knowledges->urlknowledges }}"
                            onclick="clickview('view_item','{{ $knowledges->id }}','{{ $knowledges->tags != null ? $knowledges->tags : 'null' }}','{{ $knowledges['category_id'] != null ? $knowledges['category_id'] : 'null' }}')">


                            @if ($knowledges->template == 'Multimedia')
                                @php
                                    $format_type = '';
                                    switch ($json->Format) {
                                        case '.avi':
                                            $embed = ThrcHelpers::convertYoutube($json->FileAddress);
                                            $format_type = 'youtube';
                                            break;
                                        case 'mp4':
                                            $format_type = 'mp4';
                                            break;
                                        default:
                                    }
                                @endphp
                                @if ($format_type == 'youtube')
                                    <figure class="knowledge_video">
                                        {!! $embed !!}
                                    </figure>
                                @elseif($format_type == 'mp4')
                                    <figure class="knowledge_video">
                                        <video width="420" height="315" controls>
                                            <source src="{{ $json->FileAddress }}" type="video/mp4">
                                        </video>
                                    </figure>
                                @else
                                @endif
                            @else
                                <div class="photo_coverarticle">
                                    <img src="{{ $knowledges->cover_desktop }}" alt="{{ $knowledges->title }}"
                                        class="img-responsive">
                                </div>
                            @endif
                        </a>
                    </div>
                @endif
                @if (collect($media_campaign)->isNotEmpty() && $media_campaign->id != '')
                    @php

                        $json = $media_campaign->json_data != '' ? json_decode($media_campaign->json_data) : '';
                        //dd($json);
                        if ($media_campaign->template == 'Multimedia') {
                            $media_campaign->urlmediacampaign = $media_campaign->id;
                        }
                        $media_campaign->cover_desktop = $media_campaign->getMedia('cover_desktop')->isNotEmpty() ? asset($media_campaign->getFirstMediaUrl('cover_desktop', 'thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
                        $url = route('media-detail', Hashids::encode($media_campaign->id));
                    @endphp
                    <div class="col-xs-12 col-sm-6 item_knowledge">

                        <figcaption>
                            <div class="head_article ">
                                <h1>สื่อรณรงค์</h1>
                                <a href="{{ route('media-campaign-list', ['filter' => 'media_campaign']) }}">ดูทั้งหมด</a>
                            </div>

                            <p class="dotmaster">{{ $media_campaign->title }}</p>
                        </figcaption>
                        <a href="{{ $url }}"
                            onclick="clickview('view_item','{{ $media_campaign->id }}','{{ $media_campaign->tags != null ? $media_campaign->tags : 'null' }}','{{ $media_campaign['category_id'] != null ? $media_campaign['category_id'] : 'null' }}')">
                            @if ($media_campaign->template == 'Multimedia')
                                @php
                                    $format_type = '';
                                    switch ($json->Format) {
                                        case '.avi':
                                            $embed = ThrcHelpers::convertYoutube($json->FileAddress);
                                            $format_type = 'youtube';
                                            break;
                                        case 'mp4':
                                            $format_type = 'mp4';
                                            break;
                                        default:
                                    }
                                @endphp
                                @if ($format_type == 'youtube')
                                    <figure class="knowledge_video">
                                        {!! $embed !!}
                                    </figure>
                                @elseif($format_type == 'mp4')
                                    <figure class="knowledge_video">
                                        <video width="420" height="315" controls>
                                            <source src="{{ $json->FileAddress }}" type="video/mp4">
                                        </video>
                                    </figure>
                                @else
                                @endif
                            @else
                                <div class="photo_coverarticle">
                                    <img src="{{ $media_campaign->cover_desktop }}" alt="{{ $media_campaign->title }}"
                                        class="img-responsive">
                                </div>
                            @endif
                        </a>
                    </div>
                @endif
            </div>
            <br> <br>
            <!-- <div class="container">
                                                        <figcaption>
                                                            <div class="head_article " style="position:relative;">
                                                                <div class="bg_co"></div>
                                                                <h1>Co - Creation</h1>
                                                                <img src="https://persona-uat.thaihealth.or.th/images/bg_w.png" alt="Test" class="img-responsive bg-co-img">

                                                            </div>

                                                        </figcaption>
                                                    </div> -->
            <br><br>
        </div>
    </section>
    <section class="row wow fadeInDown wrap-newbanner">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 newbanner2">
                    <br>
                    <br>
                    <div class="head_interesting" style="margin-bottom: 64px;">
                        <div class="row">
                            <div class="col-lg-10">
                                <h1>แคมเปญรณรงค์</h1>
                            </div>
                            <div class="col-lg-2" style="justify-content: end;display: flex;">
                                <div class="head_exibition" style="width: 100%;margin:0px !important;">
                                    <a class="head-watch"
                                        href="https://resourcecenter.thaihealth.or.th/media-campaigns/list?filter=media_campaign"
                                        style="color:white;position:unset !important;">ดูทั้งหมด</a>
                                </div>

                            </div>
                        </div>
                        @php
                            $video = DB::table('List_Media')
                                ->where('show_data', '=', '1')
                                ->first();
                            if (!empty($video)) {
                                if (is_null($video->local_path)) {
                                    if (!empty($data['DirectLink'])) {
                                        $FileAddress = $data['FileAddress']??'';
                                    } else {
                                        $FileAddress = $data['DirectLink']??'';
                                    }
                                    $FileAddress = $data['FileAddress']??'';
                                } else {
                                    $FileAddress = url('mediadol' . '/' . $video->UploadFileID . '/' . $video->local_path);
                                }
                            } else {
                                $FileAddress = null;
                            }

                            // dd($FileAddress);

                        @endphp
                        <div class="row">
                            <div class="col-lg-12"
                                style="margin-top: 16px;display: flex;  align-items: center;justify-content: center;">
                                <video controls autoplay muted style="max-width:1138pxpx;max-height:640px;">
                                    <source src="{{ $FileAddress }}" type="video/mp4">
                                    <!-- <source src="https://resourcecenter-uat.thaihealth.or.th/campainvideo/%E0%B8%84%E0%B8%99%E0%B8%9E%E0%B8%B2%E0%B8%99%E0%B9%84%E0%B8%A1%E0%B9%88%E0%B8%97%E0%B8%B4%E0%B9%89%E0%B8%87%E0%B8%81%E0%B8%B1%E0%B8%99%20%E0%B8%8A%E0%B8%B8%E0%B8%99%E0%B8%8A%E0%B8%99%E0%B8%9B%E0%B8%A5%E0%B8%AD%E0%B8%94%E0%B9%80%E0%B8%AB%E0%B8%A5%E0%B9%89%E0%B8%B2-%E0%B8%9A%E0%B8%B8%E0%B8%AB%E0%B8%A3%E0%B8%B5%E0%B9%88%20(%E0%B8%AD.%E0%B8%9E%E0%B8%B2%E0%B8%99%20%E0%B8%88.%E0%B9%80%E0%B8%8A%E0%B8%B5%E0%B8%A2%E0%B8%87%E0%B8%A3%E0%B8%B2%E0%B8%A2).mkv" type="video/mp4"> -->
                                </video>

                                <!-- <a class="" href="{{ route('thaihealth-watch') }}"> -->
                                <!-- <iframe width="100%" height="1005" style="max-width:1138px;max-height:640px;" src="https://www.youtube.com/embed/tgbNymZ7vqY">
                                                                    </iframe> -->
                                <!-- <img src="https://www.youtube.com/watch?v=6AEb-_cGDPM&list=RD6AEb-_cGDPM&start_radio=1" class="w-100 h-100" alt="thaihealth-watch" > -->
                                <!-- </a> -->
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-7 col-lg-12">
                    <div class="head_exibition">
                        <h1>พื้นที่เรียนรู้สร้างประสบการณ์ตรง</h1>
                        <a href="{{ route('list-learning-area-creates-direct-experience') }}">ดูทั้งหมด</a>
                    </div>
                    <div class="wrap_slidecomingsoon">
                        <div class="owl-calendar owl-carousel owl-theme">
                            @if ($learning_area_creates_direct_experience->count())
                                @foreach ($learning_area_creates_direct_experience as $key => $value)
                                    <div class="item_comingsoon">
                                        <a href="{{ $value['url'] }}"><img src="{{ $value['cover_desktop'] }}"
                                                alt="{{ $value['title'] }}" class="img-responsive"></a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <!-- <div class="col-xs-12 col-sm-5">
                                                        <div class="head_exibition">
                                                            <h1>HEALTH ASSESSMENT</h1>
                                                            <a href="{{ route('list-learning-area-creates-direct-experience') }}">ดูทั้งหมด</a>
                                                        </div>
                                                        <div class="wrap_slidecomingsoon">
                                                            <div class="owl-calendar owl-carousel owl-theme">
                                                                @if ($learning_area_creates_direct_experience->count())
    @foreach ($learning_area_creates_direct_experience as $key => $value)
    <div class="item_comingsoon">
                                                                    <a href="{{ $value['url'] }}"><img src="https://resourcecenter.thaihealth.or.th/media/1486/conversions/thumb1366x635.jpg" alt="{{ $value['title'] }}" class="img-responsive"></a>
                                                                </div>
    @endforeach
    @endif
                                                            </div>
                                                        </div>
                                                    </div> -->
                <!-- <div class="col-xs-12 col-sm-3 item_knowledge box_calendarhome">
                                                            <figcaption>
                                                                <div class="head_exibition ">
                                                                    <h1>ปฏิทินกิจกรรม</h1>
                                                                    <a href="{{ route('list-event-calendar') }}"></a>
                                                                </div>
                                                                
                                                                <div class="wrap_calendarhome">
                                                                    @if ($event_calendar->count() > 0)
    <div class="owl-calendar owl-carousel owl-theme">
                                                                        @foreach ($event_calendar as $key => $value)
    <div class="wrap_calendar_list">
                                                                            <div class="c_date">{{ Carbon\Carbon::parse($value->start_date)->format('j') }}</div>
                                                                            <div class="c_month">{{ ThrcHelpers::month($value->start_date) }}</div>
                                                                            <a href="{{ route('article-detail', $value->slug) }}" class="dotmaster">{{ $value->title }}</a>
                                                                        </div>
    @endforeach
                                                                    </div>
@else
    <div class="owl-calendar owl-carousel owl-theme">
                                                                        @php
                                                                            $data_now = Carbon\Carbon::now();
                                                                        @endphp
                                                                        <div class="wrap_calendar_list">
                                                                            <div class="c_date">{{ Carbon\Carbon::parse($data_now)->format('j') }}</div>
                                                                            <div class="c_month">{{ ThrcHelpers::month($data_now) }}</div>
                                                                        </div>
                                                                    </div>
    @endif
                                                                </div>
                                                            </figcaption>
                                                            
                                                        </div> -->
            </div>
        </div>
    </section>


    <section class="row wow fadeInDown" style="margin: 30px;"></section>
    <!--<section class="row wow fadeInDown">
                                                <div class="container">
                                                    <div class="row coc-wrap">
                                                        <div class="col-xs-12 col-sm-6">
                                                            <div class="head_news">
                                                                <h1>Co Creation</h1>
                                                            </div>
                                                            <a href="#" class="coc-img">
                                                                <img src="images/new-img/cocreation.jpg" alt="">
                                                            </a>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6">
                                                            <div class="head_news">
                                                                <h1>Partnership</h1>
                                                            </div>
                                                            <a href="#" class="coc-img">
                                                                <img src="images/new-img/partner.jpg" alt="">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section> -->


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
                                                            @if ($news->count())
    @foreach ($news as $key => $value)
    @php
        //dd($value);
    @endphp
                                                                    @if ($key == 0)
    <div class="row hl_news">
                                                                            <figure class="col-xs-12 col-sm-6">
                                                                                <a href="{{ route('news-event-detail', $value['slug']) }}">
                                                                                    <img src="{{ asset($value['cover_desktop']) }}" alt="{{ $value['title'] }}">
                                                                                </a>
                                                                            </figure>
                                                                            <figcaption class="col-xs-12 col-sm-6">
                                                                                <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                                                                <p class="dotmaster">{{ $value['description'] }}</p>
                                                                                <div class="dateandview">
                                                                                    <div class="newsdate">{{ Carbon\Carbon::parse($value['created_at'])->format('d.m.') . (Carbon\Carbon::parse($value['created_at'])->format('Y') + 543) }}</div>
                                                                                    <div class="newsview">{{ $value['hit'] }}</div>
                                                                                </div>
                                                                                <a class="readmore" href="{{ route('news-event-detail', $value['slug']) }}">อ่านต่อ</a>
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
                                                                @if ($news->count())
    @foreach ($news as $key => $value)
    @if ($key > 0)
    <a href="{{ route('news-event-detail', $value['slug']) }}" class="news_list">
                                                                        <figure>
                                                                            <img src="{{ asset($value['cover_desktop']) }}" alt="{{ $value['title'] }}">
                                                                        </figure><figcaption>
                                                                            <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                                                            <p class="dotmaster">{{ $value['description'] }}</p>
                                                                                <div class="dateandview_s">
                                                                                    <div class="newsdate">{{ Carbon\Carbon::parse($value['created_at'])->format('d.m.') . (Carbon\Carbon::parse($value['created_at'])->format('Y') + 543) }}</div>
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

    <!-- Modal ที่ต้องเอาไปแปะ-->
    <div class="modal fade" tabindex="-1" id="myModal-file" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="top:25%;">
            <div class="modal-content" style="border-radius: 0;max-width:900px;border: none;">
                <div class="modal-body" style="margin: 0;padding:0;">
                    <div style="height: auto;background-color: #259881;padding:30px 20px">

                        <div class="title_div">
                            <span class="text_title">
                                คุณสนใจประเด็นอะไร
                            </span>
                            <span class="text_title_2">
                                เลือกได้มากกว่า 1 ประเด็น
                            </span>
                        </div>


                        <div class="box_body" id="page-list">

                        </div>

                        <div class="box_fotter">

                            <button class="button_fotter_2" onclick="sent_click_back()">
                                ย้อนกลับ
                            </button>
                            <button class="button_fotter" onclick="sent_click()">
                                เสร็จสิ้น
                            </button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal ที่ต้องเอาไปแปะ -->

    @include('partials.banners.footer')
@endsection
@section('meta')
    @parent
    <title>ศูนย์บริการข้อมูล | ศูนย์เรียนรู้สุขภาวะ (สสส.)</title>
    <meta charset="UTF-8">
    <meta name="description"
        content="ศูนย์เรียนรู้สุขภาวะ (สสส.) แหล่งเรียนรู้และบริการข้อมูลข่าวสารด้านสุขภาวะ พื้นที่จุดประกายความคิดแก่สาธารณะผ่าน ข่าวสาร กิจกรรมออนไลน์ ประเด็นที่น่าสนใจ ฯลฯ">
    <meta name="keywords" content="ศูนย์บริการข้อมูล,ศูนย์เรียนรู้สุขภาวะ (สสส.)">
    <meta name="author" content="THRC">
    <meta property="og:image" content="{{ $logo }}" />
@endsection
@section('style')
    @parent

    {{-- style ที่ต้องเอาไปแปะ --}}
    <style>
        .title_div {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .text_title {
            color: #fff;
            font-size: 20px;
        }

        .text_title_2 {
            color: #fff;
            font-size: 15px;
        }

        .box_body {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .active {
            background-color: #ffffff;
            color: #000 !important;
        }

        .card_box {
            color: #fff;
            padding: 5px 10px;
            border: solid rgb(255, 255, 255) 1px;
            border-radius: 0px;
            margin-right: 20px;
            margin-bottom: 20px;
            cursor: pointer;
        }



        .box_fotter {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .button_fotter {
            margin-left: 10px;
            height: 35px;
            width: 145px;
            background-color: #ef7e26;
            color: #fff;
            border: none;
        }

        .button_fotter_2 {
            height: 35px;
            width: 145px;
            background: #052b3d;
            color: rgb(255, 255, 255);
            border: none;
        }

        .bg_co {
            position: absolute;
            background-color: #173f35;
            right: 1px;
            top: 1px;
            height: 90%;
            width: 60%;
        }

        .bg-co-img {
            width: auto;
            height: auto;
            max-width: 85%;
            position: inherit !important;
            max-height: 100%;
        }
    </style>

    <style>
        optgroup {
            background-color: #002c3e;
        }

        option {
            background-color: #002c3e;
        }
    </style>
@endsection
@section('js')
    @parent


    {{-- script ที่ต้องเอาไปแปะ --}}
    @if (Auth::check())
        @if ($pop_texonomy == 0)
            <script>
                $(document).ready(function() {
                    $('#myModal-file').modal('toggle');
                });
            </script>
        @endif
        <script>
            // คลิกจาก ย้อนกลับ



            function sent_click_back() {

                var className = $('.card_box');
                var id = [];
                $.each(className, function(key, value) {
                    id.push(value.getAttribute('value'));
                });
                //   console.log(id);
                var id_all = JSON.stringify(id);
                var user_id = "{{ Auth::user()->id }}"



                $.ajax({
                    url: "{{ route('taxonomyAdd') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        id_all: id_all,
                        user_id: user_id
                    },
                    cache: false,
                    success: function(datajson) {
                        if (datajson.status == true) {
                            $('#myModal-file').modal('hide');
                        }
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Fail',
                            html: 'เกิดข้อผิดพลาด',
                            confirmButtonText: 'OK',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                return
                            }
                        })
                    }

                })

                // var className_back = $('.card_box');
                // var id_back = [];
                // $.each( className_back, function( key, value ) {
                //     id_back.push(value.getAttribute('value'));
                // });
                // // console.log(id_back);
                // var id_all_back = JSON.stringify(id_back);
                //  createCookie("cookie_log",id_all_back,30);


                //  $('#myModal-file').modal('hide');
            }

            // คลิกจาก เสร็จสิ้น
            function sent_click() {
                var className = $('.card_box.active');
                var id = [];
                $.each(className, function(key, value) {
                    id.push(value.getAttribute('value'));
                });
                //   console.log(id);
                var id_all = JSON.stringify(id);
                var user_id = "{{ Auth::user()->id }}"



                //createCookie("cookie_log",id_all,30);
                //console.log(id_all);
                // $('#myModal-file').modal('hide');
                $.ajax({
                    url: "{{ route('taxonomyAdd') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        id_all: id_all,
                        user_id: user_id
                    },
                    cache: false,
                    success: function(datajson) {
                        if (datajson.status == true) {
                            $('#myModal-file').modal('hide');
                        }
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Fail',
                            html: 'เกิดข้อผิดพลาด',
                            confirmButtonText: 'OK',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                return
                            }
                        })
                    }

                })
            }



            // สร้าง Cookie และ กำหนดวัน
            // function createCookie(name, value, days) {
            //     if (days) {
            //         var date = new Date();
            //         date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            //         var expires = "; expires=" + date.toGMTString();
            //     }
            //     else var expires = "";
            //     document.cookie = name + "=" + value + expires + "; path=/";
            // }

            // ยิงไปเช็คว่า Cookie หมดอายุหรือยัง
            // $( document ).ready(function() {
            //     if (readCookie() == null) {
            //         $('#myModal-file').modal('toggle');
            //     }
            // });

            // เช็ค ถ้าCookie = null ให้ขึ้น modal
            // function readCookie() {
            //     var name = "cookie_log";
            //     var nameEQ = name + "=";
            //     var ca = document.cookie.split(';');
            //     for (var i = 0; i < ca.length; i++) {
            //         var c = ca[i];
            //         while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            //         if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            //     }
            //     return null;
            // }

            // คำสั่ง ลบ Cookie
            // function eraseCookie() {
            //     createCookie("cookie_log", "", -1);
            // }

            function add_active(e) {
                if (e.className == "card_box active") {
                    e.classList.remove("active");
                } else {
                    e.classList.add("active");
                }
            }

            function modal() {
                $('#myModal-file').modal('toggle');
            }

            // ยิงไปเอาข้อมูลไป modal
            $(document).ready(function() {

                $.ajax({
                    type: "get",
                    url: "https://persona.thaihealth.or.th/api/taxonomylist",
                    success: function(response) {
                        // console.log(response['res_result']);

                        let html_aa = '';
                        for (i = 0; i < response['res_result'].length; i++) {
                            html_aa +=
                                `<div class="card_box" value="${response['res_result'][i]['taxonomy_id']}" onclick="add_active(this)" >`
                            html_aa +=
                                `<span style="cursor: pointer;">${response['res_result'][i]['taxonomy_name']}</span>`
                            html_aa += `</div>`;
                        }
                        $('#page-list').html(html_aa);

                    }
                });

            });
        </script>
    @endif




    <script>
        $(document).ready(function() {



            url_ajax = "{{ route('ajaxData') }}";
            check_register = "{{ session('status') ? 1 : 0 }}";
            check_login = "{{ session('login_status') ? session('login_status') : '' }}";
            check_activate = "{{ session('activate_status') ? session('activate_status') : '' }}";
            check_forgotpassword =
                "{{ session('forgotpassword_status') ? session('forgotpassword_status') : '' }}";
            check_resetpassword = "{{ session('resetpassword_status') ? session('resetpassword_status') : '' }}";
            check_request_media = "{{ session('request_media') ? session('request_media') : '' }}";
            check_book_an_exhibition = "{{ session('book_an_exhibition') ? session('book_an_exhibition') : '' }}";
            check_update = "{{ session('update') ? 1 : 0 }}";

            if (check_login == 'success') {
                $('.iconuser img').click();
                // Swal.fire({
                //   position: 'center',
                //   type: 'success',
                //   title: 'เปิดใช้งานบัญชีสำเร็จ',
                //   showConfirmButton: false,
                //   timer: 5000
                // });
            }

            if (check_update == 1) {
                Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'แก้ไขข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            if (check_login == 'not_success') {
                Swal.fire({
                    position: 'center',
                    type: 'error',
                    title: 'ไม่พบบัญชีผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            if (check_register == 1) {
                Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'สมัครสมาชิกสำเร็จ กรุณาเปิดใช้งานบัญชีที่อีเมลของคุณก่อนเข้าสู่ระบบ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            if (check_activate == 'success') {
                Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'เปิดใช้งานบัญชีสำเร็จ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            if (check_activate == 'not_success') {
                Swal.fire({
                    position: 'center',
                    type: 'error',
                    title: 'ไม่พบบัญชี หรือ บัญชีเปิดใช้สำเร็จแล้ว',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            if (check_forgotpassword == 'success') {
                Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'ส่งอีเมลลืมรหัสผ่านสำเร็จ กรุณาเปิดรีเซ็ตรหัสผ่านใหม่ที่อีเมลของคุณ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            if (check_forgotpassword == 'not_success') {
                Swal.fire({
                    position: 'center',
                    type: 'error',
                    title: 'ส่งอีเมลลืมรหัสผ่านไม่สำเร็จ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            if (check_resetpassword == 'success') {
                Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'รีเซ็ตรหัสผ่านสำเร็จ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }


            if (check_request_media == 'success') {
                Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'ส่งคำขอรับสื่อสำเร็จ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            if (check_book_an_exhibition == 'success') {
                Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'จองนิทรรศการสำเร็จ',
                    showConfirmButton: false,
                    timer: 5000
                });
            }

            $('#keyword').keyup(function() {
                //console.log($(this).val().length);
                if ($(this).val().length >= 2) {
                    //console.log("Case True");
                    $('#keyword').typeahead({
                        source: function(query, process) {
                            return $.get(url_ajax, {
                                query: query
                            }, function(data) {
                                //console.log(data);
                                return process(data);
                            });
                        }
                    });
                }
            });

            $('#icon_search').click(function() {
                $('#form_search').submit();
            });

            $(document).on('click', ".btn_search", function() {
                let tag = $('#search_opt').find(":selected").text();
                let search = $('.input_seach').find('input').val()
                let search_data = `${search},${tag}`
                clicksearch(search_data)
                console.log(tag)
            })
        });
    </script>
@endsection

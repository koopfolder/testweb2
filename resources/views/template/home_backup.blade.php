@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    $interesting_issues = ThrcHelpers::getInterestingIssues(['limit'=>'5','retrieving_results'=>'get','page_layout'=>'interesting_issues','featured'=>true]);
    $news = ThrcHelpers::getNews(['limit'=>'4','retrieving_results'=>'get','page_layout'=>'news_event','featured'=>false]);
    $knowledges_id = ThrcHelpers::getSetting(['slug'=>'knowledges','retrieving_results'=>'first']);
    if(collect($knowledges_id)->isNotEmpty() && $knowledges_id->value !=''){
        $knowledges = ThrcHelpers::getMedia(['id'=>$knowledges_id->value]);
    }
    $media_campaign_id = ThrcHelpers::getSetting(['slug'=>'media_campaign','retrieving_results'=>'first']);
    if(collect($media_campaign_id)->isNotEmpty() && $media_campaign_id->value !=''){
        $media_campaign = ThrcHelpers::getMedia(['id'=>$media_campaign_id->value]);
    }

    $revolving_exhibition = ThrcHelpers::getExhibition(['limit'=>'3','retrieving_results'=>'get','page_layout'=>'revolving_exhibition','featured'=>false]);
    $permanent_exhibition = ThrcHelpers::getExhibition(['limit'=>'3','retrieving_results'=>'get','page_layout'=>'permanent_exhibition','featured'=>false]);
    $traveling_exhibition = ThrcHelpers::getExhibition(['limit'=>'3','retrieving_results'=>'get','page_layout'=>'traveling_exhibition','featured'=>false]);
    $exhibition_borrowed = ThrcHelpers::getExhibition(['limit'=>'3','retrieving_results'=>'get','page_layout'=>'exhibition_borrowed','featured'=>false]);
    $online_exhibition = ThrcHelpers::getExhibition(['limit'=>'3','retrieving_results'=>'get','page_layout'=>'online_exhibition']);
    //dd($online_exhibition);
    $notable_books = ThrcHelpers::getNotableBooks([]);


    //dd($notable_books);
    //dd($exhibition_borrowed);
@endphp
@if(method_exists('App\Modules\Api\Http\Controllers\TargetController', 'getFrontTarget'))   
    @php
        $target = App\Modules\Api\Http\Controllers\TargetController::getFrontTarget($request->all());  
        //dd($target);
    @endphp
@endif
@if(method_exists('App\Modules\Api\Http\Controllers\IssueController', 'getFrontIssue'))   
    @php
        $issue = App\Modules\Api\Http\Controllers\IssueController::getFrontIssue($request->all());  
        //dd($issue);
    @endphp
@endif
@if(method_exists('App\Modules\Api\Http\Controllers\AreaController', 'getFrontArea'))   
    @php
        //$area = App\Modules\Api\Http\Controllers\AreaController::getFrontArea($request->all());  
        //dd($area);
    @endphp
@endif
@if(method_exists('App\Modules\Api\Http\Controllers\IndexController', 'getFrontTempalte'))   
    @php
        //$template = App\Modules\Api\Http\Controllers\IndexController::getFrontTempalte($request->all());  
        //dd($template);
    @endphp
@endif

@if(method_exists('App\Modules\Article\Http\Controllers\ArticlesResearchController', 'getDataArticlesResearchMain')) 
    @php
        $articles_research = App\Modules\Article\Http\Controllers\ArticlesResearchController::getDataArticlesResearchMain($request->all());  
        //dd($articles_research->count());
    @endphp
@endif

@if(method_exists('App\Modules\Article\Http\Controllers\IncludeStatisticsController', 'getDataIncludeStatisticsMain')) 
    @php
        $include_statistics = App\Modules\Article\Http\Controllers\IncludeStatisticsController::getDataIncludeStatisticsMain($request->all());  
        //dd($include_statistics);
    @endphp
@endif



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
                        <div class="input_seach">{{ Form::text('keyword',old('keyword'),['class'=>'','placeholder'=>'คำค้น','maxlength'=>'50','id'=>'keyword']) }}</div>
                                <div class="selectbox">
                                    <select name="issue">
                                        <option value"0">ประเด็น</option>
                                        @if($issue->count())
                                            @foreach($issue AS $key=>$value)
                                                @php
                                                    //dd($value);
                                                @endphp
                                                @if ($value->children->count() > 0)
                                                    <optgroup label="{{ $value->name }}">
                                                        @if(!empty($value->issues_id))
                                                        <option value="{{ $value->issues_id }}">{{ $value->name }}</option>
                                                        @endif
                                                    @foreach($value->children->sortBy('name') as $children)
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
                @if($interesting_issues->count())
                    @foreach($interesting_issues AS $key=>$value)
                        @if($key ==0)
                        @php
                            //dd($value); 
                        @endphp
                        <figure class="col-xs-12 col-sm-5 item_interesting_hl">
                                <a href="{{ $value['url'] }}">
                                    <img data-src="{{ $value['cover_desktop'] }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
                                    <figcaption>
                                        <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                        <p class="dotmaster">{{ $value['description'] }}</p>
                                    </figcaption>
                                </a>
                        </figure>
                        @php
                            break;
                        @endphp
                        @endif
                    @endforeach
                @endif  
                <div class="col-xs-12 col-sm-7 wrap_item_interesting">
                    <div class="row">
                        @if($interesting_issues->count())
                            @foreach($interesting_issues AS $key=>$value)
                                @if($key >0)
                                @php
                                    //dd($value); 
                                @endphp
                                <figure class="col-xs-12 col-sm-6 item_interesting">
                                        <a href="{{ $value['url'] }}">
                                            <img data-src="{{ $value['cover_desktop'] }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
                                            <figcaption>
                                                <img src="{{ asset('themes/thrc/images/open-book.svg') }}">
                                                <h1>{{ $value['title'] }}</h1>
                                                <p class="dotmaster">{{ $value['description'] }}</p>
                                            </figcaption>
                                        </a>
                                </figure>
                                @endif
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
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="col-xs-12 head_article">
                            <h1>บทความ / งานวิจัย</h1>
                            <a href="{{ route('list-articles-research') }}">ดูทั้งหมด</a>
                        </div>
                    </div>
                    <div class="row">
                        @if($articles_research->count())
                            @foreach($articles_research AS $key=>$value)
                                @php
                                    //dd($value);
                                    $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                    if($value->data_type =='media'){
                                        $value->url = route('media-detail',Hashids::encode($value->id));
                                        if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                            $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>'App\Modules\Api\Models\ListMedia','model_id'=>$value->id]);  
                                            $value->cover_desktop = (isset($value->cover_desktop->id)  ? asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                                        }
                                    }else{
                                        
                                        $value->url = route('article-detail',$value->slug);
                                        if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                            $model_type= 'App\Modules\Article\Models\Article';
                                            //dd($value);
                                            $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                            $value->cover_desktop = (isset($value->cover_desktop->id)  ? asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):asset('themes/thrc/images/no-image-icon-3.jpg'));

                                        }

                                    }
                                @endphp
                                <figure class="col-xs-12 col-sm-6 item_article">
                                    <a href="{{ $value->url }}">
                                        <img data-src="{{ $value->cover_desktop  }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value->title }}" class="img-responsive">
                                        <h1 class="dotmaster">{{ $value->title }}</h1>
                                        <p class="dotmaster">{{ $value->description }}</p>
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
                            <a href="{{ route('list-include-statistics') }}"></a>
                        </div>
                    </div>
                    <div class="row">
                        @if($include_statistics->count())
                            @foreach($include_statistics AS $key=>$value)
                                @php
                                    //dd($value);
                                    $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                    if($value->data_type =='media'){
                                        $value->url = route('media-detail',Hashids::encode($value->id));
                                        if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                            $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>'App\Modules\Api\Models\ListMedia','model_id'=>$value->id]);  
                                            $value->cover_desktop = (isset($value->cover_desktop->id)  ? asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                                        }
                                    }else{
                                        
                                        $value->url = route('article-detail',$value->slug);
                                        if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getImage')){
                                            $model_type= 'App\Modules\Article\Models\Article';
                                            //dd($value);
                                            $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                            $value->cover_desktop = (isset($value->cover_desktop->id)  ? asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):asset('themes/thrc/images/no-image-icon-3.jpg'));

                                        }

                                    }
                                @endphp
                                <figure class="col-xs-12 item_article">
                                    <a href="{{ $value->url }}">
                                        <img data-src="{{ $value->cover_desktop  }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value->title }}" class="img-responsive">
                                        <h1 class="dotmaster">{{ $value->title }}</h1>
                                        <p class="dotmaster">{{ $value->description }}</p>
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
                                    $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                                    $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>'App\Modules\Api\Models\ListMedia','model_id'=>$value->id]);  
                                    $value->cover_desktop = (isset($value->cover_desktop->id)  ? asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                                @endphp
                                <div>
                                    <a href="{{ $json->FileAddress }}">
                                    <div class="bookimg">
                                        <img data-src="{{ $value->cover_desktop }}"  src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value->title }}" class="img-responsive">
                                    </div>
                                    <p>
                                        {{ $value->title }}
                                    </p>
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
                @if(collect($knowledges_id)->isNotEmpty() && $knowledges_id->value !='')
                @php
                    $json = ($knowledges->json_data !='' ? json_decode($knowledges->json_data):'');
                    //dd($json);
                    $knowledges->urlknowledges = $knowledges->id;
                    $knowledges->cover_desktop = $knowledges->getMedia('cover_desktop')->isNotEmpty() ? asset($knowledges->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
                    //dd($knowledges);
                @endphp
                <div class="col-xs-12 col-sm-6 item_knowledge">
                    <a href="{{ $knowledges->urlknowledges }}">
                        <figcaption>
                            <h1>ชุดความรู้</h1>
                            <p class="dotmaster">{{ $knowledges->title }}</p>
                            <div class="readmore">อ่านต่อ</div>
                        </figcaption>
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
                            <div><img data-src="{{ $knowledges->cover_desktop }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $knowledges->title }}"></div>
                        @endif
                    </a>
                </div>
                @endif
                @if(collect($media_campaign_id)->isNotEmpty() && $media_campaign_id->value !='')
                @php
                    $json = ($media_campaign->json_data !='' ? json_decode($media_campaign->json_data):'');
                    //dd($json);
                    $media_campaign->urlmediacampaign = $media_campaign->id;
                    $media_campaign->cover_desktop = $media_campaign->getMedia('cover_desktop')->isNotEmpty() ? asset($media_campaign->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
                @endphp
                <div class="col-xs-12 col-sm-6 item_knowledge">
                    <a href="{{ $media_campaign->urlmediacampaign }}">
                        <figcaption>
                            <h1>สื่อรณรงค์</h1>
                            <p class="dotmaster">{{ $media_campaign->title }}</p>
                            <div class="readmore">อ่านต่อ</div>
                        </figcaption>
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
                            <img data-src="{{ $media_campaign->cover_desktop  }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $media_campaign->title }}" class="img-responsive">
                        @endif
                    </a>
                </div>
                @endif
            </div>
        </div>
    </section>
    <section class="row wow fadeInDown row_exibition">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-9 head_exibition">
                     <h1>นิทรรศการเพื่อการเรียนรู้</h1>
                    <a href="{{ route('revolving-exhibition-list') }}" id="exhibition_url">ดูทั้งหมด</a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-9 wrapexi">
                    <ul class="menu_ex">
                        <li class="active" data-id="revolving_exhibition">นิทรรศการหลัก</li>
                        <li data-id="permanent_exhibition">นิทรรศการย่อย</li>
                        <li data-id="traveling_exhibition">นิทรรศการสัญจร</li>
                        <li data-id="online_exhibition">นิทรรศการออนไลน์</li>
                        <li data-id="exhibition_borrowed">นิทรรศการยืมคืน</li>
                    </ul>
                    <!-- revolving_exhibition -->
                    <div class="flexex flexslider">
                        <ul class="slides">
                            @if($revolving_exhibition->count())
                                @foreach($revolving_exhibition AS $key=>$value)
                                @php
                                    //dd($value);
                                @endphp
                                <li>
                                    @if($value->getMedia('cover_desktop')->isNotEmpty())
                                        <figure><img data-src="{{ asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive"></figure>
                                    @else
                                        <figure><img src="{{ asset('themes/thrc/images/no-image-icon-3.jpg') }}" class="img-responsive"></figure>
                                    @endif
                                    <div class="excaption">
                                        <h1>{{ $value->title }}</h1>
                                        <a href="{{ route('revolving-exhibition-detail',$value->slug) }}" class="btn_viewex">ชมนิทรรศการ</a>
                                        <a href="#" class="btn_register">ลงทะเบียน</a>
                                    </div>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- End revolving_exhibition -->
                    <!-- permanent_exhibition -->
                    <div class="flexex flexslider">
                        <ul class="slides">
                            @if($permanent_exhibition->count())
                                @foreach($permanent_exhibition AS $key=>$value)
                                @php
                                    //dd($value);
                                @endphp
                                <li>
                                    @if($value->getMedia('cover_desktop')->isNotEmpty())
                                        <img data-src="{{ asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
                                    @else
                                        <img src="{{ asset('themes/thrc/images/no-image-icon-3.jpg') }}" class="img-responsive">
                                    @endif
                                    <div class="excaption">
                                        <h1>{{ $value->title }}</h1>
                                        <a href="{{ route('permanent-exhibition-detail',$value->slug) }}" class="btn_viewex">ชมนิทรรศการ</a>
                                        <a href="#" class="btn_register">ลงทะเบียน</a>
                                    </div>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- End permanent_exhibition -->
                    <!-- traveling_exhibition -->
                    <div class="flexex flexslider">
                        <ul class="slides">
                            @if($traveling_exhibition->count())
                                @foreach($traveling_exhibition AS $key=>$value)
                                @php
                                    //dd($value);
                                @endphp
                                <li>
                                    @if($value->getMedia('cover_desktop')->isNotEmpty())
                                        <img data-src="{{ asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
                                    @else
                                        <img src="{{ asset('themes/thrc/images/no-image-icon-3.jpg') }}" class="img-responsive">
                                    @endif
                                    <div class="excaption">
                                        <h1>{{ $value->title }}</h1>
                                        <a href="{{ route('traveling-exhibition-detail',$value->slug) }}" class="btn_viewex">ชมนิทรรศการ</a>
                                        <a href="#" class="btn_register">ลงทะเบียน</a>
                                    </div>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- End traveling_exhibition -->
                    <!-- online_exhibition -->
                    <div class="flexex flexslider">
                        <ul class="slides">
                            @if($online_exhibition->count())
                                @foreach($online_exhibition AS $key=>$value)
                                @php
                                    //dd($value);
                                @endphp
                                <li>
                                    @if($value->getMedia('cover_desktop')->isNotEmpty())
                                        <img data-src="{{ asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
                                    @else
                                        <img src="{{ asset('themes/thrc/images/no-image-icon-3.jpg') }}" class="img-responsive">
                                    @endif
                                    <div class="excaption">
                                        <h1>{{ $value->title }}</h1>
                                        <a href="{{ $value->url_external }}" class="btn_viewex">ชมนิทรรศการ</a>
                                        <a href="#" class="btn_register">ลงทะเบียน</a>
                                    </div>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- End online_exhibition -->
                    <!-- exhibition_borrowed -->
                    <div class="flexex flexslider">
                        <ul class="slides">
                            @if($exhibition_borrowed->count())
                                @foreach($exhibition_borrowed AS $key=>$value)
                                @php
                                    //dd($value);
                                @endphp
                                <li>
                                    @if($value->getMedia('cover_desktop')->isNotEmpty())
                                        <img data-src="{{ asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" class="img-responsive">
                                    @else
                                        <img src="{{ asset('themes/thrc/images/no-image-icon-3.jpg') }}" class="img-responsive">
                                    @endif
                                    <div class="excaption">
                                        <h1>{{ $value->title }}</h1>
                                        <a href="{{ route('exhibition-borrowed-detail',$value->slug) }}" class="btn_viewex">ชมนิทรรศการ</a>
                                        <a href="#" class="btn_register">ลงทะเบียน</a>
                                    </div>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- End exhibition_borrowed -->
                </div>
                <div class="col-xs-12 col-sm-3 wrap_calendar">
                    <div class="viewall_calendar">
                        <a href="#">ดูทั้งหมด</a>
                    </div>
                    <div class="owl-calendar owl-carousel owl-theme">
                        <div class="wrap_calendar_list">
                            <h1>ปฏิทินกิจกรรม</h1>
                            <div class="c_date">29</div>
                            <div class="c_month">NOVEMBER</div>
                            <a href="#" class="dotmaster">Lorem Ipsum is simply dummy text 
        of the printing and typesetting </a>
                        </div>
                        <div class="wrap_calendar_list">
                            <h1>ปฏิทินกิจกรรม</h1>
                            <div class="c_date">29</div>
                            <div class="c_month">NOVEMBER</div>
                            <a href="#" class="dotmaster">Lorem Ipsum is simply dummy text 
        of the printing and typesetting </a>
                        </div>
                        <div class="wrap_calendar_list">
                            <h1>ปฏิทินกิจกรรม</h1>
                            <div class="c_date">29</div>
                            <div class="c_month">NOVEMBER</div>
                            <a href="#" class="dotmaster">Lorem Ipsum is simply dummy text 
        of the printing and typesetting </a>
                        </div>
                        <div class="wrap_calendar_list">
                            <h1>ปฏิทินกิจกรรม</h1>
                            <div class="c_date">29</div>
                            <div class="c_month">NOVEMBER</div>
                            <a href="#" class="dotmaster">Lorem Ipsum is simply dummy text 
        of the printing and typesetting </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_news">
                    <h1>ข่าวสารและกิจกรรม</h1>
                    <a href="{{ route('list-news-event') }}">ดูทั้งหมด</a>
                </div>
            </div>
            <div class="row row_newsd">
                <div class="col-xs-12 col-sm-8">
                    @if($news->count())
                        @foreach($news AS $key=>$value)
                            @if($key ==0)
                            <div class="row hl_news">
                                    <figure class="col-xs-12 col-sm-6">
                                        <a href="{{ route('news-event-detail',$value->slug) }}">
                                            @if($value->getMedia('cover_desktop')->isNotEmpty())
                                                <img data-src="{{ asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value->title }}">
                                            @else
                                                <img src="{{ asset('themes/thrc/images/no-image-icon-3.jpg') }}">
                                            @endif
                                        </a>
                                    </figure>
                                    <figcaption class="col-xs-12 col-sm-6">
                                        <h1 class="dotmaster">{{ $value->title }}</h1>
                                        <p class="dotmaster">{{ $value->description  }}</p>
                                        <div class="dateandview">
                                            <div class="newsdate">{{ Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('d.m.').(Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('Y')+543) }}</div>
                                            <div class="newsview">{{ $value->hit }}</div>
                                        </div>
                                        <a class="readmore" href="{{ route('news-event-detail',$value->slug) }}">อ่านต่อ</a>
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
                            <a href="{{ route('news-event-detail',$value->slug) }}" class="news_list">
                                <figure>
                                    @if($value->getMedia('cover_desktop')->isNotEmpty())
                                        <img data-src="{{ asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) }}" src="{{ asset('themes/thrc/images/Placeholder.png') }}" alt="{{ $value->title }}">
                                    @else
                                        <img src="{{ asset('themes/thrc/images/no-image-icon-3.jpg') }}">
                                    @endif
                                </figure><figcaption>
                                    <h1 class="dotmaster">{{ $value->title }}</h1>
                                    <p class="dotmaster">{{ $value->description  }}</p>
                                        <div class="dateandview_s">
                                            <div class="newsdate">{{ Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('d.m.').(Carbon\Carbon::parse(($value->updated_at != null ? $value->updated_at:$value->created_at))->format('Y')+543) }}</div>
                                            <div class="newsview">{{ $value->hit }}</div>
                                        </div>
                                </figcaption>
                            </a>
                            @endif
                            @endforeach
                        @endif
                </div>               
            </div>
        </div>
    </section>
    @include('partials.banners.footer')
@endsection
@section('meta')
    @parent
    <title></title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
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
        check_update= "{{ (session('update') ? 1:0) }}";

        if(check_login =='success'){
            $('.iconuser img').click();
            // Swal.fire({
            //   position: 'top-end',
            //   type: 'success',
            //   title: 'เปิดใช้งานบัญชีสำเร็จ',
            //   showConfirmButton: false,
            //   timer: 5000
            // });
        }

        if(check_update ==1){
            Swal.fire({
              position: 'top-end',
              type: 'success',
              title: 'แก้ไขข้อมูลสำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_login =='not_success'){
            Swal.fire({
              position: 'top-end',
              type: 'error',
              title: 'ไม่พบบัญชีผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_register ==1){
            Swal.fire({
              position: 'top-end',
              type: 'success',
              title: 'สมัครสมาชิกสำเร็จ กรุณาเปิดใช้งานบัญชีที่อีเมลของคุณก่อนเข้าสู่ระบบ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_activate =='success'){
            Swal.fire({
              position: 'top-end',
              type: 'success',
              title: 'เปิดใช้งานบัญชีสำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_activate =='not_success'){
            Swal.fire({
              position: 'top-end',
              type: 'error',
              title: 'ไม่พบบัญชี หรือ บัญชีเปิดใช้สำเร็จแล้ว',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_forgotpassword =='success'){
            Swal.fire({
              position: 'top-end',
              type: 'success',
              title: 'ส่งอีเมลลืมรหัสผ่านสำเร็จ กรุณาเปิดรีเซ็ตรหัสผ่านใหม่ที่อีเมลของคุณ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_forgotpassword =='not_success'){
            Swal.fire({
              position: 'top-end',
              type: 'error',
              title: 'ส่งอีเมลลืมรหัสผ่านไม่สำเร็จ',
              showConfirmButton: false,
              timer: 5000
            });
        }

        if(check_resetpassword =='success'){
            Swal.fire({
              position: 'top-end',
              type: 'success',
              title: 'รีเซ็ตรหัสผ่านสำเร็จ',
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

    });
</script>
@endsection
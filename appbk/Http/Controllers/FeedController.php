<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Modules\Article\Models\Article;
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ListTemplate,ViewArticlesResearch,ViewIncludeStatistics,ViewInterestingIssues};
use App\Modules\Exhibition\Models\Exhibition;
use View;
use Input,Redirect;
use App;
use Feed;
use ThrcHelpers;
use Junity\Hashids\Facades\Hashids;


class FeedController extends Controller
{
    public function getIndex(Request $reqeust,$slug=null)
    {
        $limit = 10;
        $author = 'www.resourcecenter.thaihealth.or.th';
        //dd("Rss Feed",$slug);
        //->orderByRaw('updated_at,created_at DESC')

        switch ($slug) {

            case 'news-event.xml':

                $items = Article::FrontDataRssFeed(['status'=>['publish'],
                                'page_layout'=>'news_event',
                                'limit'=>$limit
                            ]);
                //dd($items);
                if($items->count()){

                    $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
                    $logo = ($logo_value ? asset($logo_value->value) : '');
                    /* create new feed */
                    $feed = App::make("feed");

                    /* set your feed's title, description, link, pubdate and language */
                    $feed->ctype = "text/xml";

                    $feed->title = 'ข่าวสารและกิจกรรม';
                    //$feed->description ='';
                    $feed->logo = $logo;
                    $feed->link = route('home');
                    $feed->setDateFormat('datetime');
                    $feed->pubdate = $items[0]->updated_at;
                    $feed->lang = 'en';
                    $feed->setShortening(true);
                    $feed->setTextLimit(100);

                    foreach ($items as $value) {
                        //dd($value);
                        // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                        $feed->addItem([
                            'title'=>$value->title,
                            'author'=>$author,
                            'link'=>route('news-event-detail',$value['slug']),
                            'pubdate' =>$value->updated_at,
                            'description'=>$value->description,
                            'content'=>'',
                            'enclosure' => [
                                            'url' =>$value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'),
                                            'length'=>"5000",
                                            'type'=>'image/jpeg'
                                            ],

                        ]);

                    }
                        return $feed->render('rss');

                }else{
                    return abort(404);
                }

                break;
            case 'articles-research.xml':
                    
                    $items = ViewArticlesResearch::FrontRss(['limit'=>$limit]);
                    //dd($items);

                    $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
                    $logo = ($logo_value ? asset($logo_value->value) : '');

                    if($items->count()){

                        /* create new feed */
                        $feed = App::make("feed");

                        /* set your feed's title, description, link, pubdate and language */
                        $feed->ctype = "text/xml";

                        $feed->title = 'บทความ / งานวิจัย';
                        //$feed->description ='';
                        $feed->logo = $logo;
                        $feed->link = route('home');
                        $feed->setDateFormat('datetime');
                        $feed->pubdate = $items[0]->updated_at;
                        $feed->lang = 'en';
                        $feed->setShortening(true);
                        $feed->setTextLimit(100);
                        
                        foreach ($items as $value) {
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
                                    switch ($value->page_layout){
                                        case 'revolving_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'permanent_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'exhibition_borrowed':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'traveling_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        default:
                                            $model_type= 'App\Modules\Article\Models\Article';
                                        break;
                                    }

                                            //dd($value);
                                    $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                    $value->cover_desktop = (isset($value->cover_desktop->id)  ? asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):asset('themes/thrc/images/no-image-icon-3.jpg'));

                                }

                            }
  

                            // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                            $feed->addItem([
                                'title'=>$value->title,
                                'author'=>$author,
                                'link'=>$value->url,
                                'pubdate' =>$value->updated_at,
                                'description'=>$value->description,
                                'content'=>'',
                                'enclosure' => [
                                                'url' =>$value->cover_desktop,
                                                'length'=>"5000",
                                                'type'=>'image/jpeg'
                                                ],

                            ]);
                        }
 
                        return $feed->render('rss');

                    }else{
                        return abort(404);
                    }

                break;
            case 'include-statistics.xml':

                $items = ViewIncludeStatistics::FrontRssFeed(['limit'=>$limit]);

                    if($items->count()){

                        $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
                        $logo = ($logo_value ? asset($logo_value->value) : '');
                        /* create new feed */
                        $feed = App::make("feed");

                        /* set your feed's title, description, link, pubdate and language */
                        $feed->ctype = "text/xml";

                        $feed->title = 'รวมข้อมูลสถิติ';
                        //$feed->description ='';
                        $feed->logo = $logo;
                        $feed->link = route('home');
                        $feed->setDateFormat('datetime');
                        $feed->pubdate = $items[0]->updated_at;
                        $feed->lang = 'en';
                        $feed->setShortening(true);
                        $feed->setTextLimit(100);

                        foreach ($items as $value) {
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
                                    switch ($value->page_layout){
                                        case 'revolving_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'permanent_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'exhibition_borrowed':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'traveling_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        default:
                                            $model_type= 'App\Modules\Article\Models\Article';
                                        break;
                                    }

                                            //dd($value);
                                    $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                    $value->cover_desktop = (isset($value->cover_desktop->id)  ? asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):asset('themes/thrc/images/no-image-icon-3.jpg'));

                                }

                            }
  

                            // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                            $feed->addItem([
                                'title'=>$value->title,
                                'author'=>$author,
                                'link'=>$value->url,
                                'pubdate' =>$value->updated_at,
                                'description'=>$value->description,
                                'content'=>'',
                                'enclosure' => [
                                                'url' =>$value->cover_desktop,
                                                'length'=>"5000",
                                                'type'=>'image/jpeg'
                                                ],

                            ]);
                        }
 
                        return $feed->render('rss');

                    }else{
                        return abort(404);
                    }
                
                
                break;
            case 'our-service.xml':
                
                $items = Article::FrontDataRssFeed(['status'=>['publish'],
                                'page_layout'=>'our_service',
                                'limit'=>$limit
                            ]);
                //dd($items);
                if($items->count()){

                    $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
                    $logo = ($logo_value ? asset($logo_value->value) : '');
                    /* create new feed */
                    $feed = App::make("feed");

                    /* set your feed's title, description, link, pubdate and language */
                    $feed->ctype = "text/xml";

                    $feed->title = 'บริการของเรา';
                    //$feed->description ='';
                    $feed->logo = $logo;
                    $feed->link = route('home');
                    $feed->setDateFormat('datetime');
                    $feed->pubdate = $items[0]->updated_at;
                    $feed->lang = 'en';
                    $feed->setShortening(true);
                    $feed->setTextLimit(100);

                    foreach ($items as $value) {
                        //dd($value);
                        // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                        $feed->addItem([
                            'title'=>$value->title,
                            'author'=>$author,
                            'link'=>route('news-event-detail',$value['slug']),
                            'pubdate' =>$value->updated_at,
                            'description'=>$value->description,
                            'content'=>'',
                            'enclosure' => [
                                            'url' =>$value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'),
                                            'length'=>"5000",
                                            'type'=>'image/jpeg'
                                            ],

                        ]);

                    }
                        return $feed->render('rss');

                }else{
                    return abort(404);
                }


                break;
            case 'sook-library.xml':

                $items = Article::FrontDataRssFeed(['status'=>['publish'],
                                'page_layout'=>'sook_library',
                                'limit'=>$limit
                            ]);
                //dd($items);
                if($items->count()){

                    $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
                    $logo = ($logo_value ? asset($logo_value->value) : '');
                    /* create new feed */
                    $feed = App::make("feed");

                    /* set your feed's title, description, link, pubdate and language */
                    $feed->ctype = "text/xml";

                    $feed->title = 'Sook library';
                    //$feed->description ='';
                    $feed->logo = $logo;
                    $feed->link = route('home');
                    $feed->setDateFormat('datetime');
                    $feed->pubdate = $items[0]->updated_at;
                    $feed->lang = 'en';
                    $feed->setShortening(true);
                    $feed->setTextLimit(100);

                    foreach ($items as $value) {
                        //dd($value);
                        // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                        $feed->addItem([
                            'title'=>$value->title,
                            'author'=>$author,
                            'link'=>route('news-event-detail',$value['slug']),
                            'pubdate' =>$value->updated_at,
                            'description'=>$value->description,
                            'content'=>'',
                            'enclosure' => [
                                            'url' =>$value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'),
                                            'length'=>"5000",
                                            'type'=>'image/jpeg'
                                            ],

                        ]);

                    }
                        return $feed->render('rss');

                }else{
                    return abort(404);
                }
                
                break;
            case 'training-course.xml':
                
                $items = Article::FrontDataRssFeed(['status'=>['publish'],
                                'page_layout'=>'training_course',
                                'limit'=>$limit
                            ]);
                //dd($items);
                if($items->count()){

                    $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
                    $logo = ($logo_value ? asset($logo_value->value) : '');
                    /* create new feed */
                    $feed = App::make("feed");

                    /* set your feed's title, description, link, pubdate and language */
                    $feed->ctype = "text/xml";

                    $feed->title = 'หลักสูตรฝึกอบรม';
                    //$feed->description ='';
                    $feed->logo = $logo;
                    $feed->link = route('home');
                    $feed->setDateFormat('datetime');
                    $feed->pubdate = $items[0]->updated_at;
                    $feed->lang = 'en';
                    $feed->setShortening(true);
                    $feed->setTextLimit(100);

                    foreach ($items as $value) {
                        //dd($value);
                        // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                        $feed->addItem([
                            'title'=>$value->title,
                            'author'=>$author,
                            'link'=>route('news-event-detail',$value['slug']),
                            'pubdate' =>$value->updated_at,
                            'description'=>$value->description,
                            'content'=>'',
                            'enclosure' => [
                                            'url' =>$value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'),
                                            'length'=>"5000",
                                            'type'=>'image/jpeg'
                                            ],

                        ]);

                    }
                        return $feed->render('rss');

                }else{
                    return abort(404);
                }
                

                break;
            case 'e-learning.xml':
                
                $items = Article::FrontDataRssFeed(['status'=>['publish'],
                                'page_layout'=>'e-learning',
                                'limit'=>$limit
                            ]);
                //dd($items);
                if($items->count()){

                    $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
                    $logo = ($logo_value ? asset($logo_value->value) : '');
                    /* create new feed */
                    $feed = App::make("feed");

                    /* set your feed's title, description, link, pubdate and language */
                    $feed->ctype = "text/xml";

                    $feed->title = 'E-Learning';
                    //$feed->description ='';
                    $feed->logo = $logo;
                    $feed->link = route('home');
                    $feed->setDateFormat('datetime');
                    $feed->pubdate = $items[0]->updated_at;
                    $feed->lang = 'en';
                    $feed->setShortening(true);
                    $feed->setTextLimit(100);

                    foreach ($items as $value) {
                        //dd($value);
                        // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                        $feed->addItem([
                            'title'=>$value->title,
                            'author'=>$author,
                            'link'=>route('news-event-detail',$value['slug']),
                            'pubdate' =>$value->updated_at,
                            'description'=>$value->description,
                            'content'=>'',
                            'enclosure' => [
                                            'url' =>$value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'),
                                            'length'=>"5000",
                                            'type'=>'image/jpeg'
                                            ],

                        ]);

                    }
                        return $feed->render('rss');

                }else{
                    return abort(404);
                }
                


                break;
            case 'notable_books.xml':
                
                break;
            case 'interesting_issues.xml':


                    $items  = ViewInterestingIssues::FrontListRssFeed(['limit'=>$limit]);


                    if($items->count()){

                        $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
                        $logo = ($logo_value ? asset($logo_value->value) : '');
                        /* create new feed */
                        $feed = App::make("feed");

                        /* set your feed's title, description, link, pubdate and language */
                        $feed->ctype = "text/xml";

                        $feed->title = 'ประเด็นที่น่าสนใจ';
                        //$feed->description ='';
                        $feed->logo = $logo;
                        $feed->link = route('home');
                        $feed->setDateFormat('datetime');
                        $feed->pubdate = $items[0]->updated_at;
                        $feed->lang = 'en';
                        $feed->setShortening(true);
                        $feed->setTextLimit(100);

                        foreach ($items as $value) {
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
                                    switch ($value->page_layout){
                                        case 'revolving_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'permanent_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'exhibition_borrowed':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        case 'traveling_exhibition':
                                            $model_type= 'App\Modules\Exhibition\Models\Exhibition';
                                        break;

                                        default:
                                            $model_type= 'App\Modules\Article\Models\Article';
                                        break;
                                    }

                                            //dd($value);
                                    $value->cover_desktop = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>$model_type,'model_id'=>$value->id]);  
                                    $value->cover_desktop = (isset($value->cover_desktop->id)  ? asset('media/'.$value->cover_desktop->id.'/conversions/thumb1366x635.jpg'):asset('themes/thrc/images/no-image-icon-3.jpg'));

                                }

                            }
  

                            // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                            $feed->addItem([
                                'title'=>$value->title,
                                'author'=>$author,
                                'link'=>$value->url,
                                'pubdate' =>$value->updated_at,
                                'description'=>$value->description,
                                'content'=>'',
                                'enclosure' => [
                                                'url' =>$value->cover_desktop,
                                                'length'=>"5000",
                                                'type'=>'image/jpeg'
                                                ],

                            ]);
                        }
 
                        return $feed->render('rss');

                    }else{
                        return abort(404);
                    }


                break;

            default:
                    return abort(404);
                break;

        }


    }



    public static function getDataList($params){

        $result = array();

        $result[0]['title'] = 'ข่าวสารและกิจกรรม';
        $result[0]['url'] = route('rss-feed','news-event.xml');
        $result[1]['title'] = 'บทความ / งานวิจัย';
        $result[1]['url'] = route('rss-feed','articles-research.xml');
        $result[2]['title'] = 'รวมข้อมูลสถิติ';
        $result[2]['url'] = route('rss-feed','include-statistics.xml');
        $result[3]['title'] = 'บริการของเรา';
        $result[3]['url'] = route('rss-feed','our-service.xml');
        $result[4]['title'] = 'Sook library';
        $result[4]['url'] = route('rss-feed','sook-library.xml');
        $result[5]['title'] = 'หลักสูตรฝึกอบรม';
        $result[5]['url'] = route('rss-feed','training-course.xml');
        $result[6]['title'] = 'E-Learning';
        $result[6]['url'] = route('rss-feed','e-learning.xml');
        //$result[7]['title'] = 'หนังสือเด่น';
        //$result[7]['url'] = route('rss-feed','notable_books.xml');
        $result[8]['title'] = 'ประเด็นที่น่าสนใจ';
        $result[8]['url'] = route('rss-feed','interesting_issues.xml');

        //dd("TEst DataList",$result);
        return collect($result);
    }




}

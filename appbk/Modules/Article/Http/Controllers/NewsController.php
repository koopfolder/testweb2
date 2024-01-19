<?php

namespace App\Modules\Article\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Article\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Article\Models\{Article,ArticleRevision,ArticleHitLogs};
use App\Modules\Documentsdownload\Models\{Documents};
use Illuminate\Support\Facades\Response;
use DbdHelpers;
use Junity\Hashids\Facades\Hashids;
use DB;

class NewsController extends Controller
{
    public static function getDataNews($params)
    {
        ///$data =Article::FrontHighlight(['page_layout'=>'news','page'=>(isset($params['page']) ? $params['page']:'1')]);
        $query = Article::FrontList(['page_layout'=>'news','page'=>(isset($params['page']) ? $params['page']:'1')]);
        $data = array();
        $data['title_h2'] = 'ข่าวประชาสัมพันธ์';
        $data['title_h1'] = 'News information';
        $data['layout'] = 'news';
        $data['items'] = $query;

        return $data;
    }

    public static function getDataPromotion($params)
    {
        ///$data =Article::FrontHighlight(['page_layout'=>'news','page'=>(isset($params['page']) ? $params['page']:'1')]);
        $query = Article::FrontList(['page_layout'=>'promotion','page'=>(isset($params['page']) ? $params['page']:'1')]);
        $data = array();
        $data['title_h2'] = 'ข่าวกิจกรรมส่งเสริมการขาย';
        $data['title_h1'] = 'PROMOTION NEW';
        $data['layout'] = 'promotion';
        $data['items'] = $query;

        return $data;
    }

    public static function getDetailNewsEvent($slug){
  
        if(collect($slug)->isNotEmpty()){
            $data = Article::Detail(['slug'=>$slug]);
            //dd(collect($data)->isNotEmpty());
            //dd($data);
            $token= csrf_token();
            $check =  ArticleHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
            if(!isset($check->id)){
                ArticleHitLogs::create(['token'=>$token,'article_id'=>$data->id]);
                Article::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
                $data = Article::Detail(['slug'=>$slug]);
            }
            $attachments = '';
            if(collect($data)->isNotEmpty()){
                 //Related 
                //$related_data = Article::FrontListRelated(['page_layout'=>'news','related_id'=>$id]);
                //dd($related_data);
                $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                $most_view_data = Article::FrontListMostView(['page_layout'=>$data->page_layout]);
                $recommend = Article::FrontListRecommend(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);

                $attachments = Documents::Data(['model_id'=>$data->id]);
                $attachments= collect($attachments);

                $ip = \Request::ip();
                $ip = str_replace('.','_',$ip);
                if(isset($_COOKIE['thrc_'.$ip])) {
                    $cookie_data = json_decode($_COOKIE['thrc_'.$ip]);
                }else{
                    $cookie_data = '';
                }

                return view('template.news_event_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }

    }


    public static function getDetailArticle($slug){
        
        if(collect($slug)->isNotEmpty()){
            $data = Article::Detail(['slug'=>$slug]);
            //dd(collect($data)->isNotEmpty());
            //dd($data);
            $token= csrf_token();
            $check =  ArticleHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
            if(!isset($check->id)){
                ArticleHitLogs::create(['token'=>$token,'article_id'=>$data->id]);
                Article::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
                $data = Article::Detail(['slug'=>$slug]);
            }
            $attachments = '';
            if(collect($data)->isNotEmpty()){
                 //Related 
                //$related_data = Article::FrontListRelated(['page_layout'=>'news','related_id'=>$id]);
                //dd($related_data);
                $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                $most_view_data = Article::FrontListMostView(['page_layout'=>$data->page_layout]);
                $recommend = Article::FrontListRecommend(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);

                $attachments = Documents::Data(['model_id'=>$data->id]);
                $attachments= collect($attachments);

                $ip = \Request::ip();
                $ip = str_replace('.','_',$ip);
                if(isset($_COOKIE['thrc_'.$ip])) {
                    $cookie_data = json_decode($_COOKIE['thrc_'.$ip]);
                }else{
                    $cookie_data = '';
                }

                return view('template.news_event_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }

    }

    public static function getDetail($params){
    
        $result = array();
        $result['data'] = '';
        $result['related_data'] = '';
        $id =  ($params->layout_params !='' ?json_decode($params->layout_params)->id:'hap');
        $data = Article::DetailId(['id'=>$id]);

        //dd($data);
        //$related_data = Article::FrontListRelated(['page_layout'=>'news','related_id'=>$id]);

        if(isset($data->title)){
            $result['data'] =$data;
        }else{
            Abort(404);
        }
        return $result;
    }


    public static function getListNews(){
        return view('template.list_news_event_case_front');
    }

    public static function getDataListNews($params)
    {

        $article = Article::FrontList(['page_layout'=>'news_event','page'=>(isset($params['page']) ? $params['page']:'1')]);
        //dd($article);
        $data = array();
        $data['title_h1'] = 'ข่าวสารและกิจกรรม';
        $data['layout'] = 'news_event';
        $data['items'] = $article;

        return $data;
    }


}


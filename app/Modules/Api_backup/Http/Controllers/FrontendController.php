<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Article\Models\{Article,ArticleRevision,ArticleHitLogs};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ViewMedia,ViewAjaxMedia,MediaHitLogs};
use App\Modules\Documentsdownload\Models\{Documents};
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Hash;
use Crypt;
use Illuminate\Support\Facades\Log;
use DB;
use ThrcHelpers;
use Symfony\Component\HttpFoundation\Cookie;

class FrontendController extends Controller
{
  
    public static function getDetailMedia($id){

        //dd("get Media",$id);
        $id = Hashids::decode($id);
        //dd($id);
        if(collect($id)->isNotEmpty()){
            $data = ListMedia::Detail(['id'=>$id]);
            //dd(collect($data)->isNotEmpty());
            //dd($data);
            $token= csrf_token();
            //dd($token);
            $check =  MediaHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
            if(!isset($check->id)){
                MediaHitLogs::create(['token'=>$token,'media_id'=>$data->id]);
                ListMedia::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
                $data = ListMedia::Detail(['id'=>$id]);
            }

            if(collect($data)->isNotEmpty()){
                 //Related 
                 $json = ($data->json_data !='' ? json_decode($data->json_data):'');
                 $issues = (isset($json->Issues) ? $json->Issues:'');
                 if(gettype($issues) == 'array'){
                    $issue_array = array();
                    foreach($issues AS $key=>$value){
                        //dd($value);
                        array_push($issue_array,$value->ID);
                    }
                }else{
                    $issue_array = array('hap');
                }

                 $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                 $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                 $most_view_id = ViewMedia::FrontListMostView(['issues'=>$issue_array])->pluck('id');
                 $most_view_data = ListMedia::ListMediaCaseTop10MostView(['id'=>$most_view_id]);
                 $recommend_id = ViewMedia::FrontListRecommend(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                 $recommend_data = ListMedia::ListMediaCaseTop10(['id'=>$recommend_id]);


                $ip = \Request::ip();
                $ip = str_replace('.','_',$ip);
                if(isset($_COOKIE['thrc_'.$ip])) {
                    $cookie_data = json_decode($_COOKIE['thrc_'.$ip]);
                }else{
                    $cookie_data = '';
                }
                //dd($cookie_data);
                return view('template.media_detail_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend_data'=>$recommend_data,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }

    public static function getDetailMediaCaseIncludeStatistics($id){

        //dd("get Media",$id);
        $id = Hashids::decode($id);
        //dd($id);
        if(collect($id)->isNotEmpty()){
            $data = ListMedia::Detail(['id'=>$id]);
            //dd(collect($data)->isNotEmpty());
            //dd($data);
            $token= csrf_token();
            //dd($token);
            $check =  MediaHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
            if(!isset($check->id)){
                MediaHitLogs::create(['token'=>$token,'media_id'=>$data->id]);
                ListMedia::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
                $data = ListMedia::Detail(['id'=>$id]);
            }

            if(collect($data)->isNotEmpty()){
                 //Related 
                 $json = ($data->json_data !='' ? json_decode($data->json_data):'');
                 $issues = (isset($json->Issues) ? $json->Issues:'');
                 if(gettype($issues) == 'array'){
                    $issue_array = array();
                    foreach($issues AS $key=>$value){
                        //dd($value);
                        array_push($issue_array,$value->ID);
                    }
                }else{
                    $issue_array = array('hap');
                }

                 $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                 $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                 $most_view_id = ViewMedia::FrontListMostView(['issues'=>$issue_array])->pluck('id');
                 $most_view_data = ListMedia::ListMediaCaseTop10MostView(['id'=>$most_view_id]);
                 $recommend_id = ViewMedia::FrontListRecommend(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                 $recommend_data = ListMedia::ListMediaCaseTop10(['id'=>$recommend_id]);


                $ip = \Request::ip();
                $ip = str_replace('.','_',$ip);
                if(isset($_COOKIE['thrc_'.$ip])) {
                    $cookie_data = json_decode($_COOKIE['thrc_'.$ip]);
                }else{
                    $cookie_data = '';
                }
                //dd($cookie_data);
                return view('template.media_detail_case_include_statistics')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend_data'=>$recommend_data,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }


    public static function getDetailArticle($slug){
        
        //dd($slug);
        if(collect($slug)->isNotEmpty()){
            $data = Article::Detail(['slug'=>$slug]);
            //dd(collect($data)->isNotEmpty());
            //dd($data);
            $token= csrf_token();
            //dd($token);
            $check =  ArticleHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
            if(!isset($check->id)){
                ArticleHitLogs::create(['token'=>$token,'article_id'=>$data->id]);
                Article::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
                $data = Article::Detail(['slug'=>$slug]);
            }
            $attachments = '';
            if(collect($data)->isNotEmpty()){
                 //Related 
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

                return view('template.article_detail_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }


    public static function getDetailArticleCaseIncludeStatistics($slug){
        
        //dd($slug);
        if(collect($slug)->isNotEmpty()){
            $data = Article::Detail(['slug'=>$slug]);
            //dd(collect($data)->isNotEmpty());
            //dd($data);
            $token= csrf_token();
            //dd($token);
            $check =  ArticleHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
            if(!isset($check->id)){
                ArticleHitLogs::create(['token'=>$token,'article_id'=>$data->id]);
                Article::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
                $data = Article::Detail(['slug'=>$slug]);
            }
            $attachments = '';
            if(collect($data)->isNotEmpty()){
                 //Related 
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

                return view('template.article_detail_case_include_statistics')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }

    public static function getListMedia(){
        return view('template.list_media');
    }

    public static function getDataMedia($params)
    {
        //$list_data = ViewMedia::FrontList(['params'=>$params,'page'=>(isset($params['page']) ? $params['page']:'1')]);
        $list_data = ListMedia::FrontListJsonSearch(['params'=>$params,'page'=>(isset($params['page']) ? $params['page']:'1')]);
        //dd($list_data);
        $data = array();
        $data['items'] = $list_data;
        $data['old'] = $params;
        return $data;
    }


    public static function  getImage($params){

        $data = DB::table('media')
                     ->select(DB::raw('id'))
                     ->where('collection_name','=','cover_desktop')
                     ->where('model_id','=',$params['model_id'])
                     ->where('model_type','=',$params['model_type'])
                     ->first();
        
        //dd($params,$data);
        return $data;

    }


    public function ajaxData(Request $request){

        $query = $request->get('query','');        
        $view_media = ViewAjaxMedia::select('title')->where('title','LIKE',''.$query.'%')->limit(5)->get()->pluck('title');        
        return response()->json($view_media);

	}


    public function postDownload(Request $request){

        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                if(isset($inputs['id'])){

                    $ip = $request->ip();
                    $ip = str_replace('.','_',$ip);
                    $log_download = 0;

                    if(!isset($_COOKIE['thrc_'.$ip])) {
                        $cookie_data = array();
                        $cookie_data['download'] = 1;
                        $log_download = $cookie_data['download'];
                        setcookie('thrc_'.$ip,json_encode($cookie_data),time()+(86400 * 30),'/');
                        $check_cookie = 'False';
                    }else{
                        $cookie_data = json_decode($_COOKIE['thrc_'.$ip]);
                        $cookie_data->download = $cookie_data->download+1;
                        $log_download = $cookie_data->download;
                        setcookie('thrc_'.$ip,json_encode($cookie_data),time()+(86400 * 30),'/');
                        $check_cookie = 'True';
                    }

                      
                    Documents::where('id','=',$inputs['id'])->update(['download'=>DB::raw('download+1')]);
                    $response['msg'] ='sucess';
                    $response['status'] =true;
                    $response['log_download'] =$log_download;
                    //$response['data'] =$inputs;
                    return  Response::json($response,200);
                   
                }else{

                    $response['msg'] ='Bad Request';
                    $response['status'] =false;
                    $response['data'] = '';
                    return  Response::json($response,400);

                }
                
            }else{

                $response['msg'] ='Method Not Allowed';
                $response['status'] =false;
                $response['data'] = '';
                return  Response::json($response,405);

            }
        }catch (\Exception $e){

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            $response['data'] = '';
            return  Response::json($response,500);

        }

    }



    public function postMediaDownload(Request $request){

        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                if(isset($inputs['id'])){

                    $ip = $request->ip();
                    $ip = str_replace('.','_',$ip);
                    $log_download = 0;

                    if(!isset($_COOKIE['thrc_'.$ip])) {
                        $cookie_data = array();
                        $cookie_data['download'] = 1;
                        $log_download = $cookie_data['download'];
                        setcookie('thrc_'.$ip,json_encode($cookie_data),time()+(86400 * 30),'/');
                        $check_cookie = 'False';
                    }else{
                        $cookie_data = json_decode($_COOKIE['thrc_'.$ip]);
                        $cookie_data->download = $cookie_data->download+1;
                        $log_download = $cookie_data->download;
                        setcookie('thrc_'.$ip,json_encode($cookie_data),time()+(86400 * 30),'/');
                        $check_cookie = 'True';
                    }

                      
                    ListMedia::where('id','=',$inputs['id'])->update(['download'=>DB::raw('download+1')]);
                    $response['msg'] ='sucess';
                    $response['status'] =true;
                    //$response['data'] =$inputs;
                    $response['log_download'] =$log_download;
                    //$response['cookie'] =$_COOKIE;
                    //$response['check_cookie'] =$check_cookie;
                    //$response['cookie_data'] =$cookie_data;
                    return  Response::json($response,200);
                   
                }else{

                    $response['msg'] ='Bad Request';
                    $response['status'] =false;
                    $response['data'] = '';
                    return  Response::json($response,400);

                }
                
            }else{

                $response['msg'] ='Method Not Allowed';
                $response['status'] =false;
                $response['data'] = '';
                return  Response::json($response,405);

            }
        }catch (\Exception $e){

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            $response['data'] = '';
            return  Response::json($response,500);

        }

    }








  
}


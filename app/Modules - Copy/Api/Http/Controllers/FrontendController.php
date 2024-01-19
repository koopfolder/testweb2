<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Article\Models\{Article,ArticleRevision,ArticleHitLogs,ArticleCategory};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ViewMedia,ViewAjaxMedia,MediaHitLogs,DataTags,ListArticle,Age,Sex};
use App\Modules\Documentsdownload\Models\{Documents};
use App\Modules\Setting\Models\Setting;
use App\Modules\SinglePage\Models\{SinglePage};
use App\Modules\Banner\Models\{Banner};
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
use Redirect;
use Auth;
use \Cache;
class FrontendController extends Controller
{


    function __construct()
    {
        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                //echo "1".URL(\Request::path());
                //echo route('home');
                return Redirect::to(URL(\Request::path()), 301);
            }
        }  
    }   
  
    public static function getDetailMedia(Request $request,$id){

        
        $id = Hashids::decode($id);
        $input = $request->all();

        //dd($input);

        if(collect($id)->isNotEmpty()){

            if(isset($input['token']) && Hash::check(env('KEY_PARTNERSHIP'),$input['token']) && isset($input['uid']) && isset($input['email'])){
                //dd("Ok");
                if (Auth::attempt(['email' => $input['email'],'password' =>$input['email'].env('SECRET','BRAVO'),'activate'=>1])) {
                    //dd("Login Success");
                }else{
                    return Redirect::to(env('URL_SSO_LOGIN'), 301);
                }
            }            


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


                $related_data= array();
                $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                if($tags_select !='' && count($tags_select) >0){
                    $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                    //dd($related_data);
                }else{
                    $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                    $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                }
                
                //dd($data,$tags_select);


                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);
                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                //     $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                //  }

                 //dd($related_data);

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

                $json = ($data->json_data !='' ? json_decode($data->json_data):'');
                //dd($json);
                $file_download = '';
                if(isset($json->UploadFileID)){

                    try{

                        $API_USER_GenTokenDownload = (env('API_USER_GenTokenDownload') !='' ? env('API_USER_GenTokenDownload'):'thrc-pro');
                        $API_PASSWORD_GenTokenDownload = (env('API_PASSWORD_GenTokenDownload') !='' ? env('API_PASSWORD_GenTokenDownload'):'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3');
                        $URL_GenTokenDownload = (env('URL_GenTokenDownload') !='' ? env('URL_GenTokenDownload'):'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload');
                    
                        $body = '{"UserName":"'.$API_USER_GenTokenDownload.'","Password":"'.$API_PASSWORD_GenTokenDownload.'","Email":"","IsPublic":"true","UploadFileID":"'.$json->UploadFileID.'"}';
                        $client = new \GuzzleHttp\Client(['verify' => false]);
                        $request = $client->request('POST',$URL_GenTokenDownload, ['body' => $body]); 
           
                        $response_api = $request->getBody()->getContents();
                        $response_api = str_replace(" ","",substr($response_api,3));
                        $data_json = json_decode($response_api, true);

                        //dd($data_json);

                        if($data_json['Success'] === true){
                            //dd($data_json['Token']);
                            $file_download = env('URL_DownloadFile').$data_json['Token'];
                        }


                    }catch (\Exception $e){

                    }
                }
                
                //dd($file_download);
                //dd($cookie_data);
                return view('template.media_detail_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend_data'=>$recommend_data,'cookie'=>$cookie_data,'file_download'=>$file_download]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }


    public static function getDetailMedia2($id){

        //dd("get Media",$id);
        //$id = Hashids::decode($id);
        $id = base64_decode($id);
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


                $related_data= array();
                $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                if($tags_select !='' && count($tags_select) >0){
                    $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                    //dd($related_data);
                }else{
                    $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                    $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                }                

                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                //     $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                //  }
                 //dd($related_data);

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

                $json = ($data->json_data !='' ? json_decode($data->json_data):'');
                //dd($json->UploadFileID);
                $file_download = '';
                if(isset($json->UploadFileID)){

                    try{

                    	$API_USER_GenTokenDownload = (env('API_USER_GenTokenDownload') !='' ? env('API_USER_GenTokenDownload'):'thrc-pro');
                    	$API_PASSWORD_GenTokenDownload = (env('API_PASSWORD_GenTokenDownload') !='' ? env('API_PASSWORD_GenTokenDownload'):'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3');
                    	$URL_GenTokenDownload = (env('URL_GenTokenDownload') !='' ? env('URL_GenTokenDownload'):'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload');

                        $body = '{"UserName":"'.$API_USER_GenTokenDownload.'","Password":"'.$API_PASSWORD_GenTokenDownload.'","Email":"","IsPublic":"true","UploadFileID":"'.$json->UploadFileID.'"}';
                        $client = new \GuzzleHttp\Client(['verify' => false]);
                        $request = $client->request('POST',$URL_GenTokenDownload, ['body' => $body]);     
                        $response_api = $request->getBody()->getContents();
                        $response_api = str_replace(" ","",substr($response_api,3));
                        $data_json = json_decode($response_api, true);

                        //dd($data_json);

                        // echo "<pre>";
                        //      print_r($data_json);
                        // echo "</pre>";

                        if($data_json['Success'] === true){
                            //dd($data_json['Token']);
                            $file_download = env('URL_DownloadFile').$data_json['Token'];
                            
                        }

                    }catch (\Exception $e){

                    }
                }
                
                //dd($file_download);
                //dd($cookie_data);
                return view('template.media_detail_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend_data'=>$recommend_data,'cookie'=>$cookie_data,'file_download'=>$file_download]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }

    public static function getDetailMediaWebView($id){

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


                $related_data= array();
                $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                if($tags_select !='' && count($tags_select) >0){
                    $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                    //dd($related_data);
                }else{
                    $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                    $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                }

                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                //     $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                //  }
                 //dd($related_data);


                $json = ($data->json_data !='' ? json_decode($data->json_data):'');
                //dd($json->UploadFileID);
                $file_download = '';
                if(isset($json->UploadFileID)){

                    try{

					    $API_USER_GenTokenDownload = (env('API_USER_GenTokenDownload') !='' ? env('API_USER_GenTokenDownload'):'thrc-pro');
					    $API_PASSWORD_GenTokenDownload = (env('API_PASSWORD_GenTokenDownload') !='' ? env('API_PASSWORD_GenTokenDownload'):'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3');
					    $URL_GenTokenDownload = (env('URL_GenTokenDownload') !='' ? env('URL_GenTokenDownload'):'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload');

					    $body = '{"UserName":"'.$API_USER_GenTokenDownload.'","Password":"'.$API_PASSWORD_GenTokenDownload.'","Email":"","IsPublic":"true","UploadFileID":"'.$json->UploadFileID.'"}';
					    $client = new \GuzzleHttp\Client(['verify' => false]);
					    $request = $client->request('POST',$URL_GenTokenDownload, ['body' => $body]); 
                    
                        $response_api = $request->getBody()->getContents();
                        $response_api = str_replace(" ","",substr($response_api,3));
                        $data_json = json_decode($response_api, true);

                        //dd($data_json);

                        if($data_json['Success'] === true){
                            //dd($data_json['Token']);
                            $file_download = env('URL_DownloadFile').$data_json['Token'];
                        }

                    }catch (\Exception $e){

                    }

                }
                
                //dd($file_download);
                //dd($cookie_data);
                return view('template.media_detail_web_view')->with(['data'=>$data,'related_data'=>$related_data,'file_download'=>$file_download]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }    


    public static function getDetailHealthliteracy($slug){
        
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

                 $related_data= array();
                 $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                  if($tags_select !='' && count($tags_select) >0){
                      $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                      //dd($related_data);
                  }else{
                      $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                  }  
                  
                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_data = Article::FrontListRelatedHealthLiteracy(['page_layout'=>$data->page_layout,'related_id'=>$data->id,'category_id'=>$data->category_id]);
                //  }



                 $most_view_data = Article::FrontListMostViewHealthLiteracy(['page_layout'=>$data->page_layout,'category_id'=>$data->category_id]);
                 $recommend = Article::FrontListRecommendHealthLiteracy(['page_layout'=>$data->page_layout,'related_id'=>$data->id,'category_id'=>$data->category_id]);

                $attachments = Documents::Data(['model_id'=>$data->id]);
                $attachments= collect($attachments);

                $ip = \Request::ip();
                $ip = str_replace('.','_',$ip);
                if(isset($_COOKIE['thrc_'.$ip])) {
                    $cookie_data = json_decode($_COOKIE['thrc_'.$ip]);
                }else{
                    $cookie_data = '';
                }

                if($data->dol_UploadFileID !=''){
                    //dd($data->dol_UploadFileID);
                   $data->dol_url = '';
                        try{

                            $API_USER_GenTokenDownload = (env('API_USER_GenTokenDownload') !='' ? env('API_USER_GenTokenDownload'):'thrc-pro');
                            $API_PASSWORD_GenTokenDownload = (env('API_PASSWORD_GenTokenDownload') !='' ? env('API_PASSWORD_GenTokenDownload'):'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3');
                            $URL_GenTokenDownload = (env('URL_GenTokenDownload') !='' ? env('URL_GenTokenDownload'):'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload');
                        
                            $body = '{"UserName":"'.$API_USER_GenTokenDownload.'","Password":"'.$API_PASSWORD_GenTokenDownload.'","Email":"","IsPublic":"true","UploadFileID":"'.$data->dol_UploadFileID.'"}';
                            $client = new \GuzzleHttp\Client(['verify' => false]);
                            $request = $client->request('POST',$URL_GenTokenDownload, ['body' => $body]); 
                        
                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ","",substr($response_api,3));
                            $data_json = json_decode($response_api, true);

                            //dd($data_json);

                            if($data_json['Success'] === true){
                                //dd($data_json['Token']);
                                $file_download = env('URL_DownloadFile').$data_json['Token'];
                                $data->dol_url = $file_download;
                            }

                        }catch (\Exception $e){

                        }   
               }

                return view('template.article_health_detail_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }    



    private static  function date_compare($element1, $element2) { 
        $datetime1 = strtotime($element1['created_at']); 
        $datetime2 = strtotime($element2['created_at']); 
        return $datetime1 - $datetime2; 
    }  


    public static function getDetailArticle(Request $request,$slug){
        

        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                //echo "1".URL(\Request::path());
                //echo route('home');
                return Redirect::to(URL(\Request::path()), 301);
            }
        }  

        $input = $request->all();

        //dd($input);
        if(isset($input['token']) && Hash::check(env('KEY_PARTNERSHIP'),$input['token']) && isset($input['uid']) && isset($input['email'])){
                //dd("Ok");
                if (Auth::attempt(['email' => $input['email'],'password' =>$input['email'].env('SECRET','BRAVO'),'activate'=>1])) {
                    //dd("Login Success");
                }else{
                    return Redirect::to(env('URL_SSO_LOGIN'), 301);
                }
        }    


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


                 $related_data= array();
                 $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                  if($tags_select !='' && count($tags_select) >0){
                      $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                      //dd($related_data);
                  }else{
                     $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                  }                
  
                  //$related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                //dd($related_data,$tags_select);

                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                //  }



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


                if($data->dol_UploadFileID !=''){
                     //dd($data->dol_UploadFileID);
                    $data->dol_url = '';
                         try{
 
                             $body = '{"UserName":"'.env('API_USER_GenTokenDownload').'","Password":"'.env('API_PASSWORD_GenTokenDownload').'","Email":"","IsPublic":"true","UploadFileID":"'.$data->dol_UploadFileID.'"}';
                             $client = new \GuzzleHttp\Client();
                             $request = $client->request('POST', env('URL_GenTokenDownload'), ['body' => $body]);    
                             $response_api = $request->getBody()->getContents();
                             $response_api = str_replace(" ","",substr($response_api,3));
                             $data_json = json_decode($response_api, true);
 
                             //dd($data_json);
 
                             if($data_json['Success'] === true){
                                 //dd($data_json['Token']);
                                 $file_download = env('URL_DownloadFile').$data_json['Token'];
                                 $data->dol_url = $file_download;
                             }
 
                         }catch (\Exception $e){
 
                         }   
                }
                  

                return view('template.article_detail_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }


    public static function getDetailArticleNcds6($slug){
        

        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                //echo "1".URL(\Request::path());
                //echo route('home');
                return Redirect::to(URL(\Request::path()), 301);
            }
        }  

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

                 $related_data= array();
                 $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                  if($tags_select !='' && count($tags_select) >0){
                      $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                      //dd($related_data);
                  }else{
                      $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                  }                  

                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                //  }



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

                if($data->dol_UploadFileID !=''){
                    //dd($data->dol_UploadFileID);
                   $data->dol_url = '';
                        try{

                            $body = '{"UserName":"'.env('API_USER_GenTokenDownload').'","Password":"'.env('API_PASSWORD_GenTokenDownload').'","Email":"","IsPublic":"true","UploadFileID":"'.$data->dol_UploadFileID.'"}';
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('URL_GenTokenDownload'), ['body' => $body]);    
                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ","",substr($response_api,3));
                            $data_json = json_decode($response_api, true);

                            //dd($data_json);

                            if($data_json['Success'] === true){
                                //dd($data_json['Token']);
                                $file_download = env('URL_DownloadFile').$data_json['Token'];
                                $data->dol_url = $file_download;
                            }

                        }catch (\Exception $e){

                        }   
                }
                  

                return view('template.article_ncds6_detail')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }

    public static function getDetailArticleNcds1($slug){
        

        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                //echo "1".URL(\Request::path());
                //echo route('home');
                return Redirect::to(URL(\Request::path()), 301);
            }
        }  

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

                 $related_data= array();
                 $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                  if($tags_select !='' && count($tags_select) >0){
                      $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                      //dd($related_data);
                  }else{
                      $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                  }  
                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                //  }



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

                if($data->dol_UploadFileID !=''){
                    //dd($data->dol_UploadFileID);
                   $data->dol_url = '';
                        try{

                            $body = '{"UserName":"'.env('API_USER_GenTokenDownload').'","Password":"'.env('API_PASSWORD_GenTokenDownload').'","Email":"","IsPublic":"true","UploadFileID":"'.$data->dol_UploadFileID.'"}';
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('URL_GenTokenDownload'), ['body' => $body]);    
                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ","",substr($response_api,3));
                            $data_json = json_decode($response_api, true);

                            //dd($data_json);

                            if($data_json['Success'] === true){
                                //dd($data_json['Token']);
                                $file_download = env('URL_DownloadFile').$data_json['Token'];
                                $data->dol_url = $file_download;
                            }

                        }catch (\Exception $e){

                        }   
                }
                  

                return view('template.article_ncds1_detail')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }    
    
    
    public static function getDetailArticleNcds2($slug){
        

        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                //echo "1".URL(\Request::path());
                //echo route('home');
                return Redirect::to(URL(\Request::path()), 301);
            }
        }  

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

                 $related_data= array();
                 $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                  if($tags_select !='' && count($tags_select) >0){
                      $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                      //dd($related_data);
                  }else{
                      $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                  }  

                  
                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                //  }



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

                if($data->dol_UploadFileID !=''){
                    //dd($data->dol_UploadFileID);
                   $data->dol_url = '';
                        try{

                            $body = '{"UserName":"'.env('API_USER_GenTokenDownload').'","Password":"'.env('API_PASSWORD_GenTokenDownload').'","Email":"","IsPublic":"true","UploadFileID":"'.$data->dol_UploadFileID.'"}';
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('URL_GenTokenDownload'), ['body' => $body]);    
                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ","",substr($response_api,3));
                            $data_json = json_decode($response_api, true);

                            //dd($data_json);

                            if($data_json['Success'] === true){
                                //dd($data_json['Token']);
                                $file_download = env('URL_DownloadFile').$data_json['Token'];
                                $data->dol_url = $file_download;
                            }

                        }catch (\Exception $e){

                        }   
               }
  


                $input = array();
                return view('template.article_ncds2_detail')->with(['input'=>$input,'data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend,'attachments'=>$attachments,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }        


    public static function getDetailArticleWebView($slug){
        
        //dd($slug);

        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                //echo "1".URL(\Request::path());
                //echo route('home');
                return Redirect::to(URL(\Request::path()), 301);
            }
        }  
        
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

                 $related_data= array();
                 $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                  if($tags_select !='' && count($tags_select) >0){
                      $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                      //dd($related_data);
                  }else{
                      $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                  }  

                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                //  }


                $attachments = Documents::Data(['model_id'=>$data->id]);
                $attachments= collect($attachments);

                if($data->dol_UploadFileID !=''){
                    //dd($data->dol_UploadFileID);
                   $data->dol_url = '';
                        try{

                            $API_USER_GenTokenDownload = (env('API_USER_GenTokenDownload') !='' ? env('API_USER_GenTokenDownload'):'thrc-pro');
                            $API_PASSWORD_GenTokenDownload = (env('API_PASSWORD_GenTokenDownload') !='' ? env('API_PASSWORD_GenTokenDownload'):'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3');
                            $URL_GenTokenDownload = (env('URL_GenTokenDownload') !='' ? env('URL_GenTokenDownload'):'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload');
                        
                            $body = '{"UserName":"'.$API_USER_GenTokenDownload.'","Password":"'.$API_PASSWORD_GenTokenDownload.'","Email":"","IsPublic":"true","UploadFileID":"'.$data->dol_UploadFileID.'"}';
                            $client = new \GuzzleHttp\Client(['verify' => false]);
                            $request = $client->request('POST',$URL_GenTokenDownload, ['body' => $body]); 

                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ","",substr($response_api,3));
                            $data_json = json_decode($response_api, true);

                            //dd($data_json);

                            if($data_json['Success'] === true){
                                //dd($data_json['Token']);
                                $file_download = env('URL_DownloadFile').$data_json['Token'];
                                $data->dol_url = $file_download;
                            }

                        }catch (\Exception $e){

                        }   
                }               


                return view('template.article_detail_web_view')->with(['data'=>$data,'related_data'=>$related_data,'attachments'=>$attachments]);
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

                $related_data= array();
                $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                if($tags_select !='' && count($tags_select) >0){
                    $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                    //dd($related_data);
                }else{
                    $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                    $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                }                

                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_id = ViewMedia::FrontListRelated(['issues'=>$issue_array,'related_id'=>$data->id])->pluck('id');
                //     $related_data = ListMedia::ListMediaCaseTop10Related(['id'=>$related_id]);
                //  }


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


                 $related_data= array();
                 $tags_select = ($data->tags !='' ? json_decode($data->tags):'');
                  if($tags_select !='' && count($tags_select) >0){
                      $related_data = ListArticle::FrontListRelated(['tags_select'=>$tags_select,'id'=>$data->id]);
                      //dd($related_data);
                  }else{
                     $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                  }        
                //  $related_data= array();
                //  $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                //  $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                //  $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                //  $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                //  //dd($data_id);
                //  if($data_media_id->count() || $data_article_id->count()){
                //     $related_data_media = array();
                //     if($data_media_id->count()){
                //         $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                //         foreach ($related_data_media as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }   
                    
                //     $related_data_article = array();
                //     if($data_article_id->count()){
                //         $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                //         foreach ($related_data_article as $value) {
                //             array_push($related_data, $value);
                //         }
                //     }
                //     if(count($related_data) > 0){
                //         usort($related_data,function ($element1, $element2) { 

                //                 $datetime1 = strtotime($element1['created_at']); 
                //                 $datetime2 = strtotime($element2['created_at']); 
                
                //                 //dd($datetime1,$datetime2,$element1,$element2);
                //                 if ($datetime1 < $datetime2){
                //                     return 1; 
                //                 }else if($datetime1 > $datetime2){
                //                     return -1; 
                //                 }else{
                //                     return 0; 
                //                 }
                //             }); 
                //     }
                //     $related_data = collect($related_data);

                //  }else{
                //     $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                //  }


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

                if($data->dol_UploadFileID !=''){
                    //dd($data->dol_UploadFileID);
                   $data->dol_url = '';
                        try{


                            $API_USER_GenTokenDownload = (env('API_USER_GenTokenDownload') !='' ? env('API_USER_GenTokenDownload'):'thrc-pro');
                            $API_PASSWORD_GenTokenDownload = (env('API_PASSWORD_GenTokenDownload') !='' ? env('API_PASSWORD_GenTokenDownload'):'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3');
                            $URL_GenTokenDownload = (env('URL_GenTokenDownload') !='' ? env('URL_GenTokenDownload'):'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload');
                        
                            $body = '{"UserName":"'.$API_USER_GenTokenDownload.'","Password":"'.$API_PASSWORD_GenTokenDownload.'","Email":"","IsPublic":"true","UploadFileID":"'.$data->dol_UploadFileID.'"}';
                            $client = new \GuzzleHttp\Client(['verify' => false]);
                            $request = $client->request('POST',$URL_GenTokenDownload, ['body' => $body]);                         	   
                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ","",substr($response_api,3));
                            $data_json = json_decode($response_api, true);

                            //dd($data_json);

                            if($data_json['Success'] === true){
                                //dd($data_json['Token']);
                                $file_download = env('URL_DownloadFile').$data_json['Token'];
                                $data->dol_url = $file_download;
                            }

                        }catch (\Exception $e){

                        }   
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

    public static function getListMediaWebView(){
        return view('template.list_media_web_view');
    }    

    public static function getListKnowledgesMedia(){
        return view('template.list_knowledges_media');
    }

    public static function getListCampaignsMedia(){
        return view('template.list_campaigns_media');
    }

    public static function getDataMedia($params)
    {
        //$list_data = ViewMedia::FrontList(['params'=>$params,'page'=>(isset($params['page']) ? $params['page']:'1')]);
        $list_data = ListArticle::FrontListJsonSearch(['params'=>$params,'page'=>(isset($params['page']) ? $params['page']:'1')]);
        //dd($list_data);
        $data = array();
        $data['items'] = $list_data;
        $data['old'] = $params;
        return $data;
    }
    
    public static function getDataMediaWebView($params)
    {

        //dd($params['age']);
        if(isset($params['age']) && $params['age'] !="0"){
            $age = str_replace("'","",$params['age']);
            $age_search = Age::select('id','name','age_min','age_max')->get();
            $age_array = [];
            foreach ($age_search as $key => $value) {
                //dd($value);
                if($age >= $value->age_min && $age <= $value->age_max){
                    //dd($value);
                    array_push($age_array, $value->id);
                } 
            }
            $params['age'] = implode(",", $age_array);
            //dd($age_array,$params['age']);
            //dd($age_search);
        }

        //$list_data = ViewMedia::FrontList(['params'=>$params,'page'=>(isset($params['page']) ? $params['page']:'1')]);
        $list_data = ListArticle::FrontListJsonSearchWebView(['params'=>$params,'page'=>(isset($params['page']) ? $params['page']:'1')]);
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

    public function postNcds1(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            { 
                $setting = Setting::select('value')
                ->where('slug','=','ncds_1')
                ->first();

                $single_page = SinglePage::select('id','title','description')
                        ->where('id','=',$setting->value)
                        ->first();

                if(isset($single_page->id)){
                    $data['id'] =Hashids::encode($single_page->id);
                    $data['url'] =route('single-page-frontend',['id'=>Hashids::encode($single_page->id)]);
                    $data['title'] = $single_page->title;
                    $data['description'] = self::removeTagHTML($single_page->description);
                    $data['img'] = ($single_page->getMedia('cover_desktop')->isNotEmpty() ? asset($single_page->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));
                }    
                
                
                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function postNcds2(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            {   
                $date = date('Y-m-d H:i:s');
                $data['situation_ncds_1'] = [];
                $data['situation_ncds_2'] = [];

                $query = Article::select('id','title','short_description','created_at','hit','slug');
                $query->where('status','=','publish');
                $query->where('page_layout','=','ncds-2');
                $query->where('start_date','<=',$date);
                $query->where('end_date','>=',$date);       
                $target_condition = '"1"';
                $target_field = '$.situation_ncds';
                $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                $query->orderByRaw('featured,created_at DESC');
                $query->limit(2);
                $situation_ncds_1  =$query->get();

                if($situation_ncds_1->count() >0){
                    foreach ($situation_ncds_1 as $key => $value) {
                        $array = array();
                        //$array['id'] =Hashids::encode($value->id);
                        $array['url'] =route('article-ncds2-detail',['slug'=>$value->slug]);
                        $array['title'] = $value->title;
                        $array['description'] = $value->short_description;
                        $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));                      
                        $array['created_at'] = Carbon::parse($value->created_at)->format('d.m.').(Carbon::parse($value->created_at)->format('Y')+543);
                        $array['hit'] = number_format($value->hit);
                        array_push($data['situation_ncds_1'],$array);
                    }
                }


                $query = Article::select('id','title','short_description','created_at','hit','slug');
                $query->where('status','=','publish');
                $query->where('page_layout','=','ncds-2');
                $query->where('start_date','<=',$date);
                $query->where('end_date','>=',$date);       
                $target_condition = '"2"';
                $target_field = '$.situation_ncds';
                $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                $query->orderByRaw('featured,created_at DESC');
                $query->limit(2);
                $situation_ncds_2  =$query->get();

                if($situation_ncds_2->count() >0){
                    foreach ($situation_ncds_2 as $key => $value) {
                        $array = array();
                        //$array['id'] =Hashids::encode($value->id);
                        $array['url'] =route('article-ncds2-detail',['slug'=>$value->slug]);
                        $array['title'] = $value->title;
                        $array['description'] = $value->short_description;
                        $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));                      
                        $array['created_at'] = Carbon::parse($value->created_at)->format('d.m.').(Carbon::parse($value->created_at)->format('Y')+543);
                        $array['hit'] = number_format($value->hit);
                        array_push($data['situation_ncds_2'],$array);
                    }
                }                
         
                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function postNcds3(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            { 
                $setting = Setting::select('value')
                ->where('slug','=','ncds_3')
                ->first();

                $single_page = SinglePage::select('id','title','description')
                        ->where('id','=',$setting->value)
                        ->first();

                if(isset($single_page->id)){
                    $data['id'] =Hashids::encode($single_page->id);
                    $data['url'] =route('single-page-frontend',['id'=>Hashids::encode($single_page->id)]);
                    $data['title'] = $single_page->title;
                    $data['description'] = self::removeTagHTML($single_page->description);
                    $data['img'] = ($single_page->getMedia('cover_desktop')->isNotEmpty() ? asset($single_page->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));
                }    
                
                
                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function postNcds4(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();

            // if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            // {   
                $date = date('Y-m-d H:i:s');

                $query = Article::select('id','title','dol_json_data');
                $query->where('status','=','publish');
                $query->where('page_layout','=','ncds-4');
                $query->where('start_date','<=',$date);
                $query->where('end_date','>=',$date);       
                $query->orderByRaw('featured,created_at DESC');
                $query->limit(8);
                $ncds4  =$query->get();

                if($ncds4->count() >0){
                    foreach ($ncds4 as $key => $value) {
                        $json = json_decode($value->dol_json_data);
                        $array = array();
                        $array['title'] = $value->title;
                        $array['url'] = (isset($json->url) ? $json->url:'#');
                        $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));                      
                        array_push($data,$array);
                    }
                }

                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                return  Response::json($response,200);

            // }else{
            //     $response['msg'] ='401 (Unauthorized)';
            //     $response['status'] =false;
            //     return  Response::json($response,401);
            // }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function postNcds5(Request $request){
        
        try {
        
            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            {   
                $date = date('Y-m-d H:i:s');

                $query = ArticleCategory::select('id','title');
                $query->where('status','=','publish');
                $query->where('type','=','health-literacy');
                $ncds5  =$query->get();

                if($ncds5->count() >0){
                    foreach ($ncds5 as $key => $value) {
                        $array = array();
                        $array['title'] = $value->title;
                        $array['url'] = route('list-health-literacy2',\Hashids::encode($value->id));
                        $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));                      
                        array_push($data,$array);
                    }
                }

                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function postNcds6(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data_main = '';
            $data = array();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            {   
                $date = date('Y-m-d H:i:s');

                $query = Article::select('id','title','short_description','slug','created_at','hit');
                $query->where('status','=','publish');
                $query->where('page_layout','=','ncds-6');
                $query->where('start_date','<=',$date);
                $query->where('end_date','>=',$date);       
                $query->orderByRaw('featured,created_at DESC');
                $query->limit(3);
                $ncds6  =$query->get();

                if($ncds6->count() >0){
                    foreach ($ncds6 as $key => $value) {
                        $array = array();
                        if($key ==0){
                            $array['title'] = $value->title;
                            $array['url'] =route('article-ncds6-detail',['slug'=>$value->slug]);
                            $array['description'] = self::removeTagHTML($value->short_description);
                            $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));
                            $array['created_at'] = Carbon::parse($value->created_at)->format('d.m.').(Carbon::parse($value->created_at)->format('Y')+543);
                            $array['hit'] = number_format($value->hit);                      
                            $data_main = $array;                            
                        }else{
                            $array['title'] = $value->title;
                            $array['url'] =route('article-ncds6-detail',['slug'=>$value->slug]);
                            $array['description'] = self::removeTagHTML($value->short_description);
                            $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));       
                            $array['created_at'] = Carbon::parse($value->created_at)->format('d.m.').(Carbon::parse($value->created_at)->format('Y')+543);
                            $array['hit'] = number_format($value->hit);               
                            array_push($data,$array);
                        }
                    }
                }

                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val_main']=$data_main;
                $response['val']=$data;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }


    public function postNcds7(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            { 
                $setting = Setting::select('value')
                ->where('slug','=','ncds_7')
                ->first();

                $ncds7 = Banner::Data(['category'=>$setting->value,'retrieving_results'=>'get','case_query'=>'none']);
                if($ncds7->count() >0){
                    foreach ($ncds7 as $key => $value) {
                        $array = array();
                        $array['name'] = $value->name;
                        $array['link'] = ThrcHelpers::generateUrl(['use_content'=>$value->use_content,'use_content_params'=>$value->use_content_params,'link'=>$value->link]);
                        $array['img'] = $value->getMedia('desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                        array_push($data,$array);
                    }
                }            
            
                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function postNotableBooks(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            { 

                
                $time_cache  =  ThrcHelpers::time_cache(5);
  
        
                if (Cache::has('data_notable_books')){
                    $data = Cache::get('data_notable_books');
                }else{
        
                    // $items = ListMedia::FrontListNotableBooks($params);
                    // $list = [];
                    // if(collect($items)->isNotEmpty()){
                    //     foreach($items as $value) {
                    //         $list[$value->id]['title']        = $value->title;
                    //         $list[$value->id]['json_data'] = $value->json_data;
                    //         $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                    //         $list[$value->id]['cover_desktop'] = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>'App\Modules\Api\Models\ListMedia','model_id'=>$value->id]); 
                    //         $list[$value->id]['cover_desktop'] = (isset($list[$value->id]['cover_desktop']->id)  ? asset('media/'.$list[$value->id]['cover_desktop']->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
                    //     }
                    // }
                    $list = [];
                    try {
        
                        $form_params = ['username'=>env('API_BOOKDOSE_USER','service_web_resources'),'password'=>env('API_BOOKDOSE_PASSWORD','7xNEQTzPXvYd')];
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST', env('URL_BOOKDOSE_LOGIN','http://library.thaihealth.or.th/api/service_web_resources/user_controller/check_login'), ['form_params' => $form_params]);    
                        $response_api = $request->getBody()->getContents();
                        $data_json = json_decode($response_api, true);
        
                        if($data_json['status'] == 'success'){
                            Setting::where('slug','=','bookdose_token')->update(['value'=>$data_json['result']['jwt']]);
        
                            $form_params = ['jwt'=>$data_json['result']['jwt']];
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('URL_BOOKDOSE_POPPULAR_LIST','http://library.thaihealth.or.th/api/service_web_resources/web_resources_home_controller/popular'), ['form_params' => $form_params]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            
                            if($data_json['status'] == 'success'){
                                //dd($data_json);
                                foreach($data_json['result'] as $value){
                                    //dd($value);
                                    $list[$value['aid']]['title'] = $value['name'];
                                    $list[$value['aid']]['link'] = $value['link'];
                                    $list[$value['aid']]['cover_desktop'] =  $value['image_thumb_url'];
                                }
        
                            }
        
                        }
        
                        $jtw = Setting::select('value')
                                        ->where('slug','=','bookdose_token')
                                        ->first();
                        //dd($jtw->value);
                        if(collect($jtw)->isNotEmpty() && $jtw->value !=''){
                            
                            $form_params = ['jwt'=>$jtw->value];
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('URL_BOOKDOSE_POPPULAR_LIST','http://library.thaihealth.or.th/api/service_web_resources/web_resources_home_controller/popular'), ['form_params' => $form_params]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            
                            if($data_json['status'] == 'success'){
                                //dd($data_json);
                                foreach($data_json['result'] as $value){
                                    //dd($value);
                                    $list[$value['aid']]['title'] = $value['name'];
                                    $list[$value['aid']]['link'] = $value['link'];
                                    $list[$value['aid']]['cover_desktop'] =  $value['image_thumb_url'];
                                }
        
                            }
        
                        }
                       
                    } catch (Exception $e) {
        
                        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        
                        // $form_params = ['username'=>env('API_BOOKDOSE_USER'),'password'=>env('API_BOOKDOSE_PASSWORD')];
                        // $client = new \GuzzleHttp\Client();
                        // $request = $client->request('POST', env('URL_BOOKDOSE_LOGIN'), ['form_params' => $form_params]);    
                        // $response_api = $request->getBody()->getContents();
                        // $data_json = json_decode($response_api, true);
        
                        // if($data_json['status'] == 'success'){
                        //     Setting::where('slug','=','bookdose_token')->update(['value'=>$data_json['result']['jwt']]);
        
                        //     $form_params = ['jwt'=>$data_json['result']['jwt']];
                        //     $client = new \GuzzleHttp\Client();
                        //     $request = $client->request('POST', env('URL_BOOKDOSE_POPPULAR_LIST'), ['form_params' => $form_params]);    
                        //     $response_api = $request->getBody()->getContents();
                        //     $data_json = json_decode($response_api, true);
                            
                        //     if($data_json['status'] == 'success'){
                        //         //dd($data_json);
                        //         foreach($data_json['result'] as $value){
                        //             //dd($value);
                        //             $list[$value['aid']]['title'] = $value['name'];
                        //             $list[$value['aid']]['link'] = $value['link'];
                        //             $list[$value['aid']]['cover_desktop'] =  $value['image_thumb_url'];
                        //         }
        
                        //     }
        
                        // }
                        //dd($data_json['result']['jwt'],$list);
                    }
        
                    //dd($list);
                    Cache::put('data_notable_books',collect($list),$time_cache);
                    $data = Cache::get('data_notable_books');
                }
 




                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }


    public static function getListNcds6(){
        return view('template.list_ncds6');
    }

    public static function getDataNcds6($params)
    {

        $page = (isset($params['page']) ? $params['page']:'1');
        $article = '';
        $article = Article::FrontListNcds6(['page_layout'=>'ncds-6','page'=>$page,'params'=>$params]);
        //dd($article);

        $data = array();
        $data['title_h1'] = 'เครื่องมืออื่นๆ ที่น่าสนใจ';
        $data['layout'] = 'ncds-6';
        $data['items'] = $article;
        $data['old'] = $params;
        return $data;
    }


    public static function getListNcds4(){
        return view('template.list_ncds4');
    }

    public static function getDataNcds4($params)
    {

        $page = (isset($params['page']) ? $params['page']:'1');
        $article = '';
        $article = Article::FrontListNcds4(['page_layout'=>'ncds-4','page'=>$page,'params'=>$params]);
        //dd($article);

        $data = array();
        $data['title_h1'] = 'แบบทดสอบทักษะความรอบรู้ด้านสุขภาพ';
        $data['layout'] = 'ncds-4';
        $data['items'] = $article;
        $data['old'] = $params;

        return $data;
    }

    public static function getListNcds1(Request $request){
        $input = $request->all();
        return view('template.list_ncds1',compact('input'));
    }

    public static function getDataNcds1($params)
    {

        $page = (isset($params['page']) ? $params['page']:'1');
        $article = '';
        $article = Article::FrontListNcds1(['page_layout'=>'ncds-1','page'=>$page,'params'=>$params]);
        //dd($article);

        $data = array();
        $data['title_h1'] = 'รู้จัก NCDs';
        $data['layout'] = 'ncds-1';
        $data['items'] = $article;
        $data['old'] = $params;

        return $data;
    }

    public static function getListNcds2(Request $request){
        $input = $request->all();
        //dd($data);
        return view('template.list_ncds2',compact('input'));
    }

    public function postNcds2List(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();
            $limit = 8;
            
            $input = $request->all();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            {   
                $date = date('Y-m-d H:i:s');
                $data['situation_ncds_1'] = [];
                $data['situation_ncds_1_total'] = 0;
                $data['situation_ncds_1_lastPage'] = 0;
                $data['situation_ncds_1_perPage'] = 0;
                $data['situation_ncds_1_currentPage'] = 0;
                $data['situation_ncds_2'] = [];
                $data['situation_ncds_2_total'] = 0;
                $data['situation_ncds_2_lastPage'] = 0;
                $data['situation_ncds_2_perPage'] = 0;
                $data['situation_ncds_2_currentPage'] = 0;

                $query = Article::select('id','title','short_description','created_at','hit','slug');
                $query->where('status','=','publish');
                $query->where('page_layout','=','ncds-2');
                $query->where('start_date','<=',$date);
                $query->where('end_date','>=',$date);       
                $target_condition = '"1"';
                $target_field = '$.situation_ncds';
                $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                

                // if(isset($input['disease']) && $input['disease'] !="0"){
                //     //dd($input['age']);
                //     $disease = $input['disease'];
                //     $target_condition = '"'.$disease.'"';
                //     $target_field = '$.disease';
                //     $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                    
                // }
                
                // if(isset($input['area']) && $input['area'] !="0"){
                //     //dd($input['age']);
                //     $area = $input['area'];
                //     $target_condition = '"'.$area.'"';
                //     $target_field = '$.area';
                //     $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                    
                // }

                // if(isset($input['category']) && $input['category'] !="0"){
                //     //dd($input['age']);
                //     $category = $input['category'];
                //     $query->where('category_id','=',$category);
                // }
                

                // if(isset($input['keyword']) && $input['keyword'] !=null){
                //     $keyword = $input['keyword'];
                //     $keyword =str_replace("'","",$keyword);
                //     $keyword = explode(" ", $keyword);

                //     //dd($keyword);
                //     //dd(count($keyword));
                //     $sql_like_title = '';
                //     $sql_like_description = '';
                //     $sql_like_keyword = '';
                //     foreach ($keyword as $key=>$value) {
                //         //dd($value);
                //         if($key ==0){
                //           $sql_like_title .='title like "%'.$value.'%"';
                //           $sql_like_description .='description like "'.$value.'%"';    
                //           //$sql_like_keyword .='keyword like "%'.$value.'%"';                  
                //        }else{
                //           $sql_like_title .=' OR title like "%'.$value.'%"';
                //           $sql_like_description .=' OR description like "'.$value.'%"';
                //           //$sql_like_keyword .=' OR keyword like "%'.$value.'%"';
                //        }
                //     }
                //         //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //         $query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
                // }      
                
                if(isset($input['issue']) && $input['issue'] !="0"){
                    //$query->where('issues_id', '=',$input['issue']);
                    $issue_ex =  explode(",", $input['issue']);
                    //dd($issue_ex);
                    $sql_issue ='(';
                    foreach ($issue_ex as $key => $value) {
                        //dd($key,$value);
                        $issues_condition = '{"ID":'.$value.'}';
                        $issues_field = '$.Issues';
                        if($key ==0){
                            $sql_issue .= ' JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                        }else{
                            $sql_issue .=' OR JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                        }
                    }
                    $sql_issue .= ')';
                    $query->whereRaw($sql_issue);
                }
        
                if(isset($input['template']) && $input['template'] !="0"){
                    $query->where('dol_template', '=',$input['template']);
                }
        
                if(isset($input['target']) && $input['target'] !="0"){
                    //$query->where('target_id', '=',$input['target']);
                    $target_ex =  explode(",", $input['target']);
                    $sql_target ='(';
                    foreach ($target_ex as $key => $value) {
                        //dd($value);
        
                        $target_condition = '{"ID":'.$value.'}';
                        $target_field = '$.Targets';
        
                        if($key ==0){
                            $sql_target .= ' JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                        }else{
                            $sql_target .=' OR JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                        }
        
                    }
                    $sql_target .= ')';
                    $query->whereRaw($sql_target);
        
                }
        
        
                if(isset($input['setting']) && $input['setting'] !="0"){
                    //$query->where('issues_id', '=',$input['issue']);
                    $setting_ex =  explode(",", $input['setting']);
                    $sql_setting ='(';
                    foreach ($setting_ex as $key => $value) {
                        //dd($value);
                        $setting_condition = '{"ID":'.$value.'}';
                        $setting_field = '$.Settings';
        
                        if($key ==0){
                            $sql_setting .= ' JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                        }else{
                            $sql_setting .=' OR JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                        }
                    }
                    $sql_setting .= ')';
                    $query->whereRaw($sql_setting);
        
                }
        
        
        
                if(isset($input['keyword']) && $input['keyword'] !=null){
                    $keyword = $input['keyword'];
                    $keyword = explode(" ", $keyword);
                    //dd($keyword);
                    //dd(count($keyword));
                    $sql_like_title = '';
                    $sql_like_description = '';
                    $sql_like_keyword = '';
                    $sql_like_tags = '';

                    foreach ($keyword as $key=>$value) {
                        //dd($value);
                        if($key ==0){
                          $sql_like_title .='title like "%'.$value.'%"';
                          $sql_like_description .='description like "'.$value.'%"';    
                          $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";    
                          $sql_like_tags .="JSON_CONTAINS (tags,'".'"'.$value.'"'."')";                 
                       }else{
                          $sql_like_title .=' OR title like "%'.$value.'%"';
                          $sql_like_description .=' OR description like "'.$value.'%"';
                          $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";
                          $sql_like_tags .=" OR JSON_CONTAINS (list_article.tags,'".'"'.$value.'"'."')";   
                       }
                    }
                        $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
                        //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                        //$query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
                }                

                
                
                $query->orderByRaw('featured,created_at DESC');
                $situation_ncds_1  =$query->paginate($limit);

                if($situation_ncds_1->count() >0){
                    $data['situation_ncds_1_total'] = $situation_ncds_1->total();
                    $data['situation_ncds_1_lastPage'] = $situation_ncds_1->lastPage();
                    $data['situation_ncds_1_perPage'] = $situation_ncds_1->perPage();
                    $data['situation_ncds_1_currentPage'] = $situation_ncds_1->currentPage(); 
                    foreach ($situation_ncds_1 as $key => $value) {
                        $array = array();
                        //$array['id'] =Hashids::encode($value->id);
                        $array['url'] =route('article-ncds2-detail',['slug'=>$value->slug]);
                        $array['title'] = $value->title;
                        $array['description'] = $value->short_description;
                        $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));                      
                        $array['created_at'] = Carbon::parse($value->created_at)->format('d.m.').(Carbon::parse($value->created_at)->format('Y')+543);
                        $array['hit'] = number_format($value->hit);
                        array_push($data['situation_ncds_1'],$array);
                    }
                }


                $query = Article::select('id','title','short_description','created_at','hit','slug');
                $query->where('status','=','publish');
                $query->where('page_layout','=','ncds-2');
                $query->where('start_date','<=',$date);
                $query->where('end_date','>=',$date);       
                $target_condition = '"2"';
                $target_field = '$.situation_ncds';
                $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
 

                // if(isset($input['disease']) && $input['disease'] !="0"){
                //     //dd($input['age']);
                //     $disease = $input['disease'];
                //     $target_condition = '"'.$disease.'"';
                //     $target_field = '$.disease';
                //     $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                    
                // }
                
                // if(isset($input['area']) && $input['area'] !="0"){
                //     //dd($input['age']);
                //     $area = $input['area'];
                //     $target_condition = '"'.$area.'"';
                //     $target_field = '$.area';
                //     $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                    
                // }
                
                // if(isset($input['category']) && $input['category'] !="0"){
                //     //dd($input['age']);
                //     $category = $input['category'];
                //     $query->where('category_id','=',$category);
                // }               

                // if(isset($input['keyword']) && $input['keyword'] !=null){
                //     $keyword = $input['keyword'];
                //     $keyword =str_replace("'","",$keyword);
                //     $keyword = explode(" ", $keyword);
                    
                //     //dd($keyword);
                //     //dd(count($keyword));
                //     $sql_like_title = '';
                //     $sql_like_description = '';
                //     $sql_like_keyword = '';
                //     foreach ($keyword as $key=>$value) {
                //         //dd($value);
                //         if($key ==0){
                //           $sql_like_title .='title like "%'.$value.'%"';
                //           $sql_like_description .='description like "'.$value.'%"';    
                //           //$sql_like_keyword .='keyword like "%'.$value.'%"';                  
                //        }else{
                //           $sql_like_title .=' OR title like "%'.$value.'%"';
                //           $sql_like_description .=' OR description like "'.$value.'%"';
                //           //$sql_like_keyword .=' OR keyword like "%'.$value.'%"';
                //        }
                //     }
                //         //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //         $query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
                // }                

                if(isset($input['issue']) && $input['issue'] !="0"){
                    //$query->where('issues_id', '=',$input['issue']);
                    $issue_ex =  explode(",", $input['issue']);
                    //dd($issue_ex);
                    $sql_issue ='(';
                    foreach ($issue_ex as $key => $value) {
                        //dd($key,$value);
                        $issues_condition = '{"ID":'.$value.'}';
                        $issues_field = '$.Issues';
                        if($key ==0){
                            $sql_issue .= ' JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                        }else{
                            $sql_issue .=' OR JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                        }
                    }
                    $sql_issue .= ')';
                    $query->whereRaw($sql_issue);
                }
        
                if(isset($input['template']) && $input['template'] !="0"){
                    $query->where('dol_template', '=',$input['template']);
                }
        
                if(isset($input['target']) && $input['target'] !="0"){
                    //$query->where('target_id', '=',$input['target']);
                    $target_ex =  explode(",", $input['target']);
                    $sql_target ='(';
                    foreach ($target_ex as $key => $value) {
                        //dd($value);
        
                        $target_condition = '{"ID":'.$value.'}';
                        $target_field = '$.Targets';
        
                        if($key ==0){
                            $sql_target .= ' JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                        }else{
                            $sql_target .=' OR JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                        }
        
                    }
                    $sql_target .= ')';
                    $query->whereRaw($sql_target);
        
                }
        
        
                if(isset($input['setting']) && $input['setting'] !="0"){
                    //$query->where('issues_id', '=',$input['issue']);
                    $setting_ex =  explode(",", $input['setting']);
                    $sql_setting ='(';
                    foreach ($setting_ex as $key => $value) {
                        //dd($value);
                        $setting_condition = '{"ID":'.$value.'}';
                        $setting_field = '$.Settings';
        
                        if($key ==0){
                            $sql_setting .= ' JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                        }else{
                            $sql_setting .=' OR JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                        }
                    }
                    $sql_setting .= ')';
                    $query->whereRaw($sql_setting);
        
                }
        
        
        
        
                if(isset($input['keyword']) && $input['keyword'] !=null){
                    $keyword = $input['keyword'];
                    $keyword = explode(" ", $keyword);
                    //dd($keyword);
                    //dd(count($keyword));
                    $sql_like_title = '';
                    $sql_like_description = '';
                    $sql_like_keyword = '';
                    $sql_like_tags = '';

                    foreach ($keyword as $key=>$value) {
                        //dd($value);
                        if($key ==0){
                          $sql_like_title .='title like "%'.$value.'%"';
                          $sql_like_description .='description like "'.$value.'%"';    
                          $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";    
                          $sql_like_tags .="JSON_CONTAINS (tags,'".'"'.$value.'"'."')";                 
                       }else{
                          $sql_like_title .=' OR title like "%'.$value.'%"';
                          $sql_like_description .=' OR description like "'.$value.'%"';
                          $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";
                          $sql_like_tags .=" OR JSON_CONTAINS (list_article.tags,'".'"'.$value.'"'."')";   
                       }
                    }
                        $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
                        //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                        //$query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
                }                
                
                
                
                $query->orderByRaw('featured,created_at DESC');
                $situation_ncds_2  =$query->paginate($limit);

                if($situation_ncds_2->count() >0){
                    $data['situation_ncds_2_total'] = $situation_ncds_2->total();
                    $data['situation_ncds_2_lastPage'] = $situation_ncds_2->lastPage();
                    $data['situation_ncds_2_perPage'] = $situation_ncds_2->perPage();
                    $data['situation_ncds_2_currentPage'] = $situation_ncds_2->currentPage(); 
                    foreach ($situation_ncds_2 as $key => $value) {
                        $array = array();
                        //$array['id'] =Hashids::encode($value->id);
                        $array['url'] =route('article-ncds2-detail',['slug'=>$value->slug]);
                        $array['title'] = $value->title;
                        $array['description'] = $value->short_description;
                        $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));                      
                        $array['created_at'] = Carbon::parse($value->created_at)->format('d.m.').(Carbon::parse($value->created_at)->format('Y')+543);
                        $array['hit'] = number_format($value->hit);
                        array_push($data['situation_ncds_2'],$array);
                    }
                }                
         
                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                //$response['input']=$input;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }


    public function postNcds2ListLoadMore(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $data = array();
            $limit = 8;
            
            $input = $request->all();

            if (Hash::check(env('SECRET','BRAVO'),$token['1']))
            {   

                $date = date('Y-m-d H:i:s');
                $data['situation_ncds'] = [];
                $data['situation_ncds_total'] = 0;
                $data['situation_ncds_lastPage'] = 0;
                $data['situation_ncds_perPage'] = 0;
                $data['situation_ncds_currentPage'] = 0;


                $query = Article::select('id','title','short_description','created_at','hit','slug');
                $query->where('status','=','publish');
                $query->where('page_layout','=','ncds-2');
                $query->where('start_date','<=',$date);
                $query->where('end_date','>=',$date);     

                if($input['situation_type'] ==1){
                    $target_condition = '"1"';
                }else{
                    $target_condition = '"2"';
                }
            
                $target_field = '$.situation_ncds';
                $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                

                // if(isset($input['disease']) && $input['disease'] !="0"){
                //     //dd($input['age']);
                //     $disease = $input['disease'];
                //     $target_condition = '"'.$disease.'"';
                //     $target_field = '$.disease';
                //     $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                    
                // }
                
                // if(isset($input['area']) && $input['area'] !="0"){
                //     //dd($input['age']);
                //     $area = $input['area'];
                //     $target_condition = '"'.$area.'"';
                //     $target_field = '$.area';
                //     $query->whereRaw('JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
                    
                // }

                // if(isset($input['category']) && $input['category'] !="0"){
                //     //dd($input['age']);
                //     $category = $input['category'];
                //     $query->where('category_id','=',$category);
                // }
                

                // if(isset($input['keyword']) && $input['keyword'] !=null){
                //     $keyword = $input['keyword'];
                //     $keyword =str_replace("'","",$keyword);
                //     $keyword = explode(" ", $keyword);

                //     //dd($keyword);
                //     //dd(count($keyword));
                //     $sql_like_title = '';
                //     $sql_like_description = '';
                //     $sql_like_keyword = '';
                //     foreach ($keyword as $key=>$value) {
                //         //dd($value);
                //         if($key ==0){
                //           $sql_like_title .='title like "%'.$value.'%"';
                //           $sql_like_description .='description like "'.$value.'%"';    
                //           //$sql_like_keyword .='keyword like "%'.$value.'%"';                  
                //        }else{
                //           $sql_like_title .=' OR title like "%'.$value.'%"';
                //           $sql_like_description .=' OR description like "'.$value.'%"';
                //           //$sql_like_keyword .=' OR keyword like "%'.$value.'%"';
                //        }
                //     }
                //         //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //         $query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
                // }                
                
                if(isset($input['issue']) && $input['issue'] !="0"){
                    //$query->where('issues_id', '=',$input['issue']);
                    $issue_ex =  explode(",", $input['issue']);
                    //dd($issue_ex);
                    $sql_issue ='(';
                    foreach ($issue_ex as $key => $value) {
                        //dd($key,$value);
                        $issues_condition = '{"ID":'.$value.'}';
                        $issues_field = '$.Issues';
                        if($key ==0){
                            $sql_issue .= ' JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                        }else{
                            $sql_issue .=' OR JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                        }
                    }
                    $sql_issue .= ')';
                    $query->whereRaw($sql_issue);
                }
        
                if(isset($input['template']) && $input['template'] !="0"){
                    $query->where('dol_template', '=',$input['template']);
                }
        
                if(isset($input['target']) && $input['target'] !="0"){
                    //$query->where('target_id', '=',$input['target']);
                    $target_ex =  explode(",", $input['target']);
                    $sql_target ='(';
                    foreach ($target_ex as $key => $value) {
                        //dd($value);
        
                        $target_condition = '{"ID":'.$value.'}';
                        $target_field = '$.Targets';
        
                        if($key ==0){
                            $sql_target .= ' JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                        }else{
                            $sql_target .=' OR JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                        }
        
                    }
                    $sql_target .= ')';
                    $query->whereRaw($sql_target);
        
                }
        
        
                if(isset($input['setting']) && $input['setting'] !="0"){
                    //$query->where('issues_id', '=',$input['issue']);
                    $setting_ex =  explode(",", $input['setting']);
                    $sql_setting ='(';
                    foreach ($setting_ex as $key => $value) {
                        //dd($value);
                        $setting_condition = '{"ID":'.$value.'}';
                        $setting_field = '$.Settings';
        
                        if($key ==0){
                            $sql_setting .= ' JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                        }else{
                            $sql_setting .=' OR JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                        }
                    }
                    $sql_setting .= ')';
                    $query->whereRaw($sql_setting);
        
                }
        
        
        
        
                if(isset($input['keyword']) && $input['keyword'] !=null){
                    $keyword = $input['keyword'];
                    $keyword = explode(" ", $keyword);
                    //dd($keyword);
                    //dd(count($keyword));
                    $sql_like_title = '';
                    $sql_like_description = '';
                    $sql_like_keyword = '';
                    $sql_like_tags = '';

                    foreach ($keyword as $key=>$value) {
                        //dd($value);
                        if($key ==0){
                          $sql_like_title .='title like "%'.$value.'%"';
                          $sql_like_description .='description like "'.$value.'%"';    
                          $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";    
                          $sql_like_tags .="JSON_CONTAINS (tags,'".'"'.$value.'"'."')";                 
                       }else{
                          $sql_like_title .=' OR title like "%'.$value.'%"';
                          $sql_like_description .=' OR description like "'.$value.'%"';
                          $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";
                          $sql_like_tags .=" OR JSON_CONTAINS (list_article.tags,'".'"'.$value.'"'."')";   
                       }
                    }
                        $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
                        //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                        //$query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
                }                 


                
                $query->orderByRaw('featured,created_at DESC');
                $situation_ncds  =$query->paginate($limit);

                if($situation_ncds->count() >0){
                    $data['situation_ncds_total'] = $situation_ncds->total();
                    $data['situation_ncds_lastPage'] = $situation_ncds->lastPage();
                    $data['situation_ncds_perPage'] = $situation_ncds->perPage();
                    $data['situation_ncds_currentPage'] = $situation_ncds->currentPage(); 
                    foreach ($situation_ncds as $key => $value) {
                        $array = array();
                        //$array['id'] =Hashids::encode($value->id);
                        $array['url'] =route('article-ncds2-detail',['slug'=>$value->slug]);
                        $array['title'] = $value->title;
                        $array['description'] = $value->short_description;
                        $array['img'] = ($value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg'));                      
                        $array['created_at'] = Carbon::parse($value->created_at)->format('d.m.').(Carbon::parse($value->created_at)->format('Y')+543);
                        $array['hit'] = number_format($value->hit);
                        array_push($data['situation_ncds'],$array);
                    }
                }

                
                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['val']=$data;
                $response['input']=$input;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }


    public function postDolDownloadFiles(Request $request){

        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                $data = '';
                $dol_code = '';
                try{
                    $API_USER_GenTokenDownload = (env('API_USER_GenTokenDownload') !='' ? env('API_USER_GenTokenDownload'):'thrc-pro');
                    $API_PASSWORD_GenTokenDownload = (env('API_PASSWORD_GenTokenDownload') !='' ? env('API_PASSWORD_GenTokenDownload'):'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3');
                    $URL_GenTokenDownload = (env('URL_GenTokenDownload') !='' ? env('URL_GenTokenDownload'):'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload');
                    $body = '{"UserName":"'.$API_USER_GenTokenDownload.'","Password":"'.$API_PASSWORD_GenTokenDownload.'","Email":"","IsPublic":"true","UploadFileID":"'.$inputs['dol_UploadFileID'].'"}';
                    $client = new \GuzzleHttp\Client();
                    $request = $client->request('POST',$URL_GenTokenDownload, ['body' => $body]);    
                    $response_api = $request->getBody()->getContents();
                    $response_api = str_replace(" ","",substr($response_api,3));
                    $data_json = json_decode($response_api, true);

                    //dd($data_json);
                    $dol_code = $data_json['Code'];
	                if($data_json['Success'] === true){
                        //dd($data_json['Token']);
                        $file_download = (env('URL_DownloadFile') !='' ? env('URL_DownloadFile'):'https://dol.thaihealth.or.th/DownloadFile/').$data_json['Token'];
                        $data = $file_download;
                    }


                }catch (\Exception $e){
                    $response['msg'] =$e->getMessage();
                    $response['status'] =false;
                    $response['data'] = '';
                }   
                
                    $response['msg'] ='sucess';
                    $response['status'] =true;
                    $response['data'] =$data;
                    $response['dol_code'] =$dol_code;
                    return  Response::json($response,200);
                
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

    public function postDownloadCaseDol(Request $request){

        try{
            if(\Request::Ajax()){

                $inputs = $request->all();

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


                    $response['msg'] ='sucess';
                    $response['status'] =true;
                    $response['log_download'] =$log_download;
                    //$response['data'] =$inputs;
                    return  Response::json($response,200);
                   

                
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


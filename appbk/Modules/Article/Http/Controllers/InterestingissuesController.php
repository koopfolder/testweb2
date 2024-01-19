<?php

namespace App\Modules\Article\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Article\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Article\Models\{Article,ArticleRevision,ArticleHitLogs};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ViewMedia,ViewArticlesResearch,ViewIncludeStatistics,MediaHitLogs,ViewInterestingIssues,Tags,DataTags};
use App\Modules\Documentsdownload\Models\{Documents};
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use DB;
use Illuminate\Support\Facades\Cache;
use ThrcHelpers;
use Redirect;

class InterestingissuesController extends Controller
{

    function __construct()
    {
        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                return Redirect::to(URL(\Request::path()), 301);
            }
        }  
    }


    public function getIndex()
    {
        //dd("interesting-issues");
        $items = Article::Data(['status'=>['publish','draft'],'page_layout'=>'interesting_issues']);
        //dd($items);
        return view('article::backend.interesting-issues.index', compact('items'));
    }

    public function getIndexiframe()
    {
        $items = Article::Data(['status'=>['publish'],'page_layout'=>'interesting_issues']);
        return view('article::index-interesting-issues-iframe', compact('items'));
    }

    public function getCreate()
    {
        $tags = Tags::Data(['status'=>['publish']])->pluck('title','title');
        return view('article::backend.interesting-issues.create',compact('tags'));
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        //dd($data);

        $data['page_layout'] = 'interesting_issues';
        $date_year = date('Y-m-d');
        $date_year = strtotime($date_year);
        $date_year = strtotime("+50 year", $date_year);
        $data['start_date'] = Carbon::parse($data['start_date'])->format('Y-m-d H:i:s');
        $data['end_date'] = (!empty($data['end_date']) ? Carbon::parse($data['end_date'])->format('Y-m-d H:i:s'):date('Y-m-d H:i:s',$date_year));
        if(isset($data['tags'])){

            //dd($data['tags']);
            $data_tags = array();
            foreach($data['tags'] AS $key=>$value){

                //dd($value);
                $tag_check = Tags::select('id')->where('title','=',$value)->first();
                //dd($tag_check);
                if(isset($tag_check->id)){
                    array_push($data_tags, $value);
                }else{
                    $data_tag_master = array();
                    $data_tag_master['title'] = $value;
                    $data_tag_master['status'] = 'publish';
                    Tags::create($data_tag_master);
                    array_push($data_tags, $value);
                }
            }
            //dd($data_tags);
            //$item->update(['tags'=>json_encode($data_tags)]);
            $data['tags'] = json_encode($data_tags);
        }
        $data['dol_UploadFileID'] = '';        
        $item = Article::create($data);
        $id = $item->id;
        $data['article_id'] = $id;
        ArticleRevision::create($data);

        if($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }
        // if($request->hasFile('cover_mobile')) {
        //     $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        // }
        if($request->file('gallery_desktop')){
            foreach ($request->file('gallery_desktop') as $key => $value) {
                $item->addMedia($value)->toMediaCollection('gallery_desktop');
            }
        }

        if($request->hasFile('document')){
           
            $destinationPath =  public_path().'/files/attached_file/'.$item->id; // upload path
            $destinationPath2 = '/files/attached_file/'.$item->id;

            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    Documents::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'model_type'=>'article','file_path'=>$path_file,'model_id'=>$id]);
                }
            }else{
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    Documents::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'model_type'=>'article','file_path'=>$path_file,'model_id'=>$id]);
                }
            }

        }


        // if(isset($data['tags'])){
        //     $data_tags = array();
        //     DataTags::where('data_id','=',$id)->delete();
        //     foreach($data['tags'] AS $key=>$value){

        //         //dd($value);
        //         if(is_numeric($value)){
            
        //             $data_tags['data_id'] = $id;
        //             $data_tags['tags_id'] = $value;
        //             $data_tags['data_type'] = 'article';

        //             //dd($data_tags);
        //             DataTags::create($data_tags);
        //         }else{


        //             $data_tag_master = array();
        //             $data_tag_master['title'] = $value;
        //             $data_tag_master['status'] = 'publish';
        //             $tags_id = Tags::create($data_tag_master);

        //             $data_tags['data_id'] =$id;
        //             $data_tags['tags_id'] = $tags_id->id;
        //             $data_tags['data_type'] = 'article';
                    
        //             //dd($data_tags);
        //             DataTags::create($data_tags);
        //         }

        //     }
            
        // }else{
        //    DataTags::where('data_id','=',$id)->delete();
        // }


        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'7']);
        return redirect()->route('admin.interesting-issues.index')
                            ->with('status', 'success')
                            ->with('message', trans('article::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = Article::findOrFail($id);
        $revisions = ArticleRevision::where('article_id', $id)->orderBy('created_at','DESC')->get();
        $attachments = Documents::Data(['model_id'=>$id]);
        $data['attachments'] = collect($attachments);
        $tags = Tags::Data(['status'=>['publish']])->pluck('title','title');
        //$tags_select = DataTags::DataId(['data_id'=>$id,'data_type'=>'article'])->pluck('tags_id');
        $tags_select = json_decode($data['tags']);
        //dd($data,$revisions);
        return view('article::backend.interesting-issues.edit', compact('data','revisions','tags','tags_select'));
    }

    public function postEdit(EditRequest $request, $id)
    {

        $item = Article::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);
        $date_year = date('Y-m-d');
        $date_year = strtotime($date_year);
        $date_year = strtotime("+50 year", $date_year);
        $data['start_date'] = Carbon::parse($data['start_date'])->format('Y-m-d H:i:s');
        $data['end_date'] = (!empty($data['end_date']) ? Carbon::parse($data['end_date'])->format('Y-m-d H:i:s'):date('Y-m-d H:i:s',$date_year));
        //dd($data);

        if(isset($data['tags'])){

            //dd($data['tags']);
            $data_tags = array();
            foreach($data['tags'] AS $key=>$value){

                //dd($value);
                $tag_check = Tags::select('id')->where('title','=',$value)->first();
                //dd($tag_check);
                if(isset($tag_check->id)){
                    array_push($data_tags, $value);
                }else{
                    $data_tag_master = array();
                    $data_tag_master['title'] = $value;
                    $data_tag_master['status'] = 'publish';
                    Tags::create($data_tag_master);
                    array_push($data_tags, $value);
                }
            }
            //dd($data_tags);
            //$item->update(['tags'=>json_encode($data_tags)]);
            $data['tags'] = json_encode($data_tags);
        }else{
            $data['tags'] ='';
        }        
        $item->update($data);
        $data['article_id'] = $id;
        ArticleRevision::create($data);

        if ($request->hasFile('cover_desktop')){
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        // if ($request->hasFile('cover_mobile')){
        //     $item->clearMediaCollection('cover_mobile');
        //     $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        // }
        if($request->file('gallery_desktop')){
            foreach ($request->file('gallery_desktop') as $key => $value) {
                $item->addMedia($value)->toMediaCollection('gallery_desktop');
            }
        }

        if($request->hasFile('document')){
           
            $destinationPath =  public_path().'/files/attached_file/'.$item->id; // upload path
            $destinationPath2 = '/files/attached_file/'.$item->id;

            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    Documents::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'model_type'=>'article','file_path'=>$path_file,'model_id'=>$id]);
                }
            }else{
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    Documents::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'model_type'=>'article','file_path'=>$path_file,'model_id'=>$id]);
                }
            }

        }

        // if(isset($data['tags'])){
        //     $data_tags = array();
        //     DataTags::where('data_id','=',$id)->delete();
        //     foreach($data['tags'] AS $key=>$value){

        //         //dd($value);
        //         if(is_numeric($value)){
            
        //             $data_tags['data_id'] = $id;
        //             $data_tags['tags_id'] = $value;
        //             $data_tags['data_type'] = 'article';

        //             //dd($data_tags);
        //             DataTags::create($data_tags);
        //         }else{


        //             $data_tag_master = array();
        //             $data_tag_master['title'] = $value;
        //             $data_tag_master['status'] = 'publish';
        //             $tags_id = Tags::create($data_tag_master);

        //             $data_tags['data_id'] =$id;
        //             $data_tags['tags_id'] = $tags_id->id;
        //             $data_tags['data_type'] = 'article';
                    
        //             //dd($data_tags);
        //             DataTags::create($data_tags);
        //         }

        //     }
            
        // }else{
        //    DataTags::where('data_id','=',$id)->delete();
        // }


        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'7']);
        return redirect()->route('admin.interesting-issues.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Article::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Article::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
            }
            return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->back()
                        ->with('status', 'error')
                        ->with('message', 'Not users');
    }

    public function getReverse($id)
    {
        $reverse = ArticleRevision::find($id);
        $ArticleId = $reverse->article_id;
        $fields = array_except($reverse->toArray(),['article_id','created_by']);
        $article = Article::find($ArticleId);
        $article->title = $fields['title'];
        $article->description = $fields['description'];
        $article->short_description = $fields['short_description'];
        $article->featured = $fields['featured'];
        $article->meta_title = $fields['meta_title'];
        $article->meta_keywords = $fields['meta_keywords'];
        $article->meta_description = $fields['meta_description'];
        $article->hit = $fields['hit'];
        $article->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


    public function postAjaxDeleteGallery(Request $request){
        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                $media_id = $inputs['id'];
                Article::whereHas('media', function ($query) use($media_id){
                         $query->whereId($media_id);
                })->first()->deleteMedia($media_id);
                $response['msg'] ='sucess';
                $response['status'] =true;
               // $response['data'] = $directory;
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


    public function postAjaxDeleteDocument(Request $request){
        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                $id = $inputs['id'];

                $item = Documents::findOrFail($id);
                $item->delete();

                $response['msg'] ='sucess';
                $response['status'] =true;
                $response['data'] =$inputs;
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


    public static function getDataInterestingissues($params)
    {

        $page = (isset($params['page']) ? $params['page']:'1');
        $time_cache  =  ThrcHelpers::time_cache(5);
        $article = '';
        if (Cache::has('data_interesting_issues_page_'.$page)){
            $article = Cache::get('data_interesting_issues_page_'.$page);
        }else{
            $article = Article::FrontList(['page_layout'=>'interesting_issues','page'=>$page]);
            //dd($article);
            //$article = ViewInterestingIssues::FrontList(['page'=>$page]);
            Cache::put('data_interesting_issues_page_'.$page,$article,$time_cache);
            $article = Cache::get('data_interesting_issues_page_'.$page);
        }


        //dd($article);


        ///$data =Article::FrontHighlight(['page_layout'=>'news','page'=>(isset($params['page']) ? $params['page']:'1')]);
        // $article = Article::FrontList(['page_layout'=>'interesting_issues','page'=>(isset($params['page']) ? $params['page']:'1')]);
        // $media = ListMedia::FrontList(['page_layout'=>'interesting_issues','page'=>(isset($params['page']) ? $params['page']:'1')]);

        // //dd($article,$media);
        // $list = [];
        // if(collect($article)->isNotEmpty()){

        //     foreach($article as $key => $interesting_issues) {

        //         $array = array();
        //         $array['id']        = $interesting_issues->id;
        //         $array['title']        = $interesting_issues->title;
        //         $array['description'] = $interesting_issues->description;
        //         $array['type_data'] = 'article';
        //         $array['created_at'] = $interesting_issues->created_at;
        //         $array['updated_at'] = $interesting_issues->updated_at;
        //         $array['hit'] = $interesting_issues->hit;
        //         $array['url'] = route('interestingissues-article-detail',$interesting_issues->slug);
        //         $array['cover_desktop'] = $interesting_issues->getMedia('cover_desktop')->isNotEmpty() ? asset($interesting_issues->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
        //         array_push($list,$array);
                
        //     }

        // }

        // if(collect($media)->isNotEmpty()){

        //     foreach($media as $key => $interesting_issues) {
                
        //         $json = ($interesting_issues->json_data !='' ? json_decode($interesting_issues->json_data):'');
        //         //dd($interesting_issues,$json,gettype($json));
        //         $array = array();
        //         $array['id']        = $interesting_issues->id;
        //         $array['title']        = $interesting_issues->title;
        //         $array['description'] = $interesting_issues->description;
        //         $array['type_data'] = 'media';
        //         $array['created_at'] = $interesting_issues->created_at;
        //         $array['updated_at'] = $interesting_issues->updated_at;
        //         $array['hit'] = $interesting_issues->hit;
        //         $array['url'] = route('interestingissues-media-detail',Hashids::encode($interesting_issues->id));
        //         $array['cover_desktop'] = $interesting_issues->getMedia('cover_desktop')->isNotEmpty() ? asset($interesting_issues->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
        //         array_push($list,$array);

        //     }

        // }
        //dd($list);

        $data = array();
        $data['title_h1'] = 'ประเด็นที่น่าสนใจ';
        $data['layout'] = 'interesting_issue';
        $data['items'] = $article;

        return $data;
    }


    
    public static function getDetailInterestingissuesArticle($slug){
        
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
                 $tags_select = DataTags::DataIdFront(['data_id'=>$data->id])->pluck('tags_id');
                 $data_id = DataTags::DataMediaId(['data_id'=>$data->id,'tags_select'=>$tags_select]);

                 $data_media_id = collect($data_id)->where('data_type','=','media')->pluck('data_id');
                 $data_article_id = collect($data_id)->where('data_type','=','article')->pluck('data_id');
                 //dd($data_id);
                 if($data_media_id->count() || $data_article_id->count()){
                    $related_data_media = array();
                    if($data_media_id->count()){
                        $related_data_media = ListMedia::ListMediaCaseTop10Related(['id'=>$data_media_id]);
                        foreach ($related_data_media as $value) {
                            array_push($related_data, $value);
                        }
                    }   
                    
                    $related_data_article = array();
                    if($data_article_id->count()){
                        $related_data_article = Article::FrontListRelated2(['related_id'=>$data_article_id]);
                        foreach ($related_data_article as $value) {
                            array_push($related_data, $value);
                        }
                    }
                    if(count($related_data) > 0){
                        usort($related_data,function ($element1, $element2) { 

                                $datetime1 = strtotime($element1['created_at']); 
                                $datetime2 = strtotime($element2['created_at']); 
                
                                //dd($datetime1,$datetime2,$element1,$element2);
                                if ($datetime1 < $datetime2){
                                    return 1; 
                                }else if($datetime1 > $datetime2){
                                    return -1; 
                                }else{
                                    return 0; 
                                }
                            }); 
                    }
                    $related_data = collect($related_data);

                 }else{
                    $related_data = Article::FrontListRelated(['page_layout'=>$data->page_layout,'related_id'=>$data->id]);
                 }

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

    public static function getDetailInterestingissuesMedia($id){
        
        $id = Hashids::decode($id);
        //dd($id);
        if(collect($id)->isNotEmpty()){
            $data = ListMedia::Detail(['id'=>$id]);

            //dd(collect($data)->isNotEmpty());
            
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
                    //dd($data,$json,$issues);
                    $issue_array = array();
                    foreach($issues AS $key=>$value){
                        //dd($value);
                        array_push($issue_array,$value->ID);
                    }
                    //dd($data,$json,$issues,$issue_array);
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

                return view('template.media_detail_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend_data'=>$recommend_data,'cookie'=>$cookie_data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }


    public static function getListInterestingissues(){
        //dd("getListInterestingissuesCaseFront");
        return view('template.list_interesting_issues_case_front');
    }

}


<?php

namespace App\Modules\Article\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Article\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Article\Models\{Article,ArticleCategory,ArticleRevision};
use App\Modules\Api\Models\{Tags,DataTags};
use App\Modules\Documentsdownload\Models\{Documents};
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Redirect;


class HealthLiteracyController extends Controller
{
    public function getIndex()
    {
        //dd("HealthLiteracyController");
        $items = Article::DataHealth(['status'=>['publish','draft'],'page_layout'=>'health-literacy']);
        //dd($items);
        return view('article::backend.health-literacy.index', compact('items'));
    }

    public function getIndexiframe()
    {
        $items = Article::Data(['status'=>['publish'],'page_layout'=>'health-literacy']);
        return view('article::index-iframe', compact('items'));
    }

    public function getCreate()
    {

        $tags = Tags::Data(['status'=>['publish']])->pluck('title','title');
        $category_id = ArticleCategory::Data(['status'=>['publish']])->pluck('title','id')->toArray();
        //$category_id['0'] = trans('article::backend.select_category');
        //asort($category_id);
        //dd($category_id);
        return view('article::backend.health-literacy.create',compact('tags','category_id'));
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        //dd($data);

        $data['page_layout'] = 'health-literacy';

        $date_year = date('Y-m-d');
        $date_year = strtotime($date_year);
        $date_year = strtotime("+10 year", $date_year);
        $data['start_date'] = Carbon::parse($data['start_date'])->format('Y-m-d H:i:s');
        $data['end_date'] = (!empty($data['end_date']) ? Carbon::parse($data['end_date'])->format('Y-m-d H:i:s'):date('Y-m-d H:i:s',$date_year));

        if(isset($data['category_id'])){
            //dd($data['category_id'],"Case True");
            $array_category_id = [];
            foreach($data['category_id'] AS $key=>$value){
                //dd($value);
                array_push($array_category_id,(int) $value);
            }
            //dd($array_category_id);
            $data['category_id'] = json_encode($array_category_id);
            //dd($data['category_id'],"Case True");
        }else{
            $data['category_id'] = json_encode([]);
            //dd($data['category_id'],"Case Fase");
        }

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
        //dd($data);
        $item = Article::create($data);
        $id = $item->id;
        $data['article_id'] = $id;
        ArticleRevision::create($data);

        if($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
            $item->update(['image_path'=>$item->getFirstMediaUrl('cover_desktop','thumb1024x618')]);
        }
        // if($request->hasFile('cover_mobile')) {
        //     $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        // }
        if($request->file('gallery_desktop')){
            foreach ($request->file('gallery_desktop') as $key => $value) {
                $item->addMedia($value)->toMediaCollection('gallery_desktop');
            }
        }
       //dd($item->getMedia('cover_desktop')->first()->getUrl());

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

        //dd($data);
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

        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'32']);
        return redirect()->route('admin.health-literacy.index')
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
        //dd($tags_select);
        $category_id = ArticleCategory::Data(['status'=>['publish']])->pluck('title','id')->toArray();
        //$category_id['0'] = trans('article::backend.select_category');
        //asort($category_id);
        //dd($data,$revisions);
        return view('article::backend.health-literacy.edit', compact('data','revisions','tags','tags_select','category_id'));
    }

    public function postEdit(EditRequest $request, $id)
    {

        $item = Article::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);

        $data['page_layout'] = 'health-literacy';
        
        $date_year = date('Y-m-d');
        $date_year = strtotime($date_year);
        $date_year = strtotime("+10 year", $date_year);
        $data['start_date'] = Carbon::parse($data['start_date'])->format('Y-m-d H:i:s');
        $data['end_date'] = (!empty($data['end_date']) ? Carbon::parse($data['end_date'])->format('Y-m-d H:i:s'):date('Y-m-d H:i:s',$date_year));
        //dd($data);


        if(isset($data['category_id'])){
            //dd($data['category_id'],"Case True");
            $array_category_id = [];
            foreach($data['category_id'] AS $key=>$value){
                //dd($value);
                array_push($array_category_id,(int) $value);
            }
            //dd($array_category_id);
            $data['category_id'] = json_encode($array_category_id);
            //dd($data['category_id'],"Case True");
        }else{
            $data['category_id'] = json_encode([]);
            //dd($data['category_id'],"Case Fase");
        }        

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
        //dd($data);


        $item->update($data);
        $data['article_id'] = $id;

        ArticleRevision::create($data);

        if ($request->hasFile('cover_desktop')){
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
            $item->update(['image_path'=>$item->getFirstMediaUrl('cover_desktop','thumb1024x618')]);
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


        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'32']);
        return redirect()->route('admin.health-literacy.index')
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


    public static function getDataHealthLiteracy($params,$category_id)
    {
        //dd($params,Hashids::decode($category_id));
        $page = (isset($params['page']) ? $params['page']:'1');
        $category_id = Hashids::decode($category_id)[0];
		if(isset($params['issue']) && $params['issue'] !='0'){
			$issue_array = explode(",",$params['issue']);
			foreach($issue_array AS $key=>$value){
				//dd($value);
                                              if($value == 5){
                                                    #แอลกอฮอล์
                                                    $category_id  = 5;
                                                }

                                                if($value == 28){
                                                    #บุหรี่
                                                    $category_id  = 6;
                                                }

                                                if($value == 39){
                                                    #อาหาร
                                                    $category_id  = 7;
                                                }

                                                if($value == 18){
                                                    #กิจกรรมทางกาย
                                                    $category_id  = 8;
                                                }

                                                if($value == 41){
                                                    #อุบัติเหตุ
                                                    $category_id  = 9;
                                                }

                                                if($value == 37){
                                                    #เพศ เช่น ท้องไม่พร้อม
                                                    $category_id  = 10;
                                                }

                                                if($value == 34){
                                                    #สุขภาพจิต
                                                    $category_id  = 11;
                                                }                                                                                                                                             
                                                if($value == 35){
                                                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                    $category_id  = 12;
                                                }  

                                                if($value == 36){
                                                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                    $category_id  = 12;
                                                } 

                                                if($value == 27){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 33){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 49){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 16){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }

                                                if($value == 21){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }

                                                if($value == 32){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                } 

                                                if($value == 42){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }	


                                                if($value == 61){
                                                    #มลภาวะทางอากาศ
                                                    $category_id  = 15;
                                                }
                                                
                                                if($value == 55){
                                                    #ยาสูบ
                                                    $category_id  = 16;
                                                }

                                                if($value == 56){
                                                    #เหล้า
                                                    $category_id  = 18;
                                                }

                                                if($value == 58){
                                                    #โรคปอดอุดกั้นเรื้อรัง
                                                    $category_id  = 19;
                                                }
                                                
                                                if($value == 57){
                                                    #โรคมะเร็ง
                                                    $category_id  = 20;
                                                }
                                                
                                                if($value == 59){
                                                    #โรคหัวใจและหลอดเลือด
                                                    $category_id  = 21;
                                                }                                                
                                                

                                                if($value == 60){
                                                    #โรคเบาหวาน
                                                    $category_id  = 22;
                                                }                                                
                                                

			}
			  		
		}
        $category_name = ArticleCategory::select('title')->where('id','=',$category_id)->first();
        //dd($category_name);
        //$time_cache  =  ThrcHelpers::time_cache(5);
        $article = '';
        // if (Cache::has('data_interesting_issues_page_'.$page)){
        //     $article = Cache::get('data_interesting_issues_page_'.$page);
        // }else{
        //     $article = Article::FrontList(['page_layout'=>'interesting_issues','page'=>$page]);
        //     //dd($article);
        //     //$article = ViewInterestingIssues::FrontList(['page'=>$page]);
        //     Cache::put('data_interesting_issues_page_'.$page,$article,$time_cache);
        //     $article = Cache::get('data_interesting_issues_page_'.$page);
        // }

        $article = Article::FrontListHealthLiteracy(['page_layout'=>'health-literacy','category_id'=>$category_id,'page'=>$page,'params'=>$params]);
        //dd($article);

        $data = array();
        $data['title_h1'] = isset($category_name->title) ? $category_name->title:'';
        $data['layout'] = 'list_health_literacy_case_front';
        $data['items'] = $article;
        $data['old'] = $params;
        return $data;
    }



    public static function getDataHealthLiteracy2($params,$category_id)
    {
        //dd($params,Hashids::decode($category_id));
        $page = (isset($params['page']) ? $params['page']:'1');
        $category_id = Hashids::decode($category_id)[0];
		if(isset($params['issue']) && $params['issue'] !='0'){
			$issue_array = explode(",",$params['issue']);
			foreach($issue_array AS $key=>$value){
				//dd($value);
                                              if($value == 5){
                                                    #แอลกอฮอล์
                                                    $category_id  = 5;
                                                }

                                                if($value == 28){
                                                    #บุหรี่
                                                    $category_id  = 6;
                                                }

                                                if($value == 39){
                                                    #อาหาร
                                                    $category_id  = 7;
                                                }

                                                if($value == 18){
                                                    #กิจกรรมทางกาย
                                                    $category_id  = 8;
                                                }

                                                if($value == 41){
                                                    #อุบัติเหตุ
                                                    $category_id  = 9;
                                                }

                                                if($value == 37){
                                                    #เพศ เช่น ท้องไม่พร้อม
                                                    $category_id  = 10;
                                                }

                                                if($value == 34){
                                                    #สุขภาพจิต
                                                    $category_id  = 11;
                                                }                                                                                                                                             
                                                if($value == 35){
                                                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                    $category_id  = 12;
                                                }  

                                                if($value == 36){
                                                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                    $category_id  = 12;
                                                } 

                                                if($value == 27){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 33){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 49){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 16){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }

                                                if($value == 21){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }

                                                if($value == 32){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                } 

                                                if($value == 42){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }		

                                                
                                                if($value == 61){
                                                    #มลภาวะทางอากาศ
                                                    $category_id  = 15;
                                                }
                                                
                                                if($value == 55){
                                                    #ยาสูบ
                                                    $category_id  = 16;
                                                }

                                                if($value == 56){
                                                    #เหล้า
                                                    $category_id  = 18;
                                                }

                                                if($value == 58){
                                                    #โรคปอดอุดกั้นเรื้อรัง
                                                    $category_id  = 19;
                                                }
                                                
                                                if($value == 57){
                                                    #โรคมะเร็ง
                                                    $category_id  = 20;
                                                }
                                                
                                                if($value == 59){
                                                    #โรคหัวใจและหลอดเลือด
                                                    $category_id  = 21;
                                                }                                                
                                                

                                                if($value == 60){
                                                    #โรคเบาหวาน
                                                    $category_id  = 22;
                                                }




			}
			  		
		}
        $category_name = ArticleCategory::select('title')->where('id','=',$category_id)->first();
        //dd($category_name);
        //$time_cache  =  ThrcHelpers::time_cache(5);
        $article = '';
        // if (Cache::has('data_interesting_issues_page_'.$page)){
        //     $article = Cache::get('data_interesting_issues_page_'.$page);
        // }else{
        //     $article = Article::FrontList(['page_layout'=>'interesting_issues','page'=>$page]);
        //     //dd($article);
        //     //$article = ViewInterestingIssues::FrontList(['page'=>$page]);
        //     Cache::put('data_interesting_issues_page_'.$page,$article,$time_cache);
        //     $article = Cache::get('data_interesting_issues_page_'.$page);
        // }

        $article = Article::FrontListHealthLiteracy2(['page_layout'=>'health-literacy','category_id'=>$category_id,'page'=>$page,'params'=>$params]);
        //dd($article);

        $data = array();
        $data['title_h1'] = isset($category_name->title) ? $category_name->title:'';
        $data['layout'] = 'list_health_literacy_case_front2';
        $data['items'] = $article;
        $data['old'] = $params;
        return $data;
    }    

    public static function getDataHealthLiteracy3($params)
    {
        //dd($params,Hashids::decode($category_id));
        $page = (isset($params['page']) ? $params['page']:'1');
        $category_id = '';
		if(isset($params['issue']) && $params['issue'] !='0'){
			$issue_array = explode(",",$params['issue']);
			foreach($issue_array AS $key=>$value){
				//dd($value);
                                              if($value == 5){
                                                    #แอลกอฮอล์
                                                    $category_id  = 5;
                                                }

                                                if($value == 28){
                                                    #บุหรี่
                                                    $category_id  = 6;
                                                }

                                                if($value == 39){
                                                    #อาหาร
                                                    $category_id  = 7;
                                                }

                                                if($value == 18){
                                                    #กิจกรรมทางกาย
                                                    $category_id  = 8;
                                                }

                                                if($value == 41){
                                                    #อุบัติเหตุ
                                                    $category_id  = 9;
                                                }

                                                if($value == 37){
                                                    #เพศ เช่น ท้องไม่พร้อม
                                                    $category_id  = 10;
                                                }

                                                if($value == 34){
                                                    #สุขภาพจิต
                                                    $category_id  = 11;
                                                }                                                                                                                                             
                                                if($value == 35){
                                                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                    $category_id  = 12;
                                                }  

                                                if($value == 36){
                                                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                    $category_id  = 12;
                                                } 

                                                if($value == 27){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 33){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 49){
                                                    #สิ่งแวดล้อม
                                                    $category_id  = 13;
                                                }

                                                if($value == 16){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }

                                                if($value == 21){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }

                                                if($value == 32){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                } 

                                                if($value == 42){
                                                    #อื่นๆ
                                                    $category_id  = 14;
                                                }		

                                                
                                                if($value == 61){
                                                    #มลภาวะทางอากาศ
                                                    $category_id  = 15;
                                                }
                                                
                                                if($value == 55){
                                                    #ยาสูบ
                                                    $category_id  = 16;
                                                }

                                                if($value == 56){
                                                    #เหล้า
                                                    $category_id  = 18;
                                                }

                                                if($value == 58){
                                                    #โรคปอดอุดกั้นเรื้อรัง
                                                    $category_id  = 19;
                                                }
                                                
                                                if($value == 57){
                                                    #โรคมะเร็ง
                                                    $category_id  = 20;
                                                }
                                                
                                                if($value == 59){
                                                    #โรคหัวใจและหลอดเลือด
                                                    $category_id  = 21;
                                                }                                                
                                                

                                                if($value == 60){
                                                    #โรคเบาหวาน
                                                    $category_id  = 22;
                                                }




			}
			  		
		}
   
        //dd($category_name);
        //$time_cache  =  ThrcHelpers::time_cache(5);
        $article = '';
        // if (Cache::has('data_interesting_issues_page_'.$page)){
        //     $article = Cache::get('data_interesting_issues_page_'.$page);
        // }else{
        //     $article = Article::FrontList(['page_layout'=>'interesting_issues','page'=>$page]);
        //     //dd($article);
        //     //$article = ViewInterestingIssues::FrontList(['page'=>$page]);
        //     Cache::put('data_interesting_issues_page_'.$page,$article,$time_cache);
        //     $article = Cache::get('data_interesting_issues_page_'.$page);
        // }

        $article = Article::FrontListHealthLiteracy2(['page_layout'=>'health-literacy','category_id'=>$category_id,'page'=>$page,'params'=>$params]);
        //dd($article);

        $data = array();
        $data['title_h1'] = 'สื่อและเครื่องมือ';
        $data['layout'] = 'list_health_literacy_case_front3';
        $data['items'] = $article;
        $data['old'] = $params;
        return $data;
    }        

    public static function getListHealthLiteracy($category_id){
        //dd("getListInterestingissuesCaseFront");
        //dd($category_id);
        return view('template.list_health_literacy_case_front',compact('category_id'));
    }

    public static function getListHealthLiteracy2($category_id){
        //dd("getListInterestingissuesCaseFront");
        //dd($category_id);
        return view('template.list_health_literacy_case_front2',compact('category_id'));
    }

    public static function getListHealthLiteracy3(Request $request){
        //dd("getListInterestingissuesCaseFront");
        return view('template.list_health_literacy_case_front3');
    }



}


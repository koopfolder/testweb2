<?php

namespace App\Modules\Article\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Article\Http\Requests\{CreateFormgenRequest, EditFormgenRequest};
use App\Modules\Article\Models\{Article,ArticleRevision,ThaiHealthWatchFormLogs};
use App\Modules\Api\Models\{Tags,DataTags};
use App\Modules\Documentsdownload\Models\{Documents};
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Cache;
use ThrcHelpers;
use Excel;

class FormGeneratorController extends Controller
{
    public function getIndex()
    {
        //dd("FormGeneratorController");
        $items = Article::Data(['status'=>['publish','draft'],'page_layout'=>'thaihealth_watch_form_generator']);
        //dd($items);
        return view('article::backend.form-generator.index', compact('items'));
    }

    public function getIndexiframe()
    {
        $items = Article::Data(['status'=>['publish'],'page_layout'=>'thaihealth_watch_form_generator']);
        return view('article::index-iframe', compact('items'));
    }

    public function getCreate()
    {
        //dd("Create");
        $tags = Tags::Data(['status'=>['publish']])->pluck('title','title');
        return view('article::backend.form-generator.create',compact('tags'));
    }

    public function postCreate(CreateFormgenRequest $request)
    {
        $data = $request->all();
        //dd($data);

        $data['page_layout'] = 'thaihealth_watch_form_generator';

        $date_year = date('Y-m-d');
        $date_year = strtotime($date_year);
        $date_year = strtotime("+10 year", $date_year);
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
        }

        if(isset($data['form-generator-data'])){
            $data['dol_json_data'] = $data['form-generator-data'];
        }

        $data['dol_UploadFileID'] = '';        
        $item = Article::create($data);
        $id = $item->id;
        $data['article_id'] = $id;
        //dd($data);
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



        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'40']);
        return redirect()->route('admin.thaihealth-watch.form-generator.index')
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
        return view('article::backend.form-generator.edit', compact('data','revisions','tags','tags_select'));
    }

    public function postEdit(EditFormgenRequest $request, $id)
    {

        $item = Article::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);

        $data['page_layout'] = 'thaihealth_watch_form_generator';
        
        $date_year = date('Y-m-d');
        $date_year = strtotime($date_year);
        $date_year = strtotime("+10 year", $date_year);
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
        if(isset($data['form-generator-data'])){
            $data['dol_json_data'] = $data['form-generator-data'];
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


        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'40']);
        return redirect()->route('admin.thaihealth-watch.form-generator.index')
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
        $article->dol_json_data = $fields['dol_json_data'];
        //$article->short_description = $fields['short_description'];
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


    public static function getDataThaihealthWatch($params)
    {
        $page = (isset($params['page']) ? $params['page']:'1');
        $time_cache  =  ThrcHelpers::time_cache(5);
        $article = '';
        if (Cache::has('data_thaihealth_watch_page_'.$page)){
            $article = Cache::get('data_thaihealth_watch_page_'.$page);
        }else{
            $article = Article::FrontList(['page_layout'=>'thaihealth_watch','page'=>$page]);
            //$article = ViewArticlesResearch::FrontList(['page'=>$page]);
            Cache::put('data_thaihealth_watch_page_'.$page,$article,$time_cache);
            $article = Cache::get('data_thaihealth_watch_page_'.$page);
        }

        $data = array();
        $data['items'] = $article;
        //dd($data);
        return $data;
    }


    // public static function getListThaihealthWatch(){
    //     //dd("getListArticlesResearch");
    //     return view('template.list_thaihealth_watch_case_front');
    // }


    public function getReport($id)
    {
        //dd("Get Report");
        $items = ThaiHealthWatchFormLogs::Data(['form_id'=>$id]);
        //dd($items);
        return view('article::backend.form-generator.report', compact('items','id'));
    }


    public function getExcelReport($id)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
    
        $date_now = date('Y-m-d-H-i-s');
        $file_name = 'thaihealth-watch-form'."-".$date_now;
        $type = 'xlsx';
        $items = ThaiHealthWatchFormLogs::Data(['form_id'=>$id]);

        //dd($items);
        
        return Excel::create($file_name, function($excel) use ($items) {
            $excel->sheet('mySheet', function($sheet) use ($items)
            {
                //$sheet->fromArray($items,null, 'A1', true);
                if($items->count()){
                    $json = json_decode($items[0]->json_data);
                    $title = $items[0]->name->title;
                    //dd($title);
                    $array_title = [];
                    
                    foreach($json AS $key_title=>$val_title){
                        if($key_title !='_token' & $key_title !='id'){
                            array_push($array_title,$key_title);
                        }
                    }
                    //dd($array_title);
                    $sheet->row(1, array(
                        $title,
                        '',
                        ''
                    ));                   
                    $sheet->row(2,$array_title);   
                    $i = 3;
                    foreach($items AS $key_items=>$val_items){
                       //dd($key_items,$val_items);
                       $json = json_decode($val_items->json_data);
                       //dd($json);
                       $array_val = [];
                       foreach($json AS $key_json=>$val_json){
                            if($key_json !='_token' & $key_json !='id'){
                                array_push($array_val,$val_json);
                            }
                       }
                       $sheet->row($i++,$array_val); 
                    }              
                    
                }


            });
        })->download($type);
        //dd("Get Excel Report");
    }


}


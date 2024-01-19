<?php

namespace App\Modules\Article\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Article\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Article\Models\{Article,ArticleRevision};
use App\Modules\Api\Models\{Tags,DataTags};
use App\Modules\Documentsdownload\Models\{Documents};
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Cache;
use ThrcHelpers;

class EventCalendarController extends Controller
{
    public function getIndex()
    {
        //dd("News");
        $items = Article::Data(['status'=>['publish','draft'],'page_layout'=>'event_calendar']);
        //dd($items);
        return view('article::backend.event-calendar.index', compact('items'));
    }

    public function getIndexiframe()
    {
        $items = Article::Data(['status'=>['publish'],'page_layout'=>'event_calendar']);
        return view('article::index-iframe', compact('items'));
    }

    public function getCreate()
    {
        $tags = Tags::Data(['status'=>['publish']])->pluck('title','title');
        return view('article::backend.event-calendar.create',compact('tags'));
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        //dd($data);

        $data['page_layout'] = 'event_calendar';

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
        //dd($data);
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



        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'1']);
        return redirect()->route('admin.event-calendar.index')
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
        return view('article::backend.event-calendar.edit', compact('data','revisions','tags','tags_select'));
    }

    public function postEdit(EditRequest $request, $id)
    {

        $item = Article::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);

        $data['page_layout'] = 'event_calendar';
        
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
        
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'1']);
        return redirect()->route('admin.event-calendar.index')
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

    public static function getDataEventCalendar($params)
    {
        
        $time_cache  =  ThrcHelpers::time_cache(5);
        $response = array();
        if (Cache::has('data_event_calendar_full')){
            $response = Cache::get('data_event_calendar_full');
        }else{
            $event = Article::FrontEventCalendarFull(['page_layout'=>'event_calendar']);
            if($event->count()){
                
                foreach ($event as $value) {
                    $object = new \StdClass();
                    $object->title = $value->title;
                    $object->start =str_replace(" ","T",  $value->start_date);
                    $object->end = str_replace(" ","T",  $value->end_date);
                    $object->url = route('article-detail',$value->slug);
                    array_push($response, $object);
                }
            }
            //dd($response);
            Cache::put('data_event_calendar_full',$response,$time_cache);
            $response = Cache::get('data_event_calendar_full');
        }
        //dd($response);
        return json_encode($response);
    }


    public static function getListEventCalendar(){
        //dd("getListArticlesResearch");
        return view('template.list_event_calendar_case_front');
    }

}


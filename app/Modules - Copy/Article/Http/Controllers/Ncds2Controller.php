<?php

namespace App\Modules\Article\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Article\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Article\Models\{Article,ArticleRevision,ArticleCategory,ArticleHitLogs};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ViewMedia,ViewArticlesResearch,ViewIncludeStatistics,MediaHitLogs,ViewInterestingIssues,Tags,DataTags};
use App\Modules\Documentsdownload\Models\{Documents};
use App\Modules\Setting\Models\Setting;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use DB;
use Illuminate\Support\Facades\Cache;
use ThrcHelpers;
use Redirect;

class Ncds2Controller extends Controller
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
        //dd("ncds-2");
        $items = Article::Data(['status'=>['publish','draft'],'page_layout'=>'ncds-2']);
        //dd($items);
        return view('article::backend.ncds-2.index', compact('items'));
    }

    public function getIndexiframe()
    {
        $items = Article::Data(['status'=>['publish'],'page_layout'=>'ncds-2']);
        return view('article::index-ncds-iframe', compact('items'));
    }

    public function getCreate()
    {

        $tags = Tags::Data(['status'=>['publish']])->pluck('title','title');
        $categorys = ArticleCategory::DataDropdown(['status'=>['publish'],'type'=>'ncds-2'])->pluck('title','id');
        
        // $ncds_2_disease = Setting::select('value')->where('slug','=','ncds_2_disease')->first();
        // if(isset($ncds_2_disease->value)){
        //     $ncds_2_disease = json_decode($ncds_2_disease->value);
        //     $ncds_2_disease = array_combine($ncds_2_disease,$ncds_2_disease);
        // }else{
        //     $ncds_2_disease = [];
        // }
        // $ncds_2_area = Setting::select('value')->where('slug','=','ncds_2_area')->first();
        // if(isset($ncds_2_area->value)){
        //     $ncds_2_area = json_decode($ncds_2_area->value);
        //     $ncds_2_area = array_combine($ncds_2_area,$ncds_2_area);
        // }else{
        //     $ncds_2_area = [];
        // }

        //dd($ncds_2_disease,$ncds_2_area);
        return view('article::backend.ncds-2.create',compact('tags','categorys'));

    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        
        $json_data = new \StdClass;
        $json_data->UploadFileID = '';
        $json_data->FileAddress = '';
        $json_data->ThumbnailAddress = '';
        $json_data->FileSize = '';
        $json_data->ProjectCode = '';
        $json_data->SubProjectCode = '';
        $json_data->PublishLevel = '';
        $json_data->PublishLevelText = '';
        $json_data->CreativeCommons = '';
        $json_data->DepartmentID = '';
        $json_data->DepartmentName = '';
        $json_data->Title = '';
        $json_data->Description = '';
        $json_data->PublishedDate = '';
        $json_data->PublishedByName = '';
        $json_data->UpdatedDate = '';
        $json_data->UpdatedByName = '';
        $json_data->Keywords = '';
        $json_data->Template = '';
        $json_data->CategoryID = '';
        $json_data->Category = '';
        $json_data->Issues = '';
        $json_data->Targets = '';
        $json_data->Settings = '';
        $json_data->AreaID = '';
        $json_data->Area = '';
        $json_data->Province = '';
        $json_data->Source = '';
        $json_data->ReleasedDate = '';
        $json_data->Creator = '';
        $json_data->Production = '';
        $json_data->Publisher = '';
        $json_data->Contributor = '';
        $json_data->Identifier = '';
        $json_data->Language = '';
        $json_data->Relation = '';
        $json_data->Format = '';
        $json_data->IntellectualProperty = '';
        $json_data->OS = '';
        $json_data->Owner = '';
        $json_data->PeriodStart = '';
        $json_data->Duration = '';
        $json_data->SystemID = '';
        $json_data->SystemName = '';
        $json_data->situation_ncds = $data['situation_ncds'];
        // $json_data->disease = $data['disease'];
        // $json_data->area = $data['area'];
        
        // if (is_numeric($data['category_id'])) {
        //     //echo var_export($data['category_id'], true) . " is numeric", PHP_EOL;
        //     $data['category_id'] = $data['category_id'];
        // } else {
        //     $data_category = [];
        //     $data_category['title'] = $data['category_id'];
        //     $data_category['type'] = 'ncds-2';
        //     $data_category['status'] = 'publish';
        //     $data_category['created_by'] = $data['created_by'];
        //     $category_id = ArticleCategory::create($data_category);
        //     //echo var_export($data['category_id'], true) . " is NOT numeric", PHP_EOL;
        //     $data['category_id'] = $category_id->id;
        // }

        // if($data['disease'] !=''){
        //     $ncds_2_disease = Setting::select('value')->where('slug','=','ncds_2_disease')->first();
        //     $ncds_2_disease = json_decode($ncds_2_disease->value);
        //     if(array_search($data['disease'],$ncds_2_disease) === false){
        //         array_push($ncds_2_disease,$data['disease']);
        //         //dd($data['disease'].' ---> False',$ncds_2_disease);
        //         Setting::where('slug','=','ncds_2_disease')->update(['value'=>json_encode($ncds_2_disease)]);
        //     }
        // }

        // if($data['area'] !=''){
        //     $ncds_2_area = Setting::select('value')->where('slug','=','ncds_2_area')->first();
        //     $ncds_2_area = json_decode($ncds_2_area->value);
        //     if(array_search($data['area'],$ncds_2_area) === false){
        //         array_push($ncds_2_area,$data['area']);
        //         //dd($data['area'].' ---> False',$ncds_2_area);
        //         Setting::where('slug','=','ncds_2_area')->update(['value'=>json_encode($ncds_2_area)]);
        //     }
        // }
        $data['dol_json_data'] = json_encode($json_data);
        //dd($data,$json_data);

        $data['page_layout'] = 'ncds-2';
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


        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'37']);
        return redirect()->route('admin.ncds-2.index')
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
        $categorys = ArticleCategory::DataDropdown(['status'=>['publish'],'type'=>'ncds-2'])->pluck('title','id');
        // $ncds_2_disease = Setting::select('value')->where('slug','=','ncds_2_disease')->first();
        // if(isset($ncds_2_disease->value)){
        //     $ncds_2_disease = json_decode($ncds_2_disease->value);
        //     $ncds_2_disease = array_combine($ncds_2_disease,$ncds_2_disease);
        // }else{
        //     $ncds_2_disease = [];
        // }
        // $ncds_2_area = Setting::select('value')->where('slug','=','ncds_2_area')->first();
        // if(isset($ncds_2_area->value)){
        //     $ncds_2_area = json_decode($ncds_2_area->value);
        //     $ncds_2_area = array_combine($ncds_2_area,$ncds_2_area);
        // }else{
        //     $ncds_2_area = [];
        // }

        //dd($data,$ncds_2_disease,$ncds_2_area);
        return view('article::backend.ncds-2.edit', compact('data','revisions','tags','tags_select','categorys'));
    }

    public function postEdit(EditRequest $request, $id)
    {

        $item = Article::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);
        $json_data = new \StdClass;
        $json_data->UploadFileID = '';
        $json_data->FileAddress = '';
        $json_data->ThumbnailAddress = '';
        $json_data->FileSize = '';
        $json_data->ProjectCode = '';
        $json_data->SubProjectCode = '';
        $json_data->PublishLevel = '';
        $json_data->PublishLevelText = '';
        $json_data->CreativeCommons = '';
        $json_data->DepartmentID = '';
        $json_data->DepartmentName = '';
        $json_data->Title = '';
        $json_data->Description = '';
        $json_data->PublishedDate = '';
        $json_data->PublishedByName = '';
        $json_data->UpdatedDate = '';
        $json_data->UpdatedByName = '';
        $json_data->Keywords = '';
        $json_data->Template = '';
        $json_data->CategoryID = '';
        $json_data->Category = '';
        $json_data->Issues = '';
        $json_data->Targets = '';
        $json_data->Settings = '';
        $json_data->AreaID = '';
        $json_data->Area = '';
        $json_data->Province = '';
        $json_data->Source = '';
        $json_data->ReleasedDate = '';
        $json_data->Creator = '';
        $json_data->Production = '';
        $json_data->Publisher = '';
        $json_data->Contributor = '';
        $json_data->Identifier = '';
        $json_data->Language = '';
        $json_data->Relation = '';
        $json_data->Format = '';
        $json_data->IntellectualProperty = '';
        $json_data->OS = '';
        $json_data->Owner = '';
        $json_data->PeriodStart = '';
        $json_data->Duration = '';
        $json_data->SystemID = '';
        $json_data->SystemName = '';
        $json_data->situation_ncds = $data['situation_ncds'];
        // $json_data->disease = $data['disease'];
        // $json_data->area = $data['area'];
        
        // if (is_numeric($data['category_id'])) {
        //     //echo var_export($data['category_id'], true) . " is numeric", PHP_EOL;
        //     $data['category_id'] = $data['category_id'];
        // } else {
        //     $data_category = [];
        //     $data_category['title'] = $data['category_id'];
        //     $data_category['type'] = 'ncds-2';
        //     $data_category['status'] = 'publish';
        //     $data_category['created_by'] = $data['updated_by'];
        //     $category_id = ArticleCategory::create($data_category);
        //     //echo var_export($data['category_id'], true) . " is NOT numeric", PHP_EOL;
        //     $data['category_id'] = $category_id->id;
        // }

        // if($data['disease'] !=''){
        //     $ncds_2_disease = Setting::select('value')->where('slug','=','ncds_2_disease')->first();
        //     $ncds_2_disease = json_decode($ncds_2_disease->value);
        //     if(array_search($data['disease'],$ncds_2_disease) === false){
        //         array_push($ncds_2_disease,$data['disease']);
        //         //dd($data['disease'].' ---> False',$ncds_2_disease);
        //         Setting::where('slug','=','ncds_2_disease')->update(['value'=>json_encode($ncds_2_disease)]);
        //     }
        // }

        // if($data['area'] !=''){
        //     $ncds_2_area = Setting::select('value')->where('slug','=','ncds_2_area')->first();
        //     $ncds_2_area = json_decode($ncds_2_area->value);
        //     if(array_search($data['area'],$ncds_2_area) === false){
        //         array_push($ncds_2_area,$data['area']);
        //         //dd($data['area'].' ---> False',$ncds_2_area);
        //         Setting::where('slug','=','ncds_2_area')->update(['value'=>json_encode($ncds_2_area)]);
        //     }
        // }
        $data['dol_json_data'] = json_encode($json_data);
        //dd($id,$data,$json_data);

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


        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'37']);
        return redirect()->route('admin.ncds-2.index')
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



}


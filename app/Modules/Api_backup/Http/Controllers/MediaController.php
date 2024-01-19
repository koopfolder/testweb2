<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ViewMedia};
use App\Modules\Article\Models\{Article,ArticleRevision};
use App\Modules\Setting\Models\Setting;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Hash;
use Crypt;
use Illuminate\Support\Facades\Log;
use ThrcHelpers;
use App\User;

class MediaController extends Controller
{
    public function getIndex(Request $request)
    {
        //dd("Case Media");
        // $test  = ViewMedia::select('id','title','recommend','articles_research','include_statistics','notable_books','interesting_issues','featured','created_at','updated_at','created_by','updated_by','status','issues_id')
        // ->orderBy('id','desc')
        // ->groupBy('id')
        // ->with('issueName')
        // ->simplePaginate(25);

        //dd($test);
        $items = ViewMedia::Data2(['request'=>$request->all()]);
        //dd($items);
        $old = $request->all();
        $issue =  ThrcHelpers::getIssue($request->all());
        $target =ThrcHelpers::getTarget($request->all());
        $template =  ThrcHelpers::getTempalte($request->all());
        $users = User::select('name','id')->orderBy('id', 'DESC')->get()->pluck('name','id')->toArray();
        if($users){
            $users['0'] = trans('api::backend.users');
        }
        ksort($users);

        //dd($users);

        $settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');
        //dd($settings);
        //dd($target,$template);
        return view('api::backend.list_media.index',compact('items','old','issue','settings','target','template','users'));
    }


    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = ListMedia::findOrFail($id);
        $settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');
        //dd($data);
        return view('api::backend.list_media.edit', compact('data','settings'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $data = $request->all();
        $settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');
        //dd($data);
        $item = ListMedia::findOrFail($id);
        $data_update['title'] = $data['title'];
        $data_update['description'] = $data['description'];
        $data_update['status'] = $data['status'];
        $data_update['featured'] = $data['featured'];
        $data_update['interesting_issues'] = $data['interesting_issues'];
        $data_update['recommend'] = $data['recommend'];
        $data_update['articles_research'] = $data['articles_research'];
        $data_update['include_statistics'] = $data['include_statistics'];
        $data_update['updated_by'] = $data['updated_by'];
        //$data_update['notable_books'] = $data['notable_books'];
        //dd($data_update);
        $item->update($data_update);
        if($request->hasFile('cover_desktop')){
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        if($data['interesting_issues'] == 2){
            
            $check_data =Article::select('id')
                                  ->where('dol_UploadFileID','=',$data['UploadFileID'])
                                  ->where('page_layout','=','interesting_issues')
                                  ->first();

            if(!isset($check_data->id)){
         
            $data_article['page_layout'] = 'interesting_issues';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = $data['ThumbnailAddress'];
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);

            Article::create($data_article);
            }

            //dd($data['UploadFileID'],$check_data);

        }else{
            Article::where('dol_UploadFileID','=',$data['UploadFileID'])
                    ->where('page_layout','=','interesting_issues')
                    ->delete();
        }

        if($data['articles_research'] == 2){

            $check_data =Article::select('id')
                                  ->where('dol_UploadFileID','=',$data['UploadFileID'])
                                  ->where('page_layout','=','articles_research')
                                  ->first();

            if(!isset($check_data->id)){
         
            $data_article['page_layout'] = 'articles_research';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = $data['ThumbnailAddress'];
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);
            Article::create($data_article);
            }
            
            //dd($data['UploadFileID'],$check_data);
        }else{
            Article::where('dol_UploadFileID','=',$data['UploadFileID'])
                    ->where('page_layout','=','articles_research')
                    ->delete();
        }

        if($data['include_statistics'] == 2){
            
             $check_data =Article::select('id')
                                  ->where('dol_UploadFileID','=',$data['UploadFileID'])
                                  ->where('page_layout','=','include_statistics')
                                  ->first();

            if(!isset($check_data->id)){
         
            $data_article['page_layout'] = 'include_statistics';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = $data['ThumbnailAddress'];
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);
            Article::create($data_article);
            }

        }else{
            Article::where('dol_UploadFileID','=',$data['UploadFileID'])
                    ->where('page_layout','=','include_statistics')
                    ->delete();
        }

        //dd("Success");

        if($data['knowledges'] == 2){
            Setting::where('slug','knowledges')->update(['value' => $id]);
        }else{
            if($settings['knowledges'] == $id){
                Setting::where('slug','knowledges')->update(['value' =>'']);
            }
        }

        if($data['media_campaign'] == 2){
            Setting::where('slug','media_campaign')->update(['value' => $id]);
        }else{
            if($settings['media_campaign'] == $id){
                Setting::where('slug','media_campaign')->update(['value' =>'']);
            }
        }
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data_update['title'].'"','module_id'=>'13']);
        return redirect()->route('admin.api.list-media.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


 

}


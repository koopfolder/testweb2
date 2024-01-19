<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Http\Requests\CreatRequest;
use App\Modules\Setting\Http\Requests\EditRequest;
use App\Modules\Setting\Models\Setting;
use App\Modules\Banner\Models\BannerCategory;
use App\Modules\SinglePage\Models\SinglePage;
use App\Modules\Article\Models\{ArticleCategory};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets};

use DbdHelpers;
use Artisan;

class IndexController extends Controller
{
    public function getIndex()
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        
        $data = array();
        $settings = Setting::all();
        $data['banner_category'] =BannerCategory::DataDropdown();
        $data['single_page'] =SinglePage::DataDropdown();

        //$data['list_media'] = ListMedia::DataDropDown(['status'=>['publish']])->pluck('title','id');
        // $list_media = ListMedia::DataDropDown(['status'=>['publish']])->pluck('title','id');
        // $list_media = collect($list_media)->toArray();
        // $list_media[0] = " Select Media ";
        // ksort($list_media);

        // $data['list_media'] = $list_media;

        //dd($banner_category);
        //dd(BannerCategory::DataDropdown());
        if ($settings->isNotEmpty()){
            foreach ($settings  as $setting){
                if($setting->slug =='mime_type'){
                    $data[$setting->slug] = json_decode($setting->value);
                }else if($setting->slug =='api_keywords'){
                    //$data[$setting->slug] = json_decode($setting->value);
                    if(isset($setting->value)){
                        $array = json_decode($setting->value);
                        $array = array_combine($array,$array);
                        $data[$setting->slug.'_select'] = json_decode($setting->value);
                    }else{
                        $array = [];
                        $data[$setting->slug.'_select'] = [];
                    }
                    $data[$setting->slug] = $array;
                    

                }else if($setting->slug =='ncds_2_keywords'){
                    //$data[$setting->slug] = json_decode($setting->value);
                    if(isset($setting->value)){
                        $array = json_decode($setting->value);
                        $array = array_combine($array,$array);
                        $data[$setting->slug.'_select'] = json_decode($setting->value);
                    }else{
                        $array = [];
                        $data[$setting->slug.'_select'] = [];
                    }
                    $data[$setting->slug] = $array;                    
                }else if($setting->slug =='ncds_4_keywords'){
                    //$data[$setting->slug] = json_decode($setting->value);
                    if(isset($setting->value)){
                        $array = json_decode($setting->value);
                        $array = array_combine($array,$array);
                        $data[$setting->slug.'_select'] = json_decode($setting->value);
                    }else{
                        $array = [];
                        $data[$setting->slug.'_select'] = [];
                    }
                    $data[$setting->slug] = $array;                    
                }else if($setting->slug =='ncds_5_keywords'){
                    //$data[$setting->slug] = json_decode($setting->value);
                    if(isset($setting->value)){
                        $array = json_decode($setting->value);
                        $array = array_combine($array,$array);
                        $data[$setting->slug.'_select'] = json_decode($setting->value);
                    }else{
                        $array = [];
                        $data[$setting->slug.'_select'] = [];
                    }
                    $data[$setting->slug] = $array;
                }else if($setting->slug =='ncds_6_keywords'){
                    //$data[$setting->slug] = json_decode($setting->value);
                    if(isset($setting->value)){
                        $array = json_decode($setting->value);
                        $array = array_combine($array,$array);
                        $data[$setting->slug.'_select'] = json_decode($setting->value);
                    }else{
                        $array = [];
                        $data[$setting->slug.'_select'] = [];
                    }
                    $data[$setting->slug] = $array;                   
                }else{
                    $data[$setting->slug] = $setting->value;
                }
            }
        }

        //dd($data);
        return view('setting::index', $data);
    }

    public function postIndex(EditRequest $request)
    {
        $fileNameDesktop = null;
        $fileNameMobile = null;
        $fileNameNCDs = null;


        if ($request->hasFile('logo_desktop')) {
            $row = Setting::where('slug', 'logo_desktop')->get();
            if ($row->isNotEmpty()) {
                $item = $row->first();
                $item->clearMediaCollection('logo_desktop');
                $logoDesktop = $item->addMedia($request->file('logo_desktop'))->toMediaCollection('logo_desktop');
                $fileNameDesktop = $item->getMedia('logo_desktop')->first()->getUrl();
            }
        }

        if ($request->hasFile('ncds_cover_image')) {
            $row = Setting::where('slug','ncds_cover_image')->get();
            if ($row->isNotEmpty()) {
                $item = $row->first();
                $item->clearMediaCollection('ncds_cover_image');
                $ncds_cover_image = $item->addMedia($request->file('ncds_cover_image'))->toMediaCollection('ncds_cover_image');
                $fileNameNCDs =$item->getFirstMediaUrl('ncds_cover_image','thumb1366x635');
                //$item->getFirstMediaUrl('ncds_cover_image','thumb1366x635');
                //asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635'))
            }
        }

        if ($request->hasFile('logo_mobile')) {
            $row = Setting::where('slug', 'logo_mobile')->get();
            if ($row->isNotEmpty()) {
                $item = $row->first();
                $item->clearMediaCollection('logo_mobile');
                $logo_mobile = $item->addMedia($request->file('logo_mobile'))->toMediaCollection('logo_mobile');
                $fileNameMobile = $item->getMedia('logo_mobile')->first()->getUrl();
            }
        }
        
        $data = $request->all();
        //dd($data);
        unset($data['_token']);
        if ($fileNameDesktop) {
            $data['logo_desktop'] = $fileNameDesktop;
        }
        if ($fileNameMobile) {
            $data['logo_mobile'] = $fileNameMobile;
        }

        if ($fileNameNCDs) {
            $data['ncds_cover_image'] = $fileNameNCDs;
        }


        foreach ($data as $key => $value){


            if($key ==='map_location'){
                $key = 'location';
                $value = $value['lat'].",".$value['lng'];
            }

            if($key ==='mime_type'){
                $value = json_encode($value);
            }

            if($key ==='api_keywords'){
                $value = json_encode($value);
            }            

            if($key ==='ncds_2_keywords'){
                $value = json_encode($value);
            }     
            
            if($key ==='ncds_4_keywords'){
                $value = json_encode($value);
            } 
            
            if($key ==='ncds_5_keywords'){
                $value = json_encode($value);
            }
            
            if($key ==='ncds_6_keywords'){
                $value = json_encode($value);
            }             
  
            Setting::where('slug', $key)->update(['value' => $value]);
        }

        self::postLogs(['event'=>'แก้ไขข้อมูล','module_id'=>'26']);
        return redirect()->route('admin.setting.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getNcdsIndex()
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        
        $data = array();
        $settings = Setting::all();
        $data['banner_category'] =BannerCategory::DataDropdown();
        $data['single_page'] =SinglePage::DataDropdown();

        if ($settings->isNotEmpty()){
            foreach ($settings  as $setting){
                if($setting->slug =='mime_type'){
                    $data[$setting->slug] = json_decode($setting->value);
                }else{
                    $data[$setting->slug] = $setting->value;
                }
            }
        }

        $categorys = ArticleCategory::DataDropdown(['status'=>['publish'],'type'=>'ncds-2'])->pluck('title','id');
        $categorys_select = ArticleCategory::DataDropdown(['status'=>['publish'],'type'=>'ncds-2'])->pluck('id')->toArray();
        $ncds_2_disease = Setting::select('value')->where('slug','=','ncds_2_disease')->first();
        $ncds_2_disease_select = json_decode($ncds_2_disease->value);
        if(isset($ncds_2_disease->value)){
            $ncds_2_disease = json_decode($ncds_2_disease->value);
            $ncds_2_disease = array_combine($ncds_2_disease,$ncds_2_disease);
        }else{
            $ncds_2_disease = [];
        }
        $ncds_2_area = Setting::select('value')->where('slug','=','ncds_2_area')->first();
        $ncds_2_area_select = json_decode($ncds_2_area->value);
        if(isset($ncds_2_area->value)){
            $ncds_2_area = json_decode($ncds_2_area->value);
            $ncds_2_area = array_combine($ncds_2_area,$ncds_2_area);
        }else{
            $ncds_2_area = [];
        }
        $data['categorys'] = $categorys;
        $data['categorys_select'] = $categorys_select;
        $data['ncds_2_disease'] = $ncds_2_disease;
        $data['ncds_2_area'] = $ncds_2_area;
        $data['ncds_2_disease_select'] = $ncds_2_disease_select;
        $data['ncds_2_area_select'] = $ncds_2_area_select;
        //dd($data,$categorys,$ncds_2_disease,$ncds_2_area);
        return view('setting::ncds_index',$data);
    }

    public function postNcdsIndex(EditRequest $request)
    {

        $fileNameNCDs = null;

        if ($request->hasFile('ncds_cover_image')) {
            $row = Setting::where('slug','ncds_cover_image')->get();
            if ($row->isNotEmpty()) {
                $item = $row->first();
                $item->clearMediaCollection('ncds_cover_image');
                $ncds_cover_image = $item->addMedia($request->file('ncds_cover_image'))->toMediaCollection('ncds_cover_image');
                $fileNameNCDs =$item->getFirstMediaUrl('ncds_cover_image','thumb1366x635');
                //$item->getFirstMediaUrl('ncds_cover_image','thumb1366x635');
                //asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635'))
            }
        }

        $data = $request->all();
        //dd($data);
        unset($data['_token']);

        if ($fileNameNCDs) {
            $data['ncds_cover_image'] = $fileNameNCDs;
        }

        foreach ($data as $key => $value){
            if($key == 'category_id'){
                //dd($key,$value);
                $array_check = [];    
                foreach ($value as $key_category => $value_category) {
                    //dd($key_category,$value_category);
                       
                    if (is_numeric($value_category)) {
                        //echo var_export($data['category_id'], true) . " is numeric", PHP_EOL;
                        array_push($array_check,$value_category);
                    } else {
                        $data_category = [];
                        $data_category['title'] = $value_category;
                        $data_category['type'] = 'ncds-2';
                        $data_category['status'] = 'publish';
                        $data_category['created_by'] = auth()->user()->id;
                        $category_id = ArticleCategory::create($data_category);
                        array_push($array_check,$category_id->id);
                        //echo var_export($data['category_id'], true) . " is NOT numeric", PHP_EOL;
                    }

                }
                //dd($array_check);
                $array_check = collect($array_check);
                //dd($array_check->count());
                if($array_check->count() >0){
                    $category = ArticleCategory::select('id')
                                            ->whereNotIn('id',$array_check)
                                            ->where('type','=','ncds-2')
                                            ->get()
                                            ->pluck('id')
                                            ->toArray();
                
                    ArticleCategory::whereIn('id',$category)->delete();           
                }
                //dd($array_check,$category);

            }else if($key == 'ncds_2_disease'){
                Setting::where('slug', $key)->update(['value' =>json_encode($value)]);
            }else if($key == 'ncds_2_area'){
                Setting::where('slug', $key)->update(['value' =>json_encode($value)]);
            }else{
                Setting::where('slug', $key)->update(['value' => $value]);
            }

            
        }

        self::postLogs(['event'=>'แก้ไขข้อมูล','module_id'=>'26']);
        return redirect()->route('admin.ncds_setting.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');

    } 
    

    public function getThaihealthWatchIndex()
    {

        //dd("Test");
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        
        $data = array();
        $settings = Setting::all();
        $data['banner_category'] =BannerCategory::DataDropdown();
        $data['single_page'] =SinglePage::DataDropdown();

        if ($settings->isNotEmpty()){
            foreach ($settings  as $setting){
                if($setting->slug =='mime_type'){
                    $data[$setting->slug] = json_decode($setting->value);
                }else{
                    $data[$setting->slug] = $setting->value;
                }
            }
        }        

        return view('setting::thaihealth_watch_index',$data);
    }

    public function postThaihealthWatchIndex(EditRequest $request)
    {
    

        $data = $request->all();
        //dd($data);

        $fileNameThaihealthWatch = null;
        if ($request->hasFile('thaihealth_watch_cover_image')) {
            $row = Setting::where('slug','thaihealth_watch_cover_image')->get();
            if ($row->isNotEmpty()) {
                $item = $row->first();
                $item->clearMediaCollection('thaihealth_watch_cover_image');
                $thaihealth_watch_cover_image = $item->addMedia($request->file('thaihealth_watch_cover_image'))->toMediaCollection('thaihealth_watch_cover_image');
                $fileNameThaihealthWatch =$item->getFirstMediaUrl('thaihealth_watch_cover_image','thumb1366x635');
                //$item->getFirstMediaUrl('ncds_cover_image','thumb1366x635');
                //asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635'))
            }
        }
        if ($fileNameThaihealthWatch) {
            $data['thaihealth_watch_cover_image'] = $fileNameThaihealthWatch;
        }

        $fileNameThaihealthWatch = null;
        if ($request->hasFile('panel_discussion_cover_image')) {
            $row = Setting::where('slug','panel_discussion_cover_image')->get();
            if ($row->isNotEmpty()) {
                $item = $row->first();
                $item->clearMediaCollection('panel_discussion_cover_image');
                $panel_discussion_cover_image = $item->addMedia($request->file('panel_discussion_cover_image'))->toMediaCollection('panel_discussion_cover_image');
                $fileNameThaihealthWatch =$item->getFirstMediaUrl('panel_discussion_cover_image','thumb1366x635');
                //$item->getFirstMediaUrl('ncds_cover_image','thumb1366x635');
                //asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635'))
            }
        }

        if ($fileNameThaihealthWatch) {
            $data['panel_discussion_cover_image'] = $fileNameThaihealthWatch;
        }

        $fileNameThaihealthWatch = null;
        if ($request->hasFile('points_to_watch_cover_image')) {
            $row = Setting::where('slug','points_to_watch_cover_image')->get();
            if ($row->isNotEmpty()) {
                $item = $row->first();
                $item->clearMediaCollection('points_to_watch_cover_image');
                $points_to_watch_cover_image = $item->addMedia($request->file('points_to_watch_cover_image'))->toMediaCollection('points_to_watch_cover_image');
                $fileNameThaihealthWatch =$item->getFirstMediaUrl('points_to_watch_cover_image','thumb1366x635');
                //$item->getFirstMediaUrl('ncds_cover_image','thumb1366x635');
                //asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635'))
            }
        }

        if ($fileNameThaihealthWatch) {
            $data['points_to_watch_cover_image'] = $fileNameThaihealthWatch;
        } 
        

        $fileNameThaihealthWatch = null;
        if ($request->hasFile('health_trends_cover_image')) {
            $row = Setting::where('slug','health_trends_cover_image')->get();
            if ($row->isNotEmpty()) {
                $item = $row->first();
                $item->clearMediaCollection('health_trends_cover_image');
                $health_trends_cover_image = $item->addMedia($request->file('health_trends_cover_image'))->toMediaCollection('health_trends_cover_image');
                $fileNameThaihealthWatch =$item->getFirstMediaUrl('health_trends_cover_image','thumb1366x635');
                //$item->getFirstMediaUrl('ncds_cover_image','thumb1366x635');
                //asset($data->getFirstMediaUrl('cover_desktop','thumb1366x635'))
            }
        }

        if ($fileNameThaihealthWatch) {
            $data['health_trends_cover_image'] = $fileNameThaihealthWatch;
        }         

        

        unset($data['_token']);
        foreach ($data as $key => $value){
                Setting::where('slug', $key)->update(['value' => $value]);  
        }

        self::postLogs(['event'=>'แก้ไขข้อมูล','module_id'=>'46']);
        return redirect()->route('admin.thaihealth_watch_setting.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');

    }    



    public static function getKeyAndReturnValue($key, $default = '') {
        $setting = Setting::select('value')->where('slug', $key)->first();
        return $setting->value;
    }


    public static function getClearCache(Request $request){

        Artisan::call('cache:clear');
        //dd("Clear Cache");
        return redirect()->route('admin.dashboard.index')
                            ->with('status', 'success')
                            ->with('message', 'Clear Cache Successfully');

    }

}

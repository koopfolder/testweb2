<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets};
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Hash;
use Crypt;
use Illuminate\Support\Facades\Log;



class CategoryController extends Controller
{
    public function getIndex()
    {
        //dd("Case Category");
        $items = ListCategory::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('api::backend.list_category.index', compact('items'));
    }


    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = ListCategory::findOrFail($id);
        //dd($data);
        return view('api::backend.list_category.edit', compact('data'));
    }

    public function postEdit(Request $request, $id)
    {
        $data = $request->all();
        //dd($data);
        $item = ListCategory::findOrFail($id);
        $data_update['status'] = $data['status'];
        //dd($data_update);
        $item->update($data_update);
        self::postLogs(['event'=>'ปรับสถานะข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'14']);
        return redirect()->route('admin.api.list-category.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function CategoryData(){
        $category_data = ListCategory::select('category_id','name')->where('status','publish')->get()->toArray();

        if(empty($category_data)){
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => []
            ];
        }else{
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => $category_data,
            ];
        }
        return $response;
    }
    public function SettingData(){
        $setting_data = ListSetting::select('setting_id','name')->where('status','publish')->get()->toArray();
        if(empty($setting_data)){
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => []
            ];
        }else{
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => $setting_data,
            ];
        }
        return $response;
    }

    public function IssueData(){
        $issues_data = ListIssue::select('issues_id','name')->where('status','publish')->get()->toArray();
        if(empty($issues_data)){
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => []
            ];
        }else{
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => $issues_data,
            ];
        }
        return $response;
    }

    public function TargetData(){
        $target_data = ListTarget::select('target_id','name')->where('status','publish')->get()->toArray();
        if(empty($target_data)){
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => []
            ];
        }else{
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => $target_data,
            ];
        }
        return $response;
    }

    
    public function import_excal(Request $req){
       // dd($req->req);
     //  return $req->req;

    //   $add = DB::table('list_media')->where('UploadFileID','0CD1A74E-345E-EC11-80FA-00155DB45613')
    //   ->update([
    //     'SendMediaTermStatus' => 50,
    //     // 'api' => $val['Api'],
    //     // 'show_dol' => $val['dol'],
    //     // 'web_view' => $val['webview'],
    //     ]);


       // dd($req->UploadFile);
        // dd($req->UploadFile['UpdatedDate']);
        // dd($req->UploadFile);
       //  $time= Carbon::createFromFormat('Y-m-d', $req->UploadFile['UpdatedDate']);
        //  $datetime = Carbon::createFromFormat('Y-m-d\TH:i:s.u', $req->UploadFile['UpdatedDate']);
        //  $timeupdate = $datetime->format('Y-m-d H:i:s');

        //  if ($req->UploadFile['SendMediaTermStatus'] == "สื่อวาระกลาง (อยู่ระหว่างพิจารณา)") {
        //     $term = 49;
        // } elseif ($req->UploadFile['SendMediaTermStatus'] == "สื่อวาระกลาง") {
        //     $term = 50;
        // }  elseif ($req->UploadFile['SendMediaTermStatus'] == "สื่อวาระกลาง (ไม่ผ่านเกณฑ์)") {
        //     $term = 52;
        // }else {
        //     $term = null;
        // }

      //  dd($req->UploadFile['SendMediaTermStatus'], $term);

        //  $array = array();
        //  $array['title'] = !empty($req->UploadFile['Title']) ? $req->UploadFile['Title'] : '';
        //  $array['description'] = !empty($req->UploadFile['Description']) ? $req->UploadFile['Description'] : '';
        //  $array['created_at'] = $timeupdate;
        //  $array['template'] = !empty($req->UploadFile['Template']) ? $req->UploadFile['Template'] : '';
        //  $array['category_id'] = !empty($req->UploadFile['CategoryID']) ? $req->UploadFile['CategoryID'] : '';
        //  $array['area_id'] = !empty($req->UploadFile['AreaID']) ? $req->UploadFile['AreaID'] : '';
        //  $array['json_data'] = !empty($req->UploadFile) ? json_encode($req->UploadFile) : '';
        //  $array['department_id'] = !empty($req->UploadFile['DepartmentID']) ? $req->UploadFile['DepartmentID'] : '';
        //  $array['file_thumbnail_link'] = !empty($req->UploadFile['ThumbnailAddress']) ? $req->UploadFile['ThumbnailAddress'] : '';
        // // $array['province'] = !empty($req->UploadFile['Province']) ? json_encode($req->UploadFile['Province']):'';


      
        //  $array['SendMediaTermStatus'] =  $term;
         
        //  $array['UploadFileID'] = $req->UploadFile['UploadFileID'];
 
        //  $array['status'] = 'draft';
        //  ListMedia::create($array);
 
      //  dd($req->req[0]);
         foreach($req->req[0] as $key => $val)
         {

        //    // dd();
        //   //  dd($val['title'],$val['webview'],$val['Api'],$val['dol'],$val['term']);

        // //    "webview" => 0
        // //    "Api" => "draft"
        // //    "dol" => 2
        // //    "term" => 50
        // //    "UploadFileID" => "d3c1b1c4-5744-ed11-80fa-00155db45626"

        //     //   แก้ไขข้อมูลสื่อวาระกลาง
        //     //dd($val['title']);
              $add = DB::table('list_media')->where('UploadFileID',$val['UploadFileID'])
              ->update([
                'SendMediaTermStatus' => $val['term'],
                'api' => $val['Api'],
                'show_dol' => $val['dol'],
                'web_view' => $val['webview'],
                ]);

        //       $test[] = !empty($add->title) ? $add->title : null;

        //     // if($add == 1 || $add == 2){
        //     //     $show[] = $val['title'];
        //     // }
                
        //     //   เช็คพัง
       
        //  $data_val = DB::table('list_media')->where('UploadFileID',$val['UploadFileID'])->first();
        
        //      if(!empty($data_val)){
        //          $chack_data['pass'][] = $val['UploadFileID'];
        //      }else{
        //          $chack_data['fail'][] = $val['UploadFileID'];
        //      }
 
           }
          

        //   $add = DB::table('list_media')->where('SendMediaTermStatus','!=',null)
        //   ->where('created_at','>=','2022-10-01 00:00:00')->where('created_at','<=','2023-06-12 23:59:00')
        //       ->update([
        //         'SendMediaTermStatus' => null,
        //         ]);

        

 
     
       
        return $add;
     }

     public function Apiupdatetags(Request $req){
      // dd($req->id);
      $media = DB::table('list_media')->select('tags', 'json_data','id')->where('id', (int)$req->id)->first();
      $mediaString = json_encode($media); // Assuming $media is an stdClass object
    
      return $mediaString;
      
     }

    public function UpdateStatusMedia (Request $req){
        // dd($req->all());

        $dataStatus = DB::table('list_media')
        ->whereIn('id', $req->id)
        ->update([
            'SendMediaTermStatus' => 51,
            ]);
    }
}


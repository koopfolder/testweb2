<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Api\Models\{ListMedia, ListArea, ListCategory, ListIssue, ListProvince, ListSetting, ListTarget, ListMediaIssues, ListMediaKeywords, ListMediaTargets, ListTemplate, Department, ApiLogs, ViewMediaAmount, ListArticle};
use App\Modules\Article\Models\{Article};
use App\Modules\Setting\Models\Setting;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Hash;
use Crypt;
use Illuminate\Support\Facades\Log;
use DB;


class IndexController extends Controller
{

    public function GET_ENV(){
        dd(env('APP_ENV'));
    }
    public function postTaskDepartment(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ", $request->header('Authorization'));
            $response = array();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api Task Department');
                $input = $request->all();
                $department = Department::Data(['status' => ['publish']]);
                $data_api = array();

                if ($department->count()) {

                    foreach ($department as $key => $value) {

                        $body = '{"PageNo":1,"PageSize":1,"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '","DepartmentID":"' . $value->id . '"}';
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST', env('URL_LIST_MEDIA'), ['body' => $body]);
                        $response_api = $request->getBody()->getContents();
                        $response_api = str_replace(" ", "", substr($response_api, 3));
                        $data_json = json_decode($response_api, true);
                        $total_rows = $data_json['TotalRows'];
                        Department::where('id', '=', $value->id)->update(['total_items' => $total_rows]);
                        //$total_page = ceil($data_json['TotalRows'] / env('PageSize'));
                        $data_api[$value->id] = $total_rows;
                    }

                    $check_task = ApiLogs::Data(['status' => ['processes'], 'api_type' => 'list_media']);
                    if (!isset($check_task->id)) {
                        $view_media_amount = ViewMediaAmount::Data([])->pluck('total', 'department_id')->toArray();

                        foreach ($department as $key => $value) {
                            //echo $data_api[$value->code]." ".$view_media_amount[$value->code];
                            //echo "<br>";
                            if ($data_api[$value->id] != 0) {

                                // if(isset($view_media_amount[$value->code])){
                                //     if($view_media_amount[$value->code] < $data_api[$value->code]){
                                //         //echo "True-->".$view_media_amount[$value->code];
                                //         //echo "<br>";
                                //         $data = array();
                                //         $data['api_name'] = 'ListMedia '.$value->name;
                                //         $data['status'] = 'processes';
                                //         $data['total'] = $data_api[$value->code];
                                //         $data['page_size'] = env('PageSize');
                                //         $data['page_no'] = 1;
                                //         $data['page_all'] = ceil($data_api[$value->code] / env('PageSize'));
                                //         $data['params'] = $value->code;
                                //         $data['api_type'] = 'list_media';
                                //         ApiLogs::create($data);
                                //     }
                                // }else{
                                //     $data = array();
                                //     $data['api_name'] = 'ListMedia '.$value->name;
                                //     $data['status'] = 'processes';
                                //     $data['total'] = $data_api[$value->code];
                                //     $data['page_size'] = env('PageSize');
                                //     $data['page_no'] = 1;
                                //     $data['page_all'] = ceil($data_api[$value->code] / env('PageSize'));
                                //     $data['params'] = $value->code;
                                //     $data['api_type'] = 'list_media';
                                //     ApiLogs::create($data);
                                // }

                                $data = array();
                                $data['api_name'] = 'ListMedia ' . $value->name;
                                $data['status'] = 'processes';
                                $data['total'] = $data_api[$value->id];
                                $data['page_size'] = env('PageSize', '1000');
                                $data['page_no'] = 1;
                                $data['page_all'] = 1;
                                $data['params'] = $value->id;
                                $data['api_type'] = 'list_media';
                                ApiLogs::create($data);
                            }
                        }
                    }
                }

                Log::info('End Api Task Department');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                //$response['data_api'] =$data_api;
                //$response['view_media_amount'] =$view_media_amount;
                //$response['data'] =$department;
                //$response['total_rows'] =$total_rows;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api Department ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            //$response['header'] =$request->header();
            return  Response::json($response, 500);
        }
    }

    public function getJsonData(Request $request)
    {

        try {

            if ($request->media_id) {

                $item = ListMedia::find($request->media_id);

                $response['msg'] = "success";
                $response['data'] = $item->json_data;
            } else {
                $response['msg'] = "not information";
            }


            $response['status'] = true;
            //$response['header'] =$request->header();
            return  Response::json($response, 200);
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api Department ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            //$response['header'] =$request->header();
            return  Response::json($response, 500);
        }
    }



    public function getMediaDownload(Request $request)
    {
        $item = ListMedia::find(base64_decode($request->base64id));
        $filePath = 'mediadol/' . $item->UploadFileID . '/' . $item->local_path;

        $tmp = explode(".", $item->local_path);

        $file = public_path($filePath);
        $headers = array('Content-type:' . mime_content_type($filePath));

        return Response::download($file, 'filename.' . $tmp[1], $headers);
    }

    public function postListMedia(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ", $request->header('Authorization'));
            $response = array();



            if (Hash::check(env('SECRET'), $token['1'])) {

                $input = $request->all();
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api List Media');


                $loop_api = 1;
                while ($loop_api <= 2000) {

                    $task = ApiLogs::Data(['status' => ['processes'], 'api_type' => 'list_media']);

                    if (isset($task->id)) {
                        $i = 1;
                        $task->update(['note' => 'Page ' . $task->page_no . ' Start']);
                        $body = '{"PageNo":"' . $task->page_no . '","PageSize":"' . $task->page_size . '","UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '","DepartmentID":"' . $task->params . '"}';
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST', env('URL_LIST_MEDIA'), ['body' => $body]);
                        $response_api = $request->getBody()->getContents();
                        $response_api = str_replace(" ", "", substr($response_api, 3));
                        $data_json = json_decode($response_api, true);

                        if ($data_json['TotalRows'] > 0) {
                            foreach ($data_json['Files'] as $key => $value) {
                                //dd($value);
                                $rules = ['UploadFileID' => 'required|unique:list_media,UploadFileID'];/* Dont Forget */
                                $data = ['UploadFileID' => $value['UploadFileID']];
                                $validator = Validator::make($data, $rules);
                                if ($validator->passes()) {
                                    $array = array();
                                    $array['UploadFileID'] = $value['UploadFileID'];
                                    $array['department_id'] = $task->params;
                                    //$array['json_data'] = json_encode($value);
                                    $array['status'] = 'draft';
                                    ListMedia::create($array);
                                }


                                $check_data = 0;
                                $data_array_check = array("healthliteracy");

                                foreach ($value['Keywords'] as $value_keywords) {
                                    //echo $value;
                                    //echo "<br>";
                                    //exit();
                                    //$value_keywords='healthliteracy';
                                    if (array_keys($data_array_check, strtolower($value_keywords))) {
                                        $check_data = 1;
                                        //print_r($value);
                                        //echo "<br>";
                                        //exit();
                                        //dd("True")
                                    }
                                }


                                if ($check_data == 1) {

                                    $check_article = Article::select('id')
                                        ->where('dol_UploadFileID', '=', $value['UploadFileID'])
                                        ->where('page_layout', '=', 'health-literacy')
                                        ->first();

                                    if (!isset($check_article->id)) {

                                        $data_article['page_layout'] = 'health-literacy';
                                        $data_article['title'] = $value['Title'];
                                        $data_article['description'] = $value['Description'];
                                        $data_article['short_description'] = strip_tags($value['Description']);
                                        $data_article['dol_cover_image'] = $value['ThumbnailAddress'];
                                        $data_article['dol_UploadFileID'] = $value['UploadFileID'];
                                        $data_article['dol_url'] = $value['FileAddress'];
                                        $data_article['dol_template'] = $value['Template'];
                                        $data_article['dol_json_data'] = json_encode($value);
                                        $data_article['category_id'] = 0;
                                        foreach ($value['Issues'] as $value_issues) {
                                            if ($value_issues['ID'] == 5) {
                                                #แอลกอฮอล์
                                                $data_article['category_id'] = 5;
                                            }

                                            if ($value_issues['ID'] == 28) {
                                                #บุหรี่
                                                $data_article['category_id'] = 6;
                                            }

                                            if ($value_issues['ID'] == 39) {
                                                #อาหาร
                                                $data_article['c ategory_id'] = 7;
                                            }

                                            if ($value_issues['ID'] == 18) {
                                                #กิจกรรมทางกาย
                                                $data_article['category_id'] = 8;
                                            }

                                            if ($value_issues['ID'] == 41) {
                                                #อุบัติเหตุ
                                                $data_article['category_id'] = 9;
                                            }

                                            if ($value_issues['ID'] == 37) {
                                                #เพศ เช่น ท้องไม่พร้อม
                                                $data_article['category_id'] = 10;
                                            }

                                            if ($value_issues['ID'] == 34) {
                                                #สุขภาพจิต
                                                $data_article['category_id'] = 11;
                                            }
                                            if ($value_issues['ID'] == 35) {
                                                #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                $data_article['category_id'] = 12;
                                            }

                                            if ($value_issues['ID'] == 36) {
                                                #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                $data_article['category_id'] = 12;
                                            }

                                            if ($value_issues['ID'] == 27) {
                                                #สิ่งแวดล้อม
                                                $data_article['category_id'] = 13;
                                            }

                                            if ($value_issues['ID'] == 33) {
                                                #สิ่งแวดล้อม
                                                $data_article['category_id'] = 13;
                                            }

                                            if ($value_issues['ID'] == 49) {
                                                #สิ่งแวดล้อม
                                                $data_article['category_id'] = 13;
                                            }

                                            if ($value_issues['ID'] == 16) {
                                                #อื่นๆ
                                                $data_article['category_id'] = 14;
                                            }

                                            if ($value_issues['ID'] == 21) {
                                                #อื่นๆ
                                                $data_article['category_id'] = 14;
                                            }

                                            if ($value_issues['ID'] == 32) {
                                                #อื่นๆ
                                                $data_article['category_id'] = 14;
                                            }

                                            if ($value_issues['ID'] == 42) {
                                                #อื่นๆ
                                                $data_article['category_id'] = 14;
                                            }
                                        }

                                        //print_r($value['Issues']);
                                        //print_r(gettype($value_issues['ID']));
                                        //echo "<br>";
                                        //print_r($data_article['category_id']);
                                        //exit();

                                        $date_year = date('Y-m-d');
                                        $date_year = strtotime($date_year);
                                        $date_year = strtotime("+10 year", $date_year);
                                        $data_article['start_date'] = date("Y-m-d H:i:s");
                                        $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
                                        //dd($data_article);
                                        // echo "<pre>";
                                        //         print_r($data_article);
                                        // echo "</pre>";
                                        // exit();
                                        Article::create($data_article);
                                    }
                                }

                                $i++;
                            }
                            $next_page = $task->page_no + 1;
                            $task->update(['page_no' => $next_page, 'note' => 'Page ' . $task->page_no . ' End']);
                            if ($next_page > $task->page_all) {
                                $task->update(['status' => 'end_processes']);
                            }
                        } else {
                            $task->update(['status' => 'end_processes', 'note' => 'Page ' . $task->page_no . ' End']);
                        }
                        //$task->update(['page_no'=>'']);
                        $loop_api = $loop_api + $i;
                    } else {
                        $loop_api = 3000;
                    }
                }

                Log::info('End Api List Media');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                $response['task'] = $task;
                $response['loop_api'] = $loop_api;
                // $response['total']= $data_json['TotalRows'];
                // $response['body']= $body;
                // $response['data_json']= $data_json;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api List Media ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }


    // public function postListMedia(Request $request){

    //     try {

    //         ini_set('max_execution_time', 0);
    //         ini_set('request_terminate_timeout', 0);
    //         set_time_limit(0);

    //         $token = explode(" ",$request->header('Authorization'));
    //         $response = array();

    //         if (Hash::check(env('SECRET'),$token['1']))
    //         { 

    //             $input = $request->all();
    //             if(isset($input['DepartmentID'])){

    //                 Log::useDailyFiles(storage_path().'/logs/api.log');
    //                 Log::info('Start Api List Media DepartmentID =>'.$input['DepartmentID']);

    //                 $body = '{"PageNo":1,"PageSize":1,"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","DepartmentID":"'.$input['DepartmentID'].'"}';
    //                 $client = new \GuzzleHttp\Client();
    //                 $request = $client->request('POST', env('URL_LIST_MEDIA'), ['body' => $body]);    
    //                 $response_api = $request->getBody()->getContents();
    //                 $response_api = str_replace(" ","",substr($response_api,3));
    //                 $data_json = json_decode($response_api, true);
    //                 $total_page = ceil($data_json['TotalRows'] / env('PageSize'));
    //                 //dd($data_json,$total_page);

    //                 if(gettype($data_json) =='array'){

    //                     for ($i=1; $i <= $total_page; $i++) { 

    //                         $body = '{"PageNo":"'.$i.'","PageSize":"'.env('PageSize').'","UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","DepartmentID":"'.$input['DepartmentID'].'"}';
    //                         $request = $client->request('POST', env('URL_LIST_MEDIA'), ['body' => $body]);    
    //                         $response_api_loop = $request->getBody()->getContents();
    //                         $response_api_loop = str_replace(" ","",substr($response_api_loop,3));
    //                         $data_json_loop = json_decode($response_api_loop, true);
    //                         foreach($data_json_loop['Files'] AS $key=>$value){

    //                             //dd($value);
    //                             $rules = ['UploadFileID'=>'required|unique:list_media,UploadFileID'];
    //                             $data = ['UploadFileID'=>$value['UploadFileID']];
    //                             $validator = Validator::make($data, $rules);
    //                             if($validator->passes()){
    //                                 $array = array();
    //                                 $array['UploadFileID'] = $value['UploadFileID'];
    //                                 //$array['json_data'] = json_encode($value);
    //                                 $array['status'] = 'publish';
    //                                 ListMedia::create($array);  
    //                             }

    //                         } 

    //                     }/* End Insert List */

    //                 }
    //                 Log::info('End Api List Media');
    //                 $response['msg'] ='200 OK';
    //                 $response['status'] =true;
    //                 $response['DepartmentID'] = $input['DepartmentID'];
    //                 $response['total_page'] = $total_page;
    //                 $response['total_rows'] = $data_json['TotalRows'];
    //                 return  Response::json($response,200);

    //             }else{
    //                 $response['msg'] ='404 Page Not Found';
    //                 $response['status'] =false;
    //                 return  Response::json($response,404);
    //             }

    //         }else{
    //             $response['msg'] ='401 (Unauthorized)';
    //             $response['status'] =false;
    //             return  Response::json($response,401);
    //         }

    //     } catch (\Throwable $e) {
    //         Log::useDailyFiles(storage_path().'/logs/api-errors.log');
    //         Log::error('Api List Media ---> '.$e->getMessage());
    //         $response['msg'] =$e->getMessage();
    //         $response['status'] =false;
    //         return  Response::json($response,500);
    //     }

    // }

    public function postMedia(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);


            $token = explode(" ", $request->header('Authorization'));
            $response = array();
            $input = $request->all();
            //dd("Test");
            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api Get Media');

                $task = ApiLogs::Data(['status' => ['processes'], 'api_type' => 'get_media']);
                if (isset($task->id)) {

                    $media = ListMedia::select('UploadFileID')->whereRaw('title IS NULL')->limit($task->page_size)->get();
                    //$media = ListMedia::select('UploadFileID')->where('UploadFileID','=','7118b6b4-fe83-ec11-80fa-00155db45613')->limit($task->page_size)->get();
                    if (collect($media)->count()) {
                        $task->update(['note' => 'Page ' . $task->page_no . ' Start']);
                        foreach ($media as $key => $value) {
                            //dd($value->id);
                            $body = '{"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '","UploadFileID":"' . $value->UploadFileID . '"}';
                            //$body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","UploadFileID":"e63e4091-929a-e611-80db-00155d3d0608"}';
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('URL_GET_MEDIA'), ['body' => $body]);
                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ", "", substr($response_api, 3));
                            $data_json = json_decode($response_api, true);

                            //dd($data_json);

                            if (gettype($data_json) == 'array' && $data_json['Success'] == 'true') {

                                //dd($data_json,"Case True");

                                if (gettype($data_json['UploadFile']['Keywords']) == 'array') {
                                    $check_data = 0;
                                    $data_array_check = array("newscliping", "รายงานประจำปี", "รายงานความก้าวหน้า");


                                    /* Api */
                                    $check_data_api = 0;
                                    $setting_api_keywords = Setting::select('value')->where('slug', 'api_keywords')->first();
                                    $setting_api_keywords_array = [];
                                    if (isset($setting_api_keywords->value) && $setting_api_keywords->value != '') {
                                        $setting_api_keywords_array = json_decode($setting_api_keywords->value);
                                    }

                                    /* ncds_2_keywords */
                                    $check_data_ncds_2 = 0;
                                    $setting_ncds_2_keywords = Setting::select('value')->where('slug', 'ncds_2_keywords')->first();
                                    $setting_ncds_2_keywords_array = [];
                                    if (isset($setting_ncds_2_keywords->value) && $setting_ncds_2_keywords->value != '') {
                                        $setting_ncds_2_keywords_array = json_decode($setting_ncds_2_keywords->value);
                                    }

                                    /* ncds_4_keywords */
                                    $check_data_ncds_4 = 0;
                                    $setting_ncds_4_keywords = Setting::select('value')->where('slug', 'ncds_4_keywords')->first();
                                    $setting_ncds_4_keywords_array = [];
                                    if (isset($setting_ncds_4_keywords->value) && $setting_ncds_4_keywords->value != '') {
                                        $setting_ncds_4_keywords_array = json_decode($setting_ncds_4_keywords->value);
                                    }

                                    /* ncds_5_keywords */
                                    $check_data_ncds_5 = 0;
                                    $setting_ncds_5_keywords = Setting::select('value')->where('slug', 'ncds_5_keywords')->first();
                                    $setting_ncds_5_keywords_array = [];
                                    if (isset($setting_ncds_5_keywords->value) && $setting_ncds_5_keywords->value != '') {
                                        $setting_ncds_5_keywords_array = json_decode($setting_ncds_5_keywords->value);
                                    }

                                    /* ncds_6_keywords */
                                    $check_data_ncds_6 = 0;
                                    $setting_ncds_6_keywords = Setting::select('value')->where('slug', 'ncds_6_keywords')->first();
                                    $setting_ncds_6_keywords_array = [];
                                    if (isset($setting_ncds_6_keywords->value) && $setting_ncds_6_keywords->value != '') {
                                        $setting_ncds_6_keywords_array = json_decode($setting_ncds_6_keywords->value);
                                    }


                                    foreach ($data_json['UploadFile']['Keywords'] as $value_check) {
                                        //echo $value;
                                        //echo "<br>";
                                        if (array_keys($data_array_check, $value_check)) {
                                            $check_data = 1;
                                            //echo $value;
                                            //echo "<br>";
                                            //exit();
                                            $array = array();
                                            $array['title'] = 'ข้อมูลไม่ผ่านการคัดกรอง';
                                            $array['description'] = 'check_data->' . $check_data . '---' . json_encode($data_json['UploadFile']['Keywords']);
                                            //dd($array,isset($data_json['UploadFile']['Province']['0']),$data_json['UploadFile']['Province'],gettype($data_json['UploadFile']['Province']),"Case True0");
                                            ListMedia::where('UploadFileID', '=', $data_json['UploadFileID'])->update($array);
                                        }

                                        if (count($setting_api_keywords_array) > 0) {
                                            if (array_keys($setting_api_keywords_array, $value_check)) {
                                                $check_data_api = 1;
                                            }
                                        }

                                        if (count($setting_ncds_2_keywords_array) > 0) {
                                            if (array_keys($setting_ncds_2_keywords_array, $value_check)) {
                                                $check_data_ncds_2 = 1;
                                            }
                                        }

                                        if (count($setting_ncds_4_keywords_array) > 0) {
                                            if (array_keys($setting_ncds_4_keywords_array, $value_check)) {
                                                $check_data_ncds_4 = 1;
                                            }
                                        }

                                        if (count($setting_ncds_5_keywords_array) > 0) {
                                            if (array_keys($setting_ncds_5_keywords_array, $value_check)) {
                                                $check_data_ncds_5 = 1;
                                            }
                                        }

                                        if (count($setting_ncds_6_keywords_array) > 0) {
                                            if (array_keys($setting_ncds_6_keywords_array, $value_check)) {
                                                $check_data_ncds_6 = 1;
                                            }
                                        }
                                    }
                                    //dd($setting_ncds_5_keywords_array,$check_data_ncds_5,$data_json['UploadFile']['Keywords'],"check_data_ncds_5");

                                    if ($check_data != 1) {
                                        $array = array();
                                        $array['title'] = $data_json['UploadFile']['Title'];
                                        $array['description'] = $data_json['UploadFile']['Description'];
                                        $array['category_id'] = $data_json['UploadFile']['CategoryID'];
                                        $array['province'] = (isset($data_json['UploadFile']['Province']['0']) ? $data_json['UploadFile']['Province']['0'] : '');
                                        $array['template'] = $data_json['UploadFile']['Template'];
                                        $array['area_id'] = $data_json['UploadFile']['AreaID'];
                                        $array['json_data'] = json_encode($data_json['UploadFile']);
                                        //dd($array,isset($data_json['UploadFile']['Province']['0']),$data_json['UploadFile']['Province'],gettype($data_json['UploadFile']['Province']),$data_json['UploadFile'],"Case True1");
                                        ListMedia::where('UploadFileID', '=', $data_json['UploadFileID'])->update($array);
                                        //dd($list_media_data);

                                    }


                                    if ($check_data_api == 1) {

                                        //dd("Check Api");
                                        $check = ListMedia::select('id', 'api')
                                            ->where('UploadFileID', '=', $data_json['UploadFileID'])
                                            ->first();
                                        if (isset($check->id)) {
                                            $status = 'publish';
                                            $data_media = ListMedia::where('UploadFileID', '=', $data_json['UploadFileID'])->firstOrFail();
                                            $data_media->update(['api' => $status]);
                                        }
                                        //dd($data_media);
                                        $data_media->updated_by = 0;
                                        $media_json_data = json_decode($data_media->json_data);
                                        if ($media_json_data->SubProjectCode == null || $media_json_data->SubProjectCode == 'null') {
                                            $media_json_data->SubProjectCode = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->FileSize == null || $media_json_data->FileSize == 'null') {
                                            $media_json_data->FileSize = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->ProjectCode == null || $media_json_data->ProjectCode == 'null') {
                                            $media_json_data->ProjectCode = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->PublishLevel == null || $media_json_data->PublishLevel == 'null') {
                                            $media_json_data->PublishLevel = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->PublishLevelText == null || $media_json_data->PublishLevelText == 'null') {
                                            $media_json_data->PublishLevelText = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->CreativeCommons == null || $media_json_data->CreativeCommons == 'null') {
                                            $media_json_data->CreativeCommons = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->DepartmentID == null || $media_json_data->DepartmentID == 'null') {
                                            $media_json_data->DepartmentID = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->DepartmentName == null || $media_json_data->DepartmentName == 'null') {
                                            $media_json_data->DepartmentName = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->PublishedDate == null || $media_json_data->PublishedDate == 'null') {
                                            $media_json_data->PublishedDate = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->PublishedByName == null || $media_json_data->PublishedByName == 'null') {
                                            $media_json_data->PublishedByName = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->UpdatedDate == null || $media_json_data->UpdatedDate == 'null') {
                                            $media_json_data->UpdatedDate = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->UpdatedByName == null || $media_json_data->UpdatedByName == 'null') {
                                            $media_json_data->UpdatedByName = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        // if($media_json_data->Keywords == null || $media_json_data->Keywords == 'null'){
                                        //     $media_json_data->Keywords = 'not-specified';
                                        //     //dd("Case True",$media_json_data);
                                        // }                                            
                                        if ($media_json_data->Template == null || $media_json_data->Template == 'null') {
                                            $media_json_data->Template = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->CategoryID == null || $media_json_data->CategoryID == 'null') {
                                            $media_json_data->CategoryID = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Category == null || $media_json_data->Category == 'null') {
                                            $media_json_data->Category = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        // if($media_json_data->Issues == null || $media_json_data->Issues == 'null'){
                                        //     $media_json_data->Issues = 'not-specified';
                                        //     //dd("Case True",$media_json_data);
                                        // }
                                        // if($media_json_data->Targets == null || $media_json_data->Targets == 'null'){
                                        //     $media_json_data->Targets = 'not-specified';
                                        //     //dd("Case True",$media_json_data);
                                        // }
                                        if ($media_json_data->Settings == null || $media_json_data->Settings == 'null') {
                                            $media_json_data->Settings = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->AreaID == null || $media_json_data->AreaID == 'null') {
                                            $media_json_data->AreaID = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Area == null || $media_json_data->Area == 'null') {
                                            $media_json_data->Area = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Province == null || $media_json_data->Province == 'null') {
                                            $media_json_data->Province = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Source == null || $media_json_data->Source == 'null') {
                                            $media_json_data->Source = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->ReleasedDate == null || $media_json_data->ReleasedDate == 'null') {
                                            $media_json_data->ReleasedDate = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Creator == null || $media_json_data->Creator == 'null') {
                                            $media_json_data->Creator = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Production == null || $media_json_data->Production == 'null') {
                                            $media_json_data->Production = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Publisher == null || $media_json_data->Publisher == 'null') {
                                            $media_json_data->Publisher = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Publisher == null || $media_json_data->Publisher == 'null') {
                                            $media_json_data->Publisher = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Contributor == null || $media_json_data->Contributor == 'null') {
                                            $media_json_data->Contributor = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Identifier == null || $media_json_data->Identifier == 'null') {
                                            $media_json_data->Identifier = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Language == null || $media_json_data->Language == 'null') {
                                            $media_json_data->Language = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Relation == null || $media_json_data->Relation == 'null') {
                                            $media_json_data->Relation = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Format == null || $media_json_data->Format == 'null') {
                                            $media_json_data->Format = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->IntellectualProperty == null || $media_json_data->IntellectualProperty == 'null') {
                                            $media_json_data->IntellectualProperty = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->OS == null || $media_json_data->OS == 'null') {
                                            $media_json_data->OS = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Owner == null || $media_json_data->Owner == 'null') {
                                            $media_json_data->Owner = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->PeriodStart == null || $media_json_data->PeriodStart == 'null') {
                                            $media_json_data->PeriodStart = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->PeriodEnd == null || $media_json_data->PeriodEnd == 'null') {
                                            $media_json_data->PeriodEnd = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->Duration == null || $media_json_data->Duration == 'null') {
                                            $media_json_data->Duration = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->SystemID == null || $media_json_data->SystemID == 'null') {
                                            $media_json_data->SystemID = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        if ($media_json_data->SystemName == null || $media_json_data->SystemName == 'null') {
                                            $media_json_data->SystemName = 'not-specified';
                                            //dd("Case True",$media_json_data);
                                        }
                                        //$data_media->json_data = json_encode($media_json_data);
                                        $data_media->json_data = $media_json_data;

                                        //dd($data_media);

                                        /*Login*/
                                        $body = '{"username":"' . env('THRC_API_USERNAME') . '","password":"' . env('THRC_API_PASSWORD') . '","device_token":"thrc_backend"}';
                                        //dd($body);
                                        $client = new \GuzzleHttp\Client();
                                        $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_LOGIN'), [
                                            'headers' => [
                                                'Content-Type' => 'application/json; charset=utf-8'
                                            ],
                                            'body' => $body
                                        ]);
                                        $response_api = $request->getBody()->getContents();
                                        $data_api_json = json_decode($response_api);
                                        //dd($data_api_json);                            

                                        if ($data_api_json->status_code === 200) {

                                            $access_token = $data_api_json->data->access_token;
                                            //dd($access_token);
                                            $body = '{"device_token":"thrc_backend","media_type":"media","status_media":"' . $status . '","media":' . json_encode($data_media) . '}';
                                            //dd($body);
                                            $client = new \GuzzleHttp\Client();
                                            $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_UPDATE_MEDIA'), [
                                                'headers' => [
                                                    'Content-Type' => 'application/json; charset=utf-8',
                                                    'authorization' => $access_token
                                                ],
                                                'body' => $body
                                            ]);
                                            $response_api = $request->getBody()->getContents();
                                            $data_api_json = json_decode($response_api);
                                            //dd($data_json);
                                        }
                                        //dd("Success");
                                    }

                                    /*ncds-2 ncds_2_keywords อัพเดทสถานการณ์ NCDs */
                                    if ($check_data_ncds_2 == 1) {

                                        $check_data = Article::select('id')
                                            ->where('dol_UploadFileID', '=', $data_json['UploadFileID'])
                                            ->where('page_layout', '=', 'ncds-2')
                                            ->first();

                                        if (!isset($check_data->id)) {
                                            //dd("Test");
                                            $data_article = array();
                                            $data_article['page_layout'] = 'ncds-2';
                                            $data_article['title'] = $data_json['UploadFile']['Title'];
                                            $data_article['description'] = $data_json['UploadFile']['Description'];
                                            $data_article['short_description'] = strip_tags($data_json['UploadFile']['Description']);
                                            $data_article['dol_cover_image'] = $data_json['UploadFile']['ThumbnailAddress'];
                                            $data_article['dol_UploadFileID'] = $data_json['UploadFileID'];
                                            $data_article['dol_url'] = $data_json['UploadFile']['FileAddress'];
                                            $data_article['dol_template'] = $data_json['UploadFile']['Template'];
                                            $data_article['dol_json_data'] = json_encode($data_json['UploadFile']);
                                            $data_article['category_id'] = 0;


                                            $date_year = date('Y-m-d');
                                            $date_year = strtotime($date_year);
                                            $date_year = strtotime("+10 year", $date_year);
                                            $data_article['start_date'] = date("Y-m-d H:i:s");
                                            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);

                                            //dd($data_article);
                                            Article::create($data_article);
                                            //dd("test2 Sucess");
                                        }
                                        //dd("test2");
                                    }

                                    /*ncds-4 ncds_4_keywords แบบทดสอบทักษะความรอบรู้สุขภาพ*/
                                    if ($check_data_ncds_4 == 1) {

                                        $check_data = Article::select('id')
                                            ->where('dol_UploadFileID', '=', $data_json['UploadFileID'])
                                            ->where('page_layout', '=', 'ncds-4')
                                            ->first();
                                        //dd("Test1");
                                        if (!isset($check_data->id)) {
                                            //dd("Test2");
                                            $data_article = array();
                                            $data_article['page_layout'] = 'ncds-4';
                                            $data_article['title'] = $data_json['UploadFile']['Title'];
                                            $data_article['description'] = $data_json['UploadFile']['Description'];
                                            $data_article['short_description'] = strip_tags($data_json['UploadFile']['Description']);
                                            $data_article['dol_cover_image'] = $data_json['UploadFile']['ThumbnailAddress'];
                                            $data_article['dol_UploadFileID'] = $data_json['UploadFileID'];
                                            $data_article['dol_url'] = $data_json['UploadFile']['FileAddress'];
                                            $data_article['dol_template'] = $data_json['UploadFile']['Template'];
                                            $data_article['dol_json_data'] = json_encode($data_json['UploadFile']);
                                            $data_article['category_id'] = 0;


                                            $date_year = date('Y-m-d');
                                            $date_year = strtotime($date_year);
                                            $date_year = strtotime("+10 year", $date_year);
                                            $data_article['start_date'] = date("Y-m-d H:i:s");
                                            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);

                                            //dd($data_article);
                                            Article::create($data_article);
                                            //dd("test2 Sucess");
                                        }
                                        //dd("test4");
                                    }

                                    /* ncds_5_keywords สื่อและเครื่องมือ */
                                    if ($check_data_ncds_5 == 1) {

                                        $check_data = Article::select('id')
                                            ->where('dol_UploadFileID', '=', $data_json['UploadFileID'])
                                            ->where('page_layout', '=', 'health-literacy')
                                            ->first();
                                        //dd("Test1");
                                        if (!isset($check_data->id)) {

                                            $data_article = array();
                                            $data_article['page_layout'] = 'health-literacy';
                                            $data_article['title'] = $data_json['UploadFile']['Title'];
                                            $data_article['description'] = $data_json['UploadFile']['Description'];
                                            $data_article['short_description'] = strip_tags($data_json['UploadFile']['Description']);
                                            $data_article['dol_cover_image'] = $data_json['UploadFile']['ThumbnailAddress'];
                                            $data_article['dol_UploadFileID'] = $data_json['UploadFileID'];
                                            $data_article['dol_url'] = $data_json['UploadFile']['FileAddress'];
                                            $data_article['dol_template'] = $data_json['UploadFile']['Template'];
                                            $data_article['dol_json_data'] = json_encode($data_json['UploadFile']);
                                            $data_article['category_id'] = 0;

                                            $json_decode  = $data_json['UploadFile'];
                                            //dd("Test2",$json_decode);
                                            foreach ($json_decode['Issues'] as $value_issues) {
                                                //dd($value_issues->ID);
                                                if ($value_issues['ID'] == 5) {
                                                    #แอลกอฮอล์
                                                    $data_article['category_id'] = 5;
                                                }

                                                if ($value_issues['ID'] == 28) {
                                                    #บุหรี่
                                                    $data_article['category_id'] = 6;
                                                }

                                                if ($value_issues['ID'] == 39) {
                                                    #อาหาร
                                                    $data_article['category_id'] = 7;
                                                }

                                                if ($value_issues['ID'] == 18) {
                                                    #กิจกรรมทางกาย
                                                    $data_article['category_id'] = 8;
                                                }

                                                if ($value_issues['ID'] == 41) {
                                                    #อุบัติเหตุ
                                                    $data_article['category_id'] = 9;
                                                }

                                                if ($value_issues['ID'] == 37) {
                                                    #เพศ เช่น ท้องไม่พร้อม
                                                    $data_article['category_id'] = 10;
                                                }

                                                if ($value_issues['ID'] == 34) {
                                                    #สุขภาพจิต
                                                    $data_article['category_id'] = 11;
                                                }
                                                if ($value_issues['ID'] == 35) {
                                                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                    $data_article['category_id'] = 12;
                                                }

                                                if ($value_issues['ID'] == 36) {
                                                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                                                    $data_article['category_id'] = 12;
                                                }

                                                if ($value_issues['ID'] == 27) {
                                                    #สิ่งแวดล้อม
                                                    $data_article['category_id'] = 13;
                                                }

                                                if ($value_issues['ID'] == 33) {
                                                    #สิ่งแวดล้อม
                                                    $data_article['category_id'] = 13;
                                                }

                                                if ($value_issues['ID'] == 49) {
                                                    #สิ่งแวดล้อม
                                                    $data_article['category_id'] = 13;
                                                }

                                                if ($value_issues['ID'] == 16) {
                                                    #อื่นๆ
                                                    $data_article['category_id'] = 14;
                                                }

                                                if ($value_issues['ID'] == 21) {
                                                    #อื่นๆ
                                                    $data_article['category_id'] = 14;
                                                }

                                                if ($value_issues['ID'] == 32) {
                                                    #อื่นๆ
                                                    $data_article['category_id'] = 14;
                                                }

                                                if ($value_issues['ID'] == 42) {
                                                    #อื่นๆ
                                                    $data_article['category_id'] = 14;
                                                }
                                            }


                                            $date_year = date('Y-m-d');
                                            $date_year = strtotime($date_year);
                                            $date_year = strtotime("+10 year", $date_year);
                                            $data_article['start_date'] = date("Y-m-d H:i:s");
                                            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);

                                            //dd($data_article);
                                            Article::create($data_article);
                                            //dd("test2 Sucess");
                                        }
                                        //dd("test5");
                                    }

                                    /*ncds_6_keywords เครื่องมืออื่นๆ ที่น่าสนใจ*/
                                    if ($check_data_ncds_6 == 1) {

                                        $check_data = Article::select('id')
                                            ->where('dol_UploadFileID', '=', $data_json['UploadFileID'])
                                            ->where('page_layout', '=', 'ncds-6')
                                            ->first();

                                        if (!isset($check_data->id)) {
                                            //dd("Test");
                                            $data_article = array();
                                            $data_article['page_layout'] = 'ncds-6';
                                            $data_article['title'] = $data_json['UploadFile']['Title'];
                                            $data_article['description'] = $data_json['UploadFile']['Description'];
                                            $data_article['short_description'] = strip_tags($data_json['UploadFile']['Description']);
                                            $data_article['dol_cover_image'] = $data_json['UploadFile']['ThumbnailAddress'];
                                            $data_article['dol_UploadFileID'] = $data_json['UploadFileID'];
                                            $data_article['dol_url'] = $data_json['UploadFile']['FileAddress'];
                                            $data_article['dol_template'] = $data_json['UploadFile']['Template'];
                                            $data_article['dol_json_data'] = json_encode($data_json['UploadFile']);
                                            $data_article['category_id'] = 0;


                                            $date_year = date('Y-m-d');
                                            $date_year = strtotime($date_year);
                                            $date_year = strtotime("+10 year", $date_year);
                                            $data_article['start_date'] = date("Y-m-d H:i:s");
                                            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);

                                            //dd($data_article);
                                            Article::create($data_article);
                                            //dd("test2 Sucess");
                                        }
                                        //dd("test6");
                                    }

                                    //dd("test6 Sucess");




                                    //dd(count($setting_api_keywords_array),$check_data_api,$data_json['UploadFile'],$setting_api_keywords->value,$setting_api_keywords_array,"Case True1");
                                    // $array = array();
                                    // $array['description'] ='check_data->'.$check_data.'---'.json_encode($data_json['UploadFile']['Keywords']);
                                    // ListMedia::where('UploadFileID','=',$data_json['UploadFileID'])->update($array);

                                } else {

                                    $array = array();
                                    $array['title'] = $data_json['UploadFile']['Title'];
                                    $array['description'] = $data_json['UploadFile']['Description'];
                                    $array['category_id'] = $data_json['UploadFile']['CategoryID'];
                                    $array['province'] = (isset($data_json['UploadFile']['Province']['0']) ? $data_json['UploadFile']['Province']['0'] : '');
                                    $array['template'] = $data_json['UploadFile']['Template'];
                                    $array['area_id'] = $data_json['UploadFile']['AreaID'];
                                    $array['json_data'] = json_encode($data_json['UploadFile']);
                                    //dd($array,isset($data_json['UploadFile']['Province']['0']),$data_json['UploadFile']['Province'],gettype($data_json['UploadFile']['Province']),"Case True2");
                                    ListMedia::where('UploadFileID', '=', $data_json['UploadFileID'])->update($array);
                                }


                                // echo gettype($data_json['UploadFile']['Keywords']);
                                // echo "<pre>";
                                //         print_r($data_json['UploadFile']);
                                // echo "</pre>";
                                //echo "check_data----->".$check_data;
                                //exit();


                            }
                        }

                        $next_page = $task->page_no + 1;
                        $task->update(['page_no' => $next_page, 'note' => 'Page ' . $task->page_no . ' End']);
                        if ($next_page > $task->page_all) {
                            $task->update(['status' => 'end_processes']);
                        }
                    } else {
                        $next_page = $task->page_no + 1;
                        $task->update(['page_no' => $next_page, 'note' => 'Page ' . $task->page_no . ' End']);
                        if ($next_page > $task->page_all) {
                            $task->update(['status' => 'end_processes']);
                        }
                    }
                }

                //$media = ListMedia::select('UploadFileID')->whereRaw('title IS NULL')->limit(1)->get();
                //$media = ListMedia::select('UploadFileID')->whereRaw('title IS NULL')->limit(1)->get();

                //$media = ListMedia::select('id','UploadFileID')->offset($input['offset'])->limit($input['limit'])->get();
                //dd(collect($media)->count());
                //dd($media);

                //dd(collect($media)->count());
                Log::info('End Api Get Media');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                //$response['media'] =$media;
                //$response['input'] =$input;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api Media ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }



    public function postMediaUpdate(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);


            $token = explode(" ", $request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api Media Update');

                $task = ApiLogs::Data(['status' => ['processes'], 'api_type' => 'update_media']);
                if (isset($task->id)) {

                    $offset = 0;
                    if ($task->page_no != '1') {
                        $offset = ($task->page_no * $task->page_size) - $task->page_size;
                    }
                    $media = ListMedia::select('UploadFileID')->whereRaw('title IS NOT NULL')
                        ->where('title', '!=', 'ข้อมูลไม่ผ่านการคัดกรอง')
                        ->offset($offset)
                        ->limit($task->page_size)
                        ->get();
                    if (collect($media)->count()) {

                        $task->update(['note' => 'Page ' . $task->page_no . ' Start']);
                        foreach ($media as $key => $value) {
                            //dd($value->id);
                            $body = '{"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '","UploadFileID":"' . $value->UploadFileID . '"}';
                            //$body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","UploadFileID":"e63e4091-929a-e611-80db-00155d3d0608"}';
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('URL_GET_MEDIA'), ['body' => $body]);
                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ", "", substr($response_api, 3));
                            $data_json = json_decode($response_api, true);


                            if (gettype($data_json) == 'array' && $data_json['Success'] == 'true') {

                                //dd($data_json['UploadFile']);
                                $array = array();
                                $array['json_data'] = json_encode($data_json['UploadFile']);
                                //dd($array,isset($data_json['UploadFile']['Province']['0']),$data_json['UploadFile']['Province'],gettype($data_json['UploadFile']['Province']));
                                ListMedia::where('UploadFileID', '=', $data_json['UploadFileID'])->update($array);
                            }
                        }

                        $next_page = $task->page_no + 1;
                        $task->update(['page_no' => $next_page, 'note' => 'Page ' . $task->page_no . ' End']);
                        if ($next_page > $task->page_all) {
                            $task->update(['status' => 'end_processes']);
                        }
                    } else {
                        $next_page = $task->page_no + 1;
                        $task->update(['page_no' => $next_page, 'note' => 'Page ' . $task->page_no . ' End']);
                        if ($next_page > $task->page_all) {
                            $task->update(['status' => 'end_processes']);
                        }
                    }
                }

                Log::info('End Api Media Update');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                //$response['media'] =$media;
                //$response['input'] =$input;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api Media Update ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }



    public function postTaskMedia(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);


            $token = explode(" ", $request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api Task Media');

                $task = ApiLogs::Data(['status' => ['processes'], 'api_type' => 'get_media']);
                if (!isset($task->id)) {

                    $media = ListMedia::selectRaw('COUNT(id) AS count')->whereRaw('title IS NULL')->first();
                    if (isset($media->count) && $media->count > 0) {
                        $data = array();
                        $data['api_name'] = 'Get Media ';
                        $data['status'] = 'processes';
                        $data['total'] = $media->count;
                        $data['page_size'] = 2000;
                        $data['page_no'] = 1;
                        $data['page_all'] = ceil($media->count / 2000);
                        $data['params'] = '';
                        $data['api_type'] = 'get_media';
                        ApiLogs::create($data);
                    }
                }

                Log::info('End Api Task Media');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api Task Media ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }


    public function postTaskMediaUpdate(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);


            $token = explode(" ", $request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api Task Media Update');

                $task = ApiLogs::Data(['status' => ['processes'], 'api_type' => 'update_media']);
                if (!isset($task->id)) {

                    $media = ListMedia::selectRaw('COUNT(id) AS count')->whereRaw('title IS NOT NULL')->first();
                    if (isset($media->count) && $media->count > 0) {
                        $data = array();
                        $data['api_name'] = 'Media Update';
                        $data['status'] = 'processes';
                        $data['total'] = $media->count;
                        $data['page_size'] = 2000;
                        $data['page_no'] = 1;
                        $data['page_all'] = ceil($media->count / 2000);
                        $data['params'] = '';
                        $data['api_type'] = 'update_media';
                        ApiLogs::create($data);
                    }
                }

                Log::info('End Api Task Media Update');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api Task Media Update ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }



    public function postTaskMediaAttribute(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);


            $token = explode(" ", $request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api Task MediaAttribute');

                $task = ApiLogs::Data(['status' => ['processes'], 'api_type' => 'get_media_attribute']);
                if (!isset($task->id)) {

                    $media = ListMedia::selectRaw('COUNT(id) AS count')->whereRaw('title IS NOT NULL')->first();
                    if (isset($media->count) && $media->count > 0) {
                        $data = array();
                        $data['api_name'] = 'Get Media Attribute';
                        $data['status'] = 'processes';
                        $data['total'] = $media->count;
                        $data['page_size'] = 5000;
                        $data['page_no'] = 1;
                        $data['page_all'] = ceil($media->count / 5000);
                        $data['params'] = '';
                        $data['api_type'] = 'get_media_attribute';
                        ApiLogs::create($data);
                    }
                }

                Log::info('End Api Task Media Attribute');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api Media Attribute ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }




    public function postMediaAttribute(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);


            $token = explode(" ", $request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api Get Media Attribute');

                $task = ApiLogs::Data(['status' => ['processes'], 'api_type' => 'get_media_attribute']);
                if (isset($task->id)) {
                    //$task->page_size
                    $offset = 0;
                    if ($task->page_no != '1') {
                        $offset = ($task->page_no * $task->page_size) - $task->page_size;
                    }
                    $media = ListMedia::select('id', 'json_data')->whereRaw('title IS NOT NULL')
                        ->offset($offset)
                        ->limit($task->page_size)
                        ->get();

                    if (collect($media)->count()) {
                        $task->update(['note' => 'Page ' . $task->page_no . ' Start']);

                        foreach ($media as $key => $value) {
                            //dd($value->id);
                            $json = ($value->json_data != '' ? json_decode($value->json_data) : '');
                            ListMediaIssues::where('media_id', '=', $value->id)->delete();
                            ListMediaKeywords::where('media_id', '=', $value->id)->delete();
                            ListMediaTargets::where('media_id', '=', $value->id)->delete();

                            if (gettype($json->Issues) == 'array') {
                                foreach ($json->Issues as $key_issues => $value_issues) {
                                    $array_issues = array();
                                    $array_issues['media_id'] = $value->id;
                                    $array_issues['issues_id'] = $value_issues->ID;
                                    ListMediaIssues::create($array_issues);
                                }
                            }

                            if (gettype($json->Keywords) == 'array') {
                                foreach ($json->Keywords as $key_keywords => $value_keywords) {
                                    //dd($value_keywords);
                                    $array_keywords = array();
                                    $array_keywords['media_id'] = $value->id;
                                    $array_keywords['keyword'] = $value_keywords;
                                    ListMediaKeywords::create($array_keywords);
                                }
                            }

                            if (gettype($json->Targets)  == 'array') {
                                foreach ($json->Targets as $key_target => $value_target) {
                                    $array_target = array();
                                    $array_target['media_id'] = $value->id;
                                    $array_target['target_id'] = $value_target->ID;
                                    ListMediaTargets::create($array_target);
                                }
                            }
                        }

                        $next_page = $task->page_no + 1;
                        $task->update(['page_no' => $next_page, 'note' => 'Page ' . $task->page_no . ' End']);
                        if ($next_page > $task->page_all) {
                            $task->update(['status' => 'end_processes']);
                        }
                    } else {
                        $next_page = $task->page_no + 1;
                        $task->update(['page_no' => $next_page, 'note' => 'Page ' . $task->page_no . ' End']);
                        if ($next_page > $task->page_all) {
                            $task->update(['status' => 'end_processes']);
                        }
                    }
                }


                Log::info('End Api Get Media Attribute');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                //$response['media'] =$media;
                //$response['task'] =$task;
                //$response['offset'] =$offset;
                //$response['json_data'] =$array_target;
                //$response['input'] =$input;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api Get Media Attribute---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }








    // ListMediaIssues::where('media_id','=',$value->id)->delete();
    // ListMediaKeywords::where('media_id','=',$value->id)->delete();
    // ListMediaTargets::where('media_id','=',$value->id)->delete();


    // if(gettype($data_json['UploadFile']['Issues'] =='array')){

    //     foreach ($data_json['UploadFile']['Issues'] as $key_issues => $value_issues){
    //         $array_issues = array();
    //         $array_issues['media_id'] = $value->id;
    //         $array_issues['issues_id'] = $value_issues['ID'];
    //         ListMediaIssues::create($array_issues);
    //     }

    // }

    // if(gettype($data_json['UploadFile']['Targets'] =='array')){

    //     foreach ($data_json['UploadFile']['Targets'] as $key_target => $value_target){
    //         $array_target = array();
    //         $array_target['media_id'] = $value->id;
    //         $array_target['target_id'] = $value_target['ID'];
    //         ListMediaTargets::create($array_target);
    //     }

    // }

    // if(gettype($data_json['UploadFile']['Keywords'] =='array')){

    //     foreach ($data_json['UploadFile']['Keywords'] as $key_keywords => $value_keywords){
    //         //dd($value_keywords);
    //         $array_keywords = array();
    //         $array_keywords['media_id'] = $value->id;
    //         $array_keywords['keyword'] = $value_keywords;
    //         ListMediaKeywords::create($array_keywords);
    //     }

    // }



    public function postListCategory(Request $request)
    {

        try {
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ", $request->header('Authorization'));
            $response = array();

            if (Hash::check(env('SECRET'), $token['1'])) {

                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api List Category');
                $input = $request->all();

                $body = '{"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_CATEGORY'), ['body' => $body]);
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ", "", substr($response_api, 3));
                $data_json = json_decode($response_api, true);

                //dd($data_json['Categories']);

                if (gettype($data_json) == 'array') {

                    foreach ($data_json['Categories'] as $key => $value) {
                        //dd($value);

                        $rules = ['category_id' => 'required|unique:list_category,category_id'];
                        $data = ['category_id' => $value['ID']];
                        $validator = Validator::make($data, $rules);
                        if ($validator->passes()) {
                            $array = array();
                            $array['category_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListCategory::create($array);
                        } else {
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListCategory::where('category_id', '=', $value['ID'])->update($array);
                        }
                    }
                }
                Log::info('End Api List Category');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api List Category ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }

    public function postListIssue(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ", $request->header('Authorization'));
            $response = array();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api List Issue');
                $input = $request->all();

                $body = '{"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_ISSUE'), ['body' => $body]);
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ", "", substr($response_api, 3));
                $data_json = json_decode($response_api, true);

                //dd($data_json['Issues']);

                if (gettype($data_json) == 'array') {

                    foreach ($data_json['Issues'] as $key => $value) {
                        //dd($value);

                        $rules = ['issues_id' => 'required|unique:list_issue,issues_id'];
                        $data = ['issues_id' => $value['ID']];
                        $validator = Validator::make($data, $rules);
                        if ($validator->passes()) {
                            $array = array();
                            $array['issues_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListIssue::create($array);
                        } else {
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListIssue::where('issues_id', '=', $value['ID'])->update($array);
                        }
                    }
                }
                Log::info('End Api List Issue');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api List Issue ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }



    public function postListTarget(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ", $request->header('Authorization'));
            $response = array();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api List Target');
                $input = $request->all();

                $body = '{"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_TARGET'), ['body' => $body]);
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ", "", substr($response_api, 3));
                $data_json = json_decode($response_api, true);

                //dd($data_json);

                if (gettype($data_json) == 'array') {

                    foreach ($data_json['Targets'] as $key => $value) {
                        //dd($value);

                        $rules = ['target_id' => 'required|unique:list_target,target_id'];
                        $data = ['target_id' => $value['ID']];
                        $validator = Validator::make($data, $rules);
                        if ($validator->passes()) {
                            $array = array();
                            $array['target_id'] = $value['ID'];
                            $array['TargetGuoupID'] = $value['TargetGuoupID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListTarget::create($array);
                        } else {
                            $array = array();
                            $array['name'] = $value['Name'];
                            $array['TargetGuoupID'] = $value['TargetGuoupID'];
                            ListTarget::where('target_id', '=', $value['ID'])->update($array);
                        }
                    }
                }
                Log::info('Start Api List Target');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api List Target ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }

    public function postListSetting(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ", $request->header('Authorization'));
            $response = array();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api List Setting');
                $input = $request->all();

                $body = '{"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_SETTING'), ['body' => $body]);
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ", "", substr($response_api, 3));
                $data_json = json_decode($response_api, true);

                //dd($data_json);

                if (gettype($data_json) == 'array') {

                    foreach ($data_json['Settings'] as $key => $value) {
                        //dd($value);

                        $rules = ['setting_id' => 'required|unique:list_setting,setting_id'];
                        $data = ['setting_id' => $value['ID']];
                        $validator = Validator::make($data, $rules);
                        if ($validator->passes()) {
                            $array = array();
                            $array['setting_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListSetting::create($array);
                        } else {
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListSetting::where('setting_id', '=', $value['ID'])->update($array);
                        }
                    }
                }
                Log::info('End Api List Setting');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api List Setting ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }


    public function postListArea(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ", $request->header('Authorization'));
            $response = array();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api List Area');
                $input = $request->all();

                $body = '{"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_AREA'), ['body' => $body]);
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ", "", substr($response_api, 3));
                $data_json = json_decode($response_api, true);

                //dd($data_json);

                if (gettype($data_json) == 'array') {

                    foreach ($data_json['Areas'] as $key => $value) {
                        //dd($value);

                        $rules = ['area_id' => 'required|unique:list_area,area_id'];
                        $data = ['area_id' => $value['ID']];
                        $validator = Validator::make($data, $rules);
                        if ($validator->passes()) {
                            $array = array();
                            $array['area_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListArea::create($array);
                        } else {
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListArea::where('area_id', '=', $value['ID'])->update($array);
                        }
                    }
                }
                Log::info('End Api List Area');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api List Area ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }


    public function postListProvince(Request $request)
    {

        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ", $request->header('Authorization'));
            $response = array();

            if (Hash::check(env('SECRET'), $token['1'])) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api List Province');
                $input = $request->all();
                $body = '{"UserName":"' . env('API_USER') . '","Password":"' . env('API_PASSWORD') . '"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_PROVINCE'), ['body' => $body]);
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ", "", substr($response_api, 3));
                $data_json = json_decode($response_api, true);

                //dd($data_json);

                if (gettype($data_json) == 'array') {

                    foreach ($data_json['Provinces'] as $key => $value) {
                        //dd($value);

                        $rules = ['province_id' => 'required|unique:list_province,province_id'];
                        $data = ['province_id' => $value['ID']];
                        $validator = Validator::make($data, $rules);
                        if ($validator->passes()) {
                            $array = array();
                            $array['province_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListProvince::create($array);
                        } else {
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListProvince::where('province_id', '=', $value['ID'])->update($array);
                        }
                    }
                }
                Log::info('End Api List Province');
                $response['msg'] = '200 OK';
                $response['status'] = true;
                return  Response::json($response, 200);
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api List Province ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }





    public function postGenerateKey(Request $request)
    {

        try {

            $token = explode(" ", $request->header('Authorization'));
            $response = array();
            if (explode(":", env('APP_KEY'))['1'] == $token['1']) {
                Log::useDailyFiles(storage_path() . '/logs/api.log');
                Log::info('Start Api GenerateKey');
                $input = $request->all();
                if (isset($input['secret'])) {
                    Log::info('End Api GenerateKey');
                    $response['msg'] = '200 OK';
                    $response['status'] = true;
                    $response['data'] = bcrypt($input['secret']);
                    return  Response::json($response, 200);
                } else {
                    $response['msg'] = '404 Page Not Found';
                    $response['status'] = false;
                    return  Response::json($response, 404);
                }
            } else {
                $response['msg'] = '401 (Unauthorized)';
                $response['status'] = false;
                return  Response::json($response, 401);
            }
        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path() . '/logs/api-errors.log');
            Log::error('Api List GenerateKey ---> ' . $e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] = false;
            return  Response::json($response, 500);
        }
    }


    public function getTest()
    {

        dd("Test");

        $data = Article::all();
        //dd($data);
        foreach ($data as $key => $value) {
            //dd($value);
            $array = [];
            $array['article_id'] = $value->id;
            $array['UploadFileID'] = $value->dol_UploadFileID;
            $array['json_data'] = $value->dol_json_data;
            $array['title'] = $value->title;
            $array['description'] = $value->short_description;
            $array['featured'] = $value->featured;
            $array['status'] = $value->status;
            $array['created_at'] = $value->created_at;
            $array['updated_at'] = $value->updated_at;
            $array['created_by'] = $value->created_by;
            $array['updated_by'] = $value->updated_by;
            $array['knowledges'] = 1;
            $array['media_campaign'] = 1;
            $array['hit'] = $value->hit;
            $array['article_type'] = 'article';
            $array['slug'] = $value->slug;

            //dd($array);
            if ($value->dol_UploadFileID != '') {
                $check  = ListArticle::select('id')->where('UploadFileID', '=', $value->dol_UploadFileID)->first();
                //dd(isset($check->id));
                if (isset($check->id) === false) {
                    //dd($check);
                    ListArticle::create($array);
                }
            } else {
                ListArticle::create($array);
            }

            //dd("Success");
        }

        dd("Insert Success");






        //    Log::useDailyFiles(storage_path().'/logs/api.log');
        //    Log::info('Showing user profile for user:');



        // $body = '{"PageNo":1,"PageSize":10,"UserName":"thrc-uat","Password":"-8hKazfcwxG-WqA@7WH/MRxatyrdcCS3qt^DrTE-TA","DepartmentID":"10"}';
        // $client = new \GuzzleHttp\Client();
        // $request = $client->request('POST', env('URL_LIST_MEDIA'), ['body' => $body]);    
        // $response_api = $request->getBody()->getContents();
        // $response_api = str_replace(" ","",substr($response_api,3));
        // $data_json = json_decode($response_api, true);
        // echo "<pre>";
        //     print_r($response_api);
        // echo "</pre>";

        // foreach($data_json AS $key=>$value){
        //  dd($value,$key);
        // }
        // dd($data_json['Files']);

        //echo gettype(json_decode($response_api));
        //var_dump(json_decode($response_api, true));

        // $rules = ['id'=>'required'];
        // $data = ['id'=>'fsdfdsfad'];


        // $messages = [
        //     'required' => 'The :attribute field is required.',
        // ];
        // $validator = Validator::make($data, $rules);

        // if($validator->passes()){

        //     echo "True";
        // }else{

        //     echo "False";

        // }
        // dd($validator);

        echo json_last_error();
        echo '<br />';
        echo JSON_ERROR_NONE . ' JSON_ERROR_NONE' . '<br />';
        echo JSON_ERROR_DEPTH . ' JSON_ERROR_DEPTH' . '<br />';
        echo JSON_ERROR_STATE_MISMATCH . ' JSON_ERROR_STATE_MISMATCH' . '<br />';
        echo JSON_ERROR_CTRL_CHAR . ' JSON_ERROR_CTRL_CHAR' . '<br />';
        echo JSON_ERROR_SYNTAX . ' JSON_ERROR_SYNTAX' . '<br />';
        echo JSON_ERROR_UTF8 . ' JSON_ERROR_UTF8' . '<br />';
        echo JSON_ERROR_RECURSION . ' JSON_ERROR_RECURSION' . '<br />';
        echo JSON_ERROR_INF_OR_NAN . ' JSON_ERROR_INF_OR_NAN' . '<br />';
        echo JSON_ERROR_UNSUPPORTED_TYPE . ' JSON_ERROR_UNSUPPORTED_TYPE' . '<br />';
        //dd($result);
    }


    public function getImport()
    {
        dd("Import7");
        $data = DB::table('import7')
            //->select('*')
            //->limit(1)
            ->get();
        //dd("Import7",$data);
        $i = 0;
        foreach ($data as $key => $value) {
            $i++;
            //dd($value);
            $id_ex = explode("/", $value->link);
            $id = base64_decode($id_ex[4]);
            $list_media = ListMedia::where('id', '=', $id)
                ->where('json_data', '!=', '')
                ->where('json_data', '!=', 'null')
                ->first();
            if (isset($list_media->id)) {
                ListMedia::where('id', '=', $list_media->id)->update(['status' => 'publish', 'api' => 'publish', 'web_view' => 1]);
                //dd($list_media->id);
                $json_data = json_decode($list_media->json_data);
                if ($value->template != '') {

                    $template = '';
                    $str = $value->template;
                    $str = preg_replace("/[^A-Za-z0-9.!? ]/", "", $str);
                    $str = preg_replace("/[^A-Za-z0-9.!?\s]/", "", $str);
                    $str = preg_replace("/[^A-Za-z0-9.!?[:space:]]/", "", $str);
                    $str = strtolower($str);
                    $value->template = explode(" ", $str)[0];
                    //dd($value->template);         
                    if ($value->template == 'multimedia') {
                        $template = 'Multimedia';
                    }
                    if ($value->template == 'visual') {
                        $template = 'Visual';
                    }
                    if ($value->template == 'text') {
                        $template = 'Text';
                    }
                    $json_data->Template = $template;
                    ListMedia::where('id', '=', $list_media->id)->update(['template' => $template, 'json_data' => json_encode($json_data)]);

                    //echo $value->template." <---id=".$value->no;
                    //echo "<br>";
                    //ListMedia::where('id','=',$list_media->id)->update([]);

                }

                if ($value->issue != '') {
                    $issues_data = ListIssue::select('issues_id', 'name')
                        ->whereRaw('name like "%' . $value->issue . '%"')->first();
                    $array = [];
                    if (isset($issues_data->issues_id)) {
                        $object = new \Stdclass;
                        $object->ID = $issues_data->issues_id;
                        $object->Name = $issues_data->name;
                        array_push($array, $object);
                        $value->issue  = $array;
                    } else {
                        $data_issues = [];
                        $data_issues['name'] = $value->issue;
                        //$data_issues['issues_id'] = 'ncds-1';
                        $data_issues['status'] = 'publish';
                        $data_issues['parent_id'] = 0;
                        $data_issues['order'] = 0;
                        $issues_id = ListIssue::create($data_issues);
                        //echo var_export($data['issues_id'], true) . " is NOT numeric", PHP_EOL;
                        // $data['issues_id'] = $issues_id->id;
                        ListIssue::where('id', '=', $issues_id->id)->update(['issues_id' => $issues_id->id]);
                        $object = new \Stdclass;
                        $object->ID = $issues_id->id;
                        $object->Name = $value->issue;
                        array_push($array, $object);
                        $value->issue  = $array;
                    }
                    $json_data->Issues = $value->issue;
                    //dd($json_data);
                    ListMedia::where('id', '=', $list_media->id)->update(['json_data' => json_encode($json_data)]);
                }
                //print_r($value->issue);
                //echo " <---id=".$value->no;
                //echo "<br>";

                if ($value->age != '') {
                    $age_ex = explode(" ", $value->age);
                    //dd($value->age,$age_ex);
                    $value->age = [(int)$age_ex[1]];
                    ListMedia::where('id', '=', $list_media->id)->update(['age' => json_encode($value->age)]);
                    //dd($value->age);
                }
                // echo $value->age." <---id=".$value->no;
                // echo "<br>";

                if ($value->target != '') {
                    $target_ex = explode('(', $value->target);
                    // print_r($target_ex);
                    // echo " <---id=".$value->no;
                    // echo "<br>";

                    if (count($target_ex) > 2) {
                        //dd($target_ex);
                        $array = [];
                        $target_data = ListTarget::select('target_id', 'name')
                            ->whereRaw('name like "%' . str_replace(" ", "", $target_ex[0]) . '%"')->first();
                        if (isset($target_data->target_id)) {
                            $object = new \Stdclass;
                            $object->ID = $target_data->target_id;
                            $object->Name = $target_data->name;
                            array_push($array, $object);
                            //dd($target_data,$value->target);
                        }
                        $target2 = explode(",", $target_ex[1]);
                        $target_data = ListTarget::select('target_id', 'name')
                            ->whereRaw('name like "%' . str_replace(" ", "", $target2[1]) . '%"')->first();
                        if (isset($target_data->target_id)) {
                            $object = new \Stdclass;
                            $object->ID = $target_data->target_id;
                            $object->Name = $target_data->name;
                            array_push($array, $object);
                            //dd($target_data,$value->target);
                        }
                        $value->target = $array;
                        //dd($value->target);
                    } else {
                        if ($target_ex[0] == 'ทุกช่วงวัย') {
                            $array = [];
                            $object = new \Stdclass;
                            $object->ID = 13;
                            $object->Name = 'ปฐมวัย(0–5ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 24;
                            $object->Name = 'วัยเรียน(6–12ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 26;
                            $object->Name = 'วัยรุ่น(13–15ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 4;
                            $object->Name = 'เยาวชน(15-20 ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 25;
                            $object->Name = 'วัยทำงาน(21-59 ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 19;
                            $object->Name = 'ผู้สูงอายุ(60ปีขึ้นไป)';
                            array_push($array, $object);
                            $value->target  = $array;
                            //dd($value->target);
                        } else {
                            $array = [];
                            $target_data = ListTarget::select('target_id', 'name')
                                ->whereRaw('name like "%' . str_replace(" ", "", $target_ex[0]) . '%"')->first();

                            if (isset($target_data->target_id)) {
                                $object = new \Stdclass;
                                $object->ID = $target_data->target_id;
                                $object->Name = $target_data->name;
                                array_push($array, $object);
                                $value->target  = $array;
                                //dd($target_data,$value->target);
                            }
                        }
                    }
                    //dd($value->target);
                    $json_data->Targets = $value->target;
                    //dd($json_data);
                    ListMedia::where('id', '=', $list_media->id)->update(['json_data' => json_encode($json_data)]);
                    //ปฐมวัย(0–5ปี) = 13
                    //วัยเรียน(6–12ปี) = 24
                    //วัยรุ่น(13–15ปี) = 26
                    //เยาวชน(15-20 ปี) = 4
                    //วัยทำงาน(21-59 ปี) = 25
                    //ผู้สูงอายุ(60ปีขึ้นไป) = 19
                    // print_r($value->target);
                    // echo " <---id=".$value->no;
                    // echo "<br>";
                }

                if ($value->sex != '') {
                    $sex_ex = explode(" ", $value->sex);
                    //dd($value->age,$sex_ex);
                    $value->sex = $sex_ex[0];

                    if ($value->sex == 'ทุกเพศ') {
                        $value->sex = [1, 2, 3];
                    }

                    if ($value->sex == 'ผู้หญิง') {
                        $value->sex = [2];
                    }

                    if ($value->sex == 'ผู้ชาย') {
                        $value->sex = [1];
                    }
                    //dd($value->sex);
                    ListMedia::where('id', '=', $list_media->id)->update(['sex' => json_encode($value->sex)]);
                    // print_r($value->sex);
                    // echo " <---id=".$value->no;
                    // echo "<br>";

                }
                dd($json_data);
                if ($value->keyword != '') {
                    $keyword_ex = explode(",", $value->keyword);
                    $keyword_ex2 = explode("/", $value->keyword);

                    if (count($keyword_ex2) > 1) {
                        //dd(count($keyword_ex2),$keyword_ex2,$value->keyword);
                        // print_r($keyword_ex2);
                        // echo " <---id=".$value->no;
                        // echo "<br>";
                        $array = [];
                        foreach ($keyword_ex2 as $key => $value_keyword) {
                            //dd($value);
                            array_push($array, str_replace(" ", "", $value_keyword));
                        }
                        $value->keyword = $array;
                    } else {
                        // print_r($keyword_ex);
                        // echo " <---id=".$value->no;
                        // echo "<br>";
                        $array = [];
                        foreach ($keyword_ex as $key => $value_keyword) {
                            //dd($value);
                            array_push($array, str_replace(" ", "", $value_keyword));
                        }
                        //dd(json_encode($array),$value->keyword);
                        $value->keyword = $array;
                    }
                    //dd($value->keyword);
                    $json_data->Keywords = $value->keyword;
                    //dd($json_data);
                    ListMedia::where('id', '=', $list_media->id)->update(['json_data' => json_encode($json_data)]);
                    //dd($value->age,$keyword_ex);
                    // print_r($value->keyword);
                    // echo " <---id=".$value->no;
                    // echo "<br>";
                }
                //dd($issues_data,$value->issue);
                //dd($json_data,$list_media->id);
            }
            //dd($value,$id_ex,$id,$list_media,$json_data);
        }
        dd("Success", $i);
    }


    public function getImport202()
    {
        dd("getImport202");
        $data = DB::table('import202')
            //->select('*')
            //->limit(1)
            ->get();
        //dd("getImport202",$data);
        $i = 0;
        foreach ($data as $key => $value) {
            $i++;
            //dd($value);
            $id_ex = explode("/", $value->link);
            //dd($id_ex);
            if ($id_ex[3] == 'media2') {
                $id = base64_decode($id_ex[4]);
                $list_media = ListMedia::where('id', '=', $id)
                    //->where('json_data','!=','')
                    //->where('json_data','!=','null')
                    ->first();
                if (isset($list_media->id)) {
                    //dd($list_media,"Case 1");
                    echo "Case 1";
                    echo "<br>";
                    $data_insert = [];
                    $data_insert['title'] = $list_media->title;
                    $data_insert['UploadFileID'] = $list_media->UploadFileID;
                    DB::table('list_media_202')->insert($data_insert);
                } else {

                    echo "Case 2";
                    echo "<br>";
                    $data_insert = [];
                    $data_insert['title'] = $value->title;
                    //$data_insert['issues_id'] = 'ncds-1';
                    DB::table('list_media_202')->insert($data_insert);
                }
            } else {

                $id = Hashids::decode($id_ex[4]);
                // echo "Case 1.1";
                // print_r($value->title);
                // print_r($id);
                // echo "<br>";
                //dd($value,$id_ex[4],$id[0],"Case 2");
                $list_media = ListMedia::where('id', '=', $id[0])
                    //->where('json_data','!=','')
                    //->where('json_data','!=','null')
                    ->first();
                if (isset($list_media->id)) {
                    echo "Case 1.1";
                    echo "<br>";
                    $data_insert = [];
                    $data_insert['title'] = $list_media->title;
                    $data_insert['UploadFileID'] = $list_media->UploadFileID;
                    DB::table('list_media_202')->insert($data_insert);
                } else {

                    echo "Case 2.2";
                    echo "<br>";
                    $data_insert = [];
                    $data_insert['title'] = $value->title;
                    //$data_insert['issues_id'] = 'ncds-1';
                    DB::table('list_media_202')->insert($data_insert);
                }
            }

            //dd($value,$id_ex,$id,$list_media,$json_data);
        }
        dd("Success", $i);
    }


    public function getMedia202()
    {
        dd("Get Media 202");

        try {

            $data = DB::table('list_media_202')
                //->select('*')
                //->limit(2)
                ->where('status', '=', 'publish')
                ->where('json_data', '=', NULL)
                ->get();
            //dd($data);
            foreach ($data as $key => $value) {
                //dd($value);

                $body = '{"UserName":"' . env('API_USER', 'thrc-pro') . '","Password":"' . env('API_PASSWORD', 'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3') . '","UploadFileID":"' . $value->UploadFileID . '"}';

                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_GET_MEDIA', 'http://dol.thaihealth.or.th/WCF/DOLService.svc/json/GetMediaDol'), ['body' => $body]);
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ", "", substr($response_api, 3));
                $data_json = json_decode($response_api, true);

                if (gettype($data_json) == 'array' && $data_json['Success'] == 'true') {

                    $array = array();
                    $array['title'] = $data_json['UploadFile']['Title'];
                    $array['description'] = $data_json['UploadFile']['Description'];
                    $array['category_id'] = $data_json['UploadFile']['CategoryID'];
                    $array['province'] = (isset($data_json['UploadFile']['Province']['0']) ? $data_json['UploadFile']['Province']['0'] : '');
                    $array['template'] = $data_json['UploadFile']['Template'];
                    $array['area_id'] = $data_json['UploadFile']['AreaID'];
                    $array['json_data'] = json_encode($data_json['UploadFile']);
                    //ListMedia::where('UploadFileID','=',$data_json['UploadFileID'])->update($array);
                    DB::table('list_media_202')->where('UploadFileID', '=', $data_json['UploadFileID'])->update($array);
                    //dd($value);
                }
            }
            dd("Update Success");
        } catch (\Throwable $e) {
            dd($e->getMessage(), $body);
        }
    }

    public function getUpdateMedia202()
    {
        dd("Get Update Media 202");

        try {

            $data = DB::table('list_article_api_backup_27_4_2022')
                //->select('*')
                //->limit(2)
                ->where('status', '=', 'publish')
                ->get();
            $i = 1;
            foreach ($data as $key => $value) {
                //dd($value);
                $list_media = ListMedia::where('UploadFileID', '=', $value->UploadFileID)
                    ->first();
                //dd($list_media,$value);
                if (isset($list_media->id)) {
                    $media_json_data = json_decode($list_media->json_data);
                    $media_json_data_new = new \stdClass();
                    $media_json_data_new->Issues = (isset($media_json_data->Issues) ? $media_json_data->Issues : '');
                    $media_json_data_new->Targets = (isset($media_json_data->Targets) ? $media_json_data->Targets : '');
                    $media_json_data_new->Settings = (isset($media_json_data->Settings) ? $media_json_data->Settings : '');
                    $media_json_data_new->Keywords = (isset($media_json_data->Keywords) ? $media_json_data->Keywords : '');
                    $media_json_data_new->ThumbnailAddress = (isset($media_json_data->ThumbnailAddress) ? $media_json_data->ThumbnailAddress : '');
                    $media_json_data_new->UploadFileID = (isset($media_json_data->UploadFileID) ? $media_json_data->UploadFileID : '');
                    //dd($media_json_data_new);        
                    //ListMedia::where('id','=',$list_media->id)->update(['json_data'=>$media_json_data_new]); 
                    DB::table('list_article_api_backup_27_4_2022')
                        ->where('UploadFileID', '=', $value->UploadFileID)
                        //->where('article_type','=','media')
                        ->update(['json_data' => json_encode($media_json_data_new)]);
                    //dd("success");
                    $i++;
                }
            }

            dd("Update Success", $i);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }


    public function getImport2()
    {
        dd("getImport2");
        $data = DB::table('import202')
            //->select('*')
            ->limit(1)
            ->get();
        dd("getImport2", $data);
        $i = 0;
        foreach ($data as $key => $value) {

            //dd($value);

            $id_ex = explode("/", $value->link);
            if ($id_ex[3] == 'media2') {
                $id = base64_decode($id_ex[4]);
                $list_media = ListMedia::where('id', '=', $id)
                    ->where('json_data', '!=', '')
                    ->where('json_data', '!=', 'null')
                    ->first();
            } else {
                $id = Hashids::decode($id_ex[4]);
                $list_media = ListMedia::where('id', '=', $id[0])
                    ->where('json_data', '!=', '')
                    ->where('json_data', '!=', 'null')
                    ->first();
            }

            if (isset($list_media->id)) {
                $i++;
                ListMedia::where('id', '=', $list_media->id)->update(['status' => 'publish', 'api' => 'publish', 'web_view' => 1]);
                //dd($list_media->id);
                $json_data = json_decode($list_media->json_data);


                if ($value->target != '') {
                    $target_ex = explode('(', $value->target);
                    // print_r($target_ex);
                    // echo " <---id=".$value->no;
                    // echo "<br>";

                    if (count($target_ex) > 2) {
                        //dd($target_ex);
                        $array = [];
                        $target_data = ListTarget::select('target_id', 'name')
                            ->whereRaw('name like "%' . str_replace(" ", "", $target_ex[0]) . '%"')->first();
                        if (isset($target_data->target_id)) {
                            array_push($array, (int)$target_data->target_id);
                            //dd($target_data,$value->target);
                        }
                        $target2 = explode(",", $target_ex[1]);
                        $target_data = ListTarget::select('target_id', 'name')
                            ->whereRaw('name like "%' . str_replace(" ", "", $target2[1]) . '%"')->first();
                        if (isset($target_data->target_id)) {
                            array_push($array, (int)$target_data->target_id);
                            //dd($target_data,$value->target);
                        }
                        ListMedia::where('id', '=', $list_media->id)->update(['age' => json_encode($array)]);
                        //dd($value->target);
                    } else {
                        if ($target_ex[0] == 'ทุกช่วงวัย') {
                            $array = [];
                            $object = new \Stdclass;
                            $object->ID = 13;
                            $object->Name = 'ปฐมวัย(0–5ปี)';
                            array_push($array, 13);
                            $object = new \Stdclass;
                            $object->ID = 24;
                            $object->Name = 'วัยเรียน(6–12ปี)';
                            array_push($array, 24);
                            $object = new \Stdclass;
                            $object->ID = 26;
                            $object->Name = 'วัยรุ่น(13–15ปี)';
                            array_push($array, 26);
                            $object = new \Stdclass;
                            $object->ID = 4;
                            $object->Name = 'เยาวชน(15-20 ปี)';
                            array_push($array, 4);
                            $object = new \Stdclass;
                            $object->ID = 25;
                            $object->Name = 'วัยทำงาน(21-59 ปี)';
                            array_push($array, 25);
                            $object = new \Stdclass;
                            $object->ID = 19;
                            $object->Name = 'ผู้สูงอายุ(60ปีขึ้นไป)';
                            array_push($array, 19);
                            ListMedia::where('id', '=', $list_media->id)->update(['age' => json_encode($array)]);
                        } else {
                            $array = [];
                            $target_data = ListTarget::select('target_id', 'name')
                                ->whereRaw('name like "%' . str_replace(" ", "", $target_ex[0]) . '%"')->first();

                            if (isset($target_data->target_id)) {
                                $object = new \Stdclass;
                                $object->ID = $target_data->target_id;
                                $object->Name = $target_data->name;
                                array_push($array, (int)$target_data->target_id);
                                //dd($target_data,$value->target);
                                ListMedia::where('id', '=', $list_media->id)->update(['age' => json_encode($array)]);
                            }
                        }
                    }

                    //ปฐมวัย(0–5ปี) = 13
                    //วัยเรียน(6–12ปี) = 24
                    //วัยรุ่น(13–15ปี) = 26
                    //เยาวชน(15-20 ปี) = 4
                    //วัยทำงาน(21-59 ปี) = 25
                    //ผู้สูงอายุ(60ปีขึ้นไป) = 19
                    // print_r($value->target);
                    // echo " <---id=".$value->no;
                    // echo "<br>";
                }

                // if($value->sex !=''){
                //     $sex_ex = explode(" ",$value->sex);
                //     //dd($value->age,$sex_ex);
                //     $value->sex = $sex_ex[0];

                //     if($value->sex =='ทุกเพศ'){
                //         $value->sex = [1,2,3];
                //     }

                //     if($value->sex =='ผู้หญิง'){
                //         $value->sex = [2];
                //     }

                //     if($value->sex =='ผู้ชาย'){
                //         $value->sex = [1];
                //     }
                //     //dd($value->sex);
                //     ListMedia::where('id','=',$list_media->id)->update(['sex'=>json_encode($value->sex)]);
                //     // print_r($value->sex);
                //     // echo " <---id=".$value->no;
                //     // echo "<br>";

                // }

                if ($value->keyword != '') {
                    $keyword_ex = explode(",", $value->keyword);
                    $keyword_ex2 = explode("/", $value->keyword);

                    if (count($keyword_ex2) > 1) {
                        //dd(count($keyword_ex2),$keyword_ex2,$value->keyword);
                        // print_r($keyword_ex2);
                        // echo " <---id=".$value->no;
                        // echo "<br>";
                        $array = [];
                        foreach ($keyword_ex2 as $key => $value_keyword) {
                            //dd($value);
                            array_push($array, str_replace(" ", "", $value_keyword));
                        }
                        $value->keyword = $array;
                    } else {
                        // print_r($keyword_ex);
                        // echo " <---id=".$value->no;
                        // echo "<br>";
                        $array = [];
                        foreach ($keyword_ex as $key => $value_keyword) {
                            //dd($value);
                            array_push($array, str_replace(" ", "", $value_keyword));
                        }
                        //dd(json_encode($array),$value->keyword);
                        $value->keyword = $array;
                    }
                    //dd($value->keyword);
                    $json_data->Keywords = $value->keyword;
                    //dd($json_data);
                    ListMedia::where('id', '=', $list_media->id)->update(['json_data' => json_encode($json_data)]);
                    //dd($value->age,$keyword_ex);
                    // print_r($value->keyword);
                    // echo " <---id=".$value->no;
                    // echo "<br>";
                }
                //dd($issues_data,$value->issue);
                //dd($json_data,$list_media->id);
            }
            //dd($value,$id_ex,$id,$list_media,$json_data);
        }
        dd("Success", $i);
    }


    public function getImport3()
    {
        dd("getImport3");
        $data = DB::table('list_media_api_webview')
            //->select('*')
            //->limit(2)
            ->get();
        //dd("getImport3",$data);
        $i = 0;
        foreach ($data as $key => $value) {
            $json_data = json_decode($value->json_data);
            //dd($value,$json_data,json_decode($value->sex));

            $Issues_array = [];
            if (isset($json_data->Issues)) {
                foreach ($json_data->Issues as $value_issues) {
                    //dd($value_issues);
                    if (isset($value_issues->Name)) {
                        array_push($Issues_array, $value_issues->Name);
                    }
                }
            }
            $Targets_array = [];
            if (isset($json_data->Targets)) {
                foreach ($json_data->Targets as $value_targets) {
                    //dd($value_issues);
                    if (isset($value_targets->Name)) {
                        array_push($Targets_array, $value_targets->Name);
                    }
                }
            }
            $Settings_array = [];
            if (isset($json_data->Settings)) {
                foreach ($json_data->Settings as $value_settings) {
                    //dd($value_issues);
                    if (isset($value_settings->Name)) {
                        array_push($Settings_array, $value_settings->Name);
                    }
                }
            }

            $sex_text = [];
            if ($value->sex != '') {
                foreach (json_decode($value->sex) as $value_sex) {
                    //dd($value_issues);
                    $text = '';
                    if ($value_sex == 1) {
                        $text = 'ชาย';
                    }
                    if ($value_sex == 2) {
                        $text = 'หญิง';
                    }
                    if ($value_sex == 3) {
                        $text = 'หลากหลายทางเพศ';
                    }
                    array_push($sex_text, $text);
                }
            }

            $age_text = [];
            if ($value->age != '') {
                foreach (json_decode($value->age) as $value_age) {
                    //dd($value_issues);
                    $text = '';
                    if ($value_age == 4) {
                        $text = 'เยาวชน(15–24ปี)';
                    }
                    if ($value_age == 13) {
                        $text = 'ปฐมวัย(0–5ปี)';
                    }
                    if ($value_age == 19) {
                        $text = 'ผู้สูงอายุ(60ปีขึ้นไป)';
                    }
                    if ($value_age == 24) {
                        $text = 'วัยเรียน(6–12ปี)';
                    }
                    if ($value_age == 25) {
                        $text = 'วัยทำงาน(15-59ปี)';
                    }
                    if ($value_age == 26) {
                        $text = 'วัยรุ่น(13–15ปี)';
                    }
                    array_push($age_text, $text);
                }
            }
            //dd($age_text);


            //dd($Issues_array,implode(",",$Issues_array));
            DB::table('list_media_api_webview')->where('id', '=', $value->id)->update(['issue' => implode(",", $Issues_array), 'target' => implode(",", $Targets_array), 'setting' => implode(",", $Settings_array), 'keyword' => (isset($json_data->Keywords) ? implode(",", $json_data->Keywords) : ''), 'sex_text' => implode(",", $sex_text), 'age_text' => implode(",", $age_text), 'department' => (isset($json_data->DepartmentName) ? $json_data->DepartmentName : '')]);
        }
        dd("Success", $i);
    }


    public function getImportMobile()
    {
        //dd("getImportMobile");
        try {
            $data = DB::table('import202')
                //->select('*')
                //->limit(1)
                ->get();
            //dd("getImportMobile",$data);
            $i = 0;
            foreach ($data as $key => $value) {

                //dd($value);
                $id_ex = explode("/", $value->link);

                if ($id_ex[3] == 'media2') {
                    $id = base64_decode($id_ex[4]);
                    $list_media = DB::table('list_article_api_backup_30_3_2022')->where('article_id', '=', $id)
                        ->where('json_data', '!=', '')
                        ->where('json_data', '!=', 'null')
                        ->first();
                } else {
                    $id = Hashids::decode($id_ex[4]);
                    $list_media = DB::table('list_article_api_backup_30_3_2022')->where('article_id', '=', $id)
                        ->where('json_data', '!=', '')
                        ->where('json_data', '!=', 'null')
                        ->first();
                }


                //dd($list_media);
                if (isset($list_media->id)) {
                    $i++;
                    DB::table('list_article_api_backup_30_3_2022')->where('article_id', '=', $list_media->article_id)->update(['status' => 'publish']);
                    //dd($list_media->id);
                    $json_data = json_decode($list_media->json_data);


                    if ($value->target != '') {
                        $target_ex = explode('(', $value->target);
                        // print_r($target_ex);
                        // echo " <---id=".$value->no;
                        // echo "<br>";

                        if (count($target_ex) > 2) {
                            //dd($target_ex);
                            $array = [];
                            $target_data = ListTarget::select('target_id', 'name')
                                ->whereRaw('name like "%' . str_replace(" ", "", $target_ex[0]) . '%"')->first();
                            if (isset($target_data->target_id)) {
                                array_push($array, (int)$target_data->target_id);
                                //dd($target_data,$value->target);
                            }
                            $target2 = explode(",", $target_ex[1]);
                            $target_data = ListTarget::select('target_id', 'name')
                                ->whereRaw('name like "%' . str_replace(" ", "", $target2[1]) . '%"')->first();
                            if (isset($target_data->target_id)) {
                                array_push($array, (int)$target_data->target_id);
                                //dd($target_data,$value->target);
                            }
                            DB::table('list_article_api_backup_30_3_2022')->where('article_id', '=', $list_media->article_id)->update(['age' => json_encode($array)]);
                            //dd($value->target);
                        } else {
                            if ($target_ex[0] == 'ทุกช่วงวัย') {
                                $array = [];
                                $object = new \Stdclass;
                                $object->ID = 13;
                                $object->Name = 'ปฐมวัย(0–5ปี)';
                                array_push($array, 13);
                                $object = new \Stdclass;
                                $object->ID = 24;
                                $object->Name = 'วัยเรียน(6–12ปี)';
                                array_push($array, 24);
                                $object = new \Stdclass;
                                $object->ID = 26;
                                $object->Name = 'วัยรุ่น(13–15ปี)';
                                array_push($array, 26);
                                $object = new \Stdclass;
                                $object->ID = 4;
                                $object->Name = 'เยาวชน(15-20 ปี)';
                                array_push($array, 4);
                                $object = new \Stdclass;
                                $object->ID = 25;
                                $object->Name = 'วัยทำงาน(21-59 ปี)';
                                array_push($array, 25);
                                $object = new \Stdclass;
                                $object->ID = 19;
                                $object->Name = 'ผู้สูงอายุ(60ปีขึ้นไป)';
                                array_push($array, 19);
                                DB::table('list_article_api_backup_30_3_2022')->where('article_id', '=', $list_media->article_id)->update(['age' => json_encode($array)]);
                            } else {
                                $array = [];
                                $target_data = ListTarget::select('target_id', 'name')
                                    ->whereRaw('name like "%' . str_replace(" ", "", $target_ex[0]) . '%"')->first();

                                if (isset($target_data->target_id)) {
                                    $object = new \Stdclass;
                                    $object->ID = $target_data->target_id;
                                    $object->Name = $target_data->name;
                                    array_push($array, (int)$target_data->target_id);
                                    //dd($target_data,$value->target);
                                    DB::table('list_article_api_backup_30_3_2022')->where('article_id', '=', $list_media->article_id)->update(['age' => json_encode($array)]);
                                }
                            }
                        }

                        //ปฐมวัย(0–5ปี) = 13
                        //วัยเรียน(6–12ปี) = 24
                        //วัยรุ่น(13–15ปี) = 26
                        //เยาวชน(15-20 ปี) = 4
                        //วัยทำงาน(21-59 ปี) = 25
                        //ผู้สูงอายุ(60ปีขึ้นไป) = 19
                        // print_r($value->target);
                        // echo " <---id=".$value->no;
                        // echo "<br>";
                    }

                    if ($value->sex != '') {
                        $sex_ex = explode(" ", $value->sex);
                        //dd($value->age,$sex_ex);
                        $value->sex = $sex_ex[0];

                        if ($value->sex == 'ทุกเพศ') {
                            $value->sex = [1, 2, 3];
                        }

                        if ($value->sex == 'ผู้หญิง') {
                            $value->sex = [2];
                        }

                        if ($value->sex == 'ผู้ชาย') {
                            $value->sex = [1];
                        }
                        //dd($value->sex);
                        DB::table('list_article_api_backup_30_3_2022')->where('article_id', '=', $list_media->article_id)->update(['sex' => json_encode($value->sex)]);
                        // print_r($value->sex);
                        // echo " <---id=".$value->no;
                        // echo "<br>";
                    }

                    //dd($issues_data,$value->issue);
                    //dd($json_data,$list_media->id);
                } else {
                    // print_r($value->title);
                    // echo "<br>";
                    if ($id_ex[3] == 'media2') {
                        $id = base64_decode($id_ex[4]);
                        $list_media = DB::table('list_article')->where('article_id', '=', $id)
                            ->where('json_data', '!=', '')
                            ->where('json_data', '!=', 'null')
                            ->first();
                    } else {
                        $id = Hashids::decode($id_ex[4]);
                        $list_media = DB::table('list_article')->where('article_id', '=', $id)
                            ->where('json_data', '!=', '')
                            ->where('json_data', '!=', 'null')
                            ->first();
                    }
                    if (isset($list_media->id)) {
                        //dd($list_media);
                        $i++;
                        $data_insert = [];
                        $data_insert['article_id'] = $list_media->article_id;
                        $data_insert['title'] = $list_media->title;
                        $data_insert['description'] = $list_media->description;
                        $data_insert['featured'] = $list_media->featured;
                        $data_insert['template'] = $list_media->template;
                        $data_insert['UploadFileID'] = $list_media->UploadFileID;
                        $data_insert['json_data'] = $list_media->json_data;
                        $data_insert['status'] = $list_media->status;
                        $data_insert['created_at'] = $list_media->created_at;
                        $data_insert['updated_at'] = $list_media->updated_at;
                        $data_insert['created_by'] = $list_media->created_by;
                        $data_insert['updated_by'] = $list_media->updated_by;
                        $data_insert['hit'] = $list_media->hit;
                        $data_insert['download'] = $list_media->download;
                        $data_insert['knowledges'] = $list_media->knowledges;
                        $data_insert['media_campaign'] = $list_media->media_campaign;
                        $data_insert['article_type'] = $list_media->article_type;
                        $data_insert['slug'] = $list_media->slug;
                        $data_insert['sex'] = $list_media->sex;
                        $data_insert['age'] = $list_media->age;
                        $data_insert['tags'] = $list_media->tags;
                        //$data_insert['web_view'] = $list_media->web_view;
                        $data_insert['image_path'] = $list_media->image_path;
                        //dd($data_insert);
                        DB::table('list_article_api_backup_30_3_2022')->insert($data_insert);
                    }
                    //dd($list_media);
                }

                //dd($value,$id_ex,$id,$list_media,$json_data);
            }
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
        dd("Success", $i);
    }


    public function getUpdate7()
    {
        dd("Update7");
        $data = DB::table('update7')
            //->select('*')
            //->limit(1)
            ->get();
        //dd("Update7",$data);
        $i = 0;
        foreach ($data as $key => $value) {
            $i++;
            //dd($value);
            $id_ex = explode("/", $value->link);
            $id = base64_decode($id_ex[4]);
            $list_media = ListMedia::where('id', '=', $id)
                ->where('json_data', '!=', '')
                ->where('json_data', '!=', 'null')
                ->first();
            if (isset($list_media->id)) {

                //dd($value,$list_media);
                ListMedia::where('id', '=', $list_media->id)->update(['status' => 'publish', 'web_view' => 1, 'featured' => 2]);
            }
            //dd($value,$id_ex,$id,$list_media,$json_data);
        }
        dd("Success", $i);
    }


    public function getImportRecommend()
    {
        dd("getImportRecommend");
        $data = DB::table('import_recommend')
            //->select('*')
            //->limit(1)
            ->get();
        //dd("getImportRecommend",$data);
        $i = 0;
        foreach ($data as $key => $value) {
            $i++;
            //dd($value);
            $id_ex = explode("/", $value->link);
            $id = base64_decode($id_ex[4]);
            $list_media = ListMedia::where('id', '=', $id)
                ->where('json_data', '!=', '')
                ->where('json_data', '!=', 'null')
                ->first();
            if (isset($list_media->id)) {
                ListMedia::where('id', '=', $list_media->id)->update(['status' => 'publish', 'api' => 'publish', 'web_view' => 1, 'recommend' => 2]);
                //dd($list_media->id);
                $json_data = json_decode($list_media->json_data);
                if ($value->template != '') {

                    $template = '';
                    $str = $value->template;
                    $str = preg_replace("/[^A-Za-z0-9.!? ]/", "", $str);
                    $str = preg_replace("/[^A-Za-z0-9.!?\s]/", "", $str);
                    $str = preg_replace("/[^A-Za-z0-9.!?[:space:]]/", "", $str);
                    $str = strtolower($str);
                    $value->template = explode(" ", $str)[0];
                    //dd($value->template);         
                    if ($value->template == 'multimedia') {
                        $template = 'Multimedia';
                    }
                    if ($value->template == 'visual') {
                        $template = 'Visual';
                    }
                    if ($value->template == 'text') {
                        $template = 'Text';
                    }
                    $json_data->Template = $template;
                    ListMedia::where('id', '=', $list_media->id)->update(['template' => $template, 'json_data' => json_encode($json_data)]);

                    //echo $value->template." <---id=".$value->no;
                    //echo "<br>";
                    //ListMedia::where('id','=',$list_media->id)->update([]);

                }

                if ($value->issue != '') {
                    $issues_data = ListIssue::select('issues_id', 'name')
                        ->whereRaw('name like "%' . $value->issue . '%"')->first();
                    $array = [];
                    if (isset($issues_data->issues_id)) {
                        $object = new \Stdclass;
                        $object->ID = $issues_data->issues_id;
                        $object->Name = $issues_data->name;
                        array_push($array, $object);
                        $value->issue  = $array;
                    } else {
                        $data_issues = [];
                        $data_issues['name'] = $value->issue;
                        //$data_issues['issues_id'] = 'ncds-1';
                        $data_issues['status'] = 'publish';
                        $data_issues['parent_id'] = 0;
                        $data_issues['order'] = 0;
                        $issues_id = ListIssue::create($data_issues);
                        //echo var_export($data['issues_id'], true) . " is NOT numeric", PHP_EOL;
                        // $data['issues_id'] = $issues_id->id;
                        ListIssue::where('id', '=', $issues_id->id)->update(['issues_id' => $issues_id->id]);
                        $object = new \Stdclass;
                        $object->ID = $issues_id->id;
                        $object->Name = $value->issue;
                        array_push($array, $object);
                        $value->issue  = $array;
                    }
                    $json_data->Issues = $value->issue;
                    //dd($json_data);
                    ListMedia::where('id', '=', $list_media->id)->update(['json_data' => json_encode($json_data)]);
                }
                //print_r($value->issue);
                //echo " <---id=".$value->no;
                //echo "<br>";

                if ($value->age != '') {
                    $age_ex = explode(" ", $value->age);
                    //dd($value->age,$age_ex);
                    $value->age = [(int)$age_ex[1]];
                    ListMedia::where('id', '=', $list_media->id)->update(['age' => json_encode($value->age)]);
                    //dd($value->age);
                }
                // echo $value->age." <---id=".$value->no;
                // echo "<br>";

                if ($value->target != '') {
                    $target_ex = explode('(', $value->target);
                    // print_r($target_ex);
                    // echo " <---id=".$value->no;
                    // echo "<br>";

                    if (count($target_ex) > 2) {
                        //dd($target_ex);
                        $array = [];
                        $target_data = ListTarget::select('target_id', 'name')
                            ->whereRaw('name like "%' . str_replace(" ", "", $target_ex[0]) . '%"')->first();
                        if (isset($target_data->target_id)) {
                            $object = new \Stdclass;
                            $object->ID = $target_data->target_id;
                            $object->Name = $target_data->name;
                            array_push($array, $object);
                            //dd($target_data,$value->target);
                        }
                        $target2 = explode(",", $target_ex[1]);
                        $target_data = ListTarget::select('target_id', 'name')
                            ->whereRaw('name like "%' . str_replace(" ", "", $target2[1]) . '%"')->first();
                        if (isset($target_data->target_id)) {
                            $object = new \Stdclass;
                            $object->ID = $target_data->target_id;
                            $object->Name = $target_data->name;
                            array_push($array, $object);
                            //dd($target_data,$value->target);
                        }
                        $value->target = $array;
                        //dd($value->target);
                    } else {
                        if ($target_ex[0] == 'ทุกช่วงวัย') {
                            $array = [];
                            $object = new \Stdclass;
                            $object->ID = 13;
                            $object->Name = 'ปฐมวัย(0–5ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 24;
                            $object->Name = 'วัยเรียน(6–12ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 26;
                            $object->Name = 'วัยรุ่น(13–15ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 4;
                            $object->Name = 'เยาวชน(15-20 ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 25;
                            $object->Name = 'วัยทำงาน(21-59 ปี)';
                            array_push($array, $object);
                            $object = new \Stdclass;
                            $object->ID = 19;
                            $object->Name = 'ผู้สูงอายุ(60ปีขึ้นไป)';
                            array_push($array, $object);
                            $value->target  = $array;
                            //dd($value->target);
                        } else {
                            $target_data = ListTarget::select('target_id', 'name')
                                ->whereRaw('name like "%' . str_replace(" ", "", $target_ex[0]) . '%"')->first();

                            if (isset($target_data->target_id)) {
                                $object = new \Stdclass;
                                $object->ID = $target_data->target_id;
                                $object->Name = $target_data->name;
                                $value->target  = $object;
                                //dd($target_data,$value->target);
                            }
                        }
                    }
                    //dd($value->target);
                    $json_data->Targets = $value->target;
                    //dd($json_data);
                    ListMedia::where('id', '=', $list_media->id)->update(['json_data' => json_encode($json_data)]);
                    //ปฐมวัย(0–5ปี) = 13
                    //วัยเรียน(6–12ปี) = 24
                    //วัยรุ่น(13–15ปี) = 26
                    //เยาวชน(15-20 ปี) = 4
                    //วัยทำงาน(21-59 ปี) = 25
                    //ผู้สูงอายุ(60ปีขึ้นไป) = 19
                    //print_r($value->target);
                    //echo " <---id=".$value->no;
                    //echo "<br>";
                }

                if ($value->sex != '') {
                    $sex_ex = explode(" ", $value->sex);
                    //dd($value->age,$sex_ex);
                    $value->sex = $sex_ex[0];

                    if ($value->sex == 'ทุกเพศ') {
                        $value->sex = [1, 2, 3];
                    }

                    if ($value->sex == 'ผู้หญิง') {
                        $value->sex = [2];
                    }

                    if ($value->sex == 'ผู้ชาย') {
                        $value->sex = [1];
                    }
                    //dd($value->sex);
                    ListMedia::where('id', '=', $list_media->id)->update(['sex' => json_encode($value->sex)]);
                    // print_r($value->sex);
                    // echo " <---id=".$value->no;
                    // echo "<br>";

                }

                if ($value->keyword != '') {
                    $keyword_ex = explode(",", $value->keyword);
                    $keyword_ex2 = explode("/", $value->keyword);

                    if (count($keyword_ex2) > 1) {
                        //dd(count($keyword_ex2),$keyword_ex2,$value->keyword);
                        // print_r($keyword_ex2);
                        // echo " <---id=".$value->no;
                        // echo "<br>";
                        $array = [];
                        foreach ($keyword_ex2 as $key => $value_keyword) {
                            //dd($value);
                            array_push($array, str_replace(" ", "", $value_keyword));
                        }
                        $value->keyword = $array;
                    } else {
                        // print_r($keyword_ex);
                        // echo " <---id=".$value->no;
                        // echo "<br>";
                        $array = [];
                        foreach ($keyword_ex as $key => $value_keyword) {
                            //dd($value);
                            array_push($array, str_replace(" ", "", $value_keyword));
                        }
                        //dd(json_encode($array),$value->keyword);
                        $value->keyword = $array;
                    }
                    //dd($value->keyword);
                    $json_data->Keywords = $value->keyword;
                    //dd($json_data);
                    ListMedia::where('id', '=', $list_media->id)->update(['json_data' => json_encode($json_data)]);
                    //dd($value->age,$keyword_ex);
                    // print_r($value->keyword);
                    // echo " <---id=".$value->no;
                    // echo "<br>";
                }
                //dd($issues_data,$value->issue);
                //dd($json_data,$list_media->id);
            }
            //dd($value,$id_ex,$id,$list_media,$json_data);
        }
        dd("Success Recommend", $i);
    }


    public function getUpdateTags()
    {
        dd("Update Tags");
        $data = DB::table('tbl_data_tags')
            //->select('*')
            //->limit(1)
            //->GroupBy('tags_id','data_type')
            ->get();
        $data_group_by = collect($data)->groupBy('tags_id', 'data_type')->toArray();
        //dd($data,$data_group_by);

        foreach ($data_group_by as $key => $value) {
            //dd($key,$value);
            $array = [];
            $id = '';
            $data_type = '';
            foreach ($value as $value2) {
                $data_tag = DB::table('tbl_tags')->select('title')->where('id', '=', $value2->tags_id)->first();
                //dd($value2,$data_tag);
                if (isset($data_tag->title)) {
                    array_push($array, $data_tag->title);
                }
                $id = $value2->id;
                $data_type = $value2->data_type;
            }
            //dd($array);
            if ($data_type == 'media') {
                ListMedia::where('id', '=', $id)->update(['tags' => json_encode($array)]);
            } else {
                Article::where('id', '=', $id)->update(['tags' => json_encode($array)]);
            }
        }
        dd("Update Tags Success");
    }


    public function getUpdateImages()
    {

        dd("Update Images");
        $model_type = 'App\Modules\Api\Models\ListMedia';
        $data_media = DB::table('media')
            ->select('id', 'model_id')
            ->where('collection_name', '=', 'cover_desktop')
            ->where('model_type', '=', $model_type)
            ->get();

        //dd($data_media);
        if ($data_media->count() > 0) {
            foreach ($data_media as $key => $value) {
                $image_path = '/media/' . $value->id . '/conversions/thumb1366x635.jpg';
                //dd($image_path);
                ListMedia::where('id', '=', $value->model_id)->update(['image_path' => $image_path]);
            }
        }

        $model_type = 'App\Modules\Article\Models\Article';
        $data_media = DB::table('media')
            ->select('id', 'model_id')
            ->where('collection_name', '=', 'cover_desktop')
            ->where('model_type', '=', $model_type)
            ->get();

        //dd($data_media);
        if ($data_media->count() > 0) {
            foreach ($data_media as $key => $value) {
                $image_path = '/media/' . $value->id . '/conversions/thumb1366x635.jpg';
                //dd($image_path);
                Article::where('id', '=', $value->model_id)->update(['image_path' => $image_path]);
            }
        }
        dd("Success Images");
    }

    public function getUpdateNcds()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        dd("getUpdateNcds");

        $data = DB::table('importncds')
            //->select('*')
            //->limit(10)
            ->get();

        //dd($data);

        //dd("getImportRecommend",$data);
        foreach ($data as $key => $value) {

            $id_ex = explode("/", $value->link);

            $id = explode("?", $id_ex[5]);

            // $check = DB::table('importncds')->whereRaw("link LIKE '%".$id[0]."%'")
            // //->first();
            // ->update(['UploadFileID'=>$id[0]]);

            // echo "<pre>";
            //       print_r($id);
            //       print_r($check);
            // echo "</pre>";

            //dd($value);
            $list_media = ListMedia::where('UploadFileID', '=', $id[0])->first();
            //dd($value,$list_media);
            if (isset($list_media->id)) {

                $json_data = json_decode($list_media->json_data);
                //dd($json_data,$json_data->UploadFileID);
                if (isset($json_data->UploadFileID) && $json_data->UploadFileID != '') {

                    $check_article = Article::select('id')
                        ->where('dol_UploadFileID', '=', $json_data->UploadFileID)
                        ->where('page_layout', '=', 'health-literacy')
                        ->first();


                    if ($value->issue != '') {



                        $category_id = '';
                        if ($value->issue == 'แอลกอฮอล์') {
                            #แอลกอฮอล์
                            $category_id = 5;
                        }

                        if ($value->issue == 'ยาสูบ') {
                            #ยาสูบ
                            $category_id = 16;
                        }

                        if ($value->issue == 'อาหาร') {
                            #อาหาร
                            $category_id = 7;
                        }

                        if ($value->issue == 'กิจกรรมทางกาย') {
                            #กิจกรรมทางกาย
                            $category_id = 8;
                        }

                        if ($value->issue == 'อุบัติเหตุ') {
                            #อุบัติเหตุ
                            $category_id = 9;
                        }

                        if ($value->issue == 'มลภาวะทางอากาศ') {
                            #มลภาวะทางอากาศ
                            $category_id = 15;
                        }

                        if ($value->issue == 'สุขภาพจิต') {
                            #สุขภาพจิต
                            $category_id = 11;
                        }

                        if ($value->issue == 'อื่นๆ') {
                            #อื่นๆ
                            $category_id = 14;
                        }

                        if ($value->issue == 'มลภาวะทางอากาศ') {
                            #มลภาวะทางอากาศ
                            $category_id = 15;
                        }

                        if ($value->issue == 'โรคปอดอุดกั้นเรื้อรัง') {
                            #โรคปอดอุดกั้นเรื้อรัง
                            $category_id = 19;
                        }

                        if ($value->issue == 'เหล้า') {
                            #เหล้า
                            $category_id = 18;
                        }

                        if ($value->issue == 'โรคมะเร็ง') {
                            #โรคมะเร็ง
                            $category_id = 20;
                        }

                        if ($value->issue == 'โรคหัวใจและหลอดเลือด') {
                            #โรคหัวใจและหลอดเลือด
                            $category_id = 13;
                        }

                        if ($value->issue == 'โรคเบาหวาน') {
                            #โรคเบาหวาน
                            $category_id = 22;
                        }

                        //dd($category_id,$value->issue);

                        // $issues_data = ListIssue::select('issues_id','name')
                        //                         ->whereRaw('name like "%'.$value->issue.'%"')->first();
                        // $array = [];
                        // if (isset($issues_data->issues_id)) {
                        //     $object = new \Stdclass;
                        //     $object->ID = $issues_data->issues_id;
                        //     $object->Name = $issues_data->name;
                        //     array_push($array,$object);
                        //     $value->issue  =$array;

                        // } else {
                        //     $data_issues = [];
                        //     $data_issues['name'] = $value->issue;
                        //     //$data_issues['issues_id'] = 'ncds-1';
                        //     $data_issues['status'] = 'publish';
                        //     $data_issues['parent_id'] =0;
                        //     $data_issues['order'] =0;
                        //     $issues_id = ListIssue::create($data_issues);
                        //     //echo var_export($data['issues_id'], true) . " is NOT numeric", PHP_EOL;
                        //     // $data['issues_id'] = $issues_id->id;
                        //     ListIssue::where('id','=',$issues_id->id)->update(['issues_id'=>$issues_id->id]);
                        //     $object = new \Stdclass;
                        //     $object->ID = $issues_id->id;
                        //     $object->Name = $value->issue;
                        //     array_push($array,$object);
                        //     $value->issue  =$array;                    
                        // }
                        // $json_data->Issues =$value->issue;
                        // //dd($json_data);
                        // ListMedia::where('id','=',$list_media->id)->update(['json_data'=>json_encode($json_data)]);

                    }

                    if ($value->target == 'ทุกช่วงวัย') {
                        $array = [];
                        $object = new \Stdclass;
                        $object->ID = 13;
                        $object->Name = 'ปฐมวัย(0–5ปี)';
                        array_push($array, $object);
                        $object = new \Stdclass;
                        $object->ID = 24;
                        $object->Name = 'วัยเรียน(6–12ปี)';
                        array_push($array, $object);
                        $object = new \Stdclass;
                        $object->ID = 26;
                        $object->Name = 'วัยรุ่น(13–15ปี)';
                        array_push($array, $object);
                        $object = new \Stdclass;
                        $object->ID = 4;
                        $object->Name = 'เยาวชน(15-20 ปี)';
                        array_push($array, $object);
                        $object = new \Stdclass;
                        $object->ID = 25;
                        $object->Name = 'วัยทำงาน(21-59 ปี)';
                        array_push($array, $object);
                        $object = new \Stdclass;
                        $object->ID = 19;
                        $object->Name = 'ผู้สูงอายุ(60ปีขึ้นไป)';
                        array_push($array, $object);
                        $value->target  = $array;
                        $json_data->Targets = $value->target;
                        //dd($value->target);
                    }

                    //dd($check_article,$json_data);



                    if (!isset($check_article->id)) {
                        $data_article  = [];
                        $data_article['page_layout'] = 'health-literacy';
                        $data_article['title'] = $json_data->Title;
                        $data_article['description'] = $json_data->Description;
                        $data_article['short_description'] = strip_tags($json_data->Description);
                        $data_article['dol_cover_image'] = $json_data->ThumbnailAddress;
                        $data_article['dol_UploadFileID'] = $json_data->UploadFileID;
                        $data_article['dol_url'] = $json_data->FileAddress;
                        $data_article['dol_template'] = $value->template;
                        $data_article['dol_json_data'] = json_encode($json_data);
                        $data_article['category_id'] = $category_id;

                        $date_year = date('Y-m-d');
                        $date_year = strtotime($date_year);
                        $date_year = strtotime("+10 year", $date_year);
                        $data_article['start_date'] = date("Y-m-d H:i:s");
                        $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
                        $data_article['status'] = 'publish';
                        //dd($data_article);
                        // echo "<pre>";
                        //         print_r($data_article);
                        // echo "</pre>";
                        // exit();
                        dd($data_article, "case1");
                        Article::create($data_article);
                    } else {

                        $data_article  = [];
                        $data_article['page_layout'] = 'health-literacy';
                        $data_article['title'] = $json_data->Title;
                        $data_article['description'] = $json_data->Description;
                        $data_article['short_description'] = strip_tags($json_data->Description);
                        $data_article['dol_cover_image'] = $json_data->ThumbnailAddress;
                        $data_article['dol_UploadFileID'] = $json_data->UploadFileID;
                        $data_article['dol_url'] = $json_data->FileAddress;
                        $data_article['dol_template'] = $value->template;
                        $data_article['dol_json_data'] = json_encode($json_data);
                        $data_article['category_id'] = $category_id;
                        $date_year = date('Y-m-d');
                        $date_year = strtotime($date_year);
                        $date_year = strtotime("+10 year", $date_year);
                        $data_article['start_date'] = date("Y-m-d H:i:s");
                        $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
                        $data_article['status'] = 'publish';
                        //dd($data_article);
                        // echo "<pre>";
                        //         print_r($data_article);
                        // echo "</pre>";
                        // exit();
                        //dd($data_article,"case2");
                        Article::where('id', '=', $check_article->id)->update($data_article);
                    }
                }
            }
            //dd($value,$id_ex,$id,$list_media,$json_data);
        }
        dd("Success Ncds");
    }


    public function getUpdateNcds2()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        dd("getUpdateNcds2");
        $data = DB::select("SELECT
                                article.*
                            FROM
                                article
                            WHERE
                                article.page_layout = 'health-literacy'
                                AND 
                                dol_UploadFileID NOT IN (SELECT
                                UploadFileID
                            FROM
                                importncds GROUP BY UploadFileID)");
        //dd($data);
        foreach ($data as $key => $value) {
            //dd($value);
            Article::where('id', '=', $value->id)->update(['status' => 'draft']);
        }
        dd("Success Ncd2");
    }

    public function getUpdateNcds3()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        dd("getUpdateNcds3");
        $data = DB::select("SELECT
                               *
                            FROM
                                importncds
                            GROUP BY UploadFileID");
        //WHERE UploadFileID = '60fec7ab-48f9-e711-80de-00155d84fa40'              
        //dd($data);
        foreach ($data as $key => $value) {
            $data_UploadFileID = DB::table('importncds')
                ->where('UploadFileID', '=', $value->UploadFileID)
                ->groupBy('issue')
                ->get();
            $category_id_array = [];
            foreach ($data_UploadFileID as $key_2 => $value_2) {

                if ($value_2->issue != '') {

                    $category_id = '';
                    if ($value_2->issue == 'เหล้า') {
                        #เหล้า
                        $category_id = 5;
                    }

                    if ($value_2->issue == 'แอลกอฮอล์') {
                        #แอลกอฮอล์
                        $category_id = 5;
                    }


                    if ($value_2->issue == 'ยาสูบ') {
                        #ยาสูบ
                        $category_id = 6;
                    }

                    if ($value_2->issue == 'อาหาร') {
                        #อาหาร
                        $category_id = 7;
                    }

                    if ($value_2->issue == 'กิจกรรมทางกาย') {
                        #กิจกรรมทางกาย
                        $category_id = 8;
                    }

                    if ($value_2->issue == 'อุบัติเหตุ') {
                        #อุบัติเหตุ
                        $category_id = 9;
                    }

                    if ($value_2->issue == 'มลภาวะทางอากาศ') {
                        #มลภาวะทางอากาศ
                        $category_id = 15;
                    }

                    if ($value_2->issue == 'สุขภาพจิต') {
                        #สุขภาพจิต
                        $category_id = 11;
                    }

                    if ($value_2->issue == 'อื่นๆ') {
                        #อื่นๆ
                        $category_id = 14;
                    }

                    if ($value_2->issue == 'มลภาวะทางอากาศ') {
                        #มลภาวะทางอากาศ
                        $category_id = 15;
                    }

                    if ($value_2->issue == 'โรคปอดอุดกั้นเรื้อรัง') {
                        #โรคปอดอุดกั้นเรื้อรัง
                        $category_id = 19;
                    }

                    if ($value_2->issue == 'โรคมะเร็ง') {
                        #โรคมะเร็ง
                        $category_id = 20;
                    }

                    if ($value_2->issue == 'โรคหัวใจและหลอดเลือด') {
                        #โรคหัวใจและหลอดเลือด
                        $category_id = 13;
                    }

                    if ($value_2->issue == 'โรคเบาหวาน') {
                        #โรคเบาหวาน
                        $category_id = 22;
                    }
                }
                array_push($category_id_array, $category_id);
                //dd($value_2,$category_id);
            }
            $data_insert = [];
            $data_insert['issue'] = json_encode($category_id_array);
            $data_insert['title'] = $value->title;
            $data_insert['UploadFileID'] = $value->UploadFileID;
            DB::table('importncds_new')->insert($data_insert);
            //dd($data_UploadFileID->toArray(),$category_id_array,$data_insert);

            //dd($value,$data_UploadFileID);  
        }
        dd("Success Ncd3");
    }

    public function getUpdateNcds4()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        dd("getUpdateNcds4");
        $data = DB::select("SELECT
                               *
                            FROM
                                importncds_new
                            ");
        //WHERE UploadFileID = '60fec7ab-48f9-e711-80de-00155d84fa40'              
        //dd($data);
        foreach ($data as $key => $value) {
            $data_update = [];
            $data_update['category_id'] = $value->issue;

            Article::where('dol_UploadFileID', '=', $value->UploadFileID)->update($data_update);
            //dd($data_UploadFileID->toArray(),$category_id_array,$data_insert);
            //dd($data_update,$value->UploadFileID);
            //dd($value,$data_UploadFileID);  
        }
        dd("Success Ncd4");
    }


    public function getUpdateApi()
    {

        dd("Update Api");

        // $data = DB::table('list_article_api_backup_15_7_2021')
        // ->get();        
        // //dd($data);
        // foreach ($data as $key => $value) {
        //     //dd($value);
        //     $new_data = DB::table('list_article_backup_15_7_2021')
        //                     ->where('article_id','=',$value->article_id)
        //                     ->where('article_type','=',$value->article_type)
        //                     ->first();
        //     if(isset($new_data->id)){
        //         DB::table('list_article_api_backup_15_7_2021')
        //         ->where('article_id','=',$value->article_id)
        //         ->where('article_type','=',$value->article_type)
        //         ->update(['sex'=>$new_data->sex,'age'=>$new_data->age,'tags'=>$new_data->tags,'image_path'=>$new_data->image_path,'template'=>$new_data->template,'json_data'=>$new_data->json_data]);
        //     }
        //     //dd($value,$new_data);
        // }

        $data = DB::table('list_article_backup_15_7_2021')
            //->select('*')
            //->limit(1)
            ->where('web_view', '=', 1)
            ->get();
        //dd($data);    
        foreach ($data as $key => $value) {
            //dd($value);
            $new_data = DB::table('list_article_api_backup_15_7_2021')
                ->where('article_id', '=', $value->article_id)
                ->where('article_type', '=', $value->article_type)
                ->first();
            if (!isset($new_data->id)) {

                DB::table('list_article_api_backup_15_7_2021')->insert([
                    'article_id' => $value->article_id,
                    'title' => $value->title,
                    'description' => $value->description,
                    'featured' => $value->featured,
                    'template' => $value->template,
                    'UploadFileID' => $value->UploadFileID,
                    'json_data' => $value->json_data,
                    'status' => $value->status,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                    'created_by' => $value->created_by,
                    'updated_by' => $value->updated_by,
                    'hit' => $value->hit,
                    'download' => $value->download,
                    'knowledges' => $value->knowledges,
                    'media_campaign' => $value->media_campaign,
                    'article_type' => $value->article_type,
                    'slug' => $value->slug,
                    'sex' => $value->sex,
                    'age' => $value->age,
                    'tags' => $value->tags,
                    'image_path' => $value->image_path
                ]);
                //dd($value);               
            }
        }

        dd("Success Api");
    }
}

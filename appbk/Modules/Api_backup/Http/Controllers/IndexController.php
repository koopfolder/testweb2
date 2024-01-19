<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ListTemplate,Department,ApiLogs,ViewMediaAmount};
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Hash;
use Crypt;
use Illuminate\Support\Facades\Log;



class IndexController extends Controller
{


    public function postTaskDepartment(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
    
            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api Task Department');
                $input = $request->all();
                $department = Department::Data(['status'=>['publish']]);
                $data_api = array();

                if($department->count()){

                    foreach ($department AS $key=>$value) {
                        
                        $body = '{"PageNo":1,"PageSize":1,"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","DepartmentID":"'.$value->code.'"}';
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST', env('URL_LIST_MEDIA'), ['body' => $body]);    
                        $response_api = $request->getBody()->getContents();
                        $response_api = str_replace(" ","",substr($response_api,3));
                        $data_json = json_decode($response_api, true);
                        $total_rows = $data_json['TotalRows'];
                        Department::where('id','=',$value->id)->update(['total_items'=>$total_rows]);
                        //$total_page = ceil($data_json['TotalRows'] / env('PageSize'));
                        $data_api[$value->code] = $total_rows;

                    }

                    $check_task = ApiLogs::Data(['status'=>['processes'],'api_type'=>'list_media']);
                    if(!isset($check_task->id)){
                        $view_media_amount = ViewMediaAmount::Data([])->pluck('total','department_id')->toArray();

                        foreach ($department AS $key=>$value) {
                            //echo $data_api[$value->code]." ".$view_media_amount[$value->code];
                            //echo "<br>";
                            if($data_api[$value->code] !=0){

                                if(isset($view_media_amount[$value->code])){
                                    if($view_media_amount[$value->code] < $data_api[$value->code]){
                                        //echo "True-->".$view_media_amount[$value->code];
                                        //echo "<br>";
                                        $data = array();
                                        $data['api_name'] = 'ListMedia '.$value->name;
                                        $data['status'] = 'processes';
                                        $data['total'] = $data_api[$value->code];
                                        $data['page_size'] = env('PageSize');
                                        $data['page_no'] = 1;
                                        $data['page_all'] = (int) $data_api[$value->code] / env('PageSize');
                                        $data['params'] = $value->code;
                                        $data['api_type'] = 'list_media';
                                        ApiLogs::create($data);
                                    }
                                }else{
                                    $data = array();
                                    $data['api_name'] = 'ListMedia '.$value->name;
                                    $data['status'] = 'processes';
                                    $data['total'] = $data_api[$value->code];
                                    $data['page_size'] = env('PageSize');
                                    $data['page_no'] = 1;
                                    $data['page_all'] = (int) $data_api[$value->code] / env('PageSize');
                                    $data['params'] = $value->code;
                                    $data['api_type'] = 'list_media';
                                    ApiLogs::create($data);
                                }
                            }
                        }
                    }
                }

                Log::info('End Api Task Department');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                //$response['data_api'] =$data_api;
                //$response['view_media_amount'] =$view_media_amount;
                //$response['data'] =$department;
                //$response['total_rows'] =$total_rows;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api Department ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            $response['header'] =$request->header();
            return  Response::json($response,500);
        }

    }

  
    public function postListMedia(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            ini_set('request_terminate_timeout', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();



            if (Hash::check(env('SECRET'),$token['1']))
            { 

                $input = $request->all();
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api List Media');


                $loop_api = 1;
                while($loop_api <= 2000) {

                    $task = ApiLogs::Data(['status'=>['processes'],'api_type'=>'list_media']);

                    if(isset($task->id)){
                        $i=1;
                        $task->update(['note'=>'Page '.$task->page_no.' Start']);
                        $body = '{"PageNo":"'.$task->page_no.'","PageSize":"'.$task->page_size.'","UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","DepartmentID":"'.$task->params.'"}';
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST', env('URL_LIST_MEDIA'), ['body' => $body]);    
                        $response_api = $request->getBody()->getContents();
                        $response_api = str_replace(" ","",substr($response_api,3));
                        $data_json = json_decode($response_api, true);

                        if($data_json['TotalRows'] > 0){
                            foreach($data_json['Files'] AS $key=>$value){
                                        //dd($value);
                                        $rules = ['UploadFileID'=>'required|unique:list_media,UploadFileID'];/* Dont Forget */
                                        $data = ['UploadFileID'=>$value['UploadFileID']];
                                        $validator = Validator::make($data, $rules);
                                        if($validator->passes()){
                                            $array = array();
                                            $array['UploadFileID'] = $value['UploadFileID'];
                                            $array['department_id'] = $task->params;
                                            //$array['json_data'] = json_encode($value);
                                            $array['status'] = 'draft';
                                            ListMedia::create($array);  
                                        }
                                $i++;
                            }
                            $next_page = $task->page_no+1;
                            $task->update(['page_no'=>$next_page,'note'=>'Page '.$task->page_no.' End']);
                            if($next_page > $task->page_all){
                                $task->update(['status'=>'end_processes']);
                            }
                        }else{
                            $task->update(['status'=>'end_processes','note'=>'Page '.$task->page_no.' End']);
                        }
                        //$task->update(['page_no'=>'']);
                        $loop_api = $loop_api+$i;
                    }else{
                        $loop_api = 3000;
                    }

                }

                Log::info('End Api List Media');
                $response['msg'] ='200 OK';
                $response['status']=true;
                $response['task']= $task;
                $response['loop_api']= $loop_api;
                // $response['total']= $data_json['TotalRows'];
                // $response['body']= $body;
                // $response['data_json']= $data_json;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api List Media ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
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

    // public function postMedia(Request $request){
        
    //     try {
            
    //         ini_set('max_execution_time', 0);
    //         set_time_limit(0);
            
            
    //         $token = explode(" ",$request->header('Authorization'));
    //         $response = array();
    //         $input = $request->all();

    //         if (Hash::check(env('SECRET'),$token['1']))
    //         {  
    //             Log::useDailyFiles(storage_path().'/logs/api.log');
    //             Log::info('Start Api Get Media');

    //             $task = ApiLogs::Data(['status'=>['processes'],'api_type'=>'get_media']);
    //             if(isset($task->id)){

    //                 $media = ListMedia::select('UploadFileID')->whereRaw('title IS NULL')->limit($task->page_size)->get();
    //                 if(collect($media)->count()){
    //                     $task->update(['note'=>'Page '.$task->page_no.' Start']);
    //                     foreach ($media as $key => $value) {
    //                         //dd($value->id);
    //                         $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","UploadFileID":"'.$value->UploadFileID.'"}';
    //                         //$body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","UploadFileID":"e63e4091-929a-e611-80db-00155d3d0608"}';
    //                         $client = new \GuzzleHttp\Client();
    //                         $request = $client->request('POST', env('URL_GET_MEDIA'), ['body' => $body]);    
    //                         $response_api = $request->getBody()->getContents();
    //                         $response_api = str_replace(" ","",substr($response_api,3));
    //                         $data_json = json_decode($response_api, true);


    //                         if(gettype($data_json) =='array'){

    //                             //dd($data_json['UploadFile'],gettype($data_json['UploadFile']['Issues']));
    //                             $array = array();
    //                             $array['title'] = $data_json['UploadFile']['Title'];
    //                             $array['description'] = $data_json['UploadFile']['Description'];
    //                             $array['category_id'] = $data_json['UploadFile']['CategoryID'];
    //                             $array['province'] = (isset($data_json['UploadFile']['Province']['0']) ? $data_json['UploadFile']['Province']['0']:'');
    //                             $array['template'] = $data_json['UploadFile']['Template'];
    //                             $array['area_id'] = $data_json['UploadFile']['AreaID'];
    //                             $array['json_data'] = json_encode($data_json['UploadFile']);
    //                             //dd($array,isset($data_json['UploadFile']['Province']['0']),$data_json['UploadFile']['Province'],gettype($data_json['UploadFile']['Province']));
    //                             ListMedia::where('UploadFileID','=',$data_json['UploadFileID'])->update($array); 
                                
    //                         }

    //                     }

    //                     $next_page = $task->page_no+1;
    //                     $task->update(['page_no'=>$next_page,'note'=>'Page '.$task->page_no.' End']);
    //                     if($next_page > $task->page_all){
    //                         $task->update(['status'=>'end_processes']);
    //                     }
    //                 }else{
    //                     $next_page = $task->page_no+1;
    //                     $task->update(['page_no'=>$next_page,'note'=>'Page '.$task->page_no.' End']);
    //                     if($next_page > $task->page_all){
    //                         $task->update(['status'=>'end_processes']);
    //                     }
    //                 }
    //             }

    //             //$media = ListMedia::select('UploadFileID')->whereRaw('title IS NULL')->limit(1)->get();
    //             //$media = ListMedia::select('UploadFileID')->whereRaw('title IS NULL')->limit(1)->get();

    //             //$media = ListMedia::select('id','UploadFileID')->offset($input['offset'])->limit($input['limit'])->get();
    //             //dd(collect($media)->count());
    //             //dd($media);
       
    //             //dd(collect($media)->count());
    //             Log::info('End Api Get Media');
    //             $response['msg'] ='200 OK';
    //             $response['status'] =true;
    //             //$response['media'] =$media;
    //             //$response['input'] =$input;
    //             return  Response::json($response,200);


    //         }else{
    //             $response['msg'] ='401 (Unauthorized)';
    //             $response['status'] =false;
    //             return  Response::json($response,401);
    //         }

    //     } catch (\Throwable $e) {
    //         Log::useDailyFiles(storage_path().'/logs/api-errors.log');
    //         Log::error('Api Media ---> '.$e->getMessage());
    //         $response['msg'] =$e->getMessage();
    //         $response['status'] =false;
    //         return  Response::json($response,500);
    //     }

    // }


    public function postMedia(Request $request){
        
        try {
            
            ini_set('max_execution_time', 0);
            set_time_limit(0);
            
            
            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api Get Media');

                $task = ApiLogs::Data(['status'=>['processes'],'api_type'=>'get_media']);
                if(isset($task->id)){

                    $media = ListMedia::select('UploadFileID')->whereRaw('title IS NULL')->limit($task->page_size)->get();
                    if(collect($media)->count()){
                        $task->update(['note'=>'Page '.$task->page_no.' Start']);
                        foreach ($media as $key => $value) {
                            //dd($value->id);
                            $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","UploadFileID":"'.$value->UploadFileID.'"}';
                            //$body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","UploadFileID":"e63e4091-929a-e611-80db-00155d3d0608"}';
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('URL_GET_MEDIA'), ['body' => $body]);    
                            $response_api = $request->getBody()->getContents();
                            $response_api = str_replace(" ","",substr($response_api,3));
                            $data_json = json_decode($response_api, true);


                            if(gettype($data_json) =='array'){

                                //dd($data_json['UploadFile']);
         
                                if(gettype($data_json['UploadFile']['Keywords']) =='array'){
                                    $check_data = 0;
                                    $data_array_check = array("newscliping","รายงานประจำปี","รายงานความก้าวหน้า");
                                    
                                    foreach ($data_json['UploadFile']['Keywords'] as $value) {
                                        //echo $value;
                                        //echo "<br>";
                                        if(array_keys($data_array_check,$value)){
                                            $check_data=1;
                                        //echo $value;
                                        //echo "<br>";
                                        //exit();
                                        }
                                    }

                                    if($check_data !=1){
                                        $array = array();
                                        $array['title'] = $data_json['UploadFile']['Title'];
                                        $array['description'] = $data_json['UploadFile']['Description'];
                                        $array['category_id'] = $data_json['UploadFile']['CategoryID'];
                                        $array['province'] = (isset($data_json['UploadFile']['Province']['0']) ? $data_json['UploadFile']['Province']['0']:'');
                                        $array['template'] = $data_json['UploadFile']['Template'];
                                        $array['area_id'] = $data_json['UploadFile']['AreaID'];
                                        $array['json_data'] = json_encode($data_json['UploadFile']);
                                        //dd($array,isset($data_json['UploadFile']['Province']['0']),$data_json['UploadFile']['Province'],gettype($data_json['UploadFile']['Province']));
                                        ListMedia::where('UploadFileID','=',$data_json['UploadFileID'])->update($array);
                                    }

                                }else{

                                    $array = array();
                                    $array['title'] = $data_json['UploadFile']['Title'];
                                    $array['description'] = $data_json['UploadFile']['Description'];
                                    $array['category_id'] = $data_json['UploadFile']['CategoryID'];
                                    $array['province'] = (isset($data_json['UploadFile']['Province']['0']) ? $data_json['UploadFile']['Province']['0']:'');
                                    $array['template'] = $data_json['UploadFile']['Template'];
                                    $array['area_id'] = $data_json['UploadFile']['AreaID'];
                                    $array['json_data'] = json_encode($data_json['UploadFile']);
                                    //dd($array,isset($data_json['UploadFile']['Province']['0']),$data_json['UploadFile']['Province'],gettype($data_json['UploadFile']['Province']));
                                    ListMedia::where('UploadFileID','=',$data_json['UploadFileID'])->update($array);

                                }


                                // echo gettype($data_json['UploadFile']['Keywords']);
                                // echo "<pre>";
                                //         print_r($data_json['UploadFile']);
                                // echo "</pre>";
                                //echo "check_data----->".$check_data;
                                //exit();


                            }

                        }

                        $next_page = $task->page_no+1;
                        $task->update(['page_no'=>$next_page,'note'=>'Page '.$task->page_no.' End']);
                        if($next_page > $task->page_all){
                            $task->update(['status'=>'end_processes']);
                        }
                    }else{
                        $next_page = $task->page_no+1;
                        $task->update(['page_no'=>$next_page,'note'=>'Page '.$task->page_no.' End']);
                        if($next_page > $task->page_all){
                            $task->update(['status'=>'end_processes']);
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
                $response['msg'] ='200 OK';
                $response['status'] =true;
                //$response['media'] =$media;
                //$response['input'] =$input;
                return  Response::json($response,200);


            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api Media ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }



    public function postTaskMedia(Request $request){
        
        try {
            
            ini_set('max_execution_time', 0);
            set_time_limit(0);
            
            
            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api Task Media');

                $task = ApiLogs::Data(['status'=>['processes'],'api_type'=>'get_media']);
                if(!isset($task->id)){

                    $media = ListMedia::selectRaw('COUNT(id) AS count')->whereRaw('title IS NULL')->first();
                    if(isset($media->count) && $media->count >0){
                        $data = array();
                        $data['api_name'] = 'Get Media ';
                        $data['status'] = 'processes';
                        $data['total'] = $media->count;
                        $data['page_size'] = 2000;
                        $data['page_no'] = 1;
                        $data['page_all'] = (int) $media->count / 2000;
                        $data['params'] = '';
                        $data['api_type'] = 'get_media';
                        ApiLogs::create($data);
                    }

                }

                Log::info('End Api Task Media');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api Task Media ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }



    public function postTaskMediaAttribute(Request $request){
        
        try {
            
            ini_set('max_execution_time', 0);
            set_time_limit(0);
            
            
            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api Task MediaAttribute');

                $task = ApiLogs::Data(['status'=>['processes'],'api_type'=>'get_media_attribute']);
                if(!isset($task->id)){

                    $media = ListMedia::selectRaw('COUNT(id) AS count')->whereRaw('title IS NOT NULL')->first();
                    if(isset($media->count) && $media->count >0){
                        $data = array();
                        $data['api_name'] = 'Get Media Attribute';
                        $data['status'] = 'processes';
                        $data['total'] = $media->count;
                        $data['page_size'] = 5000;
                        $data['page_no'] = 1;
                        $data['page_all'] = (int) $media->count / 5000;
                        $data['params'] = '';
                        $data['api_type'] = 'get_media_attribute';
                        ApiLogs::create($data);
                    }

                }

                Log::info('End Api Task Media Attribute');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api Media Attribute ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }




    public function postMediaAttribute(Request $request){
        
        try {
            
            ini_set('max_execution_time', 0);
            set_time_limit(0);
            
            
            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            $input = $request->all();

            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api Get Media Attribute');

                $task = ApiLogs::Data(['status'=>['processes'],'api_type'=>'get_media_attribute']);
                if(isset($task->id)){
                    //$task->page_size
                    $offset = 0;
                    if($task->page_no !='1'){
                        $offset = ($task->page_no * $task->page_size) - $task->page_size;
                    }
                    $media = ListMedia::select('id','json_data')->whereRaw('title IS NOT NULL')
                                                              ->offset($offset)
                                                              ->limit($task->page_size)
                                                              ->get();

                    if(collect($media)->count()){
                        $task->update(['note'=>'Page '.$task->page_no.' Start']);

                        foreach ($media as $key => $value) {
                            //dd($value->id);
                            $json = ($value->json_data !='' ? json_decode($value->json_data):'');
                            ListMediaIssues::where('media_id','=',$value->id)->delete();
                            ListMediaKeywords::where('media_id','=',$value->id)->delete();
                            ListMediaTargets::where('media_id','=',$value->id)->delete();

                            if(gettype($json->Issues) =='array'){
                                foreach ($json->Issues as $key_issues => $value_issues){
                                    $array_issues = array();
                                    $array_issues['media_id'] = $value->id;
                                    $array_issues['issues_id'] = $value_issues->ID;
                                    ListMediaIssues::create($array_issues);
                                }
                            }

                            if(gettype($json->Keywords) =='array'){
                                foreach ($json->Keywords as $key_keywords => $value_keywords){
                                    //dd($value_keywords);
                                    $array_keywords = array();
                                    $array_keywords['media_id'] = $value->id;
                                    $array_keywords['keyword'] = $value_keywords;
                                    ListMediaKeywords::create($array_keywords);
                                }
                            }

                            if(gettype($json->Targets)  =='array'){
                                foreach ($json->Targets as $key_target => $value_target){
                                    $array_target = array();
                                    $array_target['media_id'] = $value->id;
                                    $array_target['target_id'] = $value_target->ID;
                                    ListMediaTargets::create($array_target);
                                }  
                            }

                        }

                        $next_page = $task->page_no+1;
                        $task->update(['page_no'=>$next_page,'note'=>'Page '.$task->page_no.' End']);
                        if($next_page > $task->page_all){
                            $task->update(['status'=>'end_processes']);
                        }
                    }else{
                        $next_page = $task->page_no+1;
                        $task->update(['page_no'=>$next_page,'note'=>'Page '.$task->page_no.' End']);
                        if($next_page > $task->page_all){
                            $task->update(['status'=>'end_processes']);
                        }
                    }

                }


                Log::info('End Api Get Media Attribute');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                //$response['media'] =$media;
                //$response['task'] =$task;
                //$response['offset'] =$offset;
                //$response['json_data'] =$array_target;
                //$response['input'] =$input;
                return  Response::json($response,200);


            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api Get Media Attribute---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
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



    public function postListCategory(Request $request){
        
        try {
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
    
            if (Hash::check(env('SECRET'),$token['1']))
            {  
                
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api List Category');
                $input = $request->all();

                $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_CATEGORY'), ['body' => $body]);    
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ","",substr($response_api,3));
                $data_json = json_decode($response_api, true);

                //dd($data_json['Categories']);

                if(gettype($data_json) =='array'){

                    foreach($data_json['Categories'] AS $key=>$value){
                        //dd($value);

                        $rules = ['category_id'=>'required|unique:list_category,category_id'];
                        $data = ['category_id'=>$value['ID']];
                        $validator = Validator::make($data, $rules);
                        if($validator->passes()){
                            $array = array();
                            $array['category_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListCategory::create($array);  
                        }else{
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListCategory::where('category_id','=',$value['ID'])->update($array);  
                        }

                    } 
                }
                Log::info('End Api List Category');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                return  Response::json($response,200);


            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api List Category ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function postListIssue(Request $request){
        
        try {

            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
    
            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api List Issue');
                $input = $request->all();

                $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_ISSUE'), ['body' => $body]);    
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ","",substr($response_api,3));
                $data_json = json_decode($response_api, true);

                //dd($data_json['Issues']);

                if(gettype($data_json) =='array'){

                    foreach($data_json['Issues'] AS $key=>$value){
                        //dd($value);

                        $rules = ['issues_id'=>'required|unique:list_issue,issues_id'];
                        $data = ['issues_id'=>$value['ID']];
                        $validator = Validator::make($data, $rules);
                        if($validator->passes()){
                            $array = array();
                            $array['issues_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListIssue::create($array);  
                        }else{
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListIssue::where('issues_id','=',$value['ID'])->update($array);  
                        }

                    } 

                }
                Log::info('End Api List Issue');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api List Issue ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }



    public function postListTarget(Request $request){
        
        try {
            
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
    
            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api List Target');
                $input = $request->all();

                $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_TARGET'), ['body' => $body]);    
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ","",substr($response_api,3));
                $data_json = json_decode($response_api, true);

                //dd($data_json);

                if(gettype($data_json) =='array'){

                    foreach($data_json['Targets'] AS $key=>$value){
                        //dd($value);

                        $rules = ['target_id'=>'required|unique:list_target,target_id'];
                        $data = ['target_id'=>$value['ID']];
                        $validator = Validator::make($data, $rules);
                        if($validator->passes()){
                            $array = array();
                            $array['target_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListTarget::create($array);  
                        }else{
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListTarget::where('target_id','=',$value['ID'])->update($array);  
                        }

                    } 

                }
                Log::info('Start Api List Target');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api List Target ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function postListSetting(Request $request){
        
        try {
            
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
    
            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api List Setting');
                $input = $request->all();

                $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_SETTING'), ['body' => $body]);    
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ","",substr($response_api,3));
                $data_json = json_decode($response_api, true);

                //dd($data_json);

                if(gettype($data_json) =='array'){

                    foreach($data_json['Settings'] AS $key=>$value){
                        //dd($value);

                        $rules = ['setting_id'=>'required|unique:list_setting,setting_id'];
                        $data = ['setting_id'=>$value['ID']];
                        $validator = Validator::make($data, $rules);
                        if($validator->passes()){
                            $array = array();
                            $array['setting_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListSetting::create($array);  
                        }else{
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListSetting::where('setting_id','=',$value['ID'])->update($array);  
                        }

                    } 

                }
                Log::info('End Api List Setting');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api List Setting ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }


    public function postListArea(Request $request){
        
        try {
            
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
    
            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api List Area');
                $input = $request->all();

                $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_AREA'), ['body' => $body]);    
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ","",substr($response_api,3));
                $data_json = json_decode($response_api, true);

                //dd($data_json);

                if(gettype($data_json) =='array'){

                    foreach($data_json['Areas'] AS $key=>$value){
                        //dd($value);

                        $rules = ['area_id'=>'required|unique:list_area,area_id'];
                        $data = ['area_id'=>$value['ID']];
                        $validator = Validator::make($data, $rules);
                        if($validator->passes()){
                            $array = array();
                            $array['area_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListArea::create($array);  
                        }else{
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListArea::where('area_id','=',$value['ID'])->update($array);  
                        }

                    } 

                }
                Log::info('End Api List Area');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api List Area ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }


    public function postListProvince(Request $request){
        
        try {
            
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
    
            if (Hash::check(env('SECRET'),$token['1']))
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api List Province');
                $input = $request->all();
                $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'"}';
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_LIST_PROVINCE'), ['body' => $body]);    
                $response_api = $request->getBody()->getContents();
                $response_api = str_replace(" ","",substr($response_api,3));
                $data_json = json_decode($response_api, true);

                //dd($data_json);

                if(gettype($data_json) =='array'){

                    foreach($data_json['Provinces'] AS $key=>$value){
                        //dd($value);

                        $rules = ['province_id'=>'required|unique:list_province,province_id'];
                        $data = ['province_id'=>$value['ID']];
                        $validator = Validator::make($data, $rules);
                        if($validator->passes()){
                            $array = array();
                            $array['province_id'] = $value['ID'];
                            $array['name'] = $value['Name'];
                            $array['status'] = 'publish';
                            ListProvince::create($array);  
                        }else{
                            $array = array();
                            $array['name'] = $value['Name'];
                            ListProvince::where('province_id','=',$value['ID'])->update($array);  
                        }

                    } 

                }
                Log::info('End Api List Province');
                $response['msg'] ='200 OK';
                $response['status'] =true;
                return  Response::json($response,200);

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api List Province ---> '.$e->getMessage());
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }





    public function postGenerateKey(Request $request){
        
        try {

            $token = explode(" ",$request->header('Authorization'));
            $response = array();
            if (explode(":",env('APP_KEY'))['1'] == $token['1'])
            {  
                Log::useDailyFiles(storage_path().'/logs/api.log');
                Log::info('Start Api GenerateKey');
                $input = $request->all();
                if(isset($input['secret'])){
                    Log::info('End Api GenerateKey');
                    $response['msg'] ='200 OK';
                    $response['status'] =true;
                    $response['data'] = bcrypt($input['secret']);
                    return  Response::json($response,200);
                }else{
                    $response['msg'] ='404 Page Not Found';
                    $response['status'] =false;
                    return  Response::json($response,404);
                }

            }else{
                $response['msg'] ='401 (Unauthorized)';
                $response['status'] =false;
                return  Response::json($response,401);
            }

        } catch (\Throwable $e) {
            Log::useDailyFiles(storage_path().'/logs/api-errors.log');
            Log::error('Api List GenerateKey ---> '.$e->getMessage());
            $response['msg'] = $e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }


    public function getTest(){

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


}


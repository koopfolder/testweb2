<?php

namespace App\Modules\Dashboard\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Module;
use App\User;
use App\Modules\Article\Models\{Article};
use App\Modules\Api\Models\{ViewMedia};
use App\Modules\Dashboard\Models\{Logs,Modules};
use App\Modules\Dashboard\Http\Requests\{SearchRequest};
use Illuminate\Support\Facades\Response;
use ThrcHelpers;

class ReportController extends Controller
{

    public function getReportArticle(Request $request)
    {
        $items = Article::Report(['request'=>$request->all()]);
        $old = $request->all();
        //dd($items);
        return  view('dashboard::backend.article.index',compact('items','old'));
    }

    public function getReportMedia(Request $request)
    {
        $items = ViewMedia::Report(['request'=>$request->all()]);
        $old = $request->all();
        $issue =  ThrcHelpers::getIssue($request->all());
        //dd($issue);
        //dd($items,$issue);
        return  view('dashboard::backend.media.index',compact('items','old','issue'));
    }

    public function getReportLogs(Request $request)
    {
        $module = Modules::Data(['status'=>['publish']])->pluck('name','id')->toArray();
        $module[0] = trans('dashboard::backend.select');
        ksort($module);
        $old = $request->all();
        $items = Logs::Report(['request'=>$request->all()]);
        //dd($module,$items);
        
        return  view('dashboard::backend.logs.index',compact('module','items','old'));
    }


    public function getDashboardApi()
    {

        $year = [];
        $access_token = '';
        /*Login*/
        $body = '{"username":"'.env('THRC_API_USERNAME').'","password":"'.env('THRC_API_PASSWORD').'","device_token":"thrc_backend"}';
        //dd($body);
        $client = new \GuzzleHttp\Client();
        $request = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_LOGIN'), [
                                            'headers'=>[
                                                        'Content-Type'=>'application/json; charset=utf-8'
                                                       ],
                                            'body' => $body
                                    ]);    
        $response_api = $request->getBody()->getContents();
        $data_json = json_decode($response_api);

        if($data_json->status_code === 200){
            $access_token = $data_json->data->access_token;
            //dd($access_token);
            $body = '{"device_token":"thrc_backend"}';
            //dd($body);
            $client = new \GuzzleHttp\Client();
            $request = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_YM_TOTAL'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);             
            $response_api = $request->getBody()->getContents();
            $data_json = json_decode($response_api);
            //dd($data_json);
            if($data_json->status_code === 200){
                foreach ($data_json->data as $key => $value) {
                    //dd($value);
                    $year[$value]= $value;
                   // array_push($year,$value);
                }
            }

        }

        //dd($response_api,json_decode($response_api));
        //dd('Full API');
        //dd($year);
        $month = [
                  '0'=>trans('dashboard::backend.select_month'),
                  '1'=>trans('dashboard::backend.Jan'),
                  '2'=>trans('dashboard::backend.Feb'),
                  '3'=>trans('dashboard::backend.Mar'),
                  '4'=>trans('dashboard::backend.Apr'),
                  '5'=>trans('dashboard::backend.May'),
                  '6'=>trans('dashboard::backend.Jun'),
                  '7'=>trans('dashboard::backend.Jul'),
                  '8'=>trans('dashboard::backend.Aug'),
                  '9'=>trans('dashboard::backend.Sep'),
                  '10'=>trans('dashboard::backend.Oct'),
                  '11'=>trans('dashboard::backend.Nov'),
                  '12'=>trans('dashboard::backend.Dec')
                 ];
               
        ///dd($month);
        return view('dashboard::dashboard',compact('year','month','access_token'));
    }


    public function ApiDataChartMonthYear(Request $request)
    {

        $response = array();      
        $input = $request->all();
        $access_token = $request->header('Authorization');
        try {

            $data = '';
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $body = '{"c1_year":"'.base64_decode($input['c1_year']).'","c1_month":"'.base64_decode($input['c1_month']).'","device_token":"thrc_backend"}';
            //dd($body);
            $client = new \GuzzleHttp\Client();
            $call_api = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_DATA_CHART_MONTH_YEAR'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);


            $response_api = $call_api->getBody()->getContents();
            $data_json = json_decode($response_api);

            if($data_json->status_code ===200){
                $data = $data_json->data;
                //usort($data, $this->sortByDate('date'));
            }


            $response['msg'] ='200 OK';
            $response['status'] =true;
            //$response['input'] =$input;
            //$response['body'] =$body;
            //$response['header'] =$request->header('Authorization');
            //$response['data_json'] =$data_json;
            $response['data'] = $data;
            return  Response::json($response,200);

        } catch (\Throwable $e) {

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            //$response['header'] =$request->header();
            return  Response::json($response,500);
        }


    }


    public function ApiDataChartHour(Request $request)
    {

        $response = array();      
        $input = $request->all();
        $access_token = $request->header('Authorization');
        try {

            $data = '';
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $body = '{"c2_year":"'.base64_decode($input['c2_year']).'","c2_month":"'.base64_decode($input['c2_month']).'","device_token":"thrc_backend"}';
            // //dd($body);
            $client = new \GuzzleHttp\Client();
            $call_api = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_DATA_CHART_HOUR'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);


            $response_api = $call_api->getBody()->getContents();
            $data_json = json_decode($response_api);

            if($data_json->status_code ===200){
                $data = $data_json->data;
                //usort($data, $this->sortByDate('date'));
            }


            $response['msg'] ='200 OK';
            $response['status'] =true;
            //$response['input'] =$input;
            //$response['body'] =$body;
            //$response['header'] =$request->header('Authorization');
            //$response['data_json'] =$data_json;
            $response['data'] = $data;
            return  Response::json($response,200);

        } catch (\Throwable $e) {

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            //$response['header'] =$request->header();
            return  Response::json($response,500);
        }


    }



    public function ApiDataChartIssues(Request $request)
    {

        $response = array();      
        $input = $request->all();
        $access_token = $request->header('Authorization');
        try {

            $data = '';
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $body = '{"device_token":"thrc_backend"}';
            // //dd($body);
            $client = new \GuzzleHttp\Client();
            $call_api = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_DATA_ISSUES'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);


            $response_api = $call_api->getBody()->getContents();
            $data_json = json_decode($response_api);

            if($data_json->status_code ===200){
                $data = $data_json->data;
                //usort($data, $this->sortByDate('date'));
            }


            $response['msg'] ='200 OK';
            $response['status'] =true;
            //$response['input'] =$input;
            //$response['body'] =$body;
            //$response['header'] =$request->header('Authorization');
            //$response['data_json'] =$data_json;
            $response['val'] = $data;
            return  Response::json($response,200);

        } catch (\Throwable $e) {

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            //$response['header'] =$request->header();
            return  Response::json($response,500);
        }


    }    



    public function ApiDataChartTarget(Request $request)
    {

        $response = array();      
        $input = $request->all();
        $access_token = $request->header('Authorization');
        try {

            $data = '';
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $body = '{"device_token":"thrc_backend"}';
            // //dd($body);
            $client = new \GuzzleHttp\Client();
            $call_api = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_DATA_TARGET'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);


            $response_api = $call_api->getBody()->getContents();
            $data_json = json_decode($response_api);

            if($data_json->status_code ===200){
                $data = $data_json->data;
                //usort($data, $this->sortByDate('date'));
            }


            $response['msg'] ='200 OK';
            $response['status'] =true;
            //$response['input'] =$input;
            //$response['body'] =$body;
            //$response['header'] =$request->header('Authorization');
            //$response['data_json'] =$data_json;
            $response['val'] = $data;
            return  Response::json($response,200);

        } catch (\Throwable $e) {

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            //$response['header'] =$request->header();
            return  Response::json($response,500);
        }
    } 


    public function ApiDataChartSetting(Request $request)
    {

        $response = array();      
        $input = $request->all();
        $access_token = $request->header('Authorization');
        try {

            $data = '';
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $body = '{"device_token":"thrc_backend"}';
            // //dd($body);
            $client = new \GuzzleHttp\Client();
            $call_api = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_DATA_SETTING'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);


            $response_api = $call_api->getBody()->getContents();
            $data_json = json_decode($response_api);

            if($data_json->status_code ===200){
                $data = $data_json->data;
                //usort($data, $this->sortByDate('date'));
            }


            $response['msg'] ='200 OK';
            $response['status'] =true;
            //$response['input'] =$input;
            //$response['body'] =$body;
            //$response['header'] =$request->header('Authorization');
            //$response['data_json'] =$data_json;
            $response['val'] = $data;
            return  Response::json($response,200);

        } catch (\Throwable $e) {

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            //$response['header'] =$request->header();
            return  Response::json($response,500);
        }
    } 


    public function ApiDataKeywordSearch(Request $request)
    {

        $response = array();      
        $input = $request->all();
        $access_token = $request->header('Authorization');
        try {

            $data = '';
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $body = '{"device_token":"thrc_backend"}';
            // //dd($body);
            $client = new \GuzzleHttp\Client();
            $call_api = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_DATA_KEYWORD_SEATCH'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);


            $response_api = $call_api->getBody()->getContents();
            $data_json = json_decode($response_api);

            if($data_json->status_code ===200){
                $data = $data_json->data;
                //usort($data, $this->sortByDate('date'));
            }


            $response['msg'] ='200 OK';
            $response['status'] =true;
            //$response['input'] =$input;
            //$response['body'] =$body;
            //$response['header'] =$request->header('Authorization');
            //$response['data_json'] =$data_json;
            $response['val'] = $data;
            return  Response::json($response,200);

        } catch (\Throwable $e) {

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            //$response['header'] =$request->header();
            return  Response::json($response,500);
        }
    } 


    public function ApiDataMostViewedStatistics(Request $request)
    {

        $response = array();      
        $input = $request->all();
        $access_token = $request->header('Authorization');
        try {

            $data = '';
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $body = '{"device_token":"thrc_backend"}';
            // //dd($body);
            $client = new \GuzzleHttp\Client();
            $call_api = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_DATA_MOST_VIEWED_STATISTICS'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);


            $response_api = $call_api->getBody()->getContents();
            $data_json = json_decode($response_api);

            if($data_json->status_code ===200){
                $data = $data_json->data;
                //usort($data, $this->sortByDate('date'));
            }


            $response['msg'] ='200 OK';
            $response['status'] =true;
            //$response['input'] =$input;
            //$response['body'] =$body;
            //$response['header'] =$request->header('Authorization');
            //$response['data_json'] =$data_json;
            $response['val'] = $data;
            return  Response::json($response,200);

        } catch (\Throwable $e) {

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            //$response['header'] =$request->header();
            return  Response::json($response,500);
        }
    } 



    public function ApiDataStatisticsApiByOrganization(Request $request)
    {

        $response = array();      
        $input = $request->all();
        $access_token = $request->header('Authorization');
        try {

            $data = '';
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $body = '{"device_token":"thrc_backend"}';
            // //dd($body);
            $client = new \GuzzleHttp\Client();
            $call_api = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_DATA_STATISTICS_API_BY_ORGANIZATION'), [
                                                'headers'=>[
                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                            'authorization'=>$access_token
                                                           ],
                                                'body' => $body
                                        ]);


            $response_api = $call_api->getBody()->getContents();
            $data_json = json_decode($response_api);

            if($data_json->status_code ===200){
                $data = $data_json->data;
                //usort($data, $this->sortByDate('date'));
            }


            $response['msg'] ='200 OK';
            $response['status'] =true;
            //$response['input'] =$input;
            //$response['body'] =$body;
            //$response['header'] =$request->header('Authorization');
            //$response['data_json'] =$data_json;
            $response['val'] = $data;
            return  Response::json($response,200);

        } catch (\Throwable $e) {

            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            //$response['header'] =$request->header();
            return  Response::json($response,500);
        }
    } 




    function sortByDate($key)
    {
        return function ($a, $b) use ($key) {
            //dd($a[$key]);
            $t1 = strtotime($a[$key]);
            $t2 = strtotime($b[$key]);
            return $t1 - $t2;
        };
    } 

}



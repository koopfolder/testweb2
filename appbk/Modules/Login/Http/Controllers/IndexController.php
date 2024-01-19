<?php

namespace App\Modules\Login\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Login\Http\Requests\{CreateLoginRequest,FrontLoginRequest};
use Illuminate\Support\Facades\Response;
use App\User;
use App\Modules\Login\Models\{LogsPdpa,LogsLogin};
use Auth;
use Socialite;
use Hash;
use Redirect;
class IndexController extends Controller
{
    public function getIndex()
    {
        return view('login::index');
    }

    public function postIndex(CreateLoginRequest $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            return redirect()->route('admin.dashboard.index');
        }
        return redirect()->route('admin.login')
                            ->withInput()
                            ->with('status', 'errors')
                            ->with('message', 'Can\'t Login');
    }

    public function postLogin(FrontLoginRequest $request)
    {
        
        try {
            //dd("test login");
            if (Auth::attempt(['email' => request('username_sign_in'),'password' => request('password_sign_in'),'activate'=>1])) {

                $user_data  = Auth()->user();
                //dd($user_data);
                $headers = [
                    'Content-Type' => 'application/json',
                    'APPKEY' => env('CRM_APPKEY','QqjPh25aBXbDTM6iKjRF8DxAOhMxMCRZS9RK7ztBvqQM')
                ];
                /*Check User*/
                $user_check = request('username_sign_in');
                //$user_check = 'testregister09';
                $body = '{"data":{"username":"'.$user_check.'"}}';

                $client = new \GuzzleHttp\Client([
                                                    'headers' => $headers
                                                ]);
                $request = $client->request('POST',env('URL_CRM_CHECK_MEMBER','http://thaihealthcenter.org/sook_crm2019/api/webservice/chk_member'),['body' =>$body]);    
                $response_api = $request->getBody()->getContents();
                //dd($response_api);
                $data_json = json_decode($response_api, true);
                //dd($data_json,getType($data_json['status']),$body,$headers,env('URL_CRM_CHECK_MEMBER'));

                if($data_json['status'] === '4'){
                     /*Login*/
                    
                    //dd("Case True");
                    $body = '{
                                "data": {
                                    "username": "'.$user_data->email.'",
                                    "password": "'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'"
                                }
                            }';
                    $client = new \GuzzleHttp\Client([
                                                        'headers' => $headers
                                                    ]);
                    $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                    $response_api = $request->getBody()->getContents();
                    $data_json = json_decode($response_api, true);
                    //dd($data_json);
                }else{
                    /*Register*/
                    $body = '{"member":[
                                    {
                                    "prefix":"-",
                                    "nickname":"",
                                    "firstname":"'.explode(" ",$user_data->name)[0].'",
                                    "lastname":"'.explode(" ",$user_data->name)[1].'",
                                    "sex":"",
                                    "address":"",
                                    "district":"",
                                    "amphure":"",
                                    "province":"",
                                    "postalcode":"",
                                    "photo":"",
                                    "tel":"'.$user_data->phone.'",
                                    "email":"'.$user_data->email.'",
                                    "id_cardno":"",
                                    "date_of_birth":"'.$user_data->date_of_birth.'",
                                    "height":"",
                                    "weight":"",
                                    "facebook":"",
                                    "username":"'.$user_data->email.'",
                                    "password":"'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'",
                                    "career":"-",
                                    "career_etc":"",
                                    "corporate":"",
                                    "corporate_etc":""
                                    }
                                    ]
                            }';

                    //dd("Case False",$body,$user_data->email.'-'.env('CRM_SECRET'));
                    $client = new \GuzzleHttp\Client([
                                                        'headers' => $headers
                                                    ]);
                    $request = $client->request('POST',env('URL_CRM_REGISTER','http://thaihealthcenter.org/sook_crm2019/api/webservice/register'),['body' =>$body]);    
                    $response_api = $request->getBody()->getContents();
                    $data_json = json_decode($response_api, true);
                    
                    if($data_json['status'] === '1'){
                        /*Login*/
                            $body = '{
                                        "data": {
                                            "username": "'.$user_data->email.'",
                                            "password": "'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'"
                                        }
                                    }';
                            $client = new \GuzzleHttp\Client([
                                                        'headers' => $headers
                                                    ]);
                            $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            //dd($data_json);
                    }

                }

                LogsLogin::create(['name'=>$user_data->name,'email'=>$user_data->email,'created_by'=>$user_data->id]);
                //dd("Auth Success");
                return redirect()->route('home')
                                 ->with('login_status','success');
            }else{
                //dd("Auth Not Success");
                return redirect()->route('home')
                                 ->with('login_status','not_success');
            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }


    }

    public function postLogout()
    {
        auth()->logout();
        session()->flush();
        return redirect()->route('admin.login');
    }

    public function postFrontLogout()
    {
        auth()->logout();
        session()->flush();
        return redirect()->route('home');
    }

    public function ssoLoginCallbacktest(Request $request)
    {


        try {

            $form_params =  [
                            'grant_type' => 'client_credentials',
                            'scope' => 'user,token'
                            ];
            $credentials = base64_encode(env('SSO_USER','THRC').':'.env('SSO_PASSWORD','sCxYCgphskxXVNz2'));
            $headers =  [
                        'Authorization' =>'Basic ' . $credentials,
                        'Content-Type' => 'application/x-www-form-urlencoded'
                        ];
            $sso_token = '';
            $code ='';
            $state ='';
            $uid =''; 
            $email ='';
            $firstname ='';
            $lastname ='';
            $phone='';
            $user_role = array();


            $client = new \GuzzleHttp\Client(['verify' => false]);
            /* Request Token*/
            $request_api = $client->request('POST', env('URL_SSO_REQUEST_TOKEN','https://sso-api.thaihealth.or.th:9100/oauth2/token'), ['form_params' => $form_params,'headers'=>$headers]);    
            $response_api = $request_api->getBody()->getContents();
            //dd($response_api);
            $data_array = json_decode($response_api, true);
            if(gettype($data_array) =='array'){  
                $sso_token = $data_array['access_token'];
                $code = $request->input('code');
                $state = $request->input('state');

                $form_params =  [
                                'code' => $code,
                                'grant_type' => 'authorization_code',
                                'redirect_uri'=>env('URL_CALLBACK_USER','https://resourcecenter.thaihealth.or.th/sso/login/callback-user'),
                                'client_id'=>env('SSO_USER','THRC')
                                ];
                $headers =  [
                            'Authorization' =>'Bearer ' . $sso_token,
                            'Content-Type' => 'application/x-www-form-urlencoded'
                            ];
                /* Request Get User */
                $request_api_user = $client->request('POST', env('URL_SSO_GET_USER','https://sso.thaihealth.or.th/api/token/validate'), ['form_params' => $form_params,'headers'=>$headers]);    
                $response_api_user = json_decode($request_api_user->getBody()->getContents());
                //dd($response_api_user);


                if(getType($response_api_user) == 'object'){

                    $uid = $response_api_user->uid;
                    $email = $response_api_user->email;
                    $firstname = $response_api_user->firstname;
                    $lastname = $response_api_user->lastname;
                    $phone= $response_api_user->tel;
                    $date_of_birth= $response_api_user->date_of_birth;
                    
                    //$gender = $response_api_user->gender;

                    $headers =  [
                                'Authorization' =>'Bearer ' . $sso_token
                                ];
                    /*Request Get User Role*/
                    $url_sso_get_role = env('URL_SSO_GET_ROLE','https://sso.thaihealth.or.th/api/userrole').'/'.$uid.'/client/'.env('SSO_USER','THRC');
                    $request_api_user_role = $client->request('GET', $url_sso_get_role,['headers'=>$headers]);
                    $response_api_user_role = json_decode($request_api_user_role->getBody()->getContents());

                    if(getType($response_api_user_role) == 'array'){
                        foreach ($response_api_user_role as $value) {
                                array_push($user_role, $value->role_data->code);
                        }
                    }
                }

            }

            //dd($email,$user_role);
            /* Login */
            if(isset($email) && !empty($user_role)){


                //Admin
                //Member

                //THRC-Admin
                //THRC-Member

                $check_user = User::select('id')->where('email','=',$email)->get();
                if($check_user->count() ==0){
                        $data = [
                            'name'     => $firstname.' '.$lastname,
                            'email'    => $email,
                            'phone'    => $phone,
                            'date_of_birth'=>$date_of_birth,
                            'activate' => 1,
                            'password' => bcrypt($email.env('SECRET','BRAVO'))
                        ];
                        $user = User::create($data);
                        if(in_array("THRC-Admin",$user_role)){
                            $user->assign('Admin');
                        }else{
                            $user->assign('Member');
                        }
                }   

                //dd($request->all(),$user_role,$uid,$email,$firstname,$lastname,$phone,$check_user->count());
                if (Auth::attempt(['email' => $email,'password' =>$email.env('SECRET','BRAVO'),'activate'=>1])) {

        
                        $user_data  = Auth()->user();
                        //dd($user_data);
                        $headers = [
                            'Content-Type' => 'application/json',
                            'APPKEY' => env('CRM_APPKEY','QqjPh25aBXbDTM6iKjRF8DxAOhMxMCRZS9RK7ztBvqQM')
                        ];
                        /*Check User*/
                        $user_check = $user_data->email;
                        //$user_check = 'testregister09';
                        $body = '{"data":{"username":"'.$user_check.'"}}';

                        $client = new \GuzzleHttp\Client([
                                                            'headers' => $headers
                                                        ]);
                        $request = $client->request('POST',env('URL_CRM_CHECK_MEMBER','http://thaihealthcenter.org/sook_crm2019/api/webservice/chk_member'),['body' =>$body]);    
                        $response_api = $request->getBody()->getContents();
                        //dd($response_api);
                        $data_json = json_decode($response_api, true);
                        //dd($data_json,getType($data_json['status']),$body,$headers,env('URL_CRM_CHECK_MEMBER'));

                        if($data_json['status'] === '4'){
                             /*Login*/
                            
                            //dd("Case True");
                            $body = '{
                                        "data": {
                                            "username": "'.$user_data->email.'",
                                            "password": "'.md5($user_data->email.'-'.env('CRM_SECRET')).'"
                                        }
                                    }';
                            $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                            $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            //dd($data_json,"Case1");
                        }else{

                            /*Register*/
                            $body = '{"member":[
                                            {
                                            "prefix":"-",
                                            "nickname":"",
                                            "firstname":"'.explode(" ",$user_data->name)[0].'",
                                            "lastname":"'.explode(" ",$user_data->name)[1].'",
                                            "sex":"",
                                            "address":"",
                                            "district":"",
                                            "amphure":"",
                                            "province":"",
                                            "postalcode":"",
                                            "photo":"",
                                            "tel":"'.$user_data->phone.'",
                                            "email":"'.$user_data->email.'",
                                            "id_cardno":"",
                                            "date_of_birth":"'.$user_data->date_of_birth.'",
                                            "height":"",
                                            "weight":"",
                                            "facebook":"",
                                            "username":"'.$user_data->email.'",
                                            "password":"'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'",
                                            "career":"-",
                                            "career_etc":"",
                                            "corporate":"",
                                            "corporate_etc":""
                                            }
                                            ]
                                    }';

                            //dd("Case False",$body,$user_data->email.'-'.env('CRM_SECRET'));
                            $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                            $request = $client->request('POST',env('URL_CRM_REGISTER','http://thaihealthcenter.org/sook_crm2019/api/webservice/register'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            
                            if($data_json['status'] === '1'){
                                /*Login*/
                                    $body = '{
                                                "data": {
                                                    "username": "'.$user_data->email.'",
                                                    "password": "'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'"
                                                }
                                            }';
                                    $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                                    $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                                    $response_api = $request->getBody()->getContents();
                                    $data_json = json_decode($response_api, true);
                                    //dd($data_json,"Case2");
                            }

                        }


                    LogsLogin::create(['name'=>$user_data->name,'email'=>$user_data->email,'created_by'=>$user_data->id]);

                    //dd("Auth Success");
                    if(in_array("THRC-Admin",$user_role)){
                        return redirect()->route('admin.dashboard.index')
                                     ->with('login_status','success');
                    }else{
                        return redirect()->route('home')
                                     ->with('login_status','success');
                    }

                }else{
                    return redirect()->route('home')
                                     ->with('login_status','not_success');
                }

            }else{

                $check_user = User::select('id')->where('email','=',$email)->get();
   
        
                if($check_user->count()==0){
                        $data = [
                            'name'     => $firstname.' '.$lastname,
                            'email'    => $email,
                            'phone'    => $phone,
                            'date_of_birth'=>$date_of_birth,
                            'activate' => 1,
                            'password' => bcrypt($email.env('SECRET','BRAVO'))
                        ];
                        $user = User::create($data);
                        $user->assign('Member');
                }   


                //dd($request->all(),$user_role,$uid,$email,$firstname,$lastname,$phone,$check_user->count());


                if (Auth::attempt(['email' => $email,'password' =>$email.env('SECRET','BRAVO'),'activate'=>1])) {

                        
                        $user_data  = Auth()->user();
                        //dd($user_data);
                        $headers = [
                            'Content-Type' => 'application/json',
                            'APPKEY' => env('CRM_APPKEY','QqjPh25aBXbDTM6iKjRF8DxAOhMxMCRZS9RK7ztBvqQM')
                        ];
                        /*Check User*/
                        $user_check = $user_data->email;
                        //$user_check = 'testregister09';
                        $body = '{"data":{"username":"'.$user_check.'"}}';

                        $client = new \GuzzleHttp\Client([
                                                            'headers' => $headers
                                                        ]);
                        $request = $client->request('POST',env('URL_CRM_CHECK_MEMBER','http://thaihealthcenter.org/sook_crm2019/api/webservice/chk_member'),['body' =>$body]);    
                        $response_api = $request->getBody()->getContents();
                        //dd($response_api);
                        $data_json = json_decode($response_api, true);
                        //dd($data_json,getType($data_json['status']),$body,$headers,env('URL_CRM_CHECK_MEMBER'));

                        if($data_json['status'] === '4'){
                             /*Login*/
                            
                            //dd("Case True");
                            $body = '{
                                        "data": {
                                            "username": "'.$user_data->email.'",
                                            "password": "'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'"
                                        }
                                    }';
                            $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                            $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            //dd($data_json,"Case1");
                        }else{

                            /*Register*/
                            $body = '{"member":[
                                            {
                                            "prefix":"-",
                                            "nickname":"",
                                            "firstname":"'.explode(" ",$user_data->name)[0].'",
                                            "lastname":"'.explode(" ",$user_data->name)[1].'",
                                            "sex":"",
                                            "address":"",
                                            "district":"",
                                            "amphure":"",
                                            "province":"",
                                            "postalcode":"",
                                            "photo":"",
                                            "tel":"'.$user_data->phone.'",
                                            "email":"'.$user_data->email.'",
                                            "id_cardno":"",
                                            "date_of_birth":"'.$user_data->date_of_birth.'",
                                            "height":"",
                                            "weight":"",
                                            "facebook":"",
                                            "username":"'.$user_data->email.'",
                                            "password":"'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'",
                                            "career":"-",
                                            "career_etc":"",
                                            "corporate":"",
                                            "corporate_etc":""
                                            }
                                            ]
                                    }';

                            //dd("Case False",$body,$user_data->email.'-'.env('CRM_SECRET'));
                            $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                            $request = $client->request('POST',env('URL_CRM_REGISTER','http://thaihealthcenter.org/sook_crm2019/api/webservice/register'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            
                            if($data_json['status'] === '1'){
                                /*Login*/
                                    $body = '{
                                                "data": {
                                                    "username": "'.$user_data->email.'",
                                                    "password": "'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'"
                                                }
                                            }';
                                    $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                                    $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                                    $response_api = $request->getBody()->getContents();
                                    $data_json = json_decode($response_api, true);
                                    //dd($data_json,"Case2");
                            }

                        }
                    //dd("Auth Success");
                    LogsLogin::create(['name'=>$user_data->name,'email'=>$user_data->email,'created_by'=>$user_data->id]);
                    return redirect()->route('home')
                                     ->with('login_status','success');
                 
                }else{
              
                    return redirect()->route('home')
                                     ->with('login_status','not_success');
                }

            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }
    public function ssoLoginCallback(Request $request)
    {

        //dd("test");
        try {

            $form_params =  [
                            'grant_type' => 'client_credentials',
                            'scope' => 'user,token'
                            ];
            $credentials = base64_encode(env('SSO_USER','THRC').':'.env('SSO_PASSWORD','sCxYCgphskxXVNz2'));
            $headers =  [
                        'Authorization' =>'Basic ' . $credentials,
                        'Content-Type' => 'application/x-www-form-urlencoded'
                        ];
            $sso_token = '';
            $code ='';
            $state ='';
            $uid =''; 
            $email ='';
            $firstname ='';
            $lastname ='';
            $phone='';
            $user_role = array();


            $client = new \GuzzleHttp\Client(['verify' => false]);
            /* Request Token*/
            $request_api = $client->request('POST', env('URL_SSO_REQUEST_TOKEN','https://sso-api.thaihealth.or.th:9100/oauth2/token'), ['form_params' => $form_params,'headers'=>$headers]);    
            $response_api = $request_api->getBody()->getContents();
            //dd($response_api);
            $data_array = json_decode($response_api, true);
            if(gettype($data_array) =='array'){  
                $sso_token = $data_array['access_token'];
                $code = $request->input('code');
                $state = $request->input('state');

                $form_params =  [
                                'code' => $code,
                                'grant_type' => 'authorization_code',
                                'redirect_uri'=>env('URL_CALLBACK_USER','https://resourcecenter.thaihealth.or.th/sso/login/callback-user'),
                                'client_id'=>env('SSO_USER','THRC')
                                ];
                $headers =  [
                            'Authorization' =>'Bearer ' . $sso_token,
                            'Content-Type' => 'application/x-www-form-urlencoded'
                            ];
                /* Request Get User */
                $request_api_user = $client->request('POST', env('URL_SSO_GET_USER','https://sso.thaihealth.or.th/api/token/validate'), ['form_params' => $form_params,'headers'=>$headers]);    
                $response_api_user = json_decode($request_api_user->getBody()->getContents());
                //dd($response_api_user);


                if(getType($response_api_user) == 'object'){

                    $uid = $response_api_user->uid;
                    $email = $response_api_user->email;
                    $firstname = $response_api_user->firstname;
                    $lastname = $response_api_user->lastname;
                    $phone= $response_api_user->tel;
                    $date_of_birth= $response_api_user->date_of_birth;
                    
                    //$gender = $response_api_user->gender;

                    $headers =  [
                                'Authorization' =>'Bearer ' . $sso_token
                                ];
                    /*Request Get User Role*/
                    $url_sso_get_role = env('URL_SSO_GET_ROLE','https://sso.thaihealth.or.th/api/userrole').'/'.$uid.'/client/'.env('SSO_USER','THRC');
                    $request_api_user_role = $client->request('GET', $url_sso_get_role,['headers'=>$headers]);
                    $response_api_user_role = json_decode($request_api_user_role->getBody()->getContents());

                    if(getType($response_api_user_role) == 'array'){
                        foreach ($response_api_user_role as $value) {
                                array_push($user_role, $value->role_data->code);
                        }
                    }
                }

            }

            //dd($email,$user_role);
            /* Login */
            if(isset($email) && !empty($user_role)){


                //Admin
                //Member

                //THRC-Admin
                //THRC-Member

                $check_user = User::select('id')->where('email','=',$email)->get();
                if($check_user->count() ==0){
                        $data = [
                            'name'     => $firstname.' '.$lastname,
                            'email'    => $email,
                            'phone'    => $phone,
                            'date_of_birth'=>$date_of_birth,
                            'activate' => 1,
                            'password' => bcrypt($email.env('SECRET','BRAVO'))
                        ];
                        $user = User::create($data);
                        if(in_array("THRC-Admin",$user_role)){
                            $user->assign('Admin');
                        }else{
                            $user->assign('Member');
                        }
                }   

                //dd($request->all(),$user_role,$uid,$email,$firstname,$lastname,$phone,$check_user->count());
                if (Auth::attempt(['email' => $email,'password' =>$email.env('SECRET','BRAVO'),'activate'=>1])) {


                        $user_data  = Auth()->user();
                        //dd($user_data);
                        $headers = [
                            'Content-Type' => 'application/json',
                            'APPKEY' => env('CRM_APPKEY','QqjPh25aBXbDTM6iKjRF8DxAOhMxMCRZS9RK7ztBvqQM')
                        ];
                        /*Check User*/
                        $user_check = $user_data->email;
                        //$user_check = 'testregister09';
                        $body = '{"data":{"username":"'.$user_check.'"}}';

                        $client = new \GuzzleHttp\Client([
                                                            'headers' => $headers
                                                        ]);
                        $request = $client->request('POST',env('URL_CRM_CHECK_MEMBER','http://thaihealthcenter.org/sook_crm2019/api/webservice/chk_member'),['body' =>$body]);    
                        $response_api = $request->getBody()->getContents();
                        //dd($response_api);
                        $data_json = json_decode($response_api, true);
                        //dd($data_json,getType($data_json['status']),$body,$headers,env('URL_CRM_CHECK_MEMBER'));

                        if($data_json['status'] === '4'){
                             /*Login*/
                            
                            //dd("Case True");
                            $body = '{
                                        "data": {
                                            "username": "'.$user_data->email.'",
                                            "password": "'.md5($user_data->email.'-'.env('CRM_SECRET')).'"
                                        }
                                    }';
                            $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                            $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            //dd($data_json,"Case1");
                        }else{

                            /*Register*/
                            $body = '{"member":[
                                            {
                                            "prefix":"-",
                                            "nickname":"",
                                            "firstname":"'.explode(" ",$user_data->name)[0].'",
                                            "lastname":"'.explode(" ",$user_data->name)[1].'",
                                            "sex":"",
                                            "address":"",
                                            "district":"",
                                            "amphure":"",
                                            "province":"",
                                            "postalcode":"",
                                            "photo":"",
                                            "tel":"'.$user_data->phone.'",
                                            "email":"'.$user_data->email.'",
                                            "id_cardno":"",
                                            "date_of_birth":"'.$user_data->date_of_birth.'",
                                            "height":"",
                                            "weight":"",
                                            "facebook":"",
                                            "username":"'.$user_data->email.'",
                                            "password":"'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'",
                                            "career":"-",
                                            "career_etc":"",
                                            "corporate":"",
                                            "corporate_etc":""
                                            }
                                            ]
                                    }';

                            //dd("Case False",$body,$user_data->email.'-'.env('CRM_SECRET'));
                            $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                            $request = $client->request('POST',env('URL_CRM_REGISTER','http://thaihealthcenter.org/sook_crm2019/api/webservice/register'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            
                            if($data_json['status'] === '1'){
                                /*Login*/
                                    $body = '{
                                                "data": {
                                                    "username": "'.$user_data->email.'",
                                                    "password": "'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'"
                                                }
                                            }';
                                    $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                                    $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                                    $response_api = $request->getBody()->getContents();
                                    $data_json = json_decode($response_api, true);
                                    //dd($data_json,"Case2");
                            }

                        }


                    LogsLogin::create(['name'=>$user_data->name,'email'=>$user_data->email,'created_by'=>$user_data->id]);

                    //dd("Auth Success");
                    if(in_array("THRC-Admin",$user_role)){
                        return redirect()->route('admin.dashboard.index')
                                     ->with('login_status','success');
                    }else{
                        return redirect()->route('home')
                                     ->with('login_status','success');
                    }

                }else{
                    return redirect()->route('home')
                                     ->with('login_status','not_success');
                }

            }else{

                $check_user = User::select('id')->where('email','=',$email)->get();
                if($check_user->count() ==0){
                        $data = [
                            'name'     => $firstname.' '.$lastname,
                            'email'    => $email,
                            'phone'    => $phone,
                            'date_of_birth'=>$date_of_birth,
                            'activate' => 1,
                            'password' => bcrypt($email.env('SECRET','BRAVO'))
                        ];
                        $user = User::create($data);
                        $user->assign('Member');
                }   

                //dd($request->all(),$user_role,$uid,$email,$firstname,$lastname,$phone,$check_user->count());
                if (Auth::attempt(['email' => $email,'password' =>$email.env('SECRET','BRAVO'),'activate'=>1])) {


                        $user_data  = Auth()->user();
                        //dd($user_data);
                        $headers = [
                            'Content-Type' => 'application/json',
                            'APPKEY' => env('CRM_APPKEY','QqjPh25aBXbDTM6iKjRF8DxAOhMxMCRZS9RK7ztBvqQM')
                        ];
                        /*Check User*/
                        $user_check = $user_data->email;
                        //$user_check = 'testregister09';
                        $body = '{"data":{"username":"'.$user_check.'"}}';

                        $client = new \GuzzleHttp\Client([
                                                            'headers' => $headers
                                                        ]);
                        $request = $client->request('POST',env('URL_CRM_CHECK_MEMBER','http://thaihealthcenter.org/sook_crm2019/api/webservice/chk_member'),['body' =>$body]);    
                        $response_api = $request->getBody()->getContents();
                        //dd($response_api);
                        $data_json = json_decode($response_api, true);
                        //dd($data_json,getType($data_json['status']),$body,$headers,env('URL_CRM_CHECK_MEMBER'));

                        if($data_json['status'] === '4'){
                             /*Login*/
                            
                            //dd("Case True");
                            $body = '{
                                        "data": {
                                            "username": "'.$user_data->email.'",
                                            "password": "'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'"
                                        }
                                    }';
                            $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                            $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            //dd($data_json,"Case1");
                        }else{

                            /*Register*/
                            $body = '{"member":[
                                            {
                                            "prefix":"-",
                                            "nickname":"",
                                            "firstname":"'.explode(" ",$user_data->name)[0].'",
                                            "lastname":"'.explode(" ",$user_data->name)[1].'",
                                            "sex":"",
                                            "address":"",
                                            "district":"",
                                            "amphure":"",
                                            "province":"",
                                            "postalcode":"",
                                            "photo":"",
                                            "tel":"'.$user_data->phone.'",
                                            "email":"'.$user_data->email.'",
                                            "id_cardno":"",
                                            "date_of_birth":"'.$user_data->date_of_birth.'",
                                            "height":"",
                                            "weight":"",
                                            "facebook":"",
                                            "username":"'.$user_data->email.'",
                                            "password":"'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'",
                                            "career":"-",
                                            "career_etc":"",
                                            "corporate":"",
                                            "corporate_etc":""
                                            }
                                            ]
                                    }';

                            //dd("Case False",$body,$user_data->email.'-'.env('CRM_SECRET'));
                            $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                            $request = $client->request('POST',env('URL_CRM_REGISTER','http://thaihealthcenter.org/sook_crm2019/api/webservice/register'),['body' =>$body]);    
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api, true);
                            
                            if($data_json['status'] === '1'){
                                /*Login*/
                                    $body = '{
                                                "data": {
                                                    "username": "'.$user_data->email.'",
                                                    "password": "'.md5($user_data->email.'-'.env('CRM_SECRET','TANGO')).'"
                                                }
                                            }';
                                    $client = new \GuzzleHttp\Client([
                                                                'headers' => $headers
                                                            ]);
                                    $request = $client->request('POST',env('URL_CRM_LOGIN','http://thaihealthcenter.org/sook_crm2019/api/webservice/login'),['body' =>$body]);    
                                    $response_api = $request->getBody()->getContents();
                                    $data_json = json_decode($response_api, true);
                                    //dd($data_json,"Case2");
                            }

                        }

                    //dd("Auth Success");
                    LogsLogin::create(['name'=>$user_data->name,'email'=>$user_data->email,'created_by'=>$user_data->id]);

                    return redirect()->route('home')
                                     ->with('login_status','success');
                 
                }else{
                    return redirect()->route('home')
                                     ->with('login_status','not_success');
                }

            }

        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            return  Response::json($response,500);
        }

    }

    public function ssoLogin(Request $request)
    {   
        $input = $request->all();
        //dd("SSO Login",$input);
        //http://app.pixilla.co.th/w/thc/member/third/login/{$token}?uid={$uid}&email={$email}
        //https://thrc.hap.com/sso/login?token=$2y$10$WCf4zklteiFkzyiwEs9RzO5WV/M2QEgTLX1VIQnPzOPtee3v5EyTO&uid=12345&email=tjtmtcom@outlook.com
        if(isset($input['token']) && Hash::check(env('KEY_PARTNERSHIP'),$input['token']) && isset($input['uid']) && isset($input['email'])){
            //dd("Ok");
            if (Auth::attempt(['email' => $input['email'],'password' =>$input['email'].env('SECRET','BRAVO'),'activate'=>1])) {
                $user_data  = Auth()->user();
                LogsLogin::create(['name'=>$user_data->name,'email'=>$user_data->email,'created_by'=>$user_data->id]);
                return redirect()->route('home')
                ->with('login_status','success');
            }else{
                return Redirect::to(env('URL_SSO_LOGIN'), 301);
            }
            // 
        }else{
            $response['msg'] ='400 Bad Request ';
            $response['status'] =false;
            return  Response::json($response,400);
        }

    }

    public function ssoLoginCallbackUser(Request $request)
    {
        $input = $request->all();
        return Response::json($input,200);
    }

    public function PdpaCallbackSucess(Request $request)
    {
        $input = $request->all();
        $pdpa = 'success';
        $refuid = (isset($input['RefUidKey']) ? $input['RefUidKey']:'');

        //dd($input);
        //return Response::json($input,200);
        //return redirect()->route('home',['pdpa'=>$pdpa,'refuid'=>$refuid]);
        return view('template.home',compact('pdpa','refuid'));
    }

    public function PdpaCallbackFail(Request $request)
    {
        $input = $request->all();
        $pdpa = 'fail';
        //return redirect()->route('home',['pdpa'=>$pdpa]);
        //return view('template.home')->compact('pdpa');
        return view('template.home',compact('pdpa'));
    }

    public function redirectToFacebook(Request $request)
    {
        return Socialite::driver('facebook')->redirect();
    }



    public function handleFacebookCallback()
    {

        $userSocial = Socialite::driver('facebook')->user();
        if(isset($userSocial->id)){

	        $finduser = User::select('id')
	        				  ->where('facebook_id', $userSocial->id)
	        				  ->first();
	        //dd($userSocial);
	        if(isset($finduser->id)){
	            if (Auth::attempt(['email' => $userSocial->email,'password' =>$userSocial->email.env('SECRET','BRAVO'),'activate'=>1])) {
	                $user_data  = Auth()->user();
                    LogsLogin::create(['name'=>$user_data->name,'email'=>$user_data->email,'created_by'=>$user_data->id]);
                    return redirect()->route('home')
	                                 ->with('login_status','success');
	            }else{
	                return redirect()->route('home');
	            }
	        }else{
	                //dd("False");
	            $data = [
	                        'name'     => $userSocial->name,
	                        'email'    => $userSocial->email,
	                        //'phone'    => $phone,
	                        //'date_of_birth'=>$date_of_birth,
	                        'activate' => 1,
	                        'password' => bcrypt($userSocial->email.env('SECRET','BRAVO')),
	                        'facebook_id' =>$userSocial->id
	                    ];
	            //dd($data);
	                $user = User::create($data);
	                $user->assign('Member');
	            if (Auth::attempt(['email' => $userSocial->email,'password' =>$userSocial->email.env('SECRET','BRAVO'),'activate'=>1])) {
                    $user_data  = Auth()->user();
                    LogsLogin::create(['name'=>$user_data->name,'email'=>$user_data->email,'created_by'=>$user_data->id]);
                    return redirect()->route('home')
	                                 ->with('login_status','success');
	            }else{
	                return redirect()->route('home');
	            }
	                        
	        }

        }else{
        	return redirect()->route('home');
        }

       //dd($input);
        //dd("Test Login");
        //return Response::json($input,200);
    }


	public function FacebookDataDeletionRequestCallback(Request $request)
    {
        $input = $request->all();
        //dd("Test");
        $data = '';
        $signed_request = isset($input['signed_request']) ? $input['signed_request']:'';
        if($signed_request !=''){


        //dd($signed_request);
        $data = $this->parse_signed_request($signed_request);
        $user_id = $data['user_id'];
        //User::where('facebook_id','=',$user_id)->update(['status'=>'draft']);

        //Setting::where('slug','test')->update(['value' => $user_id]);
        //dd($data);
        // Start data deletion

        //$status_url = 'https://www.<your_website>.com/deletion?id=abc123'; // URL to track the deletion
        //$confirmation_code = 'abc123'; // unique code for the deletion request

        $data = array(
        'status_code' => 200,
        'msg' => '200 Ok'
        );
        //echo json_encode($data);
    	}

        return Response::json($data,200);
    }   
      
    

    private function parse_signed_request($signed_request) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        $secret = env('FACEBOOK_CLIENT_SECRET'); // Use your app secret here
        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);
        //dd($sig);
        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
          error_log('Bad Signed JSON signature!');
          return null;
        }
        return $data;
    }

    private function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function PdpaUidKey(Request $request)
    {

        try {
            $input = $request->all();
            $RefUidKey = (isset($input['RefUidKey']) ? $input['RefUidKey']:'');
            //dd($input);
 
            $headers =  [
                        'Content-Type' => 'application/json'
                        ];

            $body = '{"UserName":"'.env('PDPA_USER','ResourceCenterUser').'","Password":"'.env('PDPA_PASSWORD','n2VVLJGdGn').'","RefUid":'.env('PDPA_UID','0').',"RefUidKey":"'.$RefUidKey.'","Email":"","ConsRoleId":'.env('PDPA_CONS_ROLE_ID','1').',"UrlSuccess":"","UrlFail":""}';
            //dd($body);
            $client = new \GuzzleHttp\Client();
            $request = $client->request('POST', env('URL_PDPA_CHECKCONSENT','https://pdpa.thaihealth.or.th/service/consents/checkconsent'), ['body' => $body,'headers'=>$headers]);    
            $response_api = $request->getBody()->getContents();
            //$response_api = str_replace(" ","",substr($response_api,3));
            $data_json = json_decode($response_api, true);
            
            if(gettype($data_json) =='array' && $data_json['Code'] == 200){  
                //dd($data_json);
                if($data_json['CheckStatus'] == "01" || $data_json['CheckStatus'] == "02"){
                    //dd("Case 01 02");
                    $response['RefUidKey'] =$data_json['RefUidKey'];
                    $response['CheckStatus'] =$data_json['CheckStatus'];
                    //$RefUidKey = $data_json['RefUidKey'];
                    $UrlSuccess = route('pdpa-success-callback',['key'=>$RefUidKey]);
                    $UrlFail = route('pdpa-fail-callback');
                    //dd($UrlSuccess,$UrlFail);
                    $body = '{"UserName":"'.env('PDPA_USER','ResourceCenterUser').'","Password":"'.env('PDPA_PASSWORD','n2VVLJGdGn').'","RefUid":'.env('PDPA_UID','0').',"RefUidKey":"'.$RefUidKey.'","Email":"","ConsRoleId":'.env('PDPA_CONS_ROLE_ID','1').',"UrlSuccess":"'.$UrlSuccess.'","UrlFail":"'.$UrlFail.'"}';
                    //dd($body);
                    $client = new \GuzzleHttp\Client();
                    $request = $client->request('POST', env('URL_PDPA_CHECKCONSENT','https://pdpa.thaihealth.or.th/service/consents/checkconsent'), ['body' => $body,'headers'=>$headers]);    
                    $response_api = $request->getBody()->getContents();
                    $data_json = json_decode($response_api, true);
                    //dd($data_json,$RefUidKey);
                    if(gettype($data_json) =='array' && $data_json['Code'] == 200){
                        $response['RedirectURL'] =$data_json['RedirectURL'];
                    }
                    //$response['body'] =$body;
                    //$response['json_data'] =$data_json;
                }else{  
                    //dd("CAse 00",$data_json);
                    $response['RefUidKey'] =$data_json['RefUidKey'];
                    $response['CheckStatus'] =$data_json['CheckStatus'];
                    $response['RedirectURL'] =$data_json['RedirectURL'];
                }

                $response['msg'] ='200';
                $response['status_code'] =200;
            }else{
                $response['msg'] ='200';
                $response['status_code'] =200;
            }
            return  Response::json($response,200);
        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['status_code'] =500;
            return  Response::json($response,500);
        }

    }

    public function PdpaReceiveResult(Request $request)
    {

        try {
            $input = $request->all();

            //$response['data'] =$input;
            LogsPdpa::create(['data'=>json_encode($input)]);
            $response['Success'] =true;
            $response['Code'] ='200';
            $response['Message'] ='สำเร็จ';
            return  Response::json($response,200);
        } catch (\Throwable $e) {
            $response['msg'] =$e->getMessage();
            $response['Success'] =false;
            $response['Code'] ='500';
            $response['Message'] =' มีปัญหาที่ฝั่ง Web Services';
            return  Response::json($response,500);
        }

    }




}

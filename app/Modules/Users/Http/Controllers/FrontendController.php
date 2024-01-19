<?php

namespace App\Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Silber\Bouncer\Database\Role;
use Illuminate\Support\Facades\Gate;
use App\User;
use App\Modules\Users\Http\Requests\{CreateFrontUserRequest,FrontForgotPasswordRequest};
use Mail;
use Illuminate\Support\Facades\Hash;
use Auth;

class FrontendController extends Controller
{


    public function postCreate(CreateFrontUserRequest $request)
    {

        $input = $request->all();
        $data = [
            'name'     => request('firstname')." ".request('lastname'),
            'email'    => request('email'),
            'phone'    => request('phone'),
            'status' =>'draft',
            'activate_token' => Hash::make(request('username').env('SECRET').request('email')),
            'password' => bcrypt(request('password')),
            'password_old' =>request('password'),
            'date_of_birth'=>$input['date_of_birth']
        ];
        ///dd($data);

        $user = User::create($data);
        if (count($request->get('roles')) > 0) {
            foreach ($request->input('roles') as $role) {
                $user->assign($role);
            }
        }

        //dd($input,"Success");
        $this->sendMail($data);
        return redirect()->route('home')
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
    }


    public function sendMail($data = []){

        $fields = $data;

        //dd("Send Mail Function",$fields);
        $emailTo = $fields['email'];
        $activate_token_url = route('user.activate',base64_encode($fields['activate_token']));
        
        $fields['emailTo'] = $emailTo;
        $fields['activate_token_url'] =$activate_token_url;
        //dd($fields);

        if(is_array($fields)){
            Mail::send('emails.register', $fields, function ($message) use ($fields){
                $message->from('info@thrc.or.th','THRC');
                $message->to($fields['emailTo'], 'ยินดีต้อนรับ คุณ'.$fields['name'])->subject('ยินดีต้อนรับ '.$fields['name']);
                //dd($message);
            });
        }
        //dd("Send Mail Success",$fields);
        return true;
    }

    public function sendMail2($data = []){

        $fields = $data;

        //dd("Send Mail Function",$fields);
        $emailTo = $fields['email'];
        $activate_token_url = route('user.activate',base64_encode($fields['activate_token']));
        
        $fields['emailTo'] = $emailTo;
        $fields['activate_token_url'] =$activate_token_url;
        //dd($fields);

        if(is_array($fields)){
            Mail::send('emails.register2', $fields, function ($message) use ($fields){
                $message->from('info@thrc.or.th','THRC');
                $message->to($fields['emailTo'], 'ยินดีต้อนรับ คุณ'.$fields['name'])->subject('ยินดีต้อนรับ '.$fields['name']);
                //dd($message);
            });
        }
        //dd("Send Mail Success",$fields);
        return true;
    }

    public function getResendEmail(Request $request)
    {

        ini_set('max_execution_time', 0);
        set_time_limit(0);
    	dd("Success");
        $input = $request->all();
        $user = User::select('name','email','activate_token')
                    ->where('activate','=',0)
                    ->where('activate_token','!=','')
                    ->get();
        dd($user);

        if($user->count() >0){
            foreach ($user as $key => $value) {
                $data = [
                    'name'=> $value->name,
                    'email'=>$value->email,
                    'activate_token'=>$value->activate_token
                ];

                //dd($data); 
                $this->sendMail2($data);              
            }
        }
        //dd($user);
        //dd($input,"Success");
        //$this->sendMail2($data);
        dd("Success");

    }


    public function sendForgotMail($data = []){

        $fields = $data->toArray();

        //dd("Send ForgotMail Function",$fields);
        $emailTo = $fields['email'];
        $forgotpassword_token_url = route('user.getreset',base64_encode($fields['forgotpassword_token']));
        
        $fields['emailTo'] = $emailTo;
        $fields['forgotpassword_token_url'] =$forgotpassword_token_url;
        //dd($fields);

        if(is_array($fields)){
            try{
                Mail::send('emails.forgotpassword', $fields, function ($message) use ($fields){
                    $message->from('info@thrc.or.th','THRC');
                    $message->to($fields['emailTo'], 'ลืมรหัสผ่าน THRC')->subject('ลืมรหัสผ่าน THRC');
                    //dd($message);
                });
            }catch(\Exception $e){
                // Get error here
                dd($e->getMessage());
            }
        }
        //dd("Send Mail Success",$fields);
        return true;
    }


    public function postCheckEmail(Request $request){


        $response = new \StdClass;
        $response->status_code = '200';
        $response->check = true;
        $response->email = $request->input('email');
        $response->msg = '200 OK';
    
        try{
            //self::escape()->quote($request->input('email'))
            $data= User::where('email','like','%'.$request->input('email').'%')->count();
            if($data > 0){
                $response->check = false;
            }
            $response->data = $data;

        } catch(\Exception $e){
            $response->status_code = '500';
            $response->check = false;
            $response->msg = $e->getMessage();
        }

        echo json_encode($response);
    }


    public function getActivate($token){

        $token = base64_decode($token);
        $user_all  = User::Data(['activate'=>[0],'retrieving_results'=>'get']);
        $check_match = false;

        if(collect($user_all)->count()){
            foreach ($user_all as $key => $value) {
                //dd($value);
                $text = $value->username.env('SECRET').$value->email;
                if (Hash::check($text,$token)) {
                    User::where('id',$value->id)->update(['activate'=>'1','status' =>'publish']);
                    $check_match = true;
                }
            }
        }

        if($check_match === true){
            return redirect()->route('home')
                                ->with('activate_status', 'success');
        }else{
            return redirect()->route('home')
                                ->with('activate_status', 'not_success');
        }
        //dd($token);
    }

    public function getReset($token){

        $token = base64_decode($token);
        $user_all  = User::Data(['activate'=>[0,1],'retrieving_results'=>'get']);
        $check_match = false;
        //dd($user_all);
        if(collect($user_all)->count()){
            foreach ($user_all as $key => $value) {
                //dd($value);
                $text = env('SECRET').$value->email;
                if (Hash::check($text,$token)) {
                    $check_match = true;
                }
            }
        }

        if($check_match === true){
            //dd("Match");
            return view('template.reset_password',compact('token'));
        }else{
            return redirect()->route('home')
                                ->with('forgotpassword_status','not_match');
        }
        //dd($token);
    }

    public function postReset(Request $request){

        $input = $request->all();
        $token = $input['forgotpassword_token'];
        //dd($input);
        $user_all  = User::Data(['activate'=>[0,1],'retrieving_results'=>'get']);
        $check_match = false;
        if(collect($user_all)->count()){
            foreach ($user_all as $key => $value) {
                //dd($value);
                $text = env('SECRET').$value->email;
                if (Hash::check($text,$token)) {
                    $password  = bcrypt($input['reset_password']);
                    //dd($password);
                    User::where('id',$value->id)->update(['password'=>$password,'forgotpassword_token'=>'']);   
                    $check_match = true;
                }
            }
        }

        if($check_match === true){
           // dd("Match");
            return redirect()->route('home')
                                ->with('resetpassword_status','success');
        }else{
            //dd("Not Match");
            return redirect()->route('home')
                                ->with('forgotpassword_status','not_match');
        }


    }


    public function postForgotpassword(FrontForgotPasswordRequest $request){

            $input = $request->all();
            //dd($input);
            if(isset($input['email_forgot']) && $input['email_forgot'] !=''){
                //dd("Case True"); 
                $forgotpassword_token = Hash::make(env('SECRET').$input['email_forgot']);
                $user = User::where('email',$input['email_forgot'])->first();
                $user->update(['forgotpassword_token'=>$forgotpassword_token]);
                //dd($forgotpassword_token,$user);
                $this->sendForgotMail($user);
                return redirect()->route('home')
                                ->with('forgotpassword_status', 'success');
            }else{
                //dd("Case False");
                return redirect()->route('home')
                                ->with('forgotpassword_status', 'not_success');
            }


    }


}

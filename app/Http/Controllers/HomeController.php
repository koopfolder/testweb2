<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Contact;
use Mail;
use App\Setting;
use View;
use App\Modules\Menus\Models\Menu;
use App\Modules\Api\Models\Texonomy;
use Auth;
use App\Modules\Api\Models\{ListMedia};
use Hashids\Hashids;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function getIndex(Request $reqeust, $slug=null)
    {
		


        if(auth()->check()){
           $chk=Texonomy::where('user_id',auth()->user()->id)->first();
            if($chk!=null){
                $pop_texonomy=1;
            }else{
                $pop_texonomy=0;
            }

        }else{
            $pop_texonomy=0;

        }


		//dd(env('DB_DATABASE'));
        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                return Redirect::to(URL(\Request::path()), 301);
            }
        }
        $template = 'home';
		
        if (is_null($slug)){
            return view('template.home',compact('pop_texonomy'));
        } else {
            $menu = Menu::select('id','name','slug','description','parent_id','link_type','layout','layout_params','meta_title','meta_keywords','meta_description')
                          ->where('status', 'publish')->where('slug', $slug)->first();
            $template = $menu ? $menu->layout : null;
			
            
            if (view()->exists('template.'.$template)) {
                return view('template.'.$template,compact('menu','pop_texonomy'));
            }
            return abort(404);
        }
    }
    // public function getIndex(Request $reqeust, $slug=null)
    // {
        
    //     if (env('APP_ENV') === 'production') {
    //         if(!\Request::secure()){
    //             return Redirect::to(URL(\Request::path()), 301);
    //         }
    //     }
    //     $template = 'home';
    //     if (is_null($slug)){
    //         return view('template.home');
    //     } else {
    //         $menu = Menu::select('id','name','slug','description','parent_id','link_type','layout','layout_params','meta_title','meta_keywords','meta_description')
    //                       ->where('status', 'publish')->where('slug', $slug)->first();
    //         $template = $menu ? $menu->layout : null;
    //         if (view()->exists('template.'.$template)) {
    //             return view('template.'.$template,compact('menu'));
    //         }
    //         return abort(404);
    //     }
    // }

    public function postContactForm(Request $request)
    {
        $this->validate($request, [
            'title'                => 'required',
            'name'                 => 'required',
            'email'                => 'required|email',
            'phone'                => 'required',
            'message'              => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        $data = [
            'title'   => request('title'),
            'name'    => request('name'),
            'phone'    => request('phone'),
            'email'   => request('email'),
            'message' => request('message'),
            'status'  => 'publish',
        ];

        $response = Contact::create($data);

        // Email admin
        $setting = Setting::where('key', 'email')->first();
        $emailAdmin = 'thinnakorn.pattha@itorama.com';
        if ($setting) {
            $emailAdmin = $setting->value;
        }
        
        $emailSubject = Setting::where('name', 'Email Subject')->first();

        $fields = array(
            'title'      => request('title'),
            'name'       => request('name'),
            'phone'       => request('phone'),
            'email'      => request('email'),
            'body'       => request('message'),
            'emailAdmin' => $emailAdmin,
            'subject'    => request('title')
        );
        if ($response) {
            Mail::send('emails.contact', $fields, function ($message) use ($fields)
            {
                $message->from($fields['email'], $fields['name']);
                $message->to($fields['emailAdmin'], 'Good Amenity')->subject($fields['subject']);
            });
        }
        return redirect()->back()->with('status', 'success')->with('message', trans('frontend.contacts.send-contact-success'));
    }

    public function getPreviewRoomUrl($slug, $module)
    {
        $room = \App\Modules\Room\Http\Controllers\FrontController::getRoomBySlug($slug);
        return view('template.room-detail', compact('room'));
    }

    public function getPreviewMenuUrl($slug)
    {
        $template = 'home';
        if (is_null($slug)){
            $menu = Menu::where('status', 'publish')->where('link_type', 'internal')->first();
            $template = $menu->layout;
            return view('template.' . $template, compact('menu'));
        }else{
            $menu = Menu::where('status', 'publish')->where('slug', $slug)->first();
            $template = $menu ? $menu->layout : null;
            if (view()->exists('template.' . $template)) {
                return view('template.' . $template, compact('menu'));
            }
            return abort(404);
        }
    }

    public function getPreview($slug)
    {
        $data['url'] = \URL::to('preview-url/' . $slug . '/room');
        if (view()->exists('layouts.preview')) {
            return view('layouts.preview', $data);
        }
    }

    public function getPreviewMenu(Request $request, $slug)
    {
        if ($request->get('r') == 1) {
            $data['url'] = \URL::to('room/' . $slug);
            if (view()->exists('layouts.preview')) {
                return view('layouts.preview', $data);
            }
        } else {
            $data['url'] = \URL::to($slug);
            if (view()->exists('layouts.preview')) {
                return view('layouts.preview', $data);
            }
        }
    }


    public function getToken(){
        //dd(csrf_token());
        if(\Request::Ajax()){
            $response = new \stdClass();
            $response->msg = 'Success';
            $response->token = csrf_token();
            $response->status = true;
        }else{
            $response = new \stdClass();
            $response->msg = 'Method Not Allowed';
            $response->status = false;
        }
        return json_encode($response);
    }


    public function postImageUpload(Request $request){
    
    
        $CKEditor = Input::get('CKEditor');
        $funcNum = Input::get('CKEditorFuncNum');
        $message = $url = '';
        if (Input::hasFile('upload')) {
            $file = Input::file('upload');
            if($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/images/', $filename);
                //$url =  '/images/' . $filename;
                $url =  URL('/images/' . $filename);
            }else{
                $message = 'An error occured while uploading the file.';
            }
        } else {
            $message = 'No file uploaded.';
        }
    
        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';

    }

        
    public function seleted_for_you(Request $request){


            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.connect-x.tech/connectx/api/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
            "email": "thaihealth-api@connect-x.tech",
            "password": "ab2cebadecfd0bb638288a88e60bba27d66199b9"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        $data_token=json_decode($response);
        $token=$data_token->access_token;
        curl_close($curl);

 
        $request->description=null;
        if(empty($request->targets)){
            $request->targets='[]';
        }
 
        if(empty($request->issues)){
            $request->issues='[]';
        }
        if(empty($request->tag)){
            $request->tag='[]';
        }
        if(empty($request->setting)){
            $request->setting='[]';
        }
        if(empty($request->category_id)){
            $request->category_id=0;
        }


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://thaihealth.connect-x.tech/recommendation',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "media_id":"'.$request->media_id.'",
            "cookie_id": "'.$request->cookie_id.'",
            "title":  "'.$request->title.'",
            "description": "'.$request->description.'",
            "issues":'.$request->issues.',
            "tag":'.$request->tag.',
            "category_id":'.$request->category_id.',
            "setting":'.$request->setting.',
            "targets":'.$request->targets.'
        }',
        CURLOPT_HTTPHEADER => array(
            'x-token:'.$token,
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
    
        $data_test = json_decode($response);
        $data_arr=[];
        
        $num=0;
        foreach ($data_test->recommended as $key => $row){
             try {
                     $data=ListMedia::where('id',$row)->get()->toArray();


                     $json=json_decode($data[0]['json_data']);
                     $CoverPath=(!empty($json->CoverPath)?$json->CoverPath:null);

                     $ThumbnailAddress=(!empty($json->ThumbnailAddress)?$json->ThumbnailAddress:null);

                // dd($data[0]);
                    if(!empty($data[0]['image_path'])){
                        $image_path = ENV('APP_URL')."/".$data[0]['image_path'];
                    }else
                    if(!empty($data[0]['thumbnail_address'])){
                        $image_path = ENV('APP_URL')."/mediadol/".$data[0]['UploadFileID'].'/'.$data[0]['thumbnail_address'];
                    }else 
                    if(!empty($CoverPath)){
                     $image_path = ENV('APP_URL')."/".$CoverPath;
                    } else {
                        $image_path = $ThumbnailAddress;
                    }
                     $hash = new Hashids();
                     $data_arr[$num]['id']=$data[0]['id'];
                     $data_arr[$num]['title']=$data[0]['title'];
                     $data_arr[$num]['image_path']=$image_path;
                     $data_arr[$num]['created_at']=Carbon::parse($data[0]['created_at'])->format('d.m.').(Carbon::parse($data[0]['created_at'])->format('Y')+543);
                     $data_arr[$num]['hit']=$data[0]['hit'];
                     $data_arr[$num]['url'] =  route('media-detail',$hash->encode($data[0]['id']));
                     $num++;
                } catch (\Throwable $th) {
                    //throw $th;
                }
        }

        

        curl_close($curl);
        
        

        return $data_arr;

    }



}

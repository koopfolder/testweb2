<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Contact;
use Mail;
use App\Setting;
use View;
use App\Modules\Menus\Models\Menu;
use Input,Redirect;

class HomeController extends Controller
{
    public function getIndex(Request $reqeust, $slug=null)
    {
        if (env('APP_ENV') === 'production') {
            if(!\Request::secure()){
                return Redirect::to(URL(\Request::path()), 301);
            }
        }
        $template = 'home';
        if (is_null($slug)){
            return view('template.home');
        } else {
            $menu = Menu::select('id','name','slug','description','parent_id','link_type','layout','layout_params','meta_title','meta_keywords','meta_description')
                          ->where('status', 'publish')->where('slug', $slug)->first();
            $template = $menu ? $menu->layout : null;
            if (view()->exists('template.'.$template)) {
                return view('template.'.$template,compact('menu'));
            }
            return abort(404);
        }
    }

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



}

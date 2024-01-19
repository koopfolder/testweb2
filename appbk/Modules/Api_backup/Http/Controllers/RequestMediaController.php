<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Models\{RequestMediaDetail,RequestMedia,RequestMediaEmail};
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use Mail;
class RequestMediaController extends Controller
{
    public function getIndex()
    {

        $items = RequestMedia::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('api::backend.request_media.index', compact('items'));
    }


    public function postFrontCreate(Request $request)
    {   
        $data = $request->all();
        $data['name'] =$data['firstname']." ".$data['lastname'];
        $data['status'] ='publish';
        //dd($data);
        $item = RequestMedia::create($data);
        $this->sendMail($data);
        //dd("Create success");
        return redirect()->route('home')
                                ->with('request_media', 'success')
                                ->with('message', 'Successfully');
    }


    public function sendMail($data = []){

        $fields = $data;
        $email_to = RequestMediaEmail::Email(['status'=>['publish']])->pluck('email')->toArray();
        //dd("Send Mail Function",$fields,$email_to);

        //dd($email_to);

        //$emailTo = $fields['email'];
        $fields['emailTo'] = $email_to['0'];
        //dd($fields,$email_to);

        if(is_array($fields)){
            Mail::send('emails.request_media', $fields, function ($message) use ($fields){
                $message->from('info@thrc.or.th','THRC');
                $message->to($fields['emailTo'], 'THRC ขอรับสื่อ')->subject('THRC ขอรับสื่อ');
                //dd($message);
            });
        }
        //dd("Send Mail Success",$fields);
        return true;
    }


    public function getRequestMediaFront()
    {
        return view('template.request_media_case_front');
    }



}

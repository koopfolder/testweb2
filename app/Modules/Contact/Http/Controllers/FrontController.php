<?php
namespace App\Modules\Contact\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Contact\Models\Contact;
use App\Modules\Contact\Models\Contactsubject;
use Validator;
use Mail;

class FrontController extends Controller
{
    public function postContact(Request $request)
    {

        $this->validate($request, [
            'name'                 => 'required|max:255',
            'subject_id'              => 'required',
            'email'                => 'required|email',
            'message'              => 'required',
            //'g-recaptcha-response' => 'required',
        ]);
        //dd("Contact");

        $data = Contact::create($request->all());
        //dd($request->all(),'Success');

        (new \App\Modules\Contact\Http\Controllers\FrontController)->sendMail($data);
        return redirect()->back()->withInput(['success'=>true]);
    }

    public function sendMail($data = []){

        $fields = $data->toArray();
        $emailAdmin = Contactsubject::select('email')->find($fields['subject_id']);
        //dd($fields,$emailAdmin->email);
        // $emailAdmin = (new \App\Modules\Setting\Http\Controllers\FrontController)->getKeyAndReturnValue('email_general');
        $emailAdmin = $emailAdmin->email;
        $fields['body'] = $fields['message'];
        $fields['emailAdmin'] = $emailAdmin;
        if (is_array($fields)) {
            Mail::send('emails.contact', $fields, function ($message) use ($fields){
                $message->from($fields['email'], $fields['name']);
                $message->to($fields['emailAdmin'], 'THRC Contact Us')->subject($fields['message']);
            });
        }

       //dd("Send Mail Success",$fields);
    }


    public static function getSubject($params){
       $data = Contactsubject::DataDropdown(['status'=>['publish']]);
       return $data;
    }

}

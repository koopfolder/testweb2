<?php

namespace App\Modules\Joinus\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Modules\Joinus\Http\Requests\{ApplyRequest};
use App\Modules\Joinus\Models\{Careers,Joinus};
use RoosterHelpers;
use Junity\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Mail;
use Carbon\Carbon;

class FrontController extends Controller
{
    public static function getData()
    {
        $items = Careers::FrontData(['status'=>'publish']);
        return $items;
    }


    public function applyJob($id){
        $id = Hashids::decode($id);
        if(collect($id)->isNotEmpty()){
            $data = Careers::Detail(['id'=>$id]);
            //dd(collect($data)->isNotEmpty());
            if(collect($data)->isNotEmpty()){
                return view('joinus::frontend.applyjob.index')->with(['data'=>$data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }

    public function postStore(ApplyRequest $request){

        $lang = \App::getLocale();
        $data = $request->all();

        //dd($data);

        if($lang  ==='th'){
            $data['birthdate'] = ($data['year']-543)."-".$data['month']."-".$data['day'];
        }else{
            $data['birthdate'] = $data['year']."-".$data['month']."-".$data['day'];
        }
        $item = Joinus::create($data);
        if($request->hasFile('attached_images')){
            //dd("File");
            $destinationPath =  public_path().'/files/applyjob/'.$item->id; // upload path
            $destinationPath2 = '/files/applyjob/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('attached_images')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."_attached_images".'.'.$extension; // renameing image
                Input::file('attached_images')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["attached_images"=>$path_file]);
            }else{
                $extension = Input::file('attached_images')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."_attached_images".'.'.$extension; // renameing image
                Input::file('attached_images')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["attached_images"=>$path_file]);
            }
        }

        if($request->hasFile('attachment_history')){
            //dd("File");
            $destinationPath =  public_path().'/files/applyjob/'.$item->id; // upload path
            $destinationPath2 = '/files/applyjob/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('attachment_history')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."attachment_history".'.'.$extension; // renameing image
                Input::file('attachment_history')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["attachment_history"=>$path_file]);
            }else{
                $extension = Input::file('attachment_history')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."attachment_history".'.'.$extension; // renameing image
                Input::file('attachment_history')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["attachment_history"=>$path_file]);
            }
        }

        if($request->hasFile('other_documents')){
            //dd("File");
            $destinationPath =  public_path().'/files/applyjob/'.$item->id; // upload path
            $destinationPath2 = '/files/applyjob/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('other_documents')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."other_documents".'.'.$extension; // renameing image
                Input::file('other_documents')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["other_documents"=>$path_file]);
            }else{
                $extension = Input::file('other_documents')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."other_documents".'.'.$extension; // renameing image
                Input::file('other_documents')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["other_documents"=>$path_file]);
            }
        }

        if($request->hasFile('other_documents_2')){
            //dd("File");
            $destinationPath =  public_path().'/files/applyjob/'.$item->id; // upload path
            $destinationPath2 = '/files/applyjob/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('other_documents_2')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."other_documents_2".'.'.$extension; // renameing image
                Input::file('other_documents_2')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["other_documents_2"=>$path_file]);
            }else{
                $extension = Input::file('other_documents_2')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."other_documents_2".'.'.$extension; // renameing image
                Input::file('other_documents_2')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["other_documents_2"=>$path_file]);
            }
        }

        if($request->hasFile('other_documents_3')){
            //dd("File");
            $destinationPath =  public_path().'/files/applyjob/'.$item->id; // upload path
            $destinationPath2 = '/files/applyjob/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('other_documents_3')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."other_documents_3".'.'.$extension; // renameing image
                Input::file('other_documents_3')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["other_documents_3"=>$path_file]);
            }else{
                $extension = Input::file('other_documents_3')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis')."other_documents_3".'.'.$extension; // renameing image
                Input::file('other_documents_3')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(["other_documents_3"=>$path_file]);
            }
        }

        $this->sendMail($item);

        //dd("Save Success");
        //dd($data,"insert Success");

        $url= redirect()->back()->getTargetUrl()."?success=1";
        return redirect()->to($url);
    }

    public function sendMail($data = []){

        $fields = $data->toArray();

        if($fields['prefix'] =='2'){
            $fields['prefix'] = 'Mrs.';
        }elseif($fields['prefix'] =='3'){
            $fields['prefix'] = 'Miss.';
        }elseif($fields['prefix'] =='4'){
            $fields['prefix'] = 'Ms.';
        }else{
            $fields['prefix'] = 'Mr.';
        }
        $fields['position_id'] =  Careers::select('position_en','id')->where('id',$fields['position_id'])->first()->position_en;
        if($fields['sex'] =="M"){
            $fields['sex'] = 'Male';
        }else{
            $fields['sex'] = 'Female';
        }
        if($fields['id_type'] =="identification_number"){
            $fields['id_type'] = 'Identification Number';
        }else{
            $fields['id_type'] = 'Passport Number';
        }
        $fields['birthdate'] = Carbon::parse($fields['birthdate'])->format('d-m-Y');

        if(array_key_exists('attached_images', $fields)){
            if($fields['attached_images'] !=''){
                $fields['attached_images'] = URL($fields['attached_images']);
            }
        }else{
            $fields['attached_images'] = '';
        }

        if(array_key_exists('attachment_history', $fields)){
            if($fields['attachment_history'] !=''){
                $fields['attachment_history'] = URL($fields['attachment_history']);
            }
        }else{
            $fields['attachment_history'] = '';
        }

        if(array_key_exists('other_documents', $fields)){
            if($fields['other_documents'] !=''){
                $fields['other_documents'] = URL($fields['other_documents']);
            }
        }else{
            $fields['other_documents'] = '';
        }

        if(array_key_exists('other_documents_2', $fields)){
            if($fields['other_documents_2'] !=''){
                $fields['other_documents_2'] = URL($fields['other_documents_2']);
            }
        }else{
            $fields['other_documents_2'] = '';
        }

        if(array_key_exists('other_documents_3', $fields)){
            if($fields['other_documents_3'] !=''){
                $fields['other_documents_3'] = URL($fields['other_documents_3']);
            }
        }else{
            $fields['other_documents_3'] = '';
        }


        //dd("Send Mail Function",$fields);
        //$emailAdmin = 'tjtmtcom1@gmail.com';
        //$emailAdmin = 'sivipa@pylon.co.th';
        $emailAdmin = 'recruit@pylon.co.th';
        
        //$emailAdmin = 'supawadee.phooim@itorama.com';
        
        $fields['emailAdmin'] = $emailAdmin;
        if(is_array($fields)){
            Mail::send('emails.applyjob', $fields, function ($message) use ($fields){
                $message->from('info@pylon.co.th','Pylon');
                if($fields['attached_images'] !=''){
                    $message->attach($fields['attached_images']);
                }
                if($fields['attachment_history'] !=''){
                    $message->attach($fields['attachment_history']);
                }
                if($fields['other_documents'] !=''){
                    $message->attach($fields['other_documents']);
                }
                if($fields['other_documents_2'] !=''){
                    $message->attach($fields['other_documents_2']);
                }
                if($fields['other_documents_3'] !=''){
                    $message->attach($fields['other_documents_3']);
                }
                $message->to($fields['emailAdmin'], 'Pylon Job Application')->subject('Pylon Job Application');
            });
        }
        //dd("Send Mail Success",$fields);
    }


}

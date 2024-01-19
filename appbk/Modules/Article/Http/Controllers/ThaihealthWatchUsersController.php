<?php

namespace App\Modules\Article\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Article\Http\Requests\{CreateSendEmailRequest};
use App\Modules\Article\Models\{ThaiHealthWatchUsers,ThaiHealthWatchSendEmailLogs};
use App\Modules\Documentsdownload\Models\{Documents};
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Cache;
use Mail;
use ThrcHelpers;
use Excel;

class ThaihealthWatchUsersController extends Controller
{
    public function getIndex()
    {
        //dd("News");
        $items = ThaiHealthWatchUsers::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('article::backend.thaihealth-watch-users.index', compact('items'));
    }

    public function getSendEmailIndex()
    {
        //dd("News");
        $items = ThaiHealthWatchSendEmailLogs::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('article::backend.thaihealth-watch-users.send_email_index', compact('items'));
    }

    public function getCreate()
    {
        $users = ThaiHealthWatchUsers::Data(['status'=>['publish','draft']])->pluck('name','email');
        //dd($users);
        return view('article::backend.thaihealth-watch-users.create',compact('users'));
    }
    

    public function postCreate(CreateSendEmailRequest $request)
    {
        $data = $request->all();
        $data['to_send'] = $data['to'];
        $data['to'] = json_encode($data['to']);
        $data['status'] = 'publish';
        $data['attach'] = [];
        //dd($data);
         
        $item = ThaiHealthWatchSendEmailLogs::create($data);
        $id = $item->id;
       
        if($request->hasFile('document')){
           
            $destinationPath =  public_path().'/files/attached_file/'.$item->id; // upload path
            $destinationPath2 = '/files/attached_file/'.$item->id;

            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    Documents::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'model_type'=>'thaihealth_watch_send_email','file_path'=>$path_file,'model_id'=>$id]);
                    array_push($data['attach'],public_path().$path_file);
                }
            }else{
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    Documents::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'model_type'=>'thaihealth_watch_send_email','file_path'=>$path_file,'model_id'=>$id]);
                    array_push($data['attach'],public_path().$path_file);
                }
            }
       
        }

        $this->sendMail($data);
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['subject'].'"','module_id'=>'44']);
        return redirect()->route('admin.thaihealth-watch.users.logs-send-email.index')
                            ->with('status', 'success')
                            ->with('message', trans('article::backend.send_email_successfully'));
    }

  

    public function sendMail($data = []){

        ini_set('max_execution_time', 0);
        ini_set('request_terminate_timeout', 0);
        set_time_limit(0);

        $fields = $data;
        //dd($fields);
        $fields['body'] = $fields['description'];
        $fields['email']= 'thaihealth-noreply@thaihealth.or.th';
        $fields['emailAdmin']=$fields['to_send'];
        if (is_array($fields)) {
            Mail::send('emails.th_watch', $fields, function ($message) use ($fields){
                $message->from($fields['email'], 'ThaiHealthWatch');
                $message->to($fields['emailAdmin'])->subject($fields['subject']);
                //$message->attach($fields['attach']);
                if($fields['attach']){
                    foreach($fields['attach'] AS $val_attach){
                        $message->attach($val_attach);
                    }
                }
            });
        }

       //dd("Send Mail Success",$fields);
    }    
   

    public function postAjaxDeleteDocument(Request $request){
        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                $id = $inputs['id'];

                $item = Documents::findOrFail($id);
                $item->delete();

                $response['msg'] ='sucess';
                $response['status'] =true;
                $response['data'] =$inputs;
                return  Response::json($response,200);
            }else{
                $response['msg'] ='Method Not Allowed';
                $response['status'] =false;
                $response['data'] = '';
                return  Response::json($response,405);
            }
        }catch (\Exception $e){
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            $response['data'] = '';
            return  Response::json($response,500);
        }
    }

    public function getExcelReport(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $input = $request->all();
        $date_now = date('Y-m-d-H-i-s');
        $file_name = 'thaihealth-watch-register'."-".$date_now;
        $type = 'xlsx';
        $items = ThaiHealthWatchUsers::DataReport(['status'=>['publish','draft']])->toArray();

        //dd($items);
        
        return Excel::create($file_name, function($excel) use ($items) {
            $excel->sheet('mySheet', function($sheet) use ($items)
            {
                $sheet->fromArray($items,null, 'A1', true);
                $sheet->row(1, array(
                    trans('article::backend.name_surname'),
                    trans('article::backend.agency'),
                    trans('article::backend.email'),
                    trans('article::backend.phone'),
                    trans('article::backend.date_register')
                ));
            });
        })->download($type);
        //dd("Get Excel Report");
    }


    public function getDelete($id)
    {
        $item = ThaiHealthWatchUsers::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = ThaiHealthWatchUsers::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
            }
            return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->back()
                        ->with('status', 'error')
                        ->with('message', 'Not users');
    }

}


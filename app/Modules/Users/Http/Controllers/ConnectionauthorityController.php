<?php

namespace App\Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Connection\ConnectionAuthority;

use App\Modules\Users\Http\Requests\CreateConnectionauthorityRequest;
use App\Modules\Users\Http\Requests\EditConnectionauthorityRequest;

class ConnectionauthorityController extends Controller
{
    public function getIndex(){
        
        $connectionauthority = ConnectionAuthority::where('client_status', '!=', 2)->orderBy('id', 'DESC')->get();
        // $connectionauthority = ConnectionAuthority::orderBy('id', 'DESC')->get();
        return view('users::connectionauthority_index', compact('connectionauthority'));
    }

    public function getCreate(){
        $vid = ConnectionAuthority::latest('id')->value('id');
       
        if($vid == null){
            $vid = 1;
            $fnumber = sprintf('%04d', $vid);
        }else {
            $fnumber = sprintf('%04d', ($vid+1));
        }
       
        $gen_client_id = 'A'.$fnumber;
        // dd($fnumber);
        return view('users::connectionauthority_create', compact('gen_client_id'));
    }

    public function postCreate(CreateConnectionauthorityRequest $request){
        // dd(request('client_status'));
        $data = [
            'client_id'     => request('client_id'),
            'client_company_name' => request('client_company_name'),
            'client_status'    => request('client_status'),
            'client_password' => bcrypt(request('password')),
            'created_at' => date('Y-m-d H:i:s')
        ];
        // dd($data);
        $connectionauthority = ConnectionAuthority::create($data);
        if($connectionauthority){
            return redirect()->route('admin.connectionauthority.index')
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
        }
    }

    public function getEdit($id)
    {
        $connectionauthority = ConnectionAuthority::findOrFail($id);
        // dd($connectionauthority);
        return view('users::connectionauthority_edit', compact('connectionauthority'));
    }

    public function postEdit(EditConnectionauthorityRequest $request, $id)
    {
        $connectionauthority = ConnectionAuthority::findOrFail($id);
        if($request['password'] !=''){
            $data = [
                'client_company_name' => request('client_company_name'),
                'client_password' => bcrypt(request('password')),
                'client_status' => request('client_status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }else {
            $data = [
                'client_company_name' => request('client_company_name'),
                'client_status' => request('client_status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        

        $connectionauthority = ConnectionAuthority::where('id', $id)->update($data);
        if($connectionauthority){
            return redirect()->route('admin.connectionauthority.index')
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
        }
        // dd($data);
    }

    public function getDelete($id)
    {
        // $connectionauthority = ConnectionAuthority::findOrFail($id);
         // $connectionauthority->delete();
        $data = [
            'client_status' => 2
        ];
        $connectionauthority = ConnectionAuthority::where('id', $id)->update($data);
        // dd($connectionauthority);
       
        if($connectionauthority){
            return redirect()->route('admin.connectionauthority.index')
            ->with('status', 'success')
            ->with('message', 'Successfully');
        }
       
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $data = [
                'client_status' => 2
            ];
            $entries = ConnectionAuthority::whereIn('id', $request->input('ids'))->get();
           
            foreach ($entries as $entry) {
                // dd($entry);
                // $entry->clearMediaCollection();
                // $entry->delete();
                $entry->update($data);
            }
            return redirect()->route('admin.connectionauthority.index')
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
        }

        return redirect()->route('admin.connectionauthority.index')
                            ->with('status', 'error')
                            ->with('message', 'Not Connection Authority');
                        
    }


   
}

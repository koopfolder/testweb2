<?php

namespace App\Modules\Users\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Silber\Bouncer\Database\Role;
use Illuminate\Support\Facades\Gate;
use App\Modules\Users\Http\Requests\CreateUserRequest;
use App\Modules\Users\Http\Requests\EditUserRequest;
use App\Modules\Settings\Http\Requests\CreateSettingRequest;
use App\User;
use App\Setting;
use Excel;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $users = User::orderBy('id', 'DESC')->get();
        return view('users::index', compact('users'));
    }
    
    public function getCreate()
    {
        $roles = Role::where('name', '!=', 'Administrator')->where('status', 'publish')->get()->pluck('name', 'name');
        return view('users::create', compact('roles'));
    }

    public function postCreate(CreateUserRequest $request)
    {
        $data = [
            'name'     => request('name'),
            'email'    => request('email'),
            'phone'    => request('phone'),
            'password' => bcrypt(request('password'))
        ];

        $user = User::create($data);
        if ( count($request->get('roles')) > 0) {
            foreach ($request->input('roles') as $role) {
                $user->assign($role);
            }
        }

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
                    $body = '{"device_token":"thrc_backend","users":'.json_encode($user).'}';
                            //dd($body);
                    $client = new \GuzzleHttp\Client();
                    $request = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_UPDATE_USERS'), [
                                                                'headers'=>[
                                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                                            'authorization'=>$access_token
                                                                           ],
                                                                'body' => $body
                                                        ]);             
                    $response_api = $request->getBody()->getContents();
                    $data_json = json_decode($response_api);
                   //dd($data_json);
            }           

        if ($request->hasFile('avatar')) {
            $avatar = User::find($user->id);
            $avatar
                    ->addMedia($request->file('avatar'))
                    ->toMediaCollection('avatar');
        }

        self::postLogs(['event'=>'เพิ่มข้อมูลสมาชิก "'.$data['name'].'"','module_id'=>'20']);
        if ($request->get('redirect') == 'save') {
            return redirect()->route('admin.users.index')
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
        } else {
            return redirect()->route('admin.users.edit', $user->id)
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
        }
    }

    public function getEdit($id)
    {
        $roles = Role::where('name', '!=', 'Administrator')->where('status', 'publish')->get()->pluck('name', 'name');
        $user = User::findOrFail($id);
        return view('users::edit', compact('user', 'roles'));
    }

    public function postEdit(EditUserRequest $request, $id)
    {

        $user = User::findOrFail($id);
        if($request['password'] !=''){
            $data = [
            'name'     => request('name'),
            'phone'    => request('phone'),
            'email'    => request('email'),
            'password' => bcrypt(request('password')),
            'status' =>request('status')
            ];
        }else{
            $data = [
            'name'     => request('name'),
            'phone'    => request('phone'),
            'email'    => request('email'),
            'status' =>request('status')
            ];
        }

        User::where('id', $id)
            ->update($data);
        $user->update($data);


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
                $body = '{"device_token":"thrc_backend","users":'.json_encode($data).'}';
                            //dd($body);
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_UPDATE_USERS'), [
                                                                'headers'=>[
                                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                                            'authorization'=>$access_token
                                                                           ],
                                                                'body' => $body
                                                        ]);             
                $response_api = $request->getBody()->getContents();
                $data_json = json_decode($response_api);
                   //dd($data_json);
        }     


        //dd($data);

        if ($user->roles->count() > 0) {
            foreach ($user->roles as $role) {
                $user->retract($role);
            }
        }
        //dd($request->get('roles'));
        if (count($request->get('roles'))) {
            foreach ($request->get('roles') as $role) {
                $user->assign($role);
            }
        }

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection();
            $item = User::find($id);
            $item->addMedia($request->file('avatar'))->toMediaCollection('avatar');
        }

        self::postLogs(['event'=>'แก้ไขข้อมูลสมาชิก "'.$data['name'].'"','module_id'=>'20']);
        if ($request->get('redirect') == 'save') {
            return redirect()->route('admin.users.index')
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
        } else {
            return redirect()->route('admin.users.edit', $id)
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
        }

    }

    public function getDelete($id)
    {
        $user = User::findOrFail($id);
        $user->clearMediaCollection();
        $user->delete();
        return redirect()->route('admin.users.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
            }
            return redirect()->route('admin.users.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.users.index')
                        ->with('status', 'error')
                        ->with('message', 'Not users');
                        
    }

    public function getExport($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->route('admin.users.index')
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('users', function($excel) {
            $users = User::select('id', 'name', 'email', 'created_at')->get();
            $excel->sheet('users', function($sheet) use ($users) {
                $sheet->fromArray($users);
            });
        })->export('xls'); 

    }

    public function getSetting()
    {
        $settings = Setting::where('module', 'users')->get();
        return view('users::setting', compact('settings'));
    }

    public function postSetting(CreateSettingRequest $request)
    {
        Setting::create($request->all());
        return redirect()->route('admin.users.setting')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postSettingDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Setting::where('module', 'users')->whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->delete();
            }

            return redirect()->route('admin.users.setting')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.users.setting')
                        ->with('status', 'error')
                        ->with('message', 'Not users');
    }

    public function getDeleteImage($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->clearMediaCollection('avatar');
            return redirect()
                    ->route('admin.users.edit', $id)
                    ->with('status', 'success')
                    ->with('message', 'Successfully');
        }

        return redirect()
                    ->route('admin.users.index')
                    ->with('status', 'error')
                    ->with('message', 'Not data');
    }
}

<?php

namespace App\Modules\Profile\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Profile\Http\Requests\EditRequest;
use App\User;

class IndexController extends Controller
{
    public function getIndex()
    {
    	return view('profile::index');
    }

    public function postIndex(EditRequest $request)
    {
    	$user = User::find(auth()->user()->id);
    	$user->update([
			'name'     => $request->get('name'),
			'password' => bcrypt($request->get('password'))
    	]);

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection();
            $user->addMedia($request->file('avatar'))->toMediaCollection('avatar');
        }

        return redirect()->route('admin.profile.index')
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
    }

    public function getDeleteAvatar(Request $request, $id)
    {
        $user = User::find($id);
        $user->clearMediaCollection('avatar');
        return redirect()->route('admin.profile.index')
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
    }


    
    public function postFront(EditRequest $request)
    {
        //dd(auth()->user()->id,$request->all());
        $data = $request->all();
       

    	$user = User::find(auth()->user()->id);
    	$user->update([
			'name'     => $request->get('name'),
			'password' => bcrypt($request->get('password')),
            'date_of_birth'=>$data['date_of_birth']
    	]);

        //dd($data,date('Y-m-d',strtotime($data['date_of_birth'])),$data['date_of_birth']);

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection();
            $user->addMedia($request->file('avatar'))->toMediaCollection('avatar');
        }
        //$this->sendMail($data);
        return redirect()->route('home')
        ->with('update', 'success')
        ->with('message', 'Successfully');
    }
}

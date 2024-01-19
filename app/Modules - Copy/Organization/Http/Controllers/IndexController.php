<?php

namespace App\Modules\Organization\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Organization\Models\{Organization};
use RoosterHelpers;

class IndexController extends Controller
{


    public function getCreate()
    {
        $data = Organization::where('id','=','1')->first();
        return view('organization::create', compact('data'));
    }

    public function postCreate(CreateRequest $request)
    {
        $check = Organization::where('id','=','1')->get()->count();
        if($check > 0){

            $data = $request->except('desktop_th','desktop_en','mobile_th','mobile_en','_token');

            $item = Organization::findOrFail(1);
            //dd($id,$data);
            $item->update($data);
            //dd("Update Success");
            if($request->hasFile('desktop_th')) {
                $item->clearMediaCollection('desktop_th');
                $item->addMedia($request->file('desktop_th'))->toMediaCollection('desktop_th');
            }
            if($request->hasFile('desktop_en')){
                $item->clearMediaCollection('desktop_en');
                $item->addMedia($request->file('desktop_en'))->toMediaCollection('desktop_en');
            }
            if($request->hasFile('mobile_th')){
                $item->clearMediaCollection('mobile_th');
                $item->addMedia($request->file('mobile_th'))->toMediaCollection('mobile_th');
            }
            if($request->hasFile('mobile_en')){
                $item->clearMediaCollection('mobile_en');
                $item->addMedia($request->file('mobile_en'))->toMediaCollection('mobile_en');
            }


        }else{

            $data = $request->except('desktop_th','desktop_en','mobile_th','mobile_en');
            $data['id'] =1;
            $item = Organization::create($data);
                        //dd($data,$check);
            if($request->hasFile('desktop_th')) {
                $item->addMedia($request->file('desktop_th'))->toMediaCollection('desktop_th');
            }
            if($request->hasFile('desktop_en')) {
                $item->addMedia($request->file('desktop_en'))->toMediaCollection('desktop_en');
            }

            if($request->hasFile('mobile_th')) {
                $item->addMedia($request->file('mobile_th'))->toMediaCollection('mobile_th');
            }

            if($request->hasFile('mobile_en')) {
                $item->addMedia($request->file('mobile_en'))->toMediaCollection('mobile_en');
            }

        }

        return redirect()->route('admin.organization.index')
                            ->with('status', 'success')
                            ->with('message', trans('organization::backend.successfully'));
    }

    public function getDelete($id)
    {
        // $item = History::findOrFail($id);
        // $item->delete();
        // return redirect()->back()
        //                     ->with('status', 'success')
        //                     ->with('message', 'Successfully');
    }
           
}

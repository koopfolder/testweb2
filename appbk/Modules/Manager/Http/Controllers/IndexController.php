<?php

namespace App\Modules\Manager\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Manager\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Manager\Models\{Manager,ManagerRevision,Categories};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;

class IndexController extends Controller
{
    public function getIndex()
    {
        $items = Manager::Data(['publish','draft']);
        //dd($items);
        return view('manager::index', compact('items'));
    }

    public function getCreate()
    {
        $categories = Categories::DataDropdown(['publish']);
        return view('manager::create', compact('categories'));
    }

    public function postCreate(CreateRequest $request)
    {

        $data = $request->all();
        $item = Manager::create($data);
        $id = $item->id;
        $data['board_and_management_id'] = $id;
        ManagerRevision::create($data);

        if($request->hasFile('desktop')) {
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }
        if($request->hasFile('mobile')) {
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }
        return redirect()->route('admin.manager.index')
                            ->with('status', 'success')
                            ->with('message', trans('manager::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $categories = Categories::DataDropdown(['publish']);
        $data = Manager::findOrFail($id);
        $revisions = ManagerRevision::where('board_and_management_id', $id)->orderBy('created_at','DESC')->get();
       //dd($categories,$data,$revisions);
        return view('manager::edit', compact('data','revisions','categories'));
    }

    public function postEdit(EditRequest $request, $id)
    {

        $item = Manager::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);
        $item->update($data);
        $data['board_and_management_id'] = $id;
        ManagerRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item->clearMediaCollection('desktop');
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item->clearMediaCollection('mobile');
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }
        //dd("Update Success");
        return redirect()->route('admin.manager.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Manager::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Manager::whereIn('id', $request->input('ids'))->get();
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

    public function getReverse($id)
    {
        $reverse = ManagerRevision::find($id);
        $ManagerId = $reverse->board_and_management_id;
        $fields = array_except($reverse->toArray(),['board_and_management_id','created_by']);
        $manager = Manager::find($ManagerId);
        $manager->name_th = $fields['name_th'];
        $manager->name_en = $fields['name_en'];
        $manager->position_th = $fields['position_th'];
        $manager->position_en = $fields['position_en'];
        $manager->education_th = $fields['education_th'];
        $manager->education_en = $fields['education_en'];
        $manager->work_experience_th = $fields['work_experience_th'];
        $manager->work_experience_en = $fields['work_experience_en'];
        $manager->iod_training_th = $fields['iod_training_th'];
        $manager->iod_training_en = $fields['iod_training_en'];
        $manager->categories_id = $fields['categories_id'];
        $manager->bord_and_management_type = $fields['bord_and_management_type'];
        $manager->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


    public function postUpdateOrder(Request $request){
        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                foreach ($inputs['data'] as $key => $value) {
                    Manager::where('id', $value)->update(['order' => ($key+1)]);
                }
                $response['msg'] ='sucess';
                $response['status'] =true;
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

}


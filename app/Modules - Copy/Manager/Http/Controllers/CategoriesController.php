<?php

namespace App\Modules\Manager\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Manager\Http\Requests\{CreateCategoriesRequest, EditCategoriesRequest};
use App\Modules\Manager\Models\{Categories};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;

class CategoriesController extends Controller
{
    public function getIndex()
    {
        $items = Categories::Data(['publish','draft']);
        return view('manager::categories.index', compact('items'));
    }

    public function getCreate()
    {
        $years= RoosterHelpers::getYear();
        return view('manager::categories.create', compact('years'));
    }

    public function postCreate(CreateCategoriesRequest $request)
    {
        $data = $request->all();
        $item = Categories::create($data);
        return redirect()->route('admin.manager.categories.index')
                            ->with('status', 'success')
                            ->with('message', trans('history::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = Categories::findOrFail($id);
        return view('manager::categories.edit', compact('data'));
    }

    public function postEdit(EditCategoriesRequest $request, $id)
    {
        $item = Categories::findOrFail($id);
        $data = $request->all();
        $item->update($data);
        return redirect()->route('admin.manager.categories.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Categories::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Categories::whereIn('id', $request->input('ids'))->get();
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


    public function postUpdateOrder(Request $request){

        try{

            if(\Request::Ajax()){

                $inputs = $request->all();
                foreach ($inputs['data'] as $key => $value) {
                    Categories::where('id', $value)->update(['order' => ($key+1)]);
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

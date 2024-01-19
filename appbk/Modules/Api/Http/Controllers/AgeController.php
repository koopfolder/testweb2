<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateAgeRequest, EditAgeRequest};
use App\Modules\Api\Models\{Age};
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;

class AgeController extends Controller
{
    public function getIndex()
    {
        //dd("Age");
        $items = Age::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('api::backend.age.index', compact('items'));
    }


    public function getCreate()
    {
        //dd("Create");
        return view('api::backend.age.create');
    }

    public function postCreate(CreateAgeRequest $request)
    {
        $data = $request->all();
        //dd($data);
        $item = Age::create($data);
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'35']);
        return redirect()->route('admin.age.index')
                            ->with('status', 'success')
                            ->with('message', trans('api::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = Age::findOrFail($id);
        //dd($data);
        return view('api::backend.age.edit', compact('data'));
    }

    public function postEdit(EditAgeRequest $request, $id)
    {

        $item = Age::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);
        //dd($data);
        $item->update($data);

        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'35']);
        return redirect()->route('admin.age.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Age::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Age::whereIn('id', $request->input('ids'))->get();
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


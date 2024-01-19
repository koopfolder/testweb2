<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateSexRequest, EditSexRequest};
use App\Modules\Api\Models\{Sex};
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;

class SexController extends Controller
{
    public function getIndex()
    {
       //dd("Sex");
        $items = Sex::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('api::backend.sex.index', compact('items'));
    }


    public function getCreate()
    {
        return view('api::backend.sex.create');
    }

    public function postCreate(CreateSexRequest $request)
    {
        $data = $request->all();
        //dd($data);
        $item = Sex::create($data);
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'34']);
        return redirect()->route('admin.sex.index')
                            ->with('status', 'success')
                            ->with('message', trans('api::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = Sex::findOrFail($id);
        //dd($data);
        return view('api::backend.sex.edit', compact('data'));
    }

    public function postEdit(EditSexRequest $request, $id)
    {

        $item = Sex::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);
        //dd($data);
        $item->update($data);

        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'34']);
        return redirect()->route('admin.sex.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Sex::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Sex::whereIn('id', $request->input('ids'))->get();
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


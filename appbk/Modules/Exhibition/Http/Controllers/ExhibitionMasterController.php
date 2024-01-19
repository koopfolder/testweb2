<?php

namespace App\Modules\Exhibition\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Exhibition\Http\Requests\{CreateExhibitionMasterRequest,EditExhibitionMasterRequest};
use App\Modules\Exhibition\Models\{Exhibition,ExhibitionMaster};
use Zipper;

class ExhibitionMasterController extends Controller
{
    public function getIndex()
    {
        $items = ExhibitionMaster::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        //dd($items);
        return view('exhibition::exhibition_master.index', compact('items'));
    }

    public function getCreate()
    {
        $exhibition_masters = ExhibitionMaster::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        //dd($exhibition_master);
        return view('exhibition::exhibition_master.create',compact('exhibition_masters'));
    }

    public function getIndexiframe()
    {
        $items = Exhibition::Data(['status'=>['publish','draft']]);
        return view('exhibition::exhibition_master.index-iframe', compact('items'));
    }


    public function postCreate(CreateExhibitionMasterRequest $request)
    {
        
        $data = $request->all();
        //dd($data);
        $item = ExhibitionMaster::create($data);
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'9']);
        return redirect()->route('admin.exhibition.master.index')
                            ->with('status', 'success')
                            ->with('message', trans('exhibition::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = ExhibitionMaster::findOrFail($id);
        $exhibition_masters = ExhibitionMaster::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        //dd($data,$exhibition_masters);
        return view('exhibition::exhibition_master.edit', compact('data','exhibition_masters'));
    }

    public function postEdit(EditExhibitionMasterRequest $request, $id)
    {

        $item = ExhibitionMaster::findOrFail($id);
        $data = $request->all();
        //dd($data);
        $item->update($data);
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'9']);
        return redirect()->route('admin.exhibition.master.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = ExhibitionMaster::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = ExhibitionMaster::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
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

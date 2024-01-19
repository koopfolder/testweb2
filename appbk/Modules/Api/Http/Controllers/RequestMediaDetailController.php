<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Models\{RequestMediaDetail,RequestMedia,RequestMediaEmail};
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};

class RequestMediaDetailController extends Controller
{
    public function getIndex()
    {
        $items = RequestMediaDetail::Data(['status'=>['publish','draft']]);
        return view('api::backend.request_media_detail.index', compact('items'));
    }

    public function getCreate()
    {
        return view('api::backend.request_media_detail.create');
    }

    public function postCreate(CreateRequest $request)
    {   
        $data = $request->all();
        $item = RequestMediaDetail::create($data);
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'11']);
        return redirect()->route('admin.request-media-detail.index')
                            ->with('status', 'success')
                            ->with('message', trans('api::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = RequestMediaDetail::findOrFail($id);
        return view('api::backend.request_media_detail.edit', compact('data'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        //dd("Post Update");
        $item = RequestMediaDetail::findOrFail($id);
        $data = $request->all();
        $item->update($data);
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'11']);
        return redirect()->route('admin.request-media-detail.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = RequestMediaDetail::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = RequestMediaDetail::whereIn('id', $request->input('ids'))->get();
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

    public static function getData($params){
        $data = RequestMediaDetail::FrontData(['status'=>['publish']]);
        return $data;
    }

}

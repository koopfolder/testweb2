<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Models\{RequestMediaDetail,RequestMedia,RequestMediaEmail};
use App\Modules\Api\Http\Requests\{CreateEmailRequest, EditEmailRequest};

class RequestMediaEmailController extends Controller
{
    public function getIndex()
    {
        $items = RequestMediaEmail::Data(['status'=>['publish','draft']]);
        return view('api::backend.request_media_email.index', compact('items'));
    }

    public function getCreate()
    {
        return view('api::backend.request_media_email.create');
    }

    public function postCreate(CreateEmailRequest $request)
    {   
        $data = $request->all();
        $item = RequestMediaEmail::create($data);
        self::postLogs(['event'=>'เพิ่มข้อมูลอิเมล์ "'.$data['email'].'"','module_id'=>'12']);
        return redirect()->route('admin.request-media-email.index')
                            ->with('status', 'success')
                            ->with('message', trans('api::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = RequestMediaEmail::findOrFail($id);
        return view('api::backend.request_media_email.edit', compact('data'));
    }

    public function postEdit(EditEmailRequest $request, $id)
    {
        //dd("Post Update");
        $item = RequestMediaEmail::findOrFail($id);
        $data = $request->all();
        $item->update($data);
        self::postLogs(['event'=>'แก้ไขข้อมูลอิเมล์ "'.$data['email'].'"','module_id'=>'12']);
        return redirect()->route('admin.request-media-email.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = RequestMediaEmail::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = RequestMediaEmail::whereIn('id', $request->input('ids'))->get();
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

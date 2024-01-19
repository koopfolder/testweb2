<?php

namespace App\Modules\Contact\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Contact\Models\{Contactsubject};
use App\Modules\Contact\Http\Requests\{CreateRequest, EditRequest};

class SubjectController extends Controller
{
    public function getIndex()
    {
        $items = Contactsubject::Data(['publish','draft']);
        return view('contact::subject.index', compact('items'));
    }

    public function getCreate()
    {
        return view('contact::subject.create');
    }

    public function postCreate(CreateRequest $request)
    {   
        $data = $request->all();
        $item = Contactsubject::create($data);
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'28']);
        return redirect()->route('admin.contact.subject.index')
                            ->with('status', 'success')
                            ->with('message', trans('history::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = Contactsubject::findOrFail($id);
        return view('contact::subject.edit', compact('data'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        //dd("Post Update");
        $item = Contactsubject::findOrFail($id);
        $data = $request->all();
        $item->update($data);
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'28']);
        return redirect()->route('admin.contact.subject.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Contactsubject::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Contactsubject::whereIn('id', $request->input('ids'))->get();
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

<?php

namespace App\Modules\SinglePage\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\SinglePage\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\SinglePage\Models\{SinglePage,SinglePageRevision};


class IndexController extends Controller
{
    public function getIndex()
    {
        $items = SinglePage::Data(['status'=>['publish','draft']]);
        return  view('single-page::index',compact('items'));
    }

    public function getCreate()
    {
        return view('single-page::create');
    }

    public function getIndexiframe()
    {
        $items = SinglePage::Data(['status'=>['publish','draft']]);
        return view('single-page::index-iframe', compact('items'));
    }


    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        //dd($data);

        $item = SinglePage::create($data);
        $id = $item->id;
        $data['single_page_id'] = $id;
        SinglePageRevision::create($data);

        if($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'2']);
        return redirect()->route('admin.single-page.index',$id)
                         ->with('status', 'success')
                         ->with('message', trans('single-page::backend.successfully'));
    }

    public function getEdit($id)
    {
       // dd("getEdit");
        $data = SinglePage::findOrFail($id);
        $revisions = SinglePageRevision::where('single_page_id', $id)->orderBy('created_at','DESC')->get();
        return view('single-page::edit', compact('data','revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $item = SinglePage::findOrFail($id);
        $data = $request->all();
        //dd($data);
        $item->update($data);
        $data['single_page_id'] = $id;
        SinglePageRevision::create($data);

        if ($request->hasFile('cover_desktop')){
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'2']);
        return redirect()->route('admin.single-page.edit',$id)
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = SinglePage::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = SinglePage::whereIn('id', $request->input('ids'))->get();
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
        $reverse = SinglePageRevision::find($id);
        $singlepageId = $reverse->single_page_id;
        $fields = array_except($reverse->toArray(),['single_page_id','created_by']);
        $singlepage = singlepage::find($singlepageId);
        $singlepage->title = $fields['title'];
        $singlepage->description = $fields['description'];
        $singlepage->short_description = $fields['short_description'];
        $singlepage->video_path = $fields['video_path'];
        $singlepage->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}

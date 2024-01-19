<?php

namespace App\Modules\History\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\History\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\History\Models\{History,HistoryRevision};


class IndexController extends Controller
{
    public function getIndex()
    {
       
        $items = History::Data(['page_layout'=>'history']);
        if(isset($items->id)){
            return redirect()->route('admin.history.edit',$items->id);
        }else{
            return redirect()->route('admin.history.create');
        }

    }

    public function getCreate()
    {
        return view('history::create');
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        $data['page_layout'] = 'history';
        //dd($data);
        $item = History::create($data);
        $id = $item->id;
        $data['news_id'] = $id;
        HistoryRevision::create($data);
        return redirect()->route('admin.history.edit',$id)
                         ->with('status', 'success')
                         ->with('message', trans('history::backend.successfully'));
    }

    public function getEdit($id)
    {
       // dd("getEdit");
        $data = History::findOrFail($id);
        $revisions = HistoryRevision::where('news_id', $id)->orderBy('created_at','DESC')->get();
        return view('history::edit', compact('data','revisions','years'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $item = History::findOrFail($id);
        $data = $request->all();
        $item->update($data);
        $data['news_id'] = $id;
        HistoryRevision::create($data);
        return redirect()->route('admin.history.edit',$id)
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = History::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = History::whereIn('id', $request->input('ids'))->get();
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
        $reverse = HistoryRevision::find($id);
        $historyId = $reverse->news_id;
        $fields = array_except($reverse->toArray(),['news_id','created_by']);
        $history = History::find($historyId);
        $history->title = $fields['title'];
        $history->description = $fields['description'];
        $history->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}

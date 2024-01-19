<?php

namespace App\Modules\Whatfranchise\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Whatfranchise\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Whatfranchise\Models\{Whatfranchise,WhatfranchiseRevision};


class IndexController extends Controller
{
    public function getIndex()
    {
       
        $items = Whatfranchise::Data(['page_layout'=>'what-is-a-franchise']);
        if(isset($items->id)){
            return redirect()->route('admin.whatfranchise.edit',$items->id);
        }else{
            return redirect()->route('admin.whatfranchise.create');
        }

    }

    public function getCreate()
    {
        return view('whatfranchise::create');
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        $data['page_layout'] = 'what-is-a-franchise';
        //dd($data);
        $item = Whatfranchise::create($data);
        $id = $item->id;
        $data['news_id'] = $id;
        WhatfranchiseRevision::create($data);
        return redirect()->route('admin.whatfranchise.edit',$id)
                         ->with('status', 'success')
                         ->with('message', trans('whatfranchise::backend.successfully'));
    }

    public function getEdit($id)
    {
       // dd("getEdit");
        $data = Whatfranchise::findOrFail($id);
        $revisions = WhatfranchiseRevision::where('news_id', $id)->orderBy('created_at','DESC')->get();
        return view('whatfranchise::edit', compact('data','revisions','years'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $item = Whatfranchise::findOrFail($id);
        $data = $request->all();
        $item->update($data);
        $data['news_id'] = $id;
        WhatfranchiseRevision::create($data);
        return redirect()->route('admin.whatfranchise.edit',$id)
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Whatfranchise::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Whatfranchise::whereIn('id', $request->input('ids'))->get();
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
        $reverse = WhatfranchiseRevision::find($id);
        $historyId = $reverse->news_id;
        $fields = array_except($reverse->toArray(),['news_id','created_by']);
        $history = Whatfranchise::find($historyId);
        $history->title = $fields['title'];
        $history->description = $fields['description'];
        $history->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}
